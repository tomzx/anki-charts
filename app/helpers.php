<?php

function secondsToTime($seconds) {
	$days = floor($seconds/(24*60*60));
	$day = gmdate('H\hi\ms\s', $seconds% (24*60*60));
	return $days.'d'.$day;
}