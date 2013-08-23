@echo OFF

cls

set PROC_PATH="transformer_test@:classes:transformer"

REM Query Test
SET ACTIONS="simple_transform"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%
