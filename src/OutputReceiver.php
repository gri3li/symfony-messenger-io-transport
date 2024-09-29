<?php

namespace Gri3li\SymfonyMessengerIoTransport;

use Gri3li\SymfonyMessengerIoTransport\Interface\OutputInterface;
use Gri3li\SymfonyMessengerSerializerPlain\PlainSerializer;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\TransportException;
use Symfony\Component\Messenger\Transport\Receiver\ReceiverInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

readonly class OutputReceiver implements ReceiverInterface
{
    public function __construct(
        private OutputInterface $output,
        private SerializerInterface $serializer = new PlainSerializer(),
    ) {
    }

    public function get(): iterable
    {
        foreach ($this->output->read() as $body) {
            try {
                yield $this->serializer->decode(['body' => $body]);
            } catch (\Throwable $throwable) {
                throw new TransportException($throwable->getMessage(), 0, $throwable);
            }
        }
    }

    public function ack(Envelope $envelope): void
    {
    }

    public function reject(Envelope $envelope): void
    {
    }
}
