Population Search
=================

A simple tool to search Finnish towns and their population. Project is unfinished on purpose and the intentions is for developers to fork the project and complete it.


Tools in use
------------

* Symfony 2
* Composer package management
* Git version control

Goals
-----

Fork this repository and implement the features below. The project basics and some initial routes are already implemented. The task is to search data from a .txt file and show it. The file has the following format:

    zip code;town;population

The first column is the zip code, then the name of the town and the population. For example:

    02920;Niipperi;5031
    02940;Lippajärvi;10078
    02970;Kalajärvi;3577

### Requirements

The app should allow searching of a town based on name or the zip code. The given town and it's population should shown in the table, as well as the nearest N number of towns in population.

### Additional features

Bonus points are given for the following extra features:

* Unit tests
* Implement separate settings page to set how many towns are shown in the table
* Visualize the table data somehow
* Add autocomplete to the search form
* 

### Getting started
* Fork this repository using the [Fork](https://github.com/Sujuwa/challenge/fork) button on the top of the page.
* Clone your own fork to your local computer `git clone git@github.com:YOUR_ACCOUNT/challenge.git`
* Install/setup [Composer package management](http://getcomposer.org) (i.e. download the Composer.phar file to the project root folder).
* Refer to the [Symfony 2 official homepage](http://symfony.com/doc/current/book/installation.html) for Symfony 2 project setup.
* Run `php composer.phar install` to install dependencies.
* Run `php app/console assets:install --symlink web` and `php app/console assetic:dump` to dump assets files.

That's it, try to access the application at `http://localhost/challenge/web/app_dev.php` (or `app.php` for production mode).

Please feel free to add the libraries that you need!

##### Dump assets
If you make changes to asset files (.css, .js), make sure to run `php app/console assetic:dump`. Alternatively, you can keep a watcher running with `php app/console assetic:dump --watch`.

##### Clearing the cache
To clear the cache (sometimes needed depending on the situation), run `php app/console cache:clear --no-warmup`. Usually you don't want to warm up the cache.

---

Datasource: http://www.stat.fi/tup/posnro/vakiluku_posnro_2011.xls (Tilastokeskus: Väkiluku postinumeroalueittain 31.12.2011, XLS)
