<?php
namespace App\Api\v1\Auth\Token;

use App\Api\HalJsonResponse;
use App\Api\Validation;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha512;
use Nocarrier\Hal;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Zend\Diactoros\Response;

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
class PostToken
{
    /**
     * Stores the preconfigured token builder.
     * @var Builder
     */
    protected $builder;

    /**
     * Stores the config.
     * @var \ArrayObject
     */
    protected $config;

    /**
     * PostToken constructor.
     *
     * @param Builder $builder
     * @param \ArrayObject $config
     */
    public function __construct(Builder $builder, \ArrayObject $config)
    {
        $this->builder = $builder;
        $this->config = $config;
    }

    /**
     * Creates a new JSON Web Token using the given claims.
     * If success, this returns a JSON response containing all the claims with HTTP response code "201 Created"
     * and the location header containing the URL to the JWS identified with its jti (JWT ID).
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable|null $next
     * @throws \Exception
     * @return Response
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        // first, validate the request
        $claimsDecoded = $this->validate($request);

        // check given credencials

        // generate jti claim (JWT ID) as RFC 4122 Version 5 UUID (name based and hashed using SHA1)
        $issuer = $request->getUri()->getScheme() . '://' . $request->getUri()->getHost();
        $uuid = Uuid::uuid5(Uuid::NAMESPACE_DNS, $issuer)->toString();

        // build and sign the token
        $token = (string) $this->builder
            ->setId($uuid)
            ->setIssuer($issuer)
            ->setSubject($claimsDecoded->tenant)
            ->set('user', $claimsDecoded->user)
            ->set('service', $claimsDecoded->service)
            ->sign(new Sha512(), new Key($this->config['jwt']['private_key_file']))
            ->getToken();

        // create hal+json
        $hal = new Hal(
            (string) $request->getUri() . '/' . $uuid,
            [
                'id' => $uuid,
                'token' => $token,
                'tenant' => $claimsDecoded->tenant,
                'user' => $claimsDecoded->user,
                'service' => $claimsDecoded->service
            ]
        );

        $response = new HalJsonResponse($hal, 201);
        return $response;
    }

    /**
     * Do the validation and return the decoded JSON Object if successfull.
     *
     * @param ServerRequestInterface $request
     * @return \StdClass
     */
    public function validate(ServerRequestInterface $request)
    {
        $validation = new Validation($request);
        $validation->validateContentTypeApplicationJson();
        $decodedJson = $validation->validateContentIsValidJson();

        // check API parameters
        $validation->validateRequiredAttributes($decodedJson, ['tenant', 'user', 'service']);
        $validation->validateStringValues($decodedJson, ['tenant', 'user', 'service']);

        return $decodedJson;
    }
}