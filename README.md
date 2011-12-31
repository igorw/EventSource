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

The first thing you need to do is output the EventSource headers, so that the
client it's talking to an EventSource server.

```php
<?php

use Igorw\EventSource\Stream;

foreach (Stream::getHeaders() as $name => $value) {
    header("$name: $value");
}
```

After that you create a ``Stream`` which provides a nice API for creating events.
Once you call flush, all queued events are sent to the client.

This example will send a new event every 2 seconds.

```php
<?php

use Igorw\EventSource\Stream;

$stream = new Stream();

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

### Custom handler

By default the library will assume you are running in a traditional apache-like
environment. This means that output happens through echo. If you are using a
server that handles web output in a different way (eg. app server), then you
will want to change this.

A handler is simply a function that takes a chunk (a single event) and sends it
to the client. You can define it as a lambda. Here is the default handler:

```php
<?php

$handler = function ($chunk) {
    echo $chunk;
    ob_flush();
    flush();
};
```

You just pass it to the constructor of the stream:

```php
<?php

$stream = new Stream($handler);
```

License
-------
MIT, see LICENSE.
