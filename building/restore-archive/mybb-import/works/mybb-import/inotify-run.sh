#!/bin/bash

cd $(dirname $0)
nohup ./inotify-go.sh >> /home/docker-vhosts/unlimit-talks.tk/logs/infonesy-inotify-import.log 2>&1 &

