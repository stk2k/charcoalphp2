@echo OFF

cls

set TEST_PROC="@:test"
set TARGET_MODULE="@:object:token_generators"

REM Charcoal_SimpleTokenGenerator
SET ACTIONS="simple_default,simple_sha1,simple_md5"
php shell.php -proc %TEST_PROC% -p1 %TARGET_MODULE% -actions %ACTIONS%

