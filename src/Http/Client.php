<?php

namespace Turbo124\Waf\Http;

use Turbo124\Waf\Waf;
use GuzzleHttp\Promise;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;

class Client
{

    protected Client $client;

    protected ResponseInterface $response;

    protected Exception $exception;

    public function __construct(Waf $waf)
    {
       $this->client = new \GuzzleHttp\Client($this->waf->getHeaders());
    }

    /**
     * Request
     *
     * @param  string $url
     * @param  string $method
     * @param  array $payload
     * 
     * @return ResponseInterface
     */
    public function request(string $url, string $method, array $payload = []): self
    {

        try {

            $this->response = $this->client->request($method, $url, $payload);

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
        return ['headers' => [
                        'X-Auth-Key' => $this->waf->x_auth_key,
                        'X-Auth-Email' => $this->waf->x_auth_email,
                        'Content-Type' => 'application/json',
                    ]
                ];
    }

    public function successful(): bool
    {
        return $this->response->getStatusCode() >= 200 && $this->response->getStatusCode() <= 300; 
    }

    public function failed(): bool
    {
        return !$this->successful();
    }

    public function json(): iterable
    {
        return json_decode((string)$this->response->getBody(), true);
    }
    
    public function error(): string
    {
        return $this->exception->getMessage();
    }

    public function exception(): \Exception
    {
        return $this->exception;
    }

    public function object(): mixed
    {
        return json_decode((string)$this->response->getBody());
    }
    
    // /**
    //  * sendPromise
    //  *
    //  * @param  mixed $promises
    //  * @return void
    //  */
    // private function sendPromise($promises): void
    // {
    //     $responses = Promise\Utils::unwrap($promises);
    // }
    

    // private function getResponseStatus($response)
    // {

    // }


// try {
//     $batch_of = 40;
//     $batch = array_chunk($metric_array, $batch_of);

//     /* Concurrency ++ */
//     foreach ($batch as $key => $value) {
//         $data['metrics'] = $value;

//         $promises = [
//         $key => $client->requestAsync('POST', $this->endPoint($metric_array[0]->type), ['form_params' => $data])
//         ];

//         $this->sendPromise($promises);
//     }
// } catch (RequestException $e) {
//     // info($e->getMessage());
// }