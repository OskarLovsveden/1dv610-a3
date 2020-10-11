<?php

namespace View;

class DateTime {

	public function show(): string {
		date_default_timezone_set("Europe/Stockholm");

		$weekday = date("l");
		$day = date("d");
		$month = date("F");
		$suffix = date("S");
		$year = date("Y");
		$time = date("h:i:s");

		$timeString = $weekday . ", the " . $day . $suffix . " of " . $month . " " . $year . ", The time is " .  $time;

		return '<p>' . $timeString . '</p>';
	}
}