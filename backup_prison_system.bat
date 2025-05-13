@echo off
setlocal

:: === CONFIGURATION ===
set DB_USER=root
set DB_PASS=
set DB_NAME=prison_system
set MYSQLDUMP_PATH="D:\E-books\mysql\bin\mysqldump.exe"
set BACKUP_DIR=D:\DB PROJ\backups

:: Create the backup directory if it doesn't exist
if not exist "%BACKUP_DIR%" (
    mkdir "%BACKUP_DIR%"
)

:: Get timestamp
for /f "tokens=1-4 delims=/ " %%a in ("%date%") do (
    set day=%%a
    set month=%%b
    set year=%%c
)
for /f "tokens=1-2 delims=: " %%a in ("%time%") do (
    set hour=%%a
    set minute=%%b
)

:: Format timestamp
set TIMESTAMP=%year%-%month%-%day%_%hour%-%minute%
set BACKUP_FILE=%BACKUP_DIR%\%DB_NAME%_%TIMESTAMP%.sql

:: Perform the backup
%MYSQLDUMP_PATH% -u%DB_USER% -p%DB_PASS% %DB_NAME% > "%BACKUP_FILE%"

:: Check if backup was successful
if %ERRORLEVEL% neq 0 (
    echo ❌ Backup failed!
) else (
    echo ✅ Backup successful! File saved as: %BACKUP_FILE%
)

pause
