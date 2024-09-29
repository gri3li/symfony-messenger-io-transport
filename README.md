Symfony Messenger Stdio Transport
=================================

This package provides transport for
the [Symfony Messenger](https://symfony.com/doc/current/components/messenger.html) component, enabling integration of
external console processes into your microservice architecture. Messages are exchanged via input/output streams,
allowing seamless communication between Symfony Messenger and standalone processes that may not support standard Symfony
Messenger message formats.

Installation
------------

Install the package via Composer:

```bash
composer require gri3li/symfony-messenger-io-transport
```

Usage
-----

This transport is particularly useful when integrating microservices or third-party systems that do not natively support
Symfony Messenger message formats. It allows you to interface with external console processes while leveraging the
flexibility and power of the Symfony Messenger component.

Since all messages will be serialized and deserialized as instances of `StdClass`, you will most likely need to provide
custom implementations of the interfaces:

- `SendersLocatorInterface`: Defines which sender will be used for dispatching a message.
- `HandlersLocatorInterface`: Defines which handler will process the message.





