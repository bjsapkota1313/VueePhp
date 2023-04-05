@echo off
:start
npm run dev
ping -n 601 127.0.0.1 > nul
taskkill /F /IM node.exe > nul
goto start
