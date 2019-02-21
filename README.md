# Amazium : Config Aggregator

Merge config files in a tree structure. Subdirectories and files are added as keys in the returned config array.

Suppose following file structure:

* project.config.php
* dependencies/
    - factories.php
    - services.php

This will create a config array with contents of the project.config.php file in the root, followed by:

```php
$config = [
    // config elements from project.config.php
    'dependencies' => [
        'factories' => <whatever is returned in factories.php>,
        'services' => <whatever is returned in services.php>,
    ],
];

```  

Currently yaml, json and php config array files are supported.

## Installation

Installation via composer:

```$xslt
composer require amazium/amz-config
```

## Explicit Aggregator Factory

You can explicitely tell the system which aggregator to use, you can specify a directory or file. The file needs
to be parsible by the selected aggregator.

When providing a directory, the files of this extension are also included als elements of the root object. A file
named `project.config.php` will have his contents in the `project` key of the config array. A consequence is that
files and sub paths are on the same level in this structure, while when you have a file as root, this file acts as
the root element.

Example usage:

```php
use Amz\Config\AggregateFactory;

$aggregator = AggregatorFactory::createPhpAggregator('/path/to/project.config.php');
$config = $aggregator->aggregate();

// OR:

$aggregator = AggregatorFactory::createPhpAggregator('/path/to');
$config = $aggregator->aggregate();

```

## Lazy Aggregator Factory

It is possible to pass the root config file to the static `createAggregatorByExtension` method of the 
`AggregatorFactory` factory. This will inflect the right aggregator from the file extension. It will then
add all config files of the same extension in the subdirectory structure.

Example usage:

```php
use Amz\Config\AggregateFactory;

$aggregator = AggregatorFactory::createAggregatorByExtension('/path/to/project.config.php');
$config = $aggregator->aggregate();
```