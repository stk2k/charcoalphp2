@echo OFF

cls

set PROC_PATH="@:classes:io"


REM RegExFileFilter
SET ACTIONS="no_regex, simple_regex,complexed_regex"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%


REM WildcardFileFilter
SET ACTIONS="no_wildcard, question_wildcard, asterisk_wildcard"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%


REM CombinedFileFilter
SET ACTIONS="combined_regex, combined_wildcard, combined_complexed"
php shell.php -proc %PROC_PATH% -actions %ACTIONS%


exit /b;
