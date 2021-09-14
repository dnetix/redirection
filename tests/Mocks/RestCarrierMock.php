<?php

namespace Tests\Mocks;

use Dnetix\Redirection\Entities\Status;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

class RestCarrierMock
{
    protected static ?self $_instance = null;

    /**
     * @var RequestInterface
     */
    protected $request;

    protected $parameters;
    protected $headers;

    private function __construct()
    {
    }

    public function response($code, $body, $headers = [], $reason = null)
    {
        if (is_array($body)) {
            $body = json_encode($body);
        }

        $headers = array_replace([
            'Content-Type' => 'application/json',
        ], $headers);

        return new FulfilledPromise(
            new Response($code, $headers, utf8_decode($body), '1.1', utf8_decode($reason))
        );
    }

    public function __invoke(RequestInterface $request, array $options)
    {
        $this->request = $request;

        $this->parameters = json_decode($request->getBody()->getContents(), true);
        $this->headers = $request->getHeaders();

        switch ($request->getUri()->getPath()) {
            case '/api/session':
                return $this->createSession();
                break;
        }
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public static function instance(): self
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public static function client(): Client
    {
        return new Client([
            'handler' => HandlerStack::create(self::instance()),
        ]);
    }

    private function createSession()
    {
        return $this->response(200, [
            'status' => Status::quick(Status::ST_OK, '00', 'La petición se ha creado exitosamente')->toArray(),
            'requestId' => '120000',
            'processUrl' => 'https://checkout.com',
        ]);
    }
}
