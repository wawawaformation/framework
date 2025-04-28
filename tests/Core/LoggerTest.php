<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Core\Logger;

final class LoggerTest extends TestCase
{
    private string $logFile;

    protected function setUp(): void
    {
        $this->logFile = LOG . '/error.log';

        // Nettoyage avant chaque test
        if (file_exists($this->logFile)) {
            unlink($this->logFile);
        }
    }

    public function testLogFileIsCreated(): void
    {
        $logger = new Logger();
        $logger->log('error', 'Message de test');

        $this->assertFileExists($this->logFile);
    }

    public function testLogContentIsCorrect(): void
    {
        $logger = new Logger();
        $logger->log('error', 'Erreur critique');

        $content = file_get_contents($this->logFile);

        $this->assertStringContainsString('ERROR', strtoupper($content));
        $this->assertStringContainsString('Erreur critique', $content);
    }

    protected function tearDown(): void
    {
        // Nettoyage après chaque test
        if (file_exists($this->logFile)) {
            unlink($this->logFile);
        }
    }

}
