<?php

namespace Gri3li\SymfonyMessengerIoTransport;

use Gri3li\SymfonyMessengerIoTransport\Interface\IOInterface;
use Gri3li\SymfonyMessengerSerializerPlain\PlainSerializer;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class IOTransport implements TransportInterface
{
    private OutputReceiver $receiver;
    private InputSender $sender;

    public function __construct(
        private readonly IOInterface $io,
        private readonly SerializerInterface $serializer = new PlainSerializer(),
    ) {
    }

    public function send(Envelope $envelope): Envelope
    {
        return $this->getSender()->send($envelope);
    }

    public function get(): iterable
    {
        yield from $this->getReceiver()->get();
    }

    public function ack(Envelope $envelope): void
    {
        $this->getReceiver()->ack($envelope);
    }

    public function reject(Envelope $envelope): void
    {
        $this->getReceiver()->reject($envelope);
    }

    private function getReceiver(): OutputReceiver
    {
        return $this->receiver ??= new OutputReceiver($this->io, $this->serializer);
    }

    private function getSender(): InputSender
    {
        return $this->sender ??= new InputSender($this->io, $this->serializer);
    }
}
