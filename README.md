psalted
=========

Baseline Requirements
---------------------

+ [XAMPP](http://www.apachefriends.org/en/xampp.html) or similar server compatible with PHP
+ [node.js](http://nodejs.org)
+ [bower](http://bower.io)
  + `npm install -g bower`
+ [grunt](http://gruntjs.com)
  + `npm install -g grunt-cli`
+ [PHP](http://php.net)
  + *Note: PHP is included in XAMPP.*
  + SQLite3 extension required!


Getting Started
---------------

1. Clone this repository to your local computer.

2. Make a copy all files ending in `.example` in the `/config` folder, and remove the `.example` extension so you have `.php` config files.

3. Make sure you point your DocumentRoot at the `/public` folder. The app is initialized from the `index.php` file in your `/public` folder.

4. Start your server, whether it be XAMPP or Apache or nginx, whatever you please.

5.  `npm install`
    This makes sure all the node modules necessary to run Grunt are installed.

6.  `php composer.phar install`
    Run this in the main project folder to update all the required package dependencies for the PHP app.

7.  `php composer.phar update`
    From time to time, you will need to run this to make sure your packages are up to date. Do it now.

8.  `bower install`
    Run this before running the app to ensure that all your front-end dependencies are loaded.

9. Follow instructions for [Using Database Migrations](#using-database-migrations) below.

10. Navigate to [localhost](http://localhost) in your browser and Psalted should load!

Database Migrations
---------------

### What are database migrations? ###

One clunky thing about using SQL-based relational databases is that they have fixed schemas. When you decide on a schema for your model and you set your columns/column types/restrictions, it is difficult to change.

Therefore, you need a way to track different versions of schemas you may have - you may have one version of the User table that only has a username and password and may want to add an email field. In order to do that, you would write a migration in the `/migrations` folder, then apply it by running `bin/phpmig migrate`. Since each migration will be tracked, all developers on different machines can keep track of their own migrations and update as they are able to.

*Note: Sadly, SQLite does not allow you to change column names or remove columns as part of a migration. Just a sad fact of life.*

### Using Database Migrations ###

1. Make sure you have a file migrations/.migrations.log; otherwise, migrations will not work. This file stores what migrations have been already run on your local version of the application. Run `touch migrations/.migrations.log` from the app folder to generate it.

2. If this is your first time, you want to begin with a fresh database. Set a path to a new empty file (e.g. psalted.sqlite) in `config/database.php`, and take a look at the `config/database.php.example` for help.

3. Make sure that when you pull in updates for the app, you check that all your database migrations are updated correctly. Run `bin/phpmig migrate` to ensure this is done correctly.

4. To revert database changes, run `bin/phpmig rollback`.

5. To write your own migration, take a look at the [documentation](https://github.com/davedevelopment/phpmig). A note with that is to make sure that migrations do not corrupt pre-existing data. We want to enforce [logical data independence](http://en.wikipedia.org/wiki/Data_independence).

Developer Documentation
-----------------------

### Stack ###

The back-end uses the lightweight [Slim Framework](http://slimframework.com/) on top of [PHP](http://php.net) and the database is [SQLite 3](http://www.sqlite.org/). The ORM used for the database and models is [Paris/Idiorm](http://j4mie.github.io/idiormandparis/).

The front-end implements [JQuery Mobile](http://jquerymobile.com) as the primary framework.

The philosophy is that we want the app to be as minimal and functional as possible so we don't have to deal with as many technical issues and can focus more on features.

### Folder Structure ###

+   **/bin**

    Contains executables needed for development, namely `phpmig` which is used for database migrations.

+   **/bower_components**

    Is in main `.gitignore` so won't be cloned. Populated by `bower install`. Contains external plugins downloaded using bower. Packages in here can be referenced in [Gruntfile](#grunt).

+   **/config**

    Contains local server configuration files. `hybridauth.php` configures social login, `database.php` configures what database file is being used, and `phpmig.php` configures the database migration tool.

+   **/db**

    Stores the database(s) for the app. Contains a `.gitignore` file; you should probably add your database's path to that `.gitignore` so that a massive file isn't pushed to the repository.

+   **/migrations**

    Contains `.migrations.log`, a text file which keeps track of what migrations have been run on the local copy of the app. In addition, `phpmig` migrations are written in this folder.

+   **/models**

    Contains Paris model files. When you need a new model to be used in the app, you'll need to add a file here, even if it's just an empty class.

+   **/node_modules**

    In `.gitignore`. Used to manage node_modules that would be used with [Browserify](#grunt), as well as modules used to help [Grunt](#grunt) run.

+   **/public**

    -   /css

        Stores JQuery Mobile CSS file and also our default `style.css` file. DO NOT directly edit `style.css`, it is compiled using [Less](#less) from elsewhere.

    -   /hybridauth

        Stores hybridauth endpoint page, probably never needs to be touched.

    -   /img

        Store image assets here.

    -   /js

        Stores [Browserify](#grunt) files. DO NOT directly edit files in here or include files, do it using the [Gruntfile](#grunt) and compile it into the `main.js` or `lib.js` bundles.

    -   `index.php`

        This is the main app file for Slim. App level configurations can be done here.

+   **/public_src**

    -   /less

        Our styles are written here, compiled later into `/public/style.css`.

    -   /libs

        For either internally developed front-end libraries or external front-end libraries that cannot be downloaded and used through Bower or npm.
    -   /main

        Directory structure mirrors `/views`. Each script will go along with a view according to the [script structure](#scripts).

+   **/routes**

    Slim routes are written here. They are split up by first-level URLs. e.g. if the URL is `/groups` or `/groups/group_name`, then the route should be in `groups.php`.

+   **/vendor**

    In `.gitignore`. Used to manage PHP packages and libraries downloaded from [Composer](http://getcomposer.org).

+   **/views**

    Views to be rendered in the routes are stored here. The directory is organized by the relevant model, not the route - if you are dealing with songs, put it in the `/songs` folder. If you are dealing with setlists, place it in the `/setlists` folder even though the route that is using it will be `groups.php` because the URL is `/groups/group_name/setlists`.

### Request/Response Flow Diagram ###

Coming soon...

### When Developing ###

1.  **Composer, Packagist, and Back-end Package Management**

    PHP libraries to be used for Slim and the back-end are from Composer (http://getcomposer.org). Using it is fairly straightforward, `composer.json` stores packages we need and `php composer.phar update` makes sure that we have the correct packages.

2.  **CoffeeScript**

    CoffeeScript is just a more legible syntax for JavaScript. Read about it [here](http://coffeescript.org). We use this for all our front-end scripts.

3.  <a name='scripts'></a>**Script Structure**

    Scripts are linked to their respective views in two ways:

    1. The `/public_src/main` folder mirrors the `/views` folder, meaning that a .coffee or .js file written in `/public_src/main/songs` will correspond to the view in `/views/songs`.

    2.  The script uses JQuery Mobile's `pageinit` event.
        Since all the scripts are eventually bundled into one file, we distinguish which script runs on which page using the `pageinit` event. Here's an example:

        ```coffeescript
        $ = require('jquery')
        $.mobile = require('jquery-mobile')

        $(document).delegate "#admin-groups", "pageinit", ->
          # Your code goes here
        ```

        The `#admin-groups` is the page id, which is formatted #directory-filename. That is, the `/public_src/main/songs/view.coffee` should have a page-id `#songs-view`.

4.  <a name='grunt'></a>**Grunt, Browserify, and Gruntfile.coffee**

    Perhaps the most confusing part of development is going to be dealing with [Browserify](http://browserify.org/). What it does is that it allows us to use CommonJS-style `require` calls to manage dependencies on the front-end, so that our code doesn't get cluttered with global variables and libraries that are included with 50 script tags on our pages.

    We essentially just load two scripts, `lib.js`, which contains external libraries that will be frequently used (like JQuery or JQuery Mobile), and `main.js`, which contains all our front-end scripts mashed into one file. The philosophy for that is [in a blogpost](http://benclinkinbeard.com/blog/2013/08/external-bundles-for-faster-browserify-builds/).

    The Browserify configuration is located in `Gruntfile.coffee`. It will automatically take care of most things - one thing that you want to do is that when you install an external front-end package, you probably want to link to it under `browserify:mains:alias` in the config so you can have a convenient shortcut. That is, if your file is in `bower_components/my_library/thiscrazyfilename.min.beta-5.19.x.js`, you probably want to alias it to simply be `my_library` so you can easily call `require('my_library')` when importing it. More details [here](https://github.com/jmreidy/grunt-browserify).

5.  <a name='packages'></a>**Bower, NPM and Front-end Package Management**

    Front-end packages can be downloaded in three ways, but there's a catch so make sure to read the notes on each:
    1.  [Bower](http://bower.io)

        If you download a package via Bower, alias the script that you need into the Browserify config in the [Gruntfile](#grunt). Many front-end packages, however, do not include the distributable script (meaning you'd have to manually compile it), so in that case it might be easier just to download the `.js` file and go with option 3 of manually downloading it into `/public_src/libs` and then aliasing it in the [Gruntfile](#grunt).

        Even if you do have a script from something installed via Bower, make sure it is CommonJS compatible. If you open up the script and there is something like:

        ```javascript
        if (typeof exports === "object" && exports) {
          module.exports = factory;
        }
        ```

        That is a good sign. If not, you can either [shim](https://github.com/jmreidy/grunt-browserify#shim) it in the [Gruntfile's](#grunt) configuration, or you may want to just manually add it in and then go with option 3 again.

    2.  [NPM](http://npmjs.org)

        Same deal as Bower above, pretty much. Some good package authors will make CommonJS compatible packages for both the front-end and back-end, so if that's the case you're in luck if you download your package from npm because you won't even need to alias it, you can just directly require the package name from NPM and Browserify will load it for you!
        
    3.  Manually downloaded into `/public_src/libs`.

        You want to alias whatever manually downloaded files you have in the [Gruntfile](#grunt). Read the instructions under the Bower section in point 1 because the same things apply about CommonJS.

6.  <a name='less'></a>**LESS**

    LESS is used to write simpler CSS. We write all our styles into `/public_src/less` and it will automatically compile into `/public/css/style.css` when `grunt watch` is running.

7.  `grunt watch`

    So after the lengthy explanations above, we need to make sure this is running at all times while developing in a terminal somewhere.

    This makes sure all your front-end scripts compile correctly into their respective bundles for the browser. If you don't do this, pages will load with outdated javascript because you didn't compile! For a one-time bundle or if you are updating the external libraries file, just run `grunt browserify`.

    This will also watch changes to the LESS file and compile the CSS accordingly.
