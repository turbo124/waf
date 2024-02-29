<?php declare(strict_types=1);

use Monolog\Level;
use Monolog\Logger;
use Turbo124\Waf\Waf;
use PHPUnit\Framework\TestCase;
use Monolog\Handler\StreamHandler;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Waf::class)]
final class CloudflareTest extends TestCase
{

    protected Logger $log;

    protected Waf $waf;

    protected function setUp(): void
    {
        $this->log = new Logger('test');
        $this->log->pushHandler(new StreamHandler('tests.log', Level::Warning));

        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        if(!$_ENV["CLOUDFLARE_ZONE_ID"])
             $this->markTestSkipped("Cloudflare Zone ID not set");
    
    
        $zone = $_ENV["CLOUDFLARE_ZONE_ID"];
        $email = $_ENV["CLOUDFLARE_EMAIL"];
        $api_key = $_ENV["CLOUDFLARE_API_KEY"];
        $account_id = $_ENV["CLOUDFLARE_ACCOUNT_ID"];

        $this->waf = new Waf($api_key, $email, $zone, $account_id);

    }

    public function testBanIp(): void
    {
        
        $this->assertInstanceOf(Waf::class, $this->waf);
        
        $response = $this->waf->banIp('103.15.248.111');

        $this->assertTrue($response);
    }

}