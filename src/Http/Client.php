<?php

namespace Turbo124\Waf\Http;

use Turbo124\Waf\Waf;
use GuzzleHttp\Promise;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;

class Client
{

    protected \GuzzleHttp\Client $transport;

    protected ResponseInterface $response;

    protected \Exception $exception;

    public function __construct(public Waf $waf)
    {
       $this->transport = new \GuzzleHttp\Client($this->getHeaders());
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

            $this->response = $this->transport->request($method, $url, $payload);

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
        return 
            [
            'headers' => [
                'X-Auth-Key' => $this->waf->x_auth_key,
                'X-Auth-Email' => $this->waf->x_auth_email,
                'Content-Type' => 'application/json',
                ]
            ];
    }
    
    /**
     * successful http response
     *
     * @return bool
     */
    public function successful(): bool
    {
        return $this->response->getStatusCode() >= 200 && $this->response->getStatusCode() <= 300; 
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
     * @return Exception
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
}