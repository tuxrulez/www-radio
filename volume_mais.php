#!/usr/bin/php -q
<?php

$volume = 0;

//exec("echo pausing set_property volume $volume' > /tmp/playlist_in");


exec("echo 'pausing_keep set_property mute 0' > /tmp/playlist_in");

exec("echo 'pause' > /tmp/playlist_in");

for ($i=0; $i < 10; $i++) 
{ 
	$volume = $volume + 10;

	exec("echo 'set_property volume $volume' > /tmp/playlist_in");


	usleep(100000);
}
?>