<?php

namespace Tests\Functionality;

use Dnetix\Redirection\Exceptions\PlacetoPayServiceException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Tests\BaseTestCase;

class ServiceInvalidResponseTest extends BaseTestCase
{
    private function serviceReturning(Response $response): \Dnetix\Redirection\PlacetoPay
    {
        return $this->getService([
            'client' => new Client(['handler' => HandlerStack::create(new MockHandler([$response]))]),
        ]);
    }

    /**
     * @dataProvider invalidResponses
     */
    public function testItThrowsAServiceExceptionOnANonJsonResponse(Response $response, string $expectedInMessage)
    {
        $this->expectException(PlacetoPayServiceException::class);
        $this->expectExceptionMessageMatches('/' . preg_quote($expectedInMessage, '/') . '/');

        $this->serviceReturning($response)->query(10008);
    }

    public static function invalidResponses(): array
    {
        return [
            'empty body on success' => [new Response(200, [], ''), 'status: 200'],
            'empty body on error' => [new Response(400, [], ''), 'status: 400'],
            'html error page' => [new Response(502, ['Content-Type' => 'text/html'], '<html>502 Bad Gateway</html>'), 'status: 502'],
            'truncated payload' => [new Response(200, [], '{"status":{"status":"APPRO'), 'length: 26'],
            'non utf-8 encoding' => [
                new Response(200, [], mb_convert_encoding('{"status":{"message":"aprobación"}}', 'ISO-8859-1', 'UTF-8')),
                'Malformed UTF-8',
            ],
            'json null literal' => [new Response(200, [], 'null'), 'length: 4'],
            'json scalar' => [new Response(200, [], '42'), 'length: 2'],
        ];
    }

    public function testItStillParsesASuccessfulResponse()
    {
        $body = '{"requestId":10008,"status":{"status":"APPROVED","reason":"00","message":"Ok","date":"2026-08-26T10:00:00-05:00"}}';

        $response = $this->serviceReturning(new Response(200, [], $body))->query(10008);

        $this->assertEquals(10008, $response->requestId());
        $this->assertTrue($response->isSuccessful());
    }

    public function testItStillParsesABusinessErrorWithAnErrorStatusCode()
    {
        $body = '{"status":{"status":"FAILED","reason":"request_not_valid","message":"La moneda proporcionada es invalida","date":"2026-08-26T10:00:00-05:00"}}';

        $response = $this->serviceReturning(new Response(400, [], $body))->query(10008);

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('request_not_valid', $response->status()->reason());
        $this->assertEquals('La moneda proporcionada es invalida', $response->status()->message());
    }
}
