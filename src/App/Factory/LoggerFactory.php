<?php
namespace App\Factory;

use Interop\Container\ContainerInterface;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Short description for class
 *
 * Long description for class
 *
 * @package   Integration Layer
 * @author    André Rademacher <andre.rademacher@entiretec.com>
 * @copyright Copyright (c) 2016 ENTIRETEC (http://www.entiretec.com)
 * @license   ENTIRETEC proprietery license
 */
class LoggerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array $options
     * @return Logger
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $writer = new Stream('./data/log/error_' . date('Y-m-d') . '.log');
        $logger = new Logger();
        $logger->addWriter($writer);

        return $logger;
    }
}