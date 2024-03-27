<?php

namespace Turbo124\Waf\Http;

use Turbo124\Waf\Waf;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;

class Client
{
    protected \GuzzleHttp\Client $transport;

    protected ResponseInterface $response;

    protected ?\Exception $exception;

    public function __construct(public Waf $waf)
    {
        $this->transport = new \GuzzleHttp\Client();
        $this->exception = null;
    }

    /**
     * Request
     *
     * @param  string $url
     * @param  string $method
     * @param  array $payload
     *
     * @return self
     */
    public function request(string $url, string $method, array $payload = []): self
    {
        
        try {

            $this->response = $this->transport->request($method, $url, ['body' => json_encode($payload), 'headers' => $this->getHeaders()]);

        } catch (RequestException $e) {

            $this->exception = $e;

        }

        return $this;

    }

    /**
     * Get Auth headersHeaders
     *
     * @return array
     */
    private function getHeaders(): array
    {
        return [
            'X-Auth-Key' => $this->waf->x_auth_key,
            'X-Auth-Email' => $this->waf->x_auth_email,
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * successful http response
     *
     * @return bool
     */
    public function successful(): bool
    {
        return !$this->exception && $this->response->getStatusCode() >= 200 && $this->response->getStatusCode() <= 300;
    }

    /**
     * failed http response
     *
     * @return bool
     */
    public function failed(): bool
    {
        return !$this->successful();
    }

    /**
     * json response body
     *
     * @return string
     */
    public function body(): string
    {
        return (string)$this->response->getBody();
    }

    /**
     * iterable response body
     *
     * @return iterable
     */
    public function json(): iterable
    {
        return json_decode((string)$this->response->getBody(), true);
    }

    /**
     * Exception message
     *
     * @return string
     */
    public function error(): string
    {
        return $this->exception->getMessage();
    }

    /**
     * Full exception
     *
     * @return \Exception
     */
    public function exception(): \Exception
    {
        return $this->exception;
    }

    /**
     * object response body
     *
     * @return mixed
     */
    public function object(): mixed
    {
        return json_decode((string)$this->response->getBody());
    }



    /**
     * Returns a list response for pagination
     *
     * @param  string $url
     * @param  int $page
     * @param  int $per_page
     *
     * @return self
     */
    public function listPaginator($url, $page = 1, $per_page = 50): self
    {

        $this->request($url, 'GET', ['page' => $page, 'per_page' => $per_page]);

        return $this;

    }

}
