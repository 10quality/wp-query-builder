# Wordpress Query Builder Library

Provides a Query Builder class built over Wordpress core Database class accessor. Usability is similar to Laravel's Eloquent.

The library also provides an anstract class and a trait to be used on data models built for custom tables. The abstract class extends our generic [PHP model](https://github.com/10quality/php-data-model) class.

## Install

This package / library requires composer.

```bash
composer require 10quality/wp-query-builder
```

## Usage

### Query Builder

#### Instantiate

```php
use TenQuality\WP\Database\QueryBuilder;
```

Regular constructor:

```php
$builder = new QueryBuilder();
```

Static constructor:

```php
$builder = QueryBuilder::create();
```

### Chaining statements

All query statements can be chained, in example:

```php
$results = QueryBuilder::create()
    ->select( '*' )
    ->from( 'posts' )
    ->get();
```

### Model class

For basic model usage, see our [PHP data model documentation](https://github.com/10quality/php-data-model).

## License

MIT License (c) 2019