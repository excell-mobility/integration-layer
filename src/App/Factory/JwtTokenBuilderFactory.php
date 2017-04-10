<?php
namespace App\Factory;

use Interop\Container\ContainerInterface;
use Lcobucci\JWT\Builder;

/**
 * Short description for class
 *
 * Long description for class
 *
 * @package   Broadcasting Service
 * @author    André Rademacher <andre.rademacher@entiretec.com>
 * @copyright Copyright (c) 2017 ENTIRETEC (http://www.entiretec.com)
 * @license   ENTIRETEC proprietery license
 */
class JwtTokenBuilderFactory
{
    /**
     * Set up the builder
     * @param ContainerInterface $container
     * @throws \Exception
     * @return Builder
     */
    public function __invoke(ContainerInterface $container)
    {
        // load JWT config via dependency injection container
        $jwtConfig = $container->get('config');

        if (isset($jwtConfig['jwt']['lifetime'])) {
            $lifetime = $jwtConfig['jwt']['lifetime'];
        } else {
            throw new \Exception('The JWT lifetime configuration parameter is missing. Please refer to the local.php.dist configuration file.', 500);
        }

        // setup JWT builder, use config values
        $builder = new Builder();

        $issuesAt = time();
        $builder->setIssuedAt($issuesAt);
        $builder->setNotBefore($issuesAt);
        $builder->setExpiration($issuesAt + $lifetime);

        if (isset($jwtConfig['jwt']['algorithm'])) {
            $algorithm = $jwtConfig['jwt']['algorithm'];
            $builder->setHeader('alg', $algorithm);

            // check for private / public key file config in case an algorithm is chosen that used these
            $algorithmsUsingAsymetricEncryption = ['RS256', 'RS384', 'RS512', 'ES256', 'ES384', 'ES512'];
            if (in_array($algorithm, $algorithmsUsingAsymetricEncryption)) {

                if (!isset($jwtConfig['jwt']['il_private_key_file'])) {
                    throw new \Exception('The private key file has not been set, but is needed for the chosen signing algorithm ' . $algorithm . '. Please refer to the local.php.dist configuration file.', 500);
                }

                if (!isset($jwtConfig['jwt']['il_public_key_file'])) {
                    throw new \Exception('The public key file has not been set, but is needed for the chosen signing algorithm ' . $algorithm . '. Please refer to the local.php.dist configuration file.', 500);
                }
            }

        } else {
            throw new \Exception('The signing algorithm has not been set. Please refer to the local.php.dist configuration file.', 500);
        }

        return $builder;
    }
}