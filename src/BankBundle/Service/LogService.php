<?php

namespace BankBundle\Service;

use Psr\Log\LoggerInterface;

class LogService
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function SuccessLog()
    {
        $this->logger->info('成功執行!');
    }
}
