<?php

	require_once './curanexus.class.php';

	$curanexus = new CuraNEXUS("CURANEXUS_READ_TOKEN");

	echo "GET Tests<hr><hr>";
	echo "<b>Endpoints</b><br><pre>";
	print var_dump( $curanexus->endpoints() );
	echo "</pre>";

	echo "<hr><b>Activity</b><br><pre>";
	print var_dump( $curanexus->activity->get(["guid" => "USER_GUID"]) );

	echo "<hr><b>Month</b><br><pre>";
	print var_dump( $curanexus->month->get() );

	echo "<hr><b>Report</b><br><pre>";
	print var_dump( $curanexus->report->get("report-id") );

	echo "<hr><b>Report Food</b><br><pre>";
	print var_dump( $curanexus->report_food->get( [ "id" => "report-id" ] ) );

	echo "<hr><b>Report Raw</b><br><pre>";
	print var_dump( $curanexus->raw->get( [ "id" => "report-id" ] ) );

	echo "<hr><b>Report Workout</b><br><pre>";
	print var_dump( $curanexus->report_workout->get( [ "id" => "report-id" ] ) );

	echo "<hr><b>Today</b><br><pre>";
	print var_dump( $curanexus->today->get() );

	echo "<hr><b>Trigger</b><br><pre>";
	print var_dump( $curanexus->trigger->get("trigger-id") );

	echo "<hr><b>Trigger Data</b><br><pre>";
	print var_dump( $curanexus->trigger_data->get() );

	echo "<hr><b>User Info</b><br><pre>";
	print var_dump( $curanexus->user->get(["guid" => "USER_GUID"]) );

	echo "<hr><b>Users</b><br><pre>";
	print var_dump( $curanexus->users->get() );

	echo "<hr><b>Week</b><br><pre>";
	print var_dump( $curanexus->week->get() );

	echo "<hr><b>Word Cloud</b><br><pre>";
	print var_dump( $curanexus->wordcloud->get() );


?>