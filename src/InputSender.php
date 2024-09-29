<?php

namespace Gri3li\SymfonyMessengerIoTransport;

use Gri3li\SymfonyMessengerIoTransport\Interface\InputInterface;
use Gri3li\SymfonyMessengerSerializerPlain\PlainSerializer;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\TransportException;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

readonly class InputSender implements SenderInterface
{
    public function __construct(
        private InputInterface $input,
        private SerializerInterface $serializer = new PlainSerializer(),
    ) {
    }

    public function send(Envelope $envelope): Envelope
    {
        try {
            ['body' => $data] = $this->serializer->encode($envelope);
        } catch (\Throwable $throwable) {
            throw new TransportException($throwable->getMessage(), 0, $throwable);
        }
        $this->input->write($data);

        return $envelope;
    }
}
