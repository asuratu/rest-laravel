<?php

namespace Modules\Common\Logging;

use Monolog\Logger as MogLog;
use Illuminate\Log\Logger;

/**
 * Class CustomizeFormatter.
 */
class CustomizeFormatter
{
    /**
     * 自定义给定的日志实例.
     * @param Logger|MogLog $logger
     */
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new CustomizeJsonFormatter());
        }
    }
}
