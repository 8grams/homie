# homie

Homie is a simple Symfony-based PHP framework designed specifically for creating homepages.

Normally, a homepage should have minimal features, but common PHP frameworks are quite complex because they are designed to tackle complex problems when creating web applications. Homie is different—it doesn’t aim to be a complete framework for web applications. Instead, it is designed for building simple homepages that can be installed anywhere, even on a cheap shared hosting provider.

### SQLite Based

Homie relies on SQLite as the backbone of its data management, using it as a persistent database, cache, queue, and more. SQLite is an excellent RDBMS—it is fast, mature, and production-ready. Moreover, it can be installed almost anywhere, from embedded devices to large server fleets.

### Dependencies

Homie is built on top of prominent open-source software to function effectively:
1. [SQLite](https://www.sqlite.org/) as the database
2. [Symfony Components](https://symfony.com/). Homie utilizes various Symfony Components such as http-foundation, routing, http-kernel, and more
3. [Redbean](https://redbeanphp.com/) for database connection and ORM
4. [Plates](https://platesphp.com/) as template engine

## Usage

`pages` directory is where we, as homepage web developers, works most of time. Homie directly maps basic URLs to the `pages` directory. For example, if you want to have URL like this

```
https://example.com/home
```

then, you should create a PHP file named `home.php` inside `pages` directory.

You can start coding using OG native PHP where you can mix PHP code with HTML. But for better maintainability, we recommend writing PHP code first before any HTML code. 

It also handles localization quite well. All of these URLs map to `home.php`.

```
https://example.com/id/home
https://example.com/en/home
```

```
<?php

$name = "Glend";

?>

<h1>Hello, my name is <?=$name ?> </h1>

```

Any file you create in the `pages` directory has direct access to utilities such as database, cache, or http client. 

```
<?php 

// get database instance
$rb = $this->db->init();
$book = $rb->dispense('book');
$book->author = "glend";
$id = $rb->store( $book );

// get cache instance
// $cache = $this->cache;
// $cache->set("book", $id);

?>

<p>Book created with ID <?=$id ?> </p>
```

### Initiate

To initiate Homie, execute `/init` path once. This path will initialize database and configure some settings

```
~$ curl -v https://example.com/init
```

### Local Development

Install Symfony cli

```
curl -sS https://get.symfony.com/cli/installer | bash
```

Start Symfony Dev Server

```
symfony server:start
>>>>>>> 732861b (update readme)
```

## Basic Layout

By default, Homie provides 3 basic layouts, all located in the `pages/layouts` directory: `main`, `navbar`, and `footer`. `main`, As the name suggests, this is the main layout where all pages are attached. It is also where you can define global JavaScript and CSS scripts.

You can override `navbar` or `footer` using functions from Plates, for example

```
<?php 

$name = "Glend";
?>

<?php $this->start('navbar') ?>
    <h1>Override Navbar</h1>
    <p>New Navbar</p>
<?php $this->stop() ?>

```

Like regular `pages` files, `navbar` and `footer` also have direct access to Homie's utilites like database or cache.

### Components

Homie is designed with a component-based approach in mind. We chose Plates for its flexibility and extensibility, allowing us to create component-based layouts.

To create a component, add a file inside `pages/components` directory. You can then use it in any page file, even in the navbar or footer. For example, we create `drawer.php` file:

```
<?php 

$name = "Glend";
?>

<div>
    <?php $this->loadComponent('drawer') ?>
</div>

```

Again, like other regular page files, this component also has direct access to Homie’s utilities, such as the database, cache, and HTTP client.

## Handle Request

### Query parameters

```
https://example.com/home?name=glend&city=Surabaya
```

```
<?php

// all params
$params = $this->getQueryParams();

// specific param
$name = $this->getQueryParams("name");

?>
```

### Query parameters

```
https://example.com/home?name=glend&city=Surabaya
```

```
<?php

// all params
$params = $this->getQueryParams();

// specific param
$name = $this->getQueryParams("name");

?>
```

### Form Data
```
curl -X POST https://example.com/home \
-H "Content-Type: application/x-www-form-urlencoded" \
-d "name=glend&city=Surabaya"
```

```
<?php

// all data
$params = $this->getFormData();

// specific data
$name = $this->getFormData("city");

?>
```

### JSON Payload

```
curl -X POST https://example.com/home \
-H "Content-Type: application/json" \
-d '{"name": "glend", "city": "Surabaya"}'
```

```
<?php

// all data
$params = $this->getJsonPayload();

// specific data
$name = $this->getJsonPayload("name");

?>
```

## Migration

All migrations are located on `migrations` directory. To create new migration, add a file with format `[order number]_[migration name].sql`

```
~$ touch migrations/004_create_user_table.sql
```

And execute `/migrate` path

```
~$ curl -v https://example.com/migrate
```

## Environment Variables

Define environment variables whether through `.env` file located in the root project folder, or direct inject using `EXPORT` command.

## Localization

Homie supports localization out of the box by providing the trans function, which we can access from the pages with `en` as default language. For example:

```
<?php

?>

<p><?= $this->trans('home', 'Rumah') ?></p>
```

Insert data into the translation table with the schema name, locale, and value. For example, the SQL should be:

```
INSERT INTO translation (label, value, locale) VALUES ("home", "Home", "en")
```

### Accessing pages with localization

Attach the language you want to use in the URL as the first path segment. For example:

```
https://example.com/id/home
https://example.com/en/home
```

## Admin Dashboard

Soon