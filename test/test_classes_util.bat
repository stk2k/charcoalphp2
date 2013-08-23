@echo OFF

cls

set PROC_PATH="@:classes:util"

REM Command line util test
SET ACTIONS="split_params1,split_params2,split_params3"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%

exit /b;
