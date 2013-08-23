@echo OFF

cls

set PROC_PATH="@:classes:bootstrap"

REM ConfigPropertySet class test
SET ACTIONS="get_section"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%

exit /b;


REM System class test
SET ACTIONS="get_object_vars"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%

exit /b;
