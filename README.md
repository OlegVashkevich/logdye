# logdye
Colored Line Formatter for Monolog
## Features
- monolog v3+
- lightweight
- 100% test coverage
- phpstan max lvl
- phpstan full strict rules

## Install
```shell
composer require olegv/logdye
```

## Usage
```php
<?php
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use OlegV\Logdye;

//logger
$logger = new Logger('Name');

$formatter = new Logdye(
    "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n",
    "Y-m-d H:i:s"
);

$handler = new StreamHandler("php://stdout", Level::Debug);
$handler->setFormatter($formatter);
$logger->pushHandler($handler);

$logger->debug('test');
$logger->info('test');
$logger->notice('test');
$logger->warning('test');
$logger->error('test');
$logger->critical('test');
$logger->alert('test');
$logger->emergency('test');
```
You will see:

![Logs of ascending levels with different colors wrapping the level, spelling "Test"](images/default.png)
