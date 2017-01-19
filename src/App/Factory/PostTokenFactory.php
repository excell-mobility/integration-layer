<?php
namespace App\Factory;

use App\Api\v1\Auth\Token\PostToken;
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
class PostTokenFactory
{

    public function __invoke(ContainerInterface $container)
    {
        $builder = $container->get(Builder::class);
        $config = $container->get('config');

        $postToken = new PostToken($builder, $config);
        return $postToken;
    }
}