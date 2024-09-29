<?php

namespace Gri3li\SymfonyMessengerIoTransport\Interface;

use Symfony\Component\Messenger\Exception\TransportException;

interface OutputInterface
{
    /**
     * Reading from a stream descriptor
     *
     * @return iterable<string>
     * @throws TransportException
     */
    public function read(): iterable;
}
