@echo OFF

cls

set TEST_PROC="@:test"
set TEST_TARGET="@:class:io"

REM RegExFileFilter
SET ACTIONS="no_regex, simple_regex,complexed_regex"
php shell.php -proc %TEST_PROC% -p1 %TEST_TARGET%  -actions %ACTIONS%

REM WildcardFileFilter
SET ACTIONS="no_wildcard, question_wildcard, asterisk_wildcard"
php shell.php -proc %TEST_PROC% -p1 %TEST_TARGET%  -actions %ACTIONS%


REM CombinedFileFilter
SET ACTIONS="combined_regex, combined_wildcard, combined_complexed"
php shell.php -proc %TEST_PROC% -p1 %TEST_TARGET%  -actions %ACTIONS%


exit /b;
