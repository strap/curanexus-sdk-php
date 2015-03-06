<?php

	require_once './strap.class.php';

	$strap = new StrapSDK("Replace with Read Token for Project");

	echo "Tests<hr><hr>";
	echo "<b>Endpoints</b><br><pre>";
	print var_dump( $strap->endpoints() );
	echo "</pre>";

	echo "<hr><b>Users</b><br><pre>";
	print var_dump( $strap->users->call() );

	echo "<hr><b>Activity</b><br><pre>";
	print var_dump( $strap->activity->call(["guid" => "brian-strap"]) );

	echo "<hr><b>Today</b><br><pre>";
	print var_dump( $strap->today->call() );


?>