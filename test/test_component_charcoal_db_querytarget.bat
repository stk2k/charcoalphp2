@echo OFF

cls

set PROC_PATH="@:component:charcoal:db"

REM Alias Test
SET ACTIONS="qt_table_alias"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%

REM Inner Join Test
SET ACTIONS="qt_inner_join,qt_inner_join_alias"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%

REM Left Join Test
SET ACTIONS="qt_left_join,qt_left_join_alias"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%

REM Right Join Test
SET ACTIONS="qt_right_join,qt_right_join_alias"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%

REM Complex Join Test
SET ACTIONS="qt_complex_join,qt_complex_join_alias"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%


exit /b;
