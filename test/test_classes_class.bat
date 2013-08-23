@echo OFF

cls

set PROC_PATH="@:classes:base"

REM New Instance Test
SET ACTIONS="new_instance"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%
