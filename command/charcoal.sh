#!/bin/sh

PROC_PATH="@:shell"

SCRIPT_DIR=$(cd $(dirname $0) && pwd)

php ${SCRIPT_DIR}/shell.php -proc $PROC_PATH -p1 "$1" -p2 "$2" -p3 "$3" -p4 "$4" -p5 "$5" -p6 "$6"
