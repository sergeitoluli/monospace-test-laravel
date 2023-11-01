<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>



## Please follow this steps

- Run [cp .env.example .env]
- Run [composer install]
- Run [php artisan migrate]
- Run [php artisan db:seed]

Then it is time to fix your [.env] file: 
Fix your database connection as follows:
-DB_CONNECTION=pgsql
-DB_HOST=127.0.0.1
-DB_PORT=5432
-DB_DATABASE=your_db
-DB_USERNAME=username
-DB_PASSWORD=password

Fix your APP_KEY by this:
-APP_KEY=base64:

Then run 
-[php artisan key:generate]

At the end run 
-[php artisan serve]