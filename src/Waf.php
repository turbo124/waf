<?php

namespace Turbo124\Waf;

use Turbo124\Waf\Http\Client;

class Waf
{

    /** @var string $url */
    public string $url = 'https://api.cloudflare.com/client/v4/';

    /** @var string $ban_ip_expression */
    private string $ban_ip_expression = '(ip.src eq :ip)';

    /** @var string $ban_country_expression */
    private string $ban_country_expression = '(ip.geoip.country eq ":iso_3166_2")';

    /** @var string $ruleset_name */
    private string $ruleset_name = 'http_request_firewall_custom';

    public Client $client;

    public function __construct(public string $x_auth_key, public string $x_auth_email, public string $zone_id)
    {
        $this->init();
    }


    private function init(): self
    {
        $this->client = new Client($this);

        return $this;
    }

    /**
     * Returns a list response for pagination
     *
     * @param  string $url
     * @param  int $page
     * @param  int $per_page
     *
     * @return Client
     */
    public function listPaginator($url, $page = 1, $per_page = 50): Client
    {

        $this->client->request($url, 'GET', ['page' => $page, 'per_page' => $per_page]);

        return $this->client;

    }





}