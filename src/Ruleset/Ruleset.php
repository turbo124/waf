<?php

namespace Turbo124\Waf\Ruleset;

use Turbo124\Waf\Waf;

class Ruleset
{

    /** @var string $ruleset_name */
    private string $ruleset_name = 'http_request_firewall_custom';

    /** @var string $ban_ip_expression */
    private string $ban_ip_expression = '(ip.src eq :ip)';

    /** @var string $ban_country_expression */
    private string $ban_country_expression = '(ip.geoip.country eq ":iso_3166_2")';

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

        $response = $this->waf->client->listPaginator($cloudflare_endpoint);
    
        if($response->successful()) {
            
            $result = $response->json()['result'];

            foreach($result as $ruleset)
            {
                if($ruleset['phase'] != $this->ruleset_name) 
                    continue;
                
                
                $cf_ruleset = $ruleset;

            }
            
            $cloudflare_endpoint = "{$this->waf->url}zones/{$this->waf->zone_id}/rulesets/{$cf_ruleset['id']}";

            $response = $this->waf->client->listPaginator($cloudflare_endpoint);
            
            return $response->json()['result'];
            
        }

        throw new \Exception("Could not get rules " . $response->error());

    }

    /**
     * Adds a rule from the Firewall
     *
     * @param  string $expression
     * @param  string $action
     * 
     * @return bool
     */
    public function addRule(string $expression, string $action)
    {
        $ruleset = $this->getRuleset();

        $rule = false;
        
        if(isset($ruleset['rules'])) {
            $rule = collect($ruleset['rules'])->first(function ($rules) use ($action) {
                return $rules['action'] == $action;
            });
        }

        if($rule) {
            return $this->updateRuleExpression($ruleset, $this->addExpression($rule, $expression), $rule);
        }

        return $this->addRuleParent($ruleset, $expression, $action);
    }
    


    /**
     * Updates the rules expression
     *
     * @param  array $ruleset
     * @param  string $expression
     * @param  array $rule
     * 
     * @return bool
     */
    public function updateRuleExpression(array $ruleset, string $expression, array $rule): bool
    {
        $rule['expression'] = $expression;

        if(strlen($expression) == 0)
            return $this->deleteRule($ruleset, $rule);

        $cloudflare_endpoint = "{$this->waf->url}zones/{$this->waf->zone_id}/rulesets/{$ruleset['id']}/rules/{$rule['id']}";

        
        $response = $this->waf->client->request($cloudflare_endpoint, "PATCH", $rule);

        if($response->successful()) {
            return true;
        }

        throw new \Exception("Could not get rules " . $response->error());

    }
    
    /**
     * Adds a expression to the rule
     *
     * @param  array $rule
     * @param  string $expression
     * 
     * @return string
     */
    public function addExpression($rule, $expression): string
    {
        
        return collect(explode("or", $rule['expression']))->filter(function ($current) use ($expression) {
            return $current != $expression;
        })->push($expression)->implode("or");

    }
    
    /**
     * Removes a expression to the rule
     *
     * @param  array $rule
     * @param  string $expression
     * 
     * @return string
     */
    public function removeExpression(array $rule, string $expression): string 
    {
        
        return collect(explode("or", $rule['expression']))->filter(function ($current) use ($expression) {
            return $current != $expression;
        })->implode("or");

    }
         
     /**
      * Delete Rule
      *
      * @param  array $ruleset
      * @param  array $rule

      * @return bool
      */
     public function deleteRule(array $ruleset, array $rule): bool 
     {

        $ruleset_id = $ruleset['id'];
        $rule_id = $rule['id'];

        $cloudflare_endpoint = "{$this->waf->url}zones/{$this->waf->zone_id}/rulesets/{$ruleset_id}/rules/{$rule_id}";

        $response = $this->waf->client->request($cloudflare_endpoint, "DELETE", []);

        if($response->successful()) {
            return true;
        }

        throw new \Exception("Could not get rules " . $response->body());

    }


    /**
     * Adds a new rule
     *
     * @param  array $ruleset
     * @param  string $expression
     * @param  string $action
     * 
     * @return bool
     */
    public function addRuleParent(array $ruleset, string $expression, string $action)
    {

        $cloudflare_endpoint = "{$this->waf->url}zones/{$this->waf->zone_id}/rulesets/{$ruleset['id']}/rules";

        $rule = [
            'action' => $action,
            'expression' => $expression,
            'description' => "Added by botlicker on " . \Carbon\Carbon::now()->toDateTimeString()
        ];

        $response = $this->waf->client->request($cloudflare_endpoint, "POST", $rule);

        if($response->successful()) {
            return true;
        }

        throw new \Exception("Could not add rule {$action} => " . $response->error());

    }

    /**
     * Get the rules in the WAF
     *
     * @return array
     */
    public function getRules(): array
    {
        $rules = [
            'block' => [],
            'managed_challenge' => [],
        ];

        $ruleset = $this->getRuleset();

        if(isset($ruleset['rules'])) {
            
            $rules = collect($ruleset['rules'])->map(function ($rule){
                return [
                    $rule['action'] => explode("or",$rule['expression']),
                ];
            })->toArray();
            
        }

        return $rules;
    }
}