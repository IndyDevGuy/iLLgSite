<?php
class DatesTimes
{
	public $months;
	public $abvMonths;
	public $times;
	
	public function __construct()
	{
		$this->months[1] = "January";
		$this->months[2] = "Febuary";
		$this->months[3] = "March";
		$this->months[4] = "April";
		$this->months[5] = "May";
		$this->months[6] = "June";
		$this->months[7] = "July";
		$this->months[8] = "August";
		$this->months[9] = "September";
		$this->months[10] = "October";
		$this->months[11] = "November";
		$this->months[12] = "December";
		
		$this->abvMonths[1] = "Jan.";
		$this->abvMonths[2] = "Feb.";
		$this->abvMonths[3] = "March";
		$this->abvMonths[4] = "April";
		$this->abvMonths[5] = "May";
		$this->abvMonths[6] = "June";
		$this->abvMonths[7] = "July";
		$this->abvMonths[8] = "Aug.";
		$this->abvMonths[9] = "Sep.";
		$this->abvMonths[10] = "Oct.";
		$this->abvMonths[11] = "Nov.";
		$this->abvMonths[12] = "Dec.";
		
		$this->times[1] = '1,am';
		$this->times[2] = '2,am';
		$this->times[3] = '3,am';
		$this->times[4] = '4,am';
		$this->times[5] = '5,am';
		$this->times[6] = '6,am';
		$this->times[7] = '7,am';
		$this->times[8] = '8,am';
		$this->times[9] = '9,am';
		$this->times[10] = '10,am';
		$this->times[11] = '11,am';
		$this->times[12] = '12,pm';
		$this->times[13] = '1,pm';
		$this->times[14] = '2,pm';
		$this->times[15] = '3,pm';
		$this->times[16] = '4,pm';
		$this->times[17] = '5,pm';
		$this->times[18] = '6,pm';
		$this->times[19] = '7,pm';
		$this->times[20] = '8,pm';
		$this->times[21] = '9,pm';
		$this->times[22] = '10,pm';
		$this->times[23] = '11,pm';
		$this->times[24] = '12,am';
	}
	
	public function getFullMonth($num)
	{
		return $this->months[$num];
	}
	
	public function getAbvMonth($num)
	{
		return $this->abvMonths[$num];
	}
	
	public function showDateBasedOffToday($oldDate)
	{
		$todaysDate = date("Y-m-d H:i:s");
		$splitTodays = explode(' ',$todaysDate);
		$todaysDate = $splitTodays[0];
		
		$todaysSplit = explode('-',$todaysDate);
		$todaysMonth = $todaysSplit[1];
		$todaysDay = $todaysSplit[2];
		$todaysYear = $todaysSplit[0];
		
		$oldSplit = explode('-',$oldDate);
		$oldMonth = $oldSplit[1];
		$oldDay = $oldSplit[2];
		$oldYear = $oldSplit[0];
		
		$yesturday = $todaysDay - 1;
		//compare years
		if($todaysYear == $oldYear)
		{
			//date was from this year check to see if date is from this month
			if($todaysMonth == $oldMonth)
			{
				//check to see if the date is todays date
				if($todaysDay == $oldDay)
				{
					return 'Today';
				}
				//check to see if date was yesturday
				elseif($oldDay == $yesturday)
				{
					return 'Yesturday';
				}
				else
				{
					//this date was not from this month 
					$month = $this->getAbvMonth($oldMonth);
					$date = $month . ' ' . $oldDay . ', '. $oldYear;
					return $date;
				}
			}
			else
			{
				//this date was not from this month 
				$month = $this->getAbvMonth($oldMonth);
				$date = $month . ' ' . $oldDay . ', '. $oldYear;
				return $date;
			}			
			
		}
		else
		{
			//date is not from this year so go ahead and make the date string
			$month = $this->getAbvMonth($oldMonth);
			$date = $month . ' ' . $oldDay . ', '. $oldYear;
			return $date;
		}
		
	}
	
}
?>