Documentation link : https://documenter.getpostman.com/view/6426562/2s8YYFsjP1

Installation steps

-   Clone the repository by running the git command: git clone https://github.com/tholscoa/estate_intel.git

-   After you have clone repository successfully. Download the dependencies by running: composer install
-   Make a copy of .env.example file and rename it to .env
-   Create a DB on your DBMS
-   Update your .env file with the correct DB details: E.g DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=estate_intel
    DB_USERNAME=takinnuoye
    DB_PASSWORD=Nigeria@123
-   Run migrations to create table on your DB by running the command: php artisan migrate
-   Serve your application by running the command: php artisan serve
-   You can now consume the endpoints
