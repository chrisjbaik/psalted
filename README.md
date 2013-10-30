psalted
=========

Baseline Requirements
---------------

+ [XAMPP](http://www.apachefriends.org/en/xampp.html) or similar server compatible with PHP
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

2. Start your server, whether it be XAMPP or Apache or nginx, whatever you please.

2. `npm install`
This makes sure all the node modules necessary to run Grunt are installed.

3. `php composer.phar install`
Run this in the main project folder to update all the required package dependencies for the app. From time to time, you will need to run `php composer.phar update` to make sure your packages are up to date.

4. `bower install`
Front-end script management is done using [Bower](http://bower.io) and [Browserify](http://browserify.org/). Take a look at their respective websites to get a grasp of how they work. Run `bower install` before running the app to ensure that all your front-end dependencies are loaded. The Browserify config is located in `Gruntfile.coffee`. The philosophy for using it is [located here](http://benclinkinbeard.com/blog/2013/08/external-bundles-for-faster-browserify-builds/)

5. `grunt watch`
While developing, make sure this is running in a console somewhere. This makes sure all your front-end scripts compile correctly into their respective bundles for the browser. If you don't do this, pages will load with outdated javascript because you didn't compile! For a one-time bundle or if you are updating the external libraries file, just run `grunt browserify`.

6. Navigate to [localhost](http://localhost) in your browser and Psalted should load!

Database Migrations
---------------

1. Make sure you have a file migrations/.migrations.log; otherwise, migrations will not work. Run `touch migrations/.migrations.log` from the app folder to generate it.

2. Make sure that when you pull in updates for the app, you check that all your database migrations are updated correctly. Run `bin/phpmig migrate` to ensure this is done correctly. (If this is your first time, you want to begin with a fresh database. Set a path to a new empty file (e.g. psalted.sqlite) in `config/database.php`, and take a look at the `config/database.php.example` for help).

3. To revert database changes, run `bin/phpmig rollback`.