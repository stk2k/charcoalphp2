@echo OFF

cls

set TEST_PROC="@:test"
set TEST_TARGET="@:classes:base"

REM New Instance Test
SET ACTIONS="new_instance"
php shell.php -proc %TEST_PROC% -p1 %TEST_TARGET%  -actions %ACTIONS%
