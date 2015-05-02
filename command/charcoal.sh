#!/bin/sh

PROC_PATH="@:shell"

php shell.php -proc $PROC_PATH -p1 "$1" -p2 "$2" -p3 "$3" -p4 "$4" -p5 "$5" -p6 "$6"
