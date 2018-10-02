var util = require('util');
var request = require("request");
var app = require('http').createServer(handler),
    io                  = require('socket.io').listen(app),
    fs                  = require('fs'),
    mysql               = require('mysql'),
    connectionsArray    = [],
    connection          = mysql.createConnection({
        host        : 'localhost',
        user        : 'root',
        password    : 'conbed',
        database    : 'rust',
        port        : 3306,
        multipleStatements: true,
        dateStrings: true
    }),
    POLLING_INTERVAL = 3000,
    pollingTimer;

// If there is an error connecting to the database
connection.connect(function(err) {
  // connected! (unless `err` is set)
  console.log( err );
});

// create a new nodejs server ( localhost:8000 )
app.listen(8000);

// on server ready we can load our client.html page
function handler ( req, res ) {
    fs.readFile( __dirname + '/index.php' , function ( err, data ) {
        if ( err ) {
            console.log( err );
            res.writeHead(500);
            return res.end( 'The page you are looking for was not found on our servers! ERR#83950' );
        }
        res.writeHead( 200 );
        res.end( data );
    });
}

app.listen(8080);

var notificationCountLoop = function (socket,uid)
{
	//make the database Query
	var query = connection.query('SELECT COUNT(*) AS notifyNum FROM user_notifications WHERE uid = '+uid+' AND seen = 0');
	var socketIndex = connectionsArray.indexOf( socket );
	var result1;
	//setup the query listeners
	query
	.on('error', function(err) {
        // Handle error, and 'end' event will be emitted after this as well
        console.log( err );
    })
    .on('result', function( result ) {
        // it fills our array looping on each user row inside the db
        var count = JSON.stringify(result);
        var arra = [];
	 	arra = count.split(":");
	 	result1 = arra[1].replace("}","");
        console.log("User "+uid+" has " + result1+" notifications. Steaming RTN Count for user now.");
    })
    .on('end',function(){
    	sendNotificationCount(socket,result1);
    	if(result1 > 0)
    	{
			sendNotificationsUpdate(socket,uid);
		}
    	//do other querys as well for other important RTN information
    	//query db for unseen message count
    	query = connection.query('SELECT COUNT(*) AS messNum FROM user_messages WHERE to_uid = '+uid+' AND seen = 0');
    	query
    	.on('error',function(err) {
    		//handle the error(s)
    		console.log(err);	
    	})
    	.on('result',function(result){
    		var count = JSON.stringify(result);
    		var arra = [];
    		arra = count.split(":");
    		result1 = arra[1].replace("}","");
    		console.log("User "+uid+" has "+result1+" messages. Streaming RTM count for user now.");
    	})
    	.on('end',function(){
    		sendMessageCount(socket,result1);
    		if(result1 > 0)
    		{
    			//only push new messages to client if they have a unseen message
				sendMessagesUpdate(socket,uid);	
			}
    		
    	});
    });
}

// create a new websocket connection to keep the content updated without any AJAX request
io.sockets.on( 'connection', function ( socket ) {
    connectionsArray.push( socket );
    console.log('Number of connections:' + connectionsArray.length);
    // start the polling loop only if at least there is one user connected
    if (!connectionsArray.length) {
        //pollingLoop();
    }
   
    socket.on('disconnect', function () {
        var socketIndex = connectionsArray.indexOf( socket );
        console.log('socket = ' + socketIndex + ' disconnected');
        if (socketIndex >= 0) {
            connectionsArray.splice( socketIndex, 1 );
        }   
    });
    
    socket.on('UserInfo', function(user)
	{
		notificationCountLoop(socket, user.uid);
		//user has sent out his uid so it is time to query database for information
		console.log(user.uid);
	});
   
});

var sendMessageCount = function(socket, data)
{
	setTimeout(function (){
		socket.emit('messageCount', data);	
	})
}

var sendNotificationCount = function (socket, data)
{
	setTimeout(function () {
		socket.emit('notificationCount' , data);
	}, 3000);
}

var sendMessagesUpdate = function(socket,uid)
{
	var result1 = [];
	var query = connection.query('SELECT * FROM user_messages WHERE to_uid = '+uid+ ' AND reply = 0 ORDER BY id DESC');
	query.on('result',function(row){
		connection.pause();
		result1.push(row);
		connection.resume();
	})
	.on('end',function(){
		setTimeout(function () {
			socket.emit('quickMessages' , result1);
		}, 3000);
	});
}

var sendNotificationsUpdate = function(socket,uid)
{
	var result1 = [];
	var query = connection.query('SELECT * FROM user_notifications WHERE uid = '+uid+' ORDER BY id DESC');
	query.on('result',function(row){
		connection.pause();
		result1.push(row);
		connection.resume();	
	})
	.on('end',function(row){
		setTimeout(function(){
			socket.emit('quickNotifications', result1);
		}, 3000)
	});
}