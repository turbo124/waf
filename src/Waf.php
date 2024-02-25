<?php

namespace Turbo124\Waf;

use Turbo124\Waf\Http\Client;
use Turbo124\Waf\Ruleset\Ruleset;

class Waf
{

    /** @var string $url */
    public string $url = 'https://api.cloudflare.com/client/v4/';

    /** @var string $ban_ip_expression */
    private string $ban_ip_expression = '(ip.src eq :ip)';

    /** @var string $ban_country_expression */
    private string $ban_country_expression = '(ip.geoip.country eq ":iso_3166_2")';


    public Client $client;

    public Ruleset $ruleset;

    public function __construct(public string $x_auth_key, public string $x_auth_email, public string $zone_id)
    {

        $this->client = new Client($this);
        $this->ruleset = new Ruleset($this);

    }

    public function banIp(string $ip): bool
    {

    }

    public function unbanIp(string $ip): bool
    {

    }

    public function challengeIp(string $ip): bool
    {

    }

    public function unchallengeIp(string $ip): bool
    {
        
    }

    public function banAsn(string $asn): bool
    {

    }

    public function unbanAsn(string $asn): bool
    {

    }

    public function banCountry(string $iso_3166_2): bool
    {

    }

    public function unbanCountry(string $iso_3166_2): bool
    {
        
    }
}