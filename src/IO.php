<?php

namespace Gri3li\SymfonyMessengerIoTransport;

use Gri3li\SymfonyMessengerIoTransport\Interface\IOInterface;
use Symfony\Component\Messenger\Exception\InvalidArgumentException;
use Symfony\Component\Messenger\Exception\TransportException;

class IO implements IOInterface
{
    private const STDIN = 0;
    private const STDOUT = 1;
    private const STDERR = 2;
    private mixed $process = false;
    private array $pipes = [];
    private array $descriptorSpec = [
        self::STDIN => ['pipe', 'r'],
        self::STDOUT => ['pipe', 'w'],
    ];

    public function __construct(
        private readonly array $command,
    ) {
    }

    public static function fromDsn(#[\SensitiveParameter] string $dsn, array $options = []): self
    {
        $parsedDsn = parse_url($dsn);
        if ($parsedDsn['scheme'] !== 'stdio') {
            throw new InvalidArgumentException('The given stdio DSN is invalid.');
        }
        $command = explode(' ', $parsedDsn['host']);

        return new self($command);
    }

    public function write(string $data): void
    {
        if (!$this->isStarted()) {
            $this->start();
        }
        fputs($this->pipes[self::STDIN], $data . PHP_EOL);
    }

    public function read(): iterable
    {
        if (!$this->isStarted()) {
            $this->start();
        }
        while ($data = trim(fgets($this->pipes[self::STDOUT]))) {
            yield $data;
        }
    }

    public function isStarted(): bool
    {
        return is_resource($this->process);
    }

    private function getDescriptorsWithParentStdErr(): array
    {
        $descriptors = $this->descriptorSpec;
        $parentStdErr = '/proc/' . getmypid() . '/fd/' . self::STDERR;
        if (file_exists($parentStdErr)) {
            $descriptors[self::STDERR] = ['file', $parentStdErr, 'a'];
        }

        return $descriptors;
    }

    public function start(): void
    {
        $this->process = proc_open($this->command, $this->getDescriptorsWithParentStdErr(), $this->pipes);
        if (!$this->isStarted()) {
            throw new TransportException(
                sprintf('Failed to start process: %s.', implode(' ', $this->command))
            );
        }
        foreach (array_keys($this->descriptorSpec) as $pipe) {
            stream_set_blocking($this->pipes[$pipe], false);
        }
    }

    public function stop(): void
    {
        if ($this->isStarted()) {
            foreach (array_keys($this->descriptorSpec) as $pipe) {
                fclose($this->pipes[$pipe]);
            }
            proc_close($this->process);
        }
    }
}
