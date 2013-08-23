@echo OFF

cls

REM ====================================================
REM 	file
REM ====================================================

set PROC_PATH="@:objects:cache_driver:file"

SET ACTIONS="get_empty_data,get_integer_data,get_string_data,get_array_data,get_boolean_data,get_float_data,get_object_data"
REM php shell.php -proc %PROC_PATH% -actions %ACTIONS%

SET ACTIONS="set_duration,delete,delete_wildcard,delete_regex,touch,touch_wildcard,touch_regex"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%

exit /b;

REM ====================================================
REM 	memcached
REM ====================================================

set PROC_PATH="@:objects:cache_driver:memcached"

SET ACTIONS="get_empty_data,get_integer_data,get_string_data,get_array_data,get_boolean_data,get_float_data,get_object_data"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%

REM ====================================================
REM 	memcache
REM ====================================================

set PROC_PATH="@:objects:cache_driver:memcache"

SET ACTIONS="get_empty_data,get_integer_data,get_string_data,get_array_data,get_boolean_data,get_float_data,get_object_data"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%


exit /b;
