#!/bin/bash

inotifywait -m --format '%w%f' -e moved_to /home/balancer/Sync/airbase-forums-push | while read -r line
do 
	php import-file.php "$line"
done

# -r - recursive? не нужно для .sync