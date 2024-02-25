<?php

namespace Turbo124\Waf;

use Turbo124\Waf\Meta\Meta;
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

    /** @var string $ban_asn_expression */
    private string $ban_asn_expression = '(ip.geoip.asnum eq :asn)';

    public Client $client;

    public Ruleset $ruleset;

    public Meta $meta;
    
    public function __construct(public string $x_auth_key, public string $x_auth_email, public string $zone_id, public string $account_id)
    {

        $this->client = new Client($this);
        $this->ruleset = new Ruleset($this);
        $this->meta = new Meta($this);
    }
    
    /**
     * ban ip
     *
     * @param  string $ip
     * @return bool
     */
    public function banIp(string $ip): bool
    {

        $expression = str_replace(":ip", $ip, $this->ban_ip_expression);

        return $this->ruleset->addRule($expression, 'block');

    }
    
    /**
     * unban ip
     *
     * @param  string $ip
     * @return bool
     */
    public function unbanIp(string $ip): bool
    {

        $expression = str_replace(":ip", $ip, $this->ban_ip_expression);

        return $this->ruleset->removeRule($expression, 'block');

    }
    
    /**
     * challenge ip address
     *
     * @param  string $ip
     * @return bool
     */
    public function challengeIp(string $ip): bool
    {
        
        $expression = str_replace(":ip", $ip, $this->ban_ip_expression);

        return $this->ruleset->addRule($expression, 'managed_challenge');

    }
    
    /**
     * unchallenge ip address
     *
     * @param  string $ip
     * @return bool
     */
    public function unchallengeIp(string $ip): bool
    {
        
        $expression = str_replace(":ip", $ip, $this->ban_ip_expression);

        return $this->ruleset->removeRule($expression, 'managed_challenge');

    }
    
    /**
     * ban Asn
     *
     * @param  string $asn
     * @return bool
     */
    public function banAsn(string $asn): bool
    {

        $expression = str_replace(":asn", $asn, $this->ban_asn_expression);

        return $this->ruleset->addRule($expression, 'block');

    }
    
    /**
     * Unban Asn
     *
     * @param  string $asn
     * @return bool
     */
    public function unbanAsn(string $asn): bool
    {

        $expression = str_replace(":asn", $asn, $this->ban_asn_expression);

        return $this->ruleset->removeRule($expression, 'block');

    }
    
    /**
     * Ban Countryby their ISO 3166 2 code representation
     *
     * @param  string $iso_3166_2
     * @return bool
     */
    public function banCountry(string $iso_3166_2): bool
    {

        $expression = str_replace(":iso_3166_2", $iso_3166_2, $this->ban_country_expression);

        return $this->ruleset->addRule($expression, 'block');

    }
    
    /**
     * Unban Country by their ISO 3166 2 code representation
     *
     * @param  string $iso_3166_2
     * @return bool
     */
    public function unbanCountry(string $iso_3166_2): bool
    {
        
        $expression = str_replace(":iso_3166_2", $iso_3166_2, $this->ban_country_expression);

        return $this->ruleset->removeRule($expression, 'block');
        
    }
}