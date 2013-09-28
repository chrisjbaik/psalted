psalted
=========

Baseline Requirements
---------------

**For Windows:**

+ [XAMPP](http://www.apachefriends.org/en/xampp-windows.html) or similar local server

**For all OSes:**

+ [node.js](http://nodejs.org)
+ [bower](http://bower.io)
+ [PHP](http://php.net)
  *Note: PHP is included in XAMPP for windows users.*
  + SQLite3 extension required!


Setup/Running the App
---------------

1. Make sure you point your DocumentRoot at the `/public` folder. The app is initialized from the `index.php` file in your `/public` folder.

2. Run `php composer.phar install` in the main project folder to update all the required package dependencies for the app. From time to time, you will need to run `php composer.phar update` to make sure your packages are up to date.

3. Front-end script management is done using [Bower](http://bower.io) and [RequireJS](http://requirejs.org). Take a look at their respective websites to get a grasp of how they work. Run `bower install` before running the app to ensure that all your front-end dependencies are loaded.

Database Migrations
---------------

1. Make sure you have a file migrations/.migrations.log; otherwise, migrations will not work. Run touch `migrations/.migrations.log` from the app folder to generate it.

2. Make sure that when you pull in updates for the app, you check that all your database migrations are updated correctly. Run `bin/phpmig migrate` to ensure this is done correctly. (If this is your first time, you want to begin with a fresh database. Set that path in `config/database.php`, and take a look at the `config/database.php.example` for help).

3. To revert database changes, run `bin/phpmig rollback`.