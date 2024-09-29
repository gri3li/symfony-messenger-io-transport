<?php

namespace Gri3li\SymfonyMessengerIoTransport\Interface;

use Symfony\Component\Messenger\Exception\TransportException;

interface InputInterface
{
    /**
     * Writing to a stream descriptor
     *
     * @param string $data
     * @return void
     * @throws TransportException
     */
    public function write(string $data): void;
}
