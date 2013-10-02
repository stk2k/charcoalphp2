@echo OFF

cls

set TEST_PROC="@:test"
set TARGET_MODULE="@:class:bootstrap"

REM Charcoal_Framework class test
SET ACTIONS="push_procedure"
php shell.php -proc %TEST_PROC% -p1 %TARGET_MODULE% -actions %ACTIONS%

REM Charcoal_ConfigPropertySet class test
SET ACTIONS="get_section"
php shell.php -proc %TEST_PROC% -p1 %TARGET_MODULE% -actions %ACTIONS%

REM Charcoal_System class test
SET ACTIONS="get_object_var,get_object_vars,snake_case,pascal_case"
php shell.php -proc %TEST_PROC% -p1 %TARGET_MODULE% -actions %ACTIONS%

exit /b;
