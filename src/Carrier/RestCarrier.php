<?php

namespace Dnetix\Redirection\Carrier;

use Dnetix\Redirection\Contracts\Carrier;
use Dnetix\Redirection\Exceptions\PlacetoPayException;
use Dnetix\Redirection\Exceptions\PlacetoPayServiceException;
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
            $data = array_merge($arguments, ['auth' => $this->settings->authentication()->asArray()]);

            $this->settings->logger()->debug('REQUEST', $data);

            $response = $this->settings->client()->post($url, [
                'json' => $data,
                'headers' => $this->settings->headers(),
            ]);
            $statusCode = $response->getStatusCode();
            $result = $response->getBody()->getContents();

            $this->settings->logger()->debug('RESPONSE', [
                'result' => $result,
            ]);
        } catch (BadResponseException $exception) {
            $statusCode = $exception->getResponse()->getStatusCode();
            $result = $exception->getResponse()->getBody()->getContents();
            $this->settings->logger()->warning('BAD_RESPONSE', [
                'class' => get_class($exception),
                'result' => $result,
            ]);
        } catch (Throwable $exception) {
            $this->settings->logger()->warning('EXCEPTION_RESPONSE', [
                'exception' => PlacetoPayException::readException($exception),
            ]);
            throw PlacetoPayServiceException::fromServiceException($exception);
        }

        $decoded = json_decode($result, true);

        if (!is_array($decoded)) {
            throw PlacetoPayServiceException::forInvalidResponse($statusCode, json_last_error_msg(), $result);
        }

        return $decoded;
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
