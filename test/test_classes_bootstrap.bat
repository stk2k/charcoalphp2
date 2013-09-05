@echo OFF

cls

set PROC_PATH="@:classes:bootstrap"

REM ConfigPropertySet class test
SET ACTIONS="get_section"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%

REM System class test
SET ACTIONS="get_object_var,get_object_vars,snake_case,pascal_case"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%

exit /b;
