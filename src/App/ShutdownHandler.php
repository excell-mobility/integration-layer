<?php
namespace App;

/**
 * Short description for class
 *
 * Long description for class
 *
 * @package   Integration Layer
 * @author    André Rademacher <andre.rademacher@entiretec.com>
 * @copyright Copyright (c) 2017 ENTIRETEC (http://www.entiretec.com)
 * @license   ENTIRETEC proprietery license
 */
class ShutdownHandler
{

    /**
     * Registers the handle() method as shutdown function.
     */
    public function register()
    {
        register_shutdown_function(array(self::class, 'handle'));
    }

    /**
     * Handles the shutdown. We can't recover from that, but in case of an fatal error, this will be logged.
     */
    public static function handle()
    {
        $error = error_get_last();
        if (!$error) {
            return;
        }

        $msg = ' with message: ' . $error['message'] . ' in file ' . $error['file'] . ' line ' . $error['line'] . PHP_EOL;
        $pathToLogdir = __DIR__ . '/../../data/log/';
        $logfile = 'error_' . date('Y-m-d') . '.log';

        switch ($error['type']) {

            // notices
            case E_NOTICE:
            case E_USER_NOTICE: {
                $msgPrefix = 'notice';
                break;
            }

            // deprecated
            case E_DEPRECATED:
            case E_USER_DEPRECATED: {
                $msgPrefix = 'deprecated warning!';
                break;
            }

            // warning
            case E_WARNING:
            case E_USER_WARNING: {
                $msgPrefix = 'Warning';
                break;
            }

            // strict
            case E_STRICT: {
                $msgPrefix = 'STRICT error!';
                break;
            }

            // fatal error
            case E_ERROR: {
                $logfile = 'FATAL_' . date('Y-m-d') . '.log';
                $msgPrefix = 'FATAL ERROR!';
                break;
            }

            // all other cases
            default: {
                $msgPrefix = 'unknown error!';
                break;
            }
        }

        // log error msg to logfile
        $result = file_put_contents($pathToLogdir . $logfile, $msgPrefix . $msg, FILE_APPEND | LOCK_EX);
    }
}

/**
 * Instantiate & register.
 */
$shutdownHandler = new ShutdownHandler();
$shutdownHandler->register();