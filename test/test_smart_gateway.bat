@echo OFF

cls

set PROC_PATH="@:components:charcoal:db"

REM Query Test
SET ACTIONS="select_cascade"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%


REM Query Test
SET ACTIONS="query"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%

REM Select Casade Test
SET ACTIONS="select_cascade"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%

REM Select Alias Test
SET ACTIONS="select,select,select_alias,select_alias_forupdate"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%

REM Join Test
SET ACTIONS="inner_join,left_join,right_join,inner_join_alias,inner_join_multi,inner_join_multi_alias"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%

REM Aggregate Functions Test
SET ACTIONS="count,max,min,avg,count_alias,max_alias"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%

REM Find Test
SET ACTIONS="find_first"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%

REM Commit
SET ACTIONS="commit"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%


exit /b;
