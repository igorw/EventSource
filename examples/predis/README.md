# Redis PUB/SUB example

How to use the EventSource library with redis PUB/SUB.

This example uses the excellent [predis](https://github.com/nrk/predis) library
to connect to a locally running redis server.

The way it works is this:

* `stream.php` accepts a request from an EventSource client.
* It opens a connection to the redis server and subscribes to the
  `notifications` channel.
* It waits until a message is published into that channel.
* Once a message comes in, it sends it to the client though the EventSource
  stream.

As soon as anyone publishes something into that channel, all connected clients
will receive the message.

## Stop talking! I want to run it now!

Just follow these simple steps:

    $ wget http://getcomposer.org/composer.phar
    $ php composer.phar install
    # make sure you have redis installed
    $ redis-server
    # open client.html in your browser
    $ redis-cli PUBLISH notifications "this is a test"
    $ redis-cli PUBLISH notifications "OMGOMGOMG ITS AMAZING"


