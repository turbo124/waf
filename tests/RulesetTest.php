<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Turbo124\Waf\Waf;
use PHPUnit\Framework\Attributes\CoversClass;
use Turbo124\Waf\Ruleset\Ruleset;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

#[CoversClass(Ruleset::class)]
final class RulesetTest extends TestCase
{
    protected Logger $log;

    protected function setUp(): void
    {
        $this->log = new Logger('test');
        $this->log->pushHandler(new StreamHandler('tests.log', Level::Warning));

        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    }

    public function testHttpClient()
    {        
        $zone = $_ENV["CLOUDFLARE_ZONE_ID"];
        $email = $_ENV["CLOUDFLARE_EMAIL"];
        $api_key = $_ENV["CLOUDFLARE_API_KEY"];

        $waf = new Waf($api_key, $email, $zone);

        $this->assertInstanceOf(Waf::class, $waf);
    }

    public function testRulesetListPaginator()
    {
        $zone = $_ENV["CLOUDFLARE_ZONE_ID"];
        $email = $_ENV["CLOUDFLARE_EMAIL"];
        $api_key = $_ENV["CLOUDFLARE_API_KEY"];

        $waf = new Waf($api_key, $email, $zone);

        $cloudflare_endpoint = "{$waf->url}zones/{$waf->zone_id}/rulesets";
        $response = $waf->listPaginator($cloudflare_endpoint);

        $this->assertTrue($response->successful());

        $this->log->warning($response->body());
    }

    public function testGetRuleset()
    {  
        $zone = $_ENV["CLOUDFLARE_ZONE_ID"];
        $email = $_ENV["CLOUDFLARE_EMAIL"];
        $api_key = $_ENV["CLOUDFLARE_API_KEY"];

        $waf = new Waf($api_key, $email, $zone);
        $ruleset = $waf->ruleset->getRuleset();

        $this->log->warning(json_encode($ruleset));

        $this->assertIsArray($ruleset);
    }

    //actions - block , managed_challenge
}
