@echo OFF

cls

set PROC_PATH="@:classes:base"

REM Array Interface Tests
SET ACTIONS="dto_array_access,dto_offset_get,dto_magic_get,dto_offset_set,dto_magic_set"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%

REM Array Interface Tests2
SET ACTIONS="dto_offset_exists,dto_offset_unset"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%

REM Iterator Interface Tests
SET ACTIONS="dto_foreach"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%

REM Merge Tests
SET ACTIONS="dto_set_array,dto_set_hashmap,dto_merge_array,dto_merge_hashmap"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%

REM Other DTO Tests
SET ACTIONS="dto_keys"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%


exit /b;
