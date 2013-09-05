@echo OFF

cls

set PROC_PATH="smart_gateway_session_handler_test@:objects:session_handler"



REM SmartGatewaySessionHandler
SET ACTIONS="open,close,read,write,destroy,gc"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%
