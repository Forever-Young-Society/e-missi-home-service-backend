#!/bin/bash
#**
#* Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
#* Author : Shiv Charan Panjeta < shiv@toxsl.com >
#*
# store the current dir
CUR_DIR=$(pwd)
SCRIPT_DIR=$(dirname $0)

echo "Starting in latest changes for SCRIPTs..."


while true
do
	/usr/bin/php $SCRIPT_DIR/scheduler.php
	sleep 1
done
echo "Complete!"
