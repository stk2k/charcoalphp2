@echo OFF

cls

set TEST_PROC="@:test"
set TARGET_MODULE="@:components:charcoal:db"

REM Query Test
SET ACTIONS="fluent_api"
REM php shell.php -proc %TEST_PROC% -p1 %TARGET_MODULE% -actions %ACTIONS% > test_smart_gateway.log
php shell.php -proc %TEST_PROC% -p1 %TARGET_MODULE% -actions %ACTIONS%

REM Query Test
SET ACTIONS="select_cascade"
php shell.php -proc %TEST_PROC% -p1 %TARGET_MODULE% -actions %ACTIONS%

REM Query Test
SET ACTIONS="query"
php shell.php -proc %TEST_PROC% -p1 %TARGET_MODULE% -actions %ACTIONS%

REM Select Casade Test
SET ACTIONS="select_cascade"
php shell.php -proc %TEST_PROC% -p1 %TARGET_MODULE% -actions %ACTIONS%

REM Select Alias Test
SET ACTIONS="select,select,select_alias,select_alias_forupdate"
php shell.php -proc %TEST_PROC% -p1 %TARGET_MODULE% -actions %ACTIONS%

REM Join Test
SET ACTIONS="inner_join,left_join,right_join,inner_join_alias,inner_join_multi,inner_join_multi_alias"
php shell.php -proc %TEST_PROC% -p1 %TARGET_MODULE% -actions %ACTIONS%

REM Aggregate Functions Test
SET ACTIONS="count,max,min,avg,count_alias,max_alias"
php shell.php -proc %TEST_PROC% -p1 %TARGET_MODULE% -actions %ACTIONS%

REM Find Test
SET ACTIONS="find_first"
php shell.php -proc %TEST_PROC% -p1 %TARGET_MODULE% -actions %ACTIONS%

REM Commit
SET ACTIONS="commit"
php shell.php -proc %TEST_PROC% -p1 %TARGET_MODULE% -actions %ACTIONS%


exit /b;
