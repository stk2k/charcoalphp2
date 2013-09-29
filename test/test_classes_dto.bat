@echo OFF

cls

set TEST_PROC="@:test"
set TEST_TARGET="@:classes:base"

SET ACTIONS="dto_array_access"
php shell.php -proc %TEST_PROC% -p1 %TEST_TARGET% -actions %ACTIONS%
exit /b;

REM Array Interface Tests
SET ACTIONS="dto_array_access,dto_offset_get,dto_magic_get,dto_offset_set,dto_magic_set"
php shell.php -proc %TEST_PROC% -p1 %TEST_TARGET% -actions %ACTIONS%

REM Array Interface Tests2
SET ACTIONS="dto_offset_exists,dto_offset_unset"
php shell.php -proc %TEST_PROC% -p1 %TEST_TARGET% -actions %ACTIONS%

REM Iterator Interface Tests
SET ACTIONS="dto_foreach"
php shell.php -proc %TEST_PROC% -p1 %TEST_TARGET% -actions %ACTIONS%

REM Merge Tests
SET ACTIONS="dto_set_array,dto_set_hashmap,dto_merge_array,dto_merge_hashmap"
php shell.php -proc %TEST_PROC% -p1 %TEST_TARGET% -actions %ACTIONS%

REM Other DTO Tests
SET ACTIONS="dto_keys"
php shell.php -proc %TEST_PROC% -p1 %TEST_TARGET% -actions %ACTIONS%


exit /b;
