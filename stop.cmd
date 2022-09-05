@echo off
echo [+] Init the project ...
cd config\local
set PWD=%cd%
set UNT_PRODUCTION=0
docker-compose down 2>&1 && echo [+] Project stopped. || echo [!] Failed to stop project.
pause