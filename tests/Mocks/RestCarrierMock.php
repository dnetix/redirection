<?php

namespace Tests\Mocks;

use Dnetix\Redirection\Entities\Status;
use Dnetix\Redirection\Message\RedirectRequest;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

class RestCarrierMock
{
    protected static ?self $_instance = null;

    protected RequestInterface $request;

    protected array $parameters = [];
    protected array $headers = [];

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

        try {
            $this->handleAuthentication();
        } catch (Exception $e) {
            return $this->response(401, ['status' => Status::quick(Status::ST_FAILED, '401', $e->getMessage())->toArray()]);
        }

        $path = $request->getUri()->getPath();
        switch ($path) {
            case '/api/session':
                return $this->createSession();
            case '/api/reverse':
                return $this->reverse();
            case '/api/collect':
                return $this->collect();
            default:
                if (preg_match('/api\/session\/(\d+)/', $path, $matches)) {
                    return $this->query($matches[1]);
                }
                throw new Exception('No path mocked ' . $path);
        }
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function parameters(): array
    {
        return $this->parameters;
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

    private function createSession(): FulfilledPromise
    {
        $request = new RedirectRequest($this->parameters());

        $requestId = time();
        if (preg_match('/_(\d+)$/', $request->reference(), $matches)) {
            $requestId = $matches[1];
        }

        if ($request->reference() == 'MAKE_EXCEPTION') {
            throw new ConnectException('Some kind of problem occurred', $this->request);
        }

        return $this->response(200, [
            'status' => Status::quick(Status::ST_OK, '00', 'La petición se ha creado exitosamente')->toArray(),
            'requestId' => $requestId,
            'processUrl' => 'https://' . $this->request->getUri()->getHost() . '/session/' . $requestId . '/' . sha1($requestId),
        ]);
    }

    private function collect(): FulfilledPromise
    {
        $request = new RedirectRequest($this->parameters());

        $requestId = time();
        if (preg_match('/_(\d+)$/', $request->reference(), $matches)) {
            $requestId = $matches[1];
        }

        switch ($request->reference()) {
            case 'PENDING':
                return $this->response(200, json_decode('{"requestId": ' . $requestId . ',"status": {"status": "PENDING","reason": "PT","message": "La petición se encuentra pendiente","date": "2021-09-14T21:40:39-05:00"},"request": {"locale": "es_CO","payer": {"document": "1040035000","documentType": "CC","name": "Nakia","surname": "Walker","email": "dnetix@yopmail.com","mobile": 3006108300},"payment": {"reference": "800166551","description": "Pago en micrositio","amount": {"currency": "COP","total": 3809000},"allowPartial": false,"subscribe": false},"returnUrl": "https://checkout-test.placetopay.com/home","ipAddress": "181.58.38.54","userAgent": "PostmanRuntime/7.28.4","expiration": "2021-09-14T22:10:38-05:00"},"payment": null,"subscription": null}', true));
            default:
                return $this->response(200, json_decode('{"requestId": ' . $requestId . ',"status": {"status": "APPROVED","reason": "00","message": "La petición ha sido aprobada exitosamente","date": "2021-09-14T22:18:36-05:00"},"request": {"locale": "es_CO","payer": {"document": "1040035000","documentType": "CC","name": "Nakia","surname": "Walker","email": "dnetix@yopmail.com","mobile": 3006108300},"payment": {"reference": "800166551","description": "Pago en micrositio","amount": {"currency": "COP","total": 3809000},"allowPartial": false,"subscribe": false},"returnUrl": "https://checkout-test.placetopay.com/home","ipAddress": "181.58.38.54","userAgent": "PostmanRuntime/7.28.4","expiration": "2021-09-14T22:48:35-05:00"},"payment": [{"status": {"status": "APPROVED","reason": "00","message": "Aprobada","date": "2021-09-14T22:18:35-05:00"},"internalReference": 1519104649,"paymentMethod": "visa","paymentMethodName": "Visa","issuerName": "JPMORGAN CHASE BANK, N.A.","amount": {"from": {"currency": "COP","total": 3809000},"to": {"currency": "COP","total": 3809000},"factor": 1},"authorization": "000000","reference": "800166551","receipt": 99975915,"franchise": "CR_VS","refunded": false,"processorFields": [{"keyword": "merchantCode","value": "011271442","displayOn": "none"},{"keyword": "terminalNumber","value": "00057742","displayOn": "none"},{"keyword": "bin","value": "411111","displayOn": "none"},{"keyword": "expiration","value": "1123","displayOn": "none"},{"keyword": "installments","value": 3,"displayOn": "none"},{"keyword": "lastDigits","value": "****1111","displayOn": "none"}]}],"subscription": null}', true));
        }
    }

    public function query($requestId): FulfilledPromise
    {
        switch ($requestId) {
            case 10008:
                $response = '{"requestId": 10008,"status": {"status": "APPROVED","reason": "00","message": "La petición ha sido aprobada exitosamente","date": "2021-09-14T19:57:23-05:00"},"request": {"locale": "es_CO","buyer": {"document": "1040035000","documentType": "CC","name": "Nakia","surname": "Walker","email": "dnetix@yopmail.com","mobile": 3006108300},"payer": {"document": "1040035000","documentType": "CC","name": "Nakia","surname": "Walker","email": "dnetix@yopmail.com","mobile": 3006108300},"payment": {"reference": "800166551","description": "Pago en micrositio","amount": {"currency": "COP","total": 3809000},"allowPartial": true,"subscribe": false},"fields": [{"keyword": "_processUrl_","value": "https://checkout-test.placetopay.com/session/1847214/3c8d4117e08c5970291c79dfa8d4237a","displayOn": "none"}],"returnUrl": "https://dnetix.co/ping/test","ipAddress": "186.84.220.137","userAgent": "Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Mobile/15E148 Safari/604.1","expiration": "2021-10-19T17:05:23-05:00"},"payment": [{"status": {"status": "APPROVED","reason": "00","message": "Aprobada","date": "2021-09-14T19:57:09-05:00"},"internalReference": 1519097768,"paymentMethod": "master","paymentMethodName": "Master","issuerName": "Banco del Pacifico, S.A.","amount": {"from": {"currency": "COP","total": 2009000},"to": {"currency": "COP","total": 2009000},"factor": 1},"authorization": "999999","reference": "800166551","receipt": 67429,"franchise": "RM_MC","refunded": false,"processorFields": [{"keyword": "merchantCode","value": "0010203040","displayOn": "none"},{"keyword": "terminalNumber","value": "ESB11134","displayOn": "none"},{"keyword": "bin","value": "518030","displayOn": "none"},{"keyword": "expiration","value": "0125","displayOn": "none"},{"keyword": "installments","value": 4,"displayOn": "none"},{"keyword": "lastDigits","value": "0005","displayOn": "none"}]},{"status": {"status": "APPROVED","reason": "00","message": "Aprobada","date": "2021-09-14T19:56:25-05:00"},"internalReference": 1519097729,"paymentMethod": "visa","paymentMethodName": "Visa","issuerName": "JPMORGAN CHASE BANK, N.A.","amount": {"from": {"currency": "COP","total": 800000},"to": {"currency": "COP","total": 800000},"factor": 1},"authorization": "000000","reference": "800166551","receipt": 99967385,"franchise": "CR_VS","refunded": false,"processorFields": [{"keyword": "merchantCode","value": "011271442","displayOn": "none"},{"keyword": "terminalNumber","value": "00057742","displayOn": "none"},{"keyword": "bin","value": "411111","displayOn": "none"},{"keyword": "expiration","value": "1122","displayOn": "none"},{"keyword": "installments","value": 5,"displayOn": "none"},{"keyword": "lastDigits","value": "****1111","displayOn": "none"}]},{"status": {"status": "APPROVED","reason": "00","message": "Aprobada","date": "2021-09-14T19:55:55-05:00"},"internalReference": 1519097680,"paymentMethod": "pse","paymentMethodName": "Cuentas débito ahorro y corriente (PSE)","issuerName": "BANCO UNION COLOMBIANO","amount": {"from": {"currency": "COP","total": "1000000.00"},"to": {"currency": "COP","total": "1000000.00"},"factor": 1},"authorization": "2408909","reference": "800166551","receipt": "1519097680","franchise": "_PSE_","refunded": false,"processorFields": [{"keyword": "merchantCode","value": "9002992280","displayOn": "none"},{"keyword": "terminalNumber","value": "001","displayOn": "none"},{"keyword": "transactionCycle","value": "6","displayOn": "none"},{"keyword": "trazabilyCode","value": "2408909","displayOn": "none"}]}],"subscription": null}';
                break;
            case 10009:
                $response = '{"requestId": 10009,"status": {"status": "APPROVED","reason": "00","message": "La petición ha sido aprobada exitosamente","date": "2021-09-14T19:16:59-05:00"},"request": {"locale": "es_CO","buyer": {"document": "1040035000","documentType": "CC","name": "Nakia","surname": "Walker","email": "dnetix@yopmail.com","mobile": 3006108300},"payer": {"document": "1040035000","documentType": "CC","name": "Nakia","surname": "Walker","email": "dnetix@yopmail.com","mobile": 3006108300},"subscription": {"reference": "800166551","description": "Pago en micrositio"},"returnUrl": "https://dnetix.co/ping/test","ipAddress": "186.84.220.137","userAgent": "Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Mobile/15E148 Safari/604.1","expiration": "2021-09-19T17:05:23-05:00"},"payment": null,"subscription": {"type": "account","status": {"status": "OK","reason": "00","message": "Account stored successfully","date": "2021-09-15T00:16:45.345048Z"},"instrument": [{"keyword": "bankCode","value": "007","displayOn": "none"},{"keyword": "bankName","value": "Bancolombia","displayOn": "none"},{"keyword": "accountType","value": "A","displayOn": "none"},{"keyword": "accountNumber","value": "00849514000","displayOn": "none"}]}}';
                break;
            case 10010:
                $response = '{"requestId": 328759,"status": {"status": "APPROVED","reason": "00","message": "La petici\u00f3n ha sido aprobada exitosamente","date": "2021-09-23T19:00:08-05:00"},"request": {"locale": "es_CO","buyer": {"document": "1040035000","documentType": "CC","name": "Nakia","surname": "Walker","email": "dnetix@yopmail.com","mobile": 3006108300},"payer": {"document": "1036949824","documentType": "CI","name": "Diego","surname": "osorio","email": "dnetix@yopmail.com","mobile": "3102903560"},"payment": {"reference": "800166551","description": "Pago en micrositio","amount": {"currency": "COP","total": 3809000},"allowPartial": true,"subscribe": false},"fields": [{"keyword": "_processUrl_","value": "https:\/\/test.placetopay.ec\/redirection\/session\/328759\/ccad4021c274a7dc3ef71f526f2b1cb5","displayOn": "none"}],"returnUrl": "https:\/\/dnetix.co\/ping\/test","ipAddress": "186.84.220.137","userAgent": "Mozilla\/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit\/605.1.15 (KHTML, like Gecko) Version\/14.1.1 Mobile\/15E148 Safari\/604.1","expiration": "2021-10-19T17:05:23-05:00"},"payment": [{"status": {"status": "APPROVED","reason": "00","message": "Aprobada","date": "2021-09-23T18:59:45-05:00"},"internalReference": "230192","paymentMethod": "diners","paymentMethodName": "Diners","issuerName": "Diners","amount": {"from": {"currency": "COP","total": 3809000},"to": {"currency": "USD","total": 990.58},"factor": 0.0002600630086636913},"authorization": "999999","reference": "800166551","receipt": "230192","franchise": "ID_DN","refunded": false,"processorFields": [{"keyword": "merchantCode","value": "1065152","displayOn": "none"},{"keyword": "terminalNumber","value": "00990099","displayOn": "none"},{"keyword": "credit","value": {"code": 1,"type": "03","groupCode": "X","installments": 9},"displayOn": "none"},{"keyword": "totalAmount","value": 3809000.0,"displayOn": "none"},{"keyword": "interestAmount","value": 0,"displayOn": "none"},{"keyword": "installmentAmount","value": 423205.13,"displayOn": "none"},{"keyword": "iceAmount","value": 0,"displayOn": "none"},{"keyword": "bin","value": "365454","displayOn": "none"},{"keyword": "expiration","value": "1122","displayOn": "none"},{"keyword": "lastDigits","value": "0008","displayOn": "none"}]}],"subscription": null}';
                break;
            default:
                $response = '{"status":{"status":"FAILED","reason":0,"message":"No existe la sesi\u00f3n que busca","date":"' . date('c') . '"}}';
        }

        return $this->response(200, json_decode($response, true));
    }

    public function reverse(): FulfilledPromise
    {
        $internalReference = $this->parameters()['internalReference'] ?? null;

        if (!$internalReference) {
            return $this->response(400, [
                'status' => [
                    'status' => 'FAILED',
                    'reason' => 0,
                    'message' => 'Referencia inválida, debe ser de 1 a 32 caracteres',
                    'date' => '2021-09-14T21:26:22-05:00',
                ],
            ]);
        }

        $response = '{"status": {"status": "APPROVED","reason": "00","message": "Aprobada","date": "2021-09-14T21:20:06-05:00"},"payment": {"status": {"status": "APPROVED","reason": "00","message": "Aprobada","date": "2021-09-14T21:20:06-05:00"},"internalReference": 1519102359,"paymentMethod": "master","paymentMethodName": "Master","issuerName": "Banco del Pacifico, S.A.","amount": {"from": {"currency": "COP","total": "2009000.00"},"to": {"currency": "COP","total": "2009000.00"},"factor": 1},"authorization": "000000","reference": "800166551","receipt": 72406,"franchise": "RM_MC","refunded": false}}';
        return $this->response(200, json_decode($response, true));
    }

    private function handleAuthentication(): void
    {
        $auth = $this->parameters['auth'] ?? null;
        if (!$auth || !isset($auth['login']) || !isset($auth['tranKey']) || !isset($auth['seed']) || !isset($auth['nonce'])) {
            throw new Exception('Autenticación fallida 106', 106);
        }

        if (($auth['additional']['testing-auth'] ?? '') == 'ERROR-200') {
            throw new Exception('Autenticación fallida 200', 200);
        }

        if ($auth['login'] == 'failed_login') {
            throw new Exception('Autenticación fallida 101', 101);
        }
    }
}
