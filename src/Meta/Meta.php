<?php

namespace Turbo124\Waf\Meta;

use Turbo124\Waf\Waf;

class Meta
{

    public function __construct(public Waf $waf) {}
    
    /**
     * parseIpInfo
     *
     * @param  mixed $response_json
     * @return array
     */
    public function parseIpInfo(iterable $response_json): array
    {
        return [
            'ip' => $response_json['result'][0]['ip'],
            'asn' => $response_json['result'][0]['belongs_to_ref']['value'],
            'country' => $response_json['result'][0]['belongs_to_ref']['country'],
            'risk_types' => $response_json['result'][0]['risk_types'] ?? []
        ];
    }
    
    /**
     * getIpInfo
     *
     * @param  string $ip
     * @return array
     */
    public function getIpInfo(string $ip): array
    {

        $url = "{$this->waf->url}accounts/{$this->waf->account_id}/intel/ip?ipv4={$ip}";

        $response = $this->waf->client->request($url, 'GET');

        if($response->successful())
            return $this->parseIpInfo($response->json());

    }
    
    /**
     * parseDomainInfo
     *
     * @param  mixed $response_json
     * @return array
     */
    public function parseDomainInfo(iterable $response_json): array
    {
        return [
            'domain' => $response_json['result']['domain'],
            'ip' => collect($response_json['result']['resolves_to_refs'])->pluck('value')->implode(",") ?? [],
            'risk_type_string' => collect($response_json['result']['risk_type'] ?? [])->pluck('name')->implode(",") ?? [],
            'risk_type' => $response_json['result']['risk_type'] ?? [],
            'content_catgories' => $response_json['result']['content_categories'] ?? [],
            'content_category_string' => collect($response_json['result']['content_categories'] ?? [])->pluck('name')->implode(",") ?? [],
        ];
    }
    
    /**
     * getDomainInfo
     *
     * @param  string $domain
     * @return array
     */
    public function getDomainInfo(string $domain): array
    {
        $url = "{$this->waf->url}accounts/{$this->waf->account_id}/intel/domain?domain={$domain}";
        
        $response = $this->waf->client->request($url, 'GET');

        if($response->successful()) {
            return $this->parseDomainInfo($response->json());
        }

    }
    
    /**
     * Format Domain
     *
     * @param  string $domain
     * 
     * @return ?string
     */
    private function getIpOfDomain(string $domain): ?string
    {
        if(strpos($domain, '@')){
            $domain = explode('@', $domain)[1];
        }

        $dns = dns_get_record($domain);

        $ip = &$dns[0]['ip'];

        if($ip)
            return $domain;
        
        return null;
    }
}
