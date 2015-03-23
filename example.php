<?php

	require_once './strap.class.php';

	$strap = new Strap("QNIODsZ4pPRbeLlEsXElu3W7C0zjS2W3");

	echo "Tests<hr><hr>";
	echo "<b>Endpoints</b><br><pre>";
	print var_dump( $strap->endpoints() );
	echo "</pre>";

	echo "<hr><b>Activity</b><br><pre>";
	print var_dump( $strap->activity->get() );

	echo "<hr><b>Month</b><br><pre>";
	print var_dump( $strap->month->get() );

	echo "<hr><b>Report</b><br><pre>";
	print var_dump( $strap->report->get() );

	echo "<hr><b>Today</b><br><pre>";
	print var_dump( $strap->today->get() );

	echo "<hr><b>Trigger</b><br><pre>";
	print var_dump( $strap->trigger->get() );

	echo "<hr><b>Users</b><br><pre>";
	print var_dump( $strap->users->get() );

	echo "<hr><b>Week</b><br><pre>";
	print var_dump( $strap->week->get() );


?>