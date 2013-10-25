Presta CMS Sandbox
=============

[![Build Status](https://secure.travis-ci.org/prestaconcept/prestacms-sandbox.png)](http://travis-ci.org/prestaconcept/prestacms-sandbox)
[![Latest Stable Version](https://poser.pugx.org/presta/cms-sandbox/v/stable.png)](https://packagist.org/packages/presta/cms-sandbox)
[![Total Downloads](https://poser.pugx.org/presta/cms-sandbox/downloads.png)](https://packagist.org/packages/presta/cms-sandbox)

##Online demo

:information_source: PrestaCMS Demo is online in english and french :

http://sandbox.prestacms.com and http://sandbox.prestacms.fr

Administration is under /admin


## Overview ##

This is a sandbox to test the [PrestaCMSCorebundle](https://github.com/prestaconcept/PrestaCMSCoreBundle)

It is based on :
* [**Symfony Standard edition**](https://github.com/symfony/symfony-standard)
* [**Symfony CMF**](http://symfony.com/doc/master/cmf/index.html)

** Important ** :construction: [WIP] This project is still under heaving development and is not yet ready to use in production.
PrestaConcept is currently developing it for their new website.

If you want to have some informations about the projet progession you can register to our :speech_balloon: [google group](https://groups.google.com/forum/?hl=fr&fromgroups#!forum/prestacms-devs)

## Features ##

- [x] Multiple website
- [x] Custom theming
- [x] Page edition
- [x] WYSIWYG integration

**Todo**
- [ ] Demonstration multiple website
- [ ] Add media block
- [ ] Add a nice documentation

### Front website with custom theming ###

PrestaCMS is made to be easily customized.

It can be plugged to several themes and can benefit of Symfony template inheritance to extends them.

#### Creative Homepage ####
![PrestaCMS Creative Homepage](https://raw.github.com/prestaconcept/prestacms-sandbox/master/app/Resources/docs/assets/creative-home.jpg)

Creative is the first of our basic theme. It use [LESS](http://lesscss.org/) and is based on [Twitter Boostrap](http://twitter.github.com/bootstrap/)


### Integrated with Sonata Admin ###

![PrestaCMS Dashboard](https://raw.github.com/prestaconcept/prestacms-sandbox/master/app/Resources/docs/assets/backend-dashboard.jpg)

### Manage multiple front website from the same backend ###

#### Website administration ####

![PrestaCMS Website administration](https://raw.github.com/prestaconcept/prestacms-sandbox/master/app/Resources/docs/assets/backend-website-administration.jpg)

PrestaCMS can managed multiple websites, each of theme can have a theme, handle multiple locales and have there own content structure.


#### Themes administration ####

![PrestaCMS theme administration](https://raw.github.com/prestaconcept/prestacms-sandbox/master/app/Resources/docs/assets/backend-theme-administration.jpg)

Theme administration allow you to edit the content which is present on every pages of your site like the footer.

#### Content administration ####

![Page edition](https://raw.github.com/prestaconcept/prestacms-sandbox/master/app/Resources/docs/assets/backend-page-edit.jpg)

Each page can have a different template on which you can add as many block as you need.

Thanks to a generic block system easily extendable, your pages can render eaverything you need.

## Installing PrestaCMS Sandbox ##

### Get the code ###

As Symfony uses [Composer](http://getcomposer.org/) to manage its dependencies, the recommended way to create a new project is to use it.

If you don't have Composer yet, download it following the instructions on http://getcomposer.org/ or just run the following command:

    curl -s http://getcomposer.org/installer | php

Then, use the `create-project` command to generate a new Symfony application:

    php composer.phar create-project presta/cms-sandbox path/to/install/prestacms-sandbox -s dev

Composer will install Symfony and all its dependencies under the `path/to/install` directory.

### Create the database ###

Sandbox is configured to work on SQLite. Database file will be create it app/database/app.sqlite.

    app/console doctrine:database:create
    app/console doctrine:schema:create
    chmod 777 app/database app/logs app/cache
    app/console doctrine:phpcr:repository:init
    app/console doctrine:phpcr:fixtures:load --no-interaction
    app/console doctrine:fixture:load --no-interaction
    app/console assets:install --symlink
    app/console assetic:dump --env=prod

If you have 'make' installed on your machine, you can use the install command

    make install

Another command to reload fixtures:

    make refresh

:speech_balloon: : if you have a permission denied error, you just need to add the execution right on your console

    chmod +x app/console

---

If you want more documentation about settings up Doctrine PHPCR-ODM with Jackrabbit or Midgard, have a look at
the [specific documentation](http://symfony.com/doc/master/cmf/tutorials/installing-configuring-doctrine-phpcr-odm.html) or
the [cmf-sandbox set-up](https://github.com/symfony-cmf/cmf-sandbox)

### Virtual host ###

As PrestaCMS depends on host configuration, you have to set up virtual hosts for the sandbox.

**/etc/hosts**

    127.0.0.1 sandbox.prestacms.com
    127.0.0.1 sandbox.prestacms.fr

**vhosts.conf**

    #PrestaCMS Sandbox
    <VirtualHost 127.0.0.1:80>
        DocumentRoot /home/nbastien/www/prestaconcept/prestacms-sandbox/web

        Servername sandbox.prestacms.com
        Serveralias sandbox.prestacms.fr

        ErrorLog "/var/log/apache2/prestacms-sandbox-error.log"
        CustomLog "/var/log/apache2/prestacms-sandbox-access.log" common

        <Directory />
            Options FollowSymLinks
            AllowOverride None
        </Directory>

        <Directory /home/nbastien/www/prestaconcept/prestacms-sandbox/web>
            Options Indexes FollowSymLinks MultiViews
            AllowOverride All
            Order allow,deny
            allow from all
        </Directory>
    </VirtualHost>

### Getting started using Vagrant

please checkout the [README.md](vagrant) in the vagrant/ folder of the project

## Ask for help ##

:speech_balloon: If you need help about this project you can [post a message on our google group](https://groups.google.com/forum/?hl=fr&fromgroups#!forum/prestacms-devs)


---

*This project is supported by [PrestaConcept](http://www.prestaconcept.net)*

:book: **Usefull links**
 * [**PrestaCMSCorebundle Documentation**](https://github.com/prestaconcept/PrestaCMSCoreBundle)
 * [**PrestaConcept Bundles**](https://github.com/prestaconcept)
 * [**Symfony CMF Documentation**](http://symfony.com/doc/master/cmf/index.html)



[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/prestaconcept/prestacms-sandbox/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

