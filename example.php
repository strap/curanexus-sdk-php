<?php

	require_once './strap.class.php';

	$strap = new Strap("STRAP_READ_TOKEN");

	echo "GET Tests<hr><hr>";
	echo "<b>Endpoints</b><br><pre>";
	print var_dump( $strap->endpoints() );
	echo "</pre>";

	echo "<hr><b>Activity</b><br><pre>";
	print var_dump( $strap->activity->get(["guid" => "USER_GUID"]) );

	echo "<hr><b>Month</b><br><pre>";
	print var_dump( $strap->month->get() );

	echo "<hr><b>Report</b><br><pre>";
	print var_dump( $strap->report->get("report-id") );

	echo "<hr><b>Report Food</b><br><pre>";
	print var_dump( $strap->report_food->get( [ "id" => "report-id" ] ) );

	echo "<hr><b>Report Raw</b><br><pre>";
	print var_dump( $strap->raw->get( [ "id" => "report-id" ] ) );

	echo "<hr><b>Report Workout</b><br><pre>";
	print var_dump( $strap->report_workout->get( [ "id" => "report-id" ] ) );

	echo "<hr><b>Today</b><br><pre>";
	print var_dump( $strap->today->get() );

	echo "<hr><b>Trigger</b><br><pre>";
	print var_dump( $strap->trigger->get("trigger-id") );

	echo "<hr><b>Trigger Data</b><br><pre>";
	print var_dump( $strap->trigger_data->get() );

	echo "<hr><b>User Info</b><br><pre>";
	print var_dump( $strap->user->get(["guid" => "USER_GUID"]) );

	echo "<hr><b>Users</b><br><pre>";
	print var_dump( $strap->users->get() );

	echo "<hr><b>Week</b><br><pre>";
	print var_dump( $strap->week->get() );

	echo "<hr><b>Word Cloud</b><br><pre>";
	print var_dump( $strap->wordcloud->get() );


?>