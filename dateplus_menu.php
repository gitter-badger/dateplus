<?php

if (!defined('e107_INIT')) { exit; }
require_once(e_HANDLER."date_handler.php");
define("DATEPLUS", e_PLUGIN."dateplus_menu/");
if(file_exists(THEME."dateplus_template.php")){
	include_once(THEME."dateplus_template.php");
}else{
	include_once(DATEPLUS."dateplus_template.php");
}

$weekday = strftime("%A");
$dayformat = "%e";
if (strtoupper(substr(PHP_OS, 0, 3)) == "WIN" && strpos($dayformat, "%e") !== false) {
    $dayformat = str_replace("%e", "%#d", $dayformat);
}
$day = strftime($dayformat);
$month = strftime("%m");
$year = strftime("%Y");
$datestamp = strftime("%s");

require_once(DATEPLUS."holidays.php");

if(file_exists(DATEPLUS."userdays.php")){
	include_once(DATEPLUS."userdays.php");
	foreach($userdays as $uday){
		if(!empty($uday['weekday'])){
			if(!empty($uday['dayspan'])){
				if($month == $uday['month'] && $weekday == $uday['weekday'] && $day > $uday['day'] && $day < $uday['dayspan']){
					$userday = $uday['name'];
				}
			}else{
				if($month == $uday['month'] && $weekday == $uday['weekday'] && $day == $uday['day']){
					$userday = $uday['name'];
				}
			}
		}else{
			if(!empty($uday['dayspan'])){
				if($month == $uday['month'] && $day > $uday['day'] && $day < $uday['dayspan']){
					$userday = $uday['name'];
				}
			}else{
				if($month == $uday['month'] && $day == $uday['day']){
					$userday = $uday['name'];
				}
			}
		}
	}
}


if(isset($holiday) && isset($userday)){

	$text = str_replace(
		array(
			"%_DATE_%",
			"%_HOLIDAY_%",
			"%_USERDAY_%"
		),
		array(
			convert_date($datestamp),
			$holiday,
			$userday
		), $USERHOLIDAYTEMPLATE);

}else if(isset($holiday) && empty($userday)){
	
	$text = str_replace(
		array(
			"%_DATE_%",
			"%_HOLIDAY_%"
		),
		array(
			convert_date($datestamp),
			$holiday
		), $HOLIDAYTEMPLATE);

}else if(isset($userday) && empty($holiday)){
	
	$text = str_replace(
		array(
			"%_DATE_%",
			"%_USERDAY_%"
		),
		array(
			convert_date($datestamp),
			$userday
		), $USERDAYTEMPLATE);

}else{
	$text = str_replace("%_DATE_%", convert_date($datestamp), $REGULARTEMPLATE);
}

$ns->tablerender("Date+", $text, 'dateplus');
?>