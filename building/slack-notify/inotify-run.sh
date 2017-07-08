#!/bin/bash

cd $(dirname $0)
nohup ./inotify-go.sh >> /var/log/infonesy/slack-inotify.log 2>&1 &

