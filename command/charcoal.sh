#!/bin/sh

PROC_PATH="@:shell"

echo $ACTIONS

php index.php -proc $PROC_PATH -actions $1
