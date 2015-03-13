<?php

namespace spec\VividCortex\RecaptchaValidator;

use GuzzleHttp\ClientInterface as Client;
use GuzzleHttp\Message\ResponseInterface as Response;
use PhpSpec\ObjectBehavior;

class ValidatorSpec extends ObjectBehavior
{
    public function let(Client $client)
    {
        $this->beConstructedWith('recaptcha-secret', $client, 'http://validate.me/verify');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('VividCortex\RecaptchaValidator\Validator');
    }

    public function it_implement_a_validator()
    {
        $this->shouldImplement('VividCortex\RecaptchaValidator\ValidatorInterface');
    }

    public function it_validates_a_response(Client $client, Response $response)
    {
        $response->getBody()->willReturn('{"success":true,"error-codes":[]}');

        $this->describePostRequest($client, 'captcha-response', '192.168.33.22', $response);

        $this->validate('captcha-response', '192.168.33.22')->shouldBe(true);
    }

    public function it_fails_on_invalid_response(Client $client, Response $response)
    {
        $response->getBody()->willReturn('{"success":false,"error-codes":["something"]}');

        $this->describePostRequest($client, 'captcha-response', '192.168.33.22', $response);

        $this->validate('captcha-response', '192.168.33.22')->shouldBe(false);
    }

    public function it_fails_on_invalid_response_from_google(Client $client, Response $response)
    {
        $response->getBody()->willReturn('this is not a json');

        $this->describePostRequest($client, 'captcha-response', '192.168.33.22', $response);

        $this->shouldThrow('VividCortex\RecaptchaValidator\Exception\InvalidResponseException')
            ->during('validate', ['captcha-response', '192.168.33.22']);
    }

    private function describePostRequest(Client $client, $captchaResponse, $clientIp, Response $response)
    {
        $client->post('http://validate.me/verify', [
            'headers' => ['Accept' => 'application/json'],
            'body'    => [
                'secret'   => 'recaptcha-secret',
                'response' => $captchaResponse,
                'remoteip' => $clientIp,
            ],
        ])->shouldBeCalled()->willReturn($response);
    }
}
