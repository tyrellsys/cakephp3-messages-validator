# CakePHP3 Messages Validator plugin

CakePHP3 Validator that set the validation message

## Requirements

The master branch has the following requirements:

* CakePHP >=3.6.0,<4.0.0
* PHP 5.6.0 or greater

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require tyrellsys/cakephp3-messages-validator
```

Load your pligin load Tyrellsys/CakePHP3MessagesValidator
```
bin/cake plugin load Tyrellsys/CakePHP3MessagesValidator
```
or by manually putting `$this->addPlugin('Tyrellsys/CakePHP3MessagesValidator')` in your `Application.php`.

## Config

set messages `messagesValidator.messages`.

config/messages.php

```php
return [
    'messagesValidator' => [
        'messages' => [
            'required' => 'required messages',
            'notEmpty' => 'notEmpty messages',
            'maxLength' => 'maxLength messages',
        ]
    ]
];

// for po file setting
__d('validation', 'required messages');
__d('validation', 'notEmpty messages');
__d('validation', 'maxLength messages');

// fieldName for po file setting
__d('validation', 'fieldName');

```


and putting `config/bootstrap.php`.
```
Configure::load('messages');
```

## Model validation

```php
namespace App\Model\Table;

...

class PostsTable extends Table
{
    protected $_validatorClass = \Tyrellsys\CakePHP3MessagesValidator\Validation\Validator::class;
}
```
