#!/bin/sh

PROC_PATH="@:shell"

php shell.php -proc $PROC_PATH -action "$1"
