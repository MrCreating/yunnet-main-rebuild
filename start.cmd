@echo off
echo [+] Init the project ...
cd config\local
set PWD=%cd%
set UNT_PRODUCTION=0
docker-compose up -d 2>&1 && echo [+] Project started. To stop run stop.cmd || echo [!] Failed to start project.
pause