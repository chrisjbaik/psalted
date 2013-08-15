sawadicop
=========

Setup/Running the App
---------------

1. Make sure you point your Apache DocumentRoot at the `/public` folder. The app is initialized from the `index.php` file in your `/public` folder.

2. Run `php composer.phar install` in the main project folder to update all the required package dependencies for the app. From time to time, you will need to run `php composer.phar update` to make sure your packages are up to date.

Database Migrations
---------------

1. Make sure that when you pull in updates for the app, you check that all your database migrations are updated correctly. Run `bin/phpmig migrate` to ensure this is done correctly. (If this is your first time, you want to begin with a fresh database. Set that path in `config/database.php`, and take a look at the `config/database.php.example` for help).

2. To revert database changes, run `bin/phpmig rollback`.