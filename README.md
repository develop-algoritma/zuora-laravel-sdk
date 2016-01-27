# zuora-laravel-sdk
Lumen/Laravel service provider for interacting with [Zuora SOAP API](https://knowledgecenter.zuora.com/BC_Developers/SOAP_API)

[![Build Status](https://travis-ci.org/spira/zuora-laravel-sdk.svg?branch=master)](https://travis-ci.org/spira/zuora-laravel-sdk) 
[![Coverage Status](https://coveralls.io/repos/github/spira/zuora-laravel-sdk/badge.svg?branch=master)](https://coveralls.io/github/spira/zuora-laravel-sdk?branch=master)
[![StyleCI](https://styleci.io/repos/50143983/shield)](https://styleci.io/repos/50143983)

# Install and configuration

You can install this package via [Composer](http://getcomposer.org).

## Direct usage (framework agnostic)

For using Zuora API you should register an account and get credentials for accessing an API.
All credentials are passed into `API::__construct` method in array with structure:
~~~ php
[
    'wsdl'     => '/path/to/zuora.wsdl',
    'username' => 'user@example.com',
    'password' => 'very-good-password',
    'endpoint' => 'https://apisandbox.zuora.com/apps/services/a/74.0',
]
~~~
You can find sample config file in `storage/config.dist.php`

## Lumen\Laravel Service Provider

1. Register `ZuoraSdkServiceProvider` in your app
2. Create a config file named `zuora.php` in config folder and put there contents of `storage/config.dist.php`
3. Have fun

# Tests

This library is tested using PHPUnit and [Mockery](http://docs.mockery.io/en/latest/index.html).

If you want to run full set of integration tests you should fill your `storage/config.php` file, which is excluded from source tree and copied automatically from `config.dist.php` with Composer's `post-install-cmd`. Otherwise all tests making real api calls will be skipped. *Make sure you provided sandbox credentials!*

# Class Reference

All classes are located under `Spira\ZuoraSdk` namespace is omitted below.

## API

This class:
* Configures and stores `\SoapClient`, you can use `getClient()` and `setClient(\SoapClient $client)` methods for access
* Performs lazy authorization on first request 
* Provides methods for operating with `DataObject` entites
* Provides methods for selecting `DataObject` with ZOQL
* Converts Zuora API Errors to thrown `\Spira\ZuoraSdk\Exception\ApiException`

Current list of implemented methods:
* `create($objects)` - Create object in API. Accepts `DataObject|DataObject[]`
* `update($objects)` - Update object in API. Accepts `DataObject|DataObject[]`
* `delete($type, $ids)` - Delete objects of `$type` with IDs `int|array $ids`
* `query($query, $limit = null)` - Runs a query and returns objects of type was queried.
* `queryMore($limit)` - If resultset for `query()` has few pages use this method to get next pages of previous call.
* `hasMore()` - Check does last `query()` has next page of resultset.

## DataObject

`DataObject` is an object for storing data while operating with API. 

It extends `\Illuminate\Support\Fluent` so all data manipulation is done easilly in array- or object-like syntax.

## QueryBuilder

Zuora API provides [ZOQL](https://knowledgecenter.zuora.com/BC_Developers/SOAP_API/M_Zuora_Object_Query_Language) - simplified query language for querying objects from api.

QueryBuilder allows building such queries in fluent style:
~~~ php
$builder = new QueryBuilder('Products', ['Id', 'Name']);
$builder->where('Age', '=', $age)
    ->orWhere('Id', '=', $id);
echo $builder->toZoql(); // Output: SELECT Id, Name FROM Products WHERE Age = 18 OR Id = 1
~~~

Mostly it used for making queries in `Zuora` class described below.

## Zuora

This class provides querying helpers and some part of common logic for using API.

* `getAll($table, array $columns, $limit = null, \Closure $filtered = null)` - builds a query and returns an array of objects of type `$table`
* `getOne($table, array $columns, \Closure $filtered = null)` - builds a query and returns one object of type `$table`
* `getOneById($table, $columns, $id)` - pretty the same as `getOne` but with built-in filter by id.

Instance of `QueryBuilder` is passed into `$filtered` lambda for adding conditions to query if needed.
While ZOQL does not support wildcards for columns you should provide list of them manually.

For some types of objects there are added custom methods:
* `getAllProducts($limit = null, array $columns = null)`
* `getOneProduct($id, array $columns = null)`

# Usage Example

~~~ php
use Monolog\Logger;
use Spira\ZuoraSdk\API;
use Spira\ZuoraSdk\Zuora;
use Monolog\Handler\StreamHandler;
use Spira\ZuoraSdk\DataObjects\Product;

$config = require '/path/to/config.php';
$logger = new Logger('zuora', [new StreamHandler('path/to/zuora.log')]); // optional logger usage
$api    = new API($config, $logger);
$zuora  = new Zuora($api);

// Create a new product

$product = new Product(['Name' => 'My Product']);
$api->create($product);

// Get list of a products

/** @var $products Product[] */
$products = $zuora->getAllProducts();

// Delete product

$api->delete('Product', [$product->Id]);
~~~

You can find more samples in tests.

# Contribution

All commits MUST follow [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) coding style guide.

You may use the [PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) manually or use git `pre-commit` hook supplied in `hooks/pre-commit`. Follow instructions in file and make sure it is executable.
