<?php

namespace Dnetix\Redirection\Carrier;

use Dnetix\Redirection\Contracts\Carrier;
use Dnetix\Redirection\Entities\Status;
use Dnetix\Redirection\Exceptions\PlacetoPayException;
use Dnetix\Redirection\Message\CollectRequest;
use Dnetix\Redirection\Message\RedirectInformation;
use Dnetix\Redirection\Message\RedirectRequest;
use Dnetix\Redirection\Message\RedirectResponse;
use Dnetix\Redirection\Message\ReverseResponse;
use GuzzleHttp\Exception\BadResponseException;
use Throwable;

class RestCarrier extends Carrier
{
    private function makeRequest(string $url, array $arguments): array
    {
        try {
            $data = array_merge($arguments, ['auth' => $this->settings->authentication()]);
            $response = $this->settings->client()->post($url, [
                'json' => $data,
            ]);
            $result = $response->getBody()->getContents();
        } catch (BadResponseException $exception) {
            $result = $exception->getResponse()->getBody()->getContents();
        } catch (Throwable $exception) {
            return [
                'status' => [
                    'status' => Status::ST_ERROR,
                    'reason' => 'WR',
                    'message' => PlacetoPayException::readException($exception),
                    'date' => date('c'),
                ],
            ];
        }

        return json_decode($result, true);
    }

    public function request(RedirectRequest $redirectRequest): RedirectResponse
    {
        $result = $this->makeRequest($this->settings->baseUrl('api/session'), $redirectRequest->toArray());
        return new RedirectResponse($result);
    }

    public function query(string $requestId): RedirectInformation
    {
        $result = $this->makeRequest($this->settings->baseUrl('api/session/' . $requestId), []);
        return new RedirectInformation($result);
    }

    public function collect(CollectRequest $collectRequest): RedirectInformation
    {
        $result = $this->makeRequest($this->settings->baseUrl('api/collect'), $collectRequest->toArray());
        return new RedirectInformation($result);
    }

    public function reverse(string $transactionId): ReverseResponse
    {
        $result = $this->makeRequest($this->settings->baseUrl('api/reverse'), [
            'internalReference' => $transactionId,
        ]);
        return new ReverseResponse($result);
    }
}
