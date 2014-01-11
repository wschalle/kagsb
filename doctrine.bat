@echo off

if "%PHPBIN%" == "" set PHPBIN=C:\Program Files (x86)\PHP\v5.3\php.exe
REM if not exist "%PHPBIN%" if "%PHP_PEAR_PHP_BIN%" neq "" goto USE_PEAR_PATH
GOTO RUN
:USE_PEAR_PATH
set PHPBIN=%PHP_PEAR_PHP_BIN%
:RUN
"%PHPBIN%" "%~dp0\doctrine.php" %*
