<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Turbo124\Waf\Waf;
use PHPUnit\Framework\Attributes\CoversClass;
use Turbo124\Waf\Ruleset\Ruleset;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Turbo124\Waf\Meta\Meta;

#[CoversClass(Meta::class)]
final class MetaTest extends TestCase
{
    protected Logger $log;

    protected function setUp(): void
    {
        $this->log = new Logger('test');
        $this->log->pushHandler(new StreamHandler('tests.log', Level::Warning));

        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    }

    public function testIpCheck()
    {
        $zone = $_ENV["CLOUDFLARE_ZONE_ID"];
        $email = $_ENV["CLOUDFLARE_EMAIL"];
        $api_key = $_ENV["CLOUDFLARE_API_KEY"];
        $account_id = $_ENV["CLOUDFLARE_ACCOUNT_ID"];

        $waf = new Waf($api_key, $email, $zone, $account_id);

        $response = $waf->meta->getIpInfo('210.140.43.55');

        $this->assertIsArray($response);
        $this->assertEquals('210.140.43.55', $response['ip']);

        $this->log->warning(json_encode($response));
        $this->log->warning(json_encode($response));

    }

    public function testDomainCheck()
    {

        $zone = $_ENV["CLOUDFLARE_ZONE_ID"];
        $email = $_ENV["CLOUDFLARE_EMAIL"];
        $api_key = $_ENV["CLOUDFLARE_API_KEY"];
        $account_id = $_ENV["CLOUDFLARE_ACCOUNT_ID"];

        $waf = new Waf($api_key, $email, $zone, $account_id);

        $response = $waf->meta->getDomainInfo('puabook.com');

        $this->log->warning(json_encode($response));

        $this->assertEquals('puabook.com', $response['domain']);

        $this->assertIsArray($response);

    }
}