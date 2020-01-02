#!/bin/bash
while true; do
    begin=`date +%s`
    /usr/bin/wget -O /dev/null http://winimi.impulsive-projects.de/auction_countdown/ >> /home/winimibh/www.winimi.impulsive-projects.de/winimi/storage/logs/cron.log
    end=`date +%s`
    if [ $(($end - $begin)) -lt 1 ]; then
        sleep $(($begin + 1 - $end))
    fi
done
