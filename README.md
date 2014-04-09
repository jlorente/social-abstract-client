Social Abstract Client (v.1.0.0-beta)
=====================================

A PHP abstraction layer for differents social networks OAuth and API operations

This repository contains an open source PHP library that allows you to 
access Twitter and Facebook from your server. The Social Abstract Client is 
licensed under the GPLv3 (https://www.gnu.org/copyleft/gpl.html).


Usage
-----

Package must be installed with composer in order to install dependencies:

- Add the `"jlorente/social-abstract-client": "@stable"` into the `require` section of your `composer.json`.
- Run `composer install`.

First of all you must establish the configuration keys of your application in the 
config file.
To begin the OAuth process:
```php
// .config/config.php
return [
    'facebook' => [
        'appId' => '', //application api id for facebook
        'appSecret' => '', //application api secret for facebook
        'scope' => 'email, user_birthday, publish_actions' //facebook scopes
    ],
    'twitter' => [
        'appId' => '', //twitter application consumer key
        'appSecret' => '' //twitter application consumer secret
        //Twitter scopes must be defined in dev.twitter.com
    ]
];
```

To receive Authorization callback and obtain the user credentials. 
Credentials have to be stored in your persistance system in order to perform api calls on behalf the user. 
It size may vary depending on the concrete social network in use.
```php
use \jlorente\social\networks\ClientFactory;

if (($loader = require_once __DIR__ . '/vendor/autoload.php') == null)  {
  die('Vendor directory not found, Please run composer install.');
}

$client = ClientFactory::create(ClientFactory::FACEBOOK);
$client->credentialsRequest();
$credentials = $client->getCredentials();
```

To obtain information about the user with the stored credentials.
```php
use \jlorente\social\networks\ClientFactory;

if (($loader = require_once __DIR__ . '/vendor/autoload.php') == null)  {
  die('Vendor directory not found, Please run composer install.');
}

$client = ClientFactory::create(ClientFactory::FACEBOOK);
$client->setCredentials($credentials);

$userInfo = $client->getUserInfo();
```

To obtain information about the user with the stored credentials.
```php
use \jlorente\social\networks\ClientFactory;

if (($loader = require_once __DIR__ . '/vendor/autoload.php') == null)  {
  die('Vendor directory not found, Please run composer install.');
}

$client = ClientFactory::create(ClientFactory::FACEBOOK);
$client->setCredentials($credentials);

$userInfo = $client->getUserInfo();
```

To publish something on behalf the user.
```php
use \jlorente\social\networks\ClientFactory;

if (($loader = require_once __DIR__ . '/vendor/autoload.php') == null)  {
  die('Vendor directory not found, Please run composer install.');
}

$client = ClientFactory::create(ClientFactory::TWITTER);
$client->setCredentials($credentials);

$publication = new \jlorente\social\networks\twitter\Publication();
$publication->setMessage('This is a tweet');
$userInfo = $client->publish(publication);
```

To revoke the credentials of a user
```php
use \jlorente\social\networks\ClientFactory;

if (($loader = require_once __DIR__ . '/vendor/autoload.php') == null)  {
  die('Vendor directory not found, Please run composer install.');
}

$client = ClientFactory::create(ClientFactory::FACEBOOK);
$client->setCredentials($credentials);
$client->revokeCredentials();
```
