psalted
=========

Baseline Requirements
---------------

+ [XAMPP](http://www.apachefriends.org/en/xampp.html) or similar local server compatible with PHP
+ [node.js](http://nodejs.org)
+ [bower](http://bower.io)
  + `npm install -g bower`
+ [grunt](http://gruntjs.com)
  + `npm install -g grunt-cli`
+ [PHP](http://php.net)
  + *Note: PHP is included in XAMPP.*
  + SQLite3 extension required!


Quick Start
---------------

1. Make sure you point your DocumentRoot at the `/public` folder. The app is initialized from the `index.php` file in your `/public` folder.

2. Start your local server, whether it be XAMPP or Apache or nginx, whatever you please.

2. `npm install`
This makes sure all the node modules necessary to run Grunt are installed.

3. `php composer.phar install`
Run this in the main project folder to update all the required package dependencies for the app. From time to time, you will need to run `php composer.phar update` to make sure your packages are up to date.

4. `bower install`
Front-end script management is done using [Bower](http://bower.io) and [RequireJS](http://requirejs.org). Take a look at their respective websites to get a grasp of how they work. Run this before running the app to ensure that all your front-end dependencies are loaded.

5. Navigate to [localhost](http://localhost) in your browser and Psalted should load!

Database Migrations
---------------

1. Make sure you have a file migrations/.migrations.log; otherwise, migrations will not work. Run touch `migrations/.migrations.log` from the app folder to generate it.

2. Make sure that when you pull in updates for the app, you check that all your database migrations are updated correctly. Run `bin/phpmig migrate` to ensure this is done correctly. (If this is your first time, you want to begin with a fresh database. Set that path in `config/database.php`, and take a look at the `config/database.php.example` for help).

3. To revert database changes, run `bin/phpmig rollback`.