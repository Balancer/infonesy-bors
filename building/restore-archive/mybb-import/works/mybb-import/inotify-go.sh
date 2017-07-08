#!/bin/bash

inotifywait -m --format '%w%f' -e moved_to,close_write /home/airbase/airbase-lxc/var/www/sync/airbase-forums-push/ | while read -r line
do
	php import-file.php "$line"
done

# -r - recursive? не нужно для .sync