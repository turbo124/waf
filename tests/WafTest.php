<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Turbo124\Waf\Waf;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Waf::class)]
final class WafTest extends TestCase
{
    public function testInitWafClass(): void
    {
        $waf = new Waf("x","y","z","a");

        $this->assertInstanceOf(Waf::class, $waf);
    }

}


// Mocks
// use GuzzleHttp\Client;
// use GuzzleHttp\Handler\MockHandler;
// use GuzzleHttp\HandlerStack;
// use GuzzleHttp\Psr7\Response;
// use GuzzleHttp\Psr7\Request;
// use GuzzleHttp\Exception\RequestException;

// // Create a mock and queue two responses.
// $mock = new MockHandler([
//     new Response(200, ['X-Foo' => 'Bar'], 'Hello, World'),
//     new Response(202, ['Content-Length' => 0]),
//     new RequestException('Error Communicating with Server', new Request('GET', 'test'))
// ]);

// $handlerStack = HandlerStack::create($mock);
// $client = new Client(['handler' => $handlerStack]);

// // The first request is intercepted with the first response.
// $response = $client->request('GET', '/');
// echo $response->getStatusCode();
// //> 200
// echo $response->getBody();
// //> Hello, World
// // The second request is intercepted with the second response.
// echo $client->request('GET', '/')->getStatusCode();
// //> 202

// // Reset the queue and queue up a new response
// $mock->reset();
// $mock->append(new Response(201));

// // As the mock was reset, the new response is the 201 CREATED,
// // instead of the previously queued RequestException
// echo $client->request('GET', '/')->getStatusCode();
// //> 201
