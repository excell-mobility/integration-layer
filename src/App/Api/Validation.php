<?php
namespace App\Api;

use Psr\Http\Message\ServerRequestInterface;

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
class Validation
{
    /**
     * Stores the Http request.
     * @var ServerRequestInterface $request
     */
    protected $request;

    /**
     * Validation constructor.
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * Checks if the HTTP header "Content-Type" is set to "application/json", otherwise a HTTP Error 400 Bad Request is returned.
     *
     * @throws \Exception
     */
    public function validateContentTypeApplicationJson()
    {
        $contentType = $this->request->getHeaderLine('Content-Type');
        if (false === strpos($contentType, 'application/json')) {
            throw new \Exception('Content-Type application/json is mandatory.', 415);
        }
    }

    /**
     * Checks the HTTP Content for valid JSON. If successful, the JSON decoded body is returned.
     *
     * @throws \Exception
     * @return \StdClass
     */
    public function validateContentIsValidJson()
    {
        $content = $this->request->getBody();
        $decodedBody = json_decode($content);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('The given JSON is invalid: ' . json_last_error_msg(), 400);
        }

        return $decodedBody;
    }

    /**
     * Checks whether all the required attributes are set in the decoded JSON .
     *
     * @param \StdClass $decodedJson
     * @param array $requiredAttributes
     * @throws \Exception
     */
    public function validateRequiredAttributes(\StdClass $decodedJson, array $requiredAttributes)
    {
        foreach ($requiredAttributes as $requiredAttribute) {
            if (!isset($decodedJson->{$requiredAttribute})) {
                throw new \Exception('Missing required attribute: ' . $requiredAttribute, 400);
            }
        }
    }

    /**
     * Checks whether all the given attributes are of the type "string".
     *
     * @param \StdClass $decodedJson
     * @param array $stringAttributes
     * @throws \Exception
     */
    public function validateStringValues(\StdClass $decodedJson, array $stringAttributes)
    {
        foreach ($stringAttributes as $stringAttribute) {
            if (!is_string($decodedJson->{$stringAttribute})) {
                throw new \Exception('Attribute ' . $stringAttribute . ' has to be a string.', 400);
            }
        }
    }
}