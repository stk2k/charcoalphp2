@echo OFF

cls

set PROC_PATH="@:classes:bootstrap"

REM Charcoal_Framework class test
SET ACTIONS="push_procedure"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%

exit /b;

REM Charcoal_ConfigPropertySet class test
SET ACTIONS="get_section"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%

REM Charcoal_System class test
SET ACTIONS="get_object_var,get_object_vars,snake_case,pascal_case"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%

exit /b;
