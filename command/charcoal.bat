@echo OFF

cls

set PROC_PATH="@:shell"


REM versiont
SET ACTIONS="hello"
php shell.php -proc %PROC_PATH% -p1 "%1" -p2 "%2" -p3 "%3"
