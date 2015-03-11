<?php

	require_once './strap.class.php';

	$strap = new Strap("Replace with Read Token for Project");

	echo "Tests<hr><hr>";
	echo "<b>Endpoints</b><br><pre>";
	print var_dump( $strap->endpoints() );
	echo "</pre>";

	echo "<hr><b>Activity</b><br><pre>";
	print var_dump( $strap->getActivity("brian-strap") );

	echo "<hr><b>Report</b><br><pre>";
	print var_dump( $strap->getReport() );

	echo "<hr><b>Today</b><br><pre>";
	print var_dump( $strap->getToday() );

	echo "<hr><b>Trigger</b><br><pre>";
	print var_dump( $strap->getTrigger() );

	echo "<hr><b>Users</b><br><pre>";
	print var_dump( $strap->getUsers() );


?>