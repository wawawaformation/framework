<?php

use Core\Logger;
use DI\Container;
use DI\ContainerBuilder;
use Psr\Log\LoggerInterface;

return (function (): Container {
    $builder = new ContainerBuilder();

    // Active la compilation en prod pour des performances accrues
    if (APP_ENV === 'production') {
        $builder->enableCompilation(ROOT . '/var/cache/php-di');
    }

    $builder->addDefinitions([
        LoggerInterface::class => DI\create(Logger::class),
    ]);

    return $builder->build();
})();
