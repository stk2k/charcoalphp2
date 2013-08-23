@echo OFF

cls

set PROC_PATH="@:shell"


REM versiont
SET ACTIONS="hello"
php index.php -proc %PROC_PATH% -actions %ACTIONS%
