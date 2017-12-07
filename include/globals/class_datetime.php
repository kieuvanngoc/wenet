<?php
defined('PG_PAGE') or die();

date_default_timezone_set('Asia/Ho_Chi_Minh');

class PGDatetime {

	// INITIALIZE VARIABLES
	var $is_error;			// DETERMINES WHETHER THERE IS AN ERROR OR NOT

	function PGDatetime() {
	}

	function timestampToDateTime($timestamp=''){
		if (!$timestamp) $timestamp=time();
		return date("Y-m-d H:i:s", $timestamp);
	}
	
	function datetimeToTimestamp($datetime=''){
		if (!$datetime || $datetime=='0000-00-00 00:00:00') return time();
		
		list($date, $time) = explode(' ', $datetime);
		list($year, $month, $day) = explode('-', $date);
		list($hour, $minute, $second) = explode(':', $time);		
		return mktime($hour, $minute, $second, $month, $day, $year);
	}
	
	function datetimeDisplay($datetime, $onlyDate=false, $formatViet = false ){
		if ($onlyDate) return date('d/m/Y', PGDatetime::datetimeToTimestamp($datetime));
		if ( $formatViet ) return date('d/m/Y H:i:s ', PGDatetime::datetimeToTimestamp($datetime));
		return date('H:i:s d/m/Y', PGDatetime::datetimeToTimestamp($datetime));
	}
	
	function convertDate($string, $format = 'mm/dd/yyyy'){
		$d = explode('/', $string);
		if ($format == "dd/mm/yyyy") {
			return date('Y-m-d', mktime(0, 0, 0, $d[1], $d[0], $d[2]));
		}
		else if ($format == "yyyy/mm/dd") {
			return date('Y-m-d', mktime(0, 0, 0, $d[1], $d[2], $d[0]));
		}
		else if ($format == "dd-mm-yyyy") {
			$d = explode('-', $string);
			return date('Y-m-d', mktime(0, 0, 0, $d[1], $d[0], $d[2]));
		}
		else if ($format == "mm-dd-yyyy") {
			$d = explode('-', $string);
			return date('Y-m-d', mktime(0, 0, 0, $d[0], $d[1], $d[2]));
		}		
		return date('Y-m-d', mktime(0, 0, 0, $d[0], $d[1], $d[2]));
	}
	
	function unFormatDate($d=null , $f="d-m-Y h:i:s"){
		$d=($d?$d:date("Y-m-d h:i:s"));
	  return date($f,strtotime($d));
	}
	
	function formatDate($d=null){
		$arr = explode('-',$d);
		return $arr[2]."-".$arr[1]."-".$arr[0];
	}
	
	function numberDays($dateInput){
		$nows = mktime(0,0,0,date("m"),date("d"),date("Y"));
		$date_input = mktime(0,0,0,$this->unFormatdate($dateInput,"m"),$this->unFormatdate($dateInput,"d"),$this->unFormatdate($dateInput,"Y"));
		$days = ($nows - $date_input)/86400;
		return $days;
	}
	
	function getTimestampValue($datefilter){
		if ( !$datefilter ) return false;

		if ( $datefilter == 'today' ){
			$currentDate = date('Y-m-d');
			$startdate = strtotime($currentDate . ' 01:00:00');
			$endate = strtotime($currentDate . ' 23:59:59');
		}else if ( $datefilter == 'yesterday' ){
			$currentDate = date('Y-m-d');
			$start_today = strtotime($currentDate . ' 01:00:00');
			$end_today = strtotime($currentDate . ' 23:59:59');

			$startdate = strtotime("-1 days", $start_today);
			$endate = strtotime("-1 days", $end_today);
		}else if ( $datefilter == 'thisweek' ){
			$startdate = strtotime('monday this week');
			$endate = strtotime('sunday this week');
		}else if ( $datefilter == 'lastweek' ){
			$startdate_this_week = strtotime('monday this week');
			$endate_this_week = strtotime('sunday this week');
			$startdate = strtotime("-7 days", $startdate_this_week);
			$endate = strtotime("-7 days", $endate_this_week);
			//$startdate = strtotime('first day of previous week');
			//$endate = strtotime('last day of previous week');
		}else if ( $datefilter == 'thismonth' ){
			$startdate = strtotime('first day of this month');
			$endate = strtotime('last day of this month');
		}else if ( $datefilter == 'lastmonth' ){
			$startdate = strtotime('first day of previous month');
			$endate = strtotime('last day of previous month');
		}else{
			$times  = array();
			for($month = 1; $month <= 12; $month++) {
				$first_minute = mktime(0, 0, 0, $month, 1);
				$last_minute = mktime(23, 59, 0, $month, date('t', $first_minute));
				$times[$month] = array($first_minute, $last_minute);
			}
			//print_r($times);
			$startdate = $times[1][0];
			$endate = $times[12][1];
		}
		//echo $startdate .'----'.$endate; die;
		//echo date('Y-m-d H:i:s', $startdate); echo '---';
		//echo date('Y-m-d H:i:s', $endate); die;
			
		$aryDate = array($startdate, $endate);
		return $aryDate;
	}
	
	function calculateTime($time_to_calculate)
	{
		date_default_timezone_set('Asia/Ho_Chi_Minh');
		$time_now = intval(strtotime(date('H:i:s d-m-Y')));
		$temp_time = intval($time_to_calculate);
		$distance_time = $time_now - $temp_time;
		if($distance_time < 0)
		{
			//return "Thời gian tính toán phải trước thời gian hiện tại";
			//echo date("H:i:s d-m-Y", $time_to_calculate); die;
			return $time_to_calculate;
			exit();
		}
	
		if($distance_time <= 10)
		{
			return "Vừa xong";
		}
		else if(ceil($distance_time/60) < 1)
		{
			return "vài giây trước";
		}
		else if(ceil($distance_time/60) == 1)
		{
			return "1 phút trước";
		}
		else if(ceil($distance_time/60) > 1 && ceil($distance_time/60) < 60)
		{
			return ceil($distance_time/60)." phút trước";
		}
		else if(ceil($distance_time/(60*60)) == 1)
		{
			return "Một giờ trước";
		}
		else if(ceil($distance_time/(60*60)) < 24)
		{
			return ceil($distance_time/(60*60))." giờ trước";
		}
		else if(ceil($distance_time/(60*60*24)) == 1)
		{
			return "Một ngày trước";
		}
		else if(ceil($distance_time/(60*60*24)) == 2)
		{
			return "Hôm qua lúc ".date("h:i", $temp_time);
		}
		else if(ceil($distance_time/(60*60*24)) == 3)
		{
			return "Hôm kia lúc ".date("h:i", $temp_time);
		}
		else if(ceil($distance_time/(60*60*24)) < 7)
		{
			$start_week = strtotime('monday this week');
			$end_week = strtotime('sunday this week');
			if ( ($temp_time >= $start_week) && ($temp_time <= $end_week) ){
				$date = $this->sw_get_current_weekday($temp_time);
				return $date;
			}else{
				return date("d/m/Y h:i:s", $temp_time);
			}
		}
		/*
		else if(ceil($distance_time/(60*60*24*7)) == 1)
		{
			return "Một tuần trước";
		}
		else if(ceil($distance_time/(60*60*24*7)) < 4)
		{
			return ceil($distance_time/(60*60*24*7))." tuần trước";
		}
		 else if(ceil($distance_time/(60*60*24*7*4)) == 1)
		 {
			return "Một tháng trước";
			}
			else if(ceil($distance_time/(60*60*24*7*4)) < 12)
			{
			return "Khoảng ".ceil($distance_time/(60*60*24*7*4))." tháng trước";
			}
			else if(ceil($distance_time/(60*60*24*7*4*12)) == 1)
			{
			return "Một năm trước";
			}
			else
			{
			return "Khoảng ".ceil($distance_time/(60*60*24*7*4*12))." năm trước";
			}
			*/
		else{
			return date("d/m/Y h:i:s", $temp_time);
		}
	}

	/*
	 * Get thứ trong tuần
	 */
	function sw_get_current_weekday($time, $only_thu = false) {
		date_default_timezone_set('Asia/Ho_Chi_Minh');
		$weekday = date("l", $time);
		$weekday = strtolower($weekday);
		switch($weekday) {
			case 'monday':
				$weekday = 'Thứ hai';
				break;
			case 'tuesday':
				$weekday = 'Thứ ba';
				break;
			case 'wednesday':
				$weekday = 'Thứ tư';
				break;
			case 'thursday':
				$weekday = 'Thứ năm';
				break;
			case 'friday':
				$weekday = 'Thứ sáu';
				break;
			case 'saturday':
				$weekday = 'Thứ bảy';
				break;
			default:
				$weekday = 'Chủ nhật';
				break;
		}
		if ( $only_thu ){
			return $weekday;
		}
		return $weekday.', lúc '.date('H:i:s', $time);
	}
}
?>