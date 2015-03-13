<?php

namespace VividCortex\RecaptchaValidator;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use VividCortex\RecaptchaValidator\Exception\InvalidResponseException;

/**
 * Class Validator.
 *
 * @author Ismael Ambrosi<ismael@vividcortex.com>
 */
class Validator implements ValidatorInterface
{
    const VALIDATION_URL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var string
     */
    private $validationUrl;

    /**
     * Constructor
     *
     * @param string          $secret
     * @param ClientInterface $client
     * @param string          $validationUrl
     */
    public function __construct($secret, ClientInterface $client = null, $validationUrl = self::VALIDATION_URL)
    {
        if (null === $client) {
            $client = new Client();
        }

        $this->client        = $client;
        $this->secret        = $secret;
        $this->validationUrl = $validationUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($response, $clientIp)
    {
        try {
            $httpResponse = $this->client->post($this->validationUrl, [
                'headers' => ['Accept' => 'application/json'],
                'body'    => [
                    'secret'   => $this->secret,
                    'response' => $response,
                    'remoteip' => $clientIp,
                ],
            ]);

            $result = json_decode($httpResponse->getBody(), true);
            if (false === $result) {
                throw new InvalidResponseException('Verification response is not a JSON.');
            } elseif (!isset($result['success'])) {
                throw new InvalidResponseException('Verification request sent an invalid structure in JSON response.');
            }

            return $result['success'];
        } catch (BadResponseException $e) {
            return false;
        }
    }
}
