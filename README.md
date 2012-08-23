# minvc

Minimalist MVC implementation in PHP 5.3, written as a baseline to
explore speed improvement possibilities in the
[Elefant PHP framework](http://www.elefantcms.com/).

## Installation

To install, clone the project into your website:

```
git clone https://github.com/jbroadway/minvc.git
```

And edit the `config/config.php` file. You should now be able
to visit the website in a web browser and see the default example
output.

## Directory structure

minvc has the following folder structure:

```
apps/                    # each app is a subfolder of apps
     example/            # the included example app
             handlers/   # request handlers for the example app
             helpers/    # helper classes for the example app
             models/     # model classes for the example app
             views/      # view templates for the example app
config/                  # global configurations
index.php                # the front controller script
lib/                     # PHP libraries
    minvc/               # the core libraries
tests/                   # PHPUnit unit tests
```

In addition to these, there are a few extra files in the root
of the site:

```
.htaccess                # sample Apache configuration file
bootstrap.php            # put additional global configurations here
composer.json            # for installing libraries via Composer
nginx.conf               # sample Nginx configuration file
README.md                # this readme file
```

## What's here and what's not

minvc is not a complete framework, omitting many things like database
access, input validation, forms, etc. These can easily be satisfied by
including external libraries. What is included are the following:

* Pimple dependency injection container
* Basic autoloader with PSR-0 fallback support
* Very basic output sanitizer (`minvc\Filter::sanitize`)
* Simple controller to route requests to handlers
* Simple view class with pluggable template renderer
* Front controller script with a few niceties:
  * Support for PHP's built-in server
  * Basic CLI support (`php index.php example/hello`)
  * MINVC_ENV environment variable for loading dev/staging/prod configs
  * Optional bootstrap.php file for additional dependency configurations
  * GZip output compression
* Unit tests for the core libraries (run `phpunit tests`)

The functionality is modelled after [Elefant](http://www.elefantcms.com/)
so that we can use it to potentially explore ways to speed up,
clean up, and simplify its underlying framework.
