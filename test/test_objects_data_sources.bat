@echo OFF

cls

set TEST_PROC="@:test"
set TARGET_MODULE="@:objects:data_sources"


REM Charcoal_SQLiteDataSource
SET ACTIONS="open,close,read,write,destroy,gc"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%
