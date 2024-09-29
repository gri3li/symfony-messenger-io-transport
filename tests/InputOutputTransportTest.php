<?php

namespace App\Tests;

use Gri3li\SymfonyMessengerIoTransport\IO;
use Gri3li\SymfonyMessengerIoTransport\IOTransport;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;

class InputOutputTransportTest extends TestCase
{
    private const SEND_COUNT = 100;

    public function testEcho(): void
    {
        $io = new IO(['cat']);
        $transport = new IOTransport($io);
        $asserts = [];
        $i = 0;
        while ($i < self::SEND_COUNT) {
            $message = ['number' => $i];
            $asserts[$i]['expect'] = $transport->send(new Envelope((object)$message));
            $i++;
        }
        $j = 0;
        while (true) {
            foreach ($transport->get() as $item) {
                $asserts[$j]['actual'] = $item;
                $j++;
            }
            if ($j >= self::SEND_COUNT) {
                break;
            }
        }
        foreach ($asserts as $assert) {
            $this->assertEquals($assert['expect'], $assert['actual']);
        }
        $io->stop();
    }
}
