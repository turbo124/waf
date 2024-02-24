<?php

namespace Turbo124\Waf\Ruleset;

use Turbo124\Waf\Waf;

class Ruleset
{

    /** @var string $ruleset_name */
    private string $ruleset_name = 'http_request_firewall_custom';

    public function __construct(public Waf $waf)
    {
    }

    /**
     * Returns the custom firewall ruleset
     *
     * @return array
     */
    public function getRuleset(): array
    {
        $cloudflare_endpoint = "{$this->waf->url}zones/{$this->waf->zone_id}/rulesets";

        $response = $this->waf->listPaginator($cloudflare_endpoint);
    
        if($response->successful()) {
            
            $result = $response->json()['result'];

            foreach($result as $ruleset)
            {
                if($ruleset['phase'] != $this->ruleset_name) 
                    continue;
                
                
                $cf_ruleset = $ruleset;

            }
            
            $cloudflare_endpoint = "{$this->waf->url}zones/{$this->waf->zone_id}/rulesets/{$cf_ruleset['id']}";

            $response = $this->waf->listPaginator($cloudflare_endpoint);
            
            return $response->json()['result'];
            
        }

        throw new \Exception("Could not get rules " . $response->error());

    }
    
}