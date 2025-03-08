<?php

namespace Tests;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use OlegV\Logdye;
use PHPUnit\Framework\TestCase;
use Tests\Helper\Intercept;

class LogdyeTest extends TestCase
{
    public function testMonologColored(): void
    {
        $states = [
            'Debug' => '[0;37mDEBUG[0m',
            'Info' => '[1;34mINFO[0m',
            'Notice' => '[1;32mNOTICE[0m',
            'Warning' => '[0;33mWARNING[0m',
            'Error' => '[1;33mERROR[0m',
            'Critical' => '[0;31;51mCRITICAL[0m',
            'Alert' => '[1;30;41mALERT[0m',
            'Emergency' => '[1;5;21;41mEMERGENCY[0m',
        ];
        stream_filter_register("intercept", Intercept::class);
        // Create a logger instance
        $logger = new Logger('test');

        $formatter = new Logdye(
            "%level_name%",
            "",
        );

        // Add a handler to output logs to the standard output stream
        $handler = new StreamHandler("php://stdout", Level::Debug);
        $handler->setFormatter($formatter);
        $logger->pushHandler($handler);
        $logger->debug("just activate");
        //start stream
        $stderr = $handler->getStream();
        if (is_resource($stderr)) {
            stream_filter_append($stderr, "intercept");
        }

        $logger->debug('test');
        $logger->info('test');
        $logger->notice('test');
        $logger->warning('test');
        $logger->error('test');
        $logger->critical('test');
        $logger->alert('test');
        $logger->emergency('test');
        //end stream
        $this->assertSame(Intercept::$cache, implode('', $states));
    }

    public function testFulltextWithStateColored(): void
    {
        $logger = new Logger('Name');

        $formatter = new Logdye(
            "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n",
            "Y-m-d H:i:s",
            true,
        );
        $data = [1 => "11", '2' => '22'];
        $handler = new StreamHandler("php://stdout", Level::Debug);
        $handler->setFormatter($formatter);
        $logger->pushHandler($handler);

        $logger->debug('test', [$data]);
        $logger->info('test');
        $logger->notice('test');
        $logger->warning('test');
        $logger->error('test');
        $logger->critical('test');
        $logger->alert('test');
        $logger->emergency('test');

        $this->expectNotToPerformAssertions();
        //check workflow
    }
}