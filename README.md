# EventSource

A PHP 5.3 library for creating an [EventSource](http://dev.w3.org/html5/eventsource/) stream.

EventSource or Server-Sent-Events is a W3C specification that defines a protocol and an API
for pushing data from the server to the client. This library is a server-side implementation
of this protocol.

It is designed to be transport agnostic, allowing you to use it with apache directly or with
other webservers, such as mongrel2.

## Fetch

The recommended way to install EventSource is [through composer](http://packagist.org).

Just create a composer.json file for your project:

    {
        "require": {
            "igorw/event-source": "*"
        }
    }

And run these two commands to install it:

    $ wget http://getcomposer.org/composer.phar
    $ php composer.phar install

Now you can add the autoloader, and you will have access to the library:

```php
<?php
require 'vendor/.composer/autoload.php';
```

## Usage

```php
<?php

use Igorw\EventSource\Stream;

foreach (Stream::getHeaders() as $name => $value) {
    header("$name: $value");
}

$handler = function ($chunk) {
    echo $chunk;
    ob_flush();
    flush();
};

$stream = new Stream($handler);

while (true) {
    $stream
        ->event()
            ->setData("Hello World")
        ->end()
        ->flush();
    
    sleep(2);
}
```

And an example JavaScript client:

```JavaScript
var stream = new EventSource('stream.php');

stream.addEventListener('message', function (event) {
    console.log(event.data);
});
```

License
-------
MIT, see LICENSE.
