@echo off

echo [+] Start building the project ...
type nul > logs\build.log || mkdir logs && touch logs\build.log

cd config\local

set PWD=%cd%
set UNT_PRODUCTION=0

mkdir docker\context\memcached > ..\..\logs\build.log 2>&1 || echo [?] The memcached context already created.
mkdir docker\context\nginx > ..\..\logs\build.log 2>&1 || echo [?] The nginx context already created.
mkdir docker\context\rabbitmq > ..\..\logs\build.log 2>&1 || echo [?] The rabbitmq context already created.
echo [+] Building project ...

(docker-compose build > ..\..\logs\build.log 2>&1) && echo [+] Build success. Now you can start the project. || echo [!] Build failed. See logs\build.txt for details.

echo [+] Cleanup contexts...
del /f /q docker\context\nginx  > ..\..\logs\build.log 2>&1 && echo [+] Cleaned nginx context || echo [!] Context for nginx failed to clean.
del /f /q docker\context\memcached  > ..\..\logs\build.log 2>&1 && echo [+] Cleaned memcached context || echo [!] Context for memcached failed to clean.
del /f /q docker\context\rabbitmq  > ..\..\logs\build.log 2>&1 && echo [+] Cleaned rabbitmq context || echo [!] Context for rabbitmq failed to clean.

pause