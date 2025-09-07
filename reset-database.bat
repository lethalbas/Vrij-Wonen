@echo off
REM reset-database.bat - Reset database with fresh mock data

echo 🔄 Resetting database with fresh mock data...

REM Stop containers
echo Stopping containers...
docker-compose down

REM Remove database volume to start fresh
echo Removing old database data...
docker volume rm vrij-wonen_db_data 2>nul

REM Start containers with fresh data
echo Starting containers with fresh mock data...
docker-compose up -d --build

REM Wait for database to be ready
echo Waiting for database to initialize...
timeout /t 10 /nobreak >nul

REM Check if database is ready
echo Checking database status...
docker-compose exec db mysql -u root -ppassword -e "SELECT COUNT(*) as object_count FROM vrijwonen.objects;" 2>nul

echo ✅ Database reset complete!
echo 🌐 Website: http://localhost:8080
echo 🗄️  phpMyAdmin: http://localhost:8081
echo.
echo 📊 Mock data includes:
echo    - 8 house objects with Pexels images
echo    - 20 Dutch cities
echo    - 15 properties
echo    - 3 staff users (admin/password)
echo    - 5 sample inquiries
pause
