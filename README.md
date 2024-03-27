# Web Application Firewall

A wrapper over the Cloudflare WAF.

## Ban IP addresses at the network edge

### Initialize the Cloudflare WAF

```
$zone = The Cloudflare Zone ID
$account_id = The Cloudflare Account ID;
$email = The email address associated with the cloudflare account;
$api_key = The cloudflare API key (must have permissions with write Zone Rulesets and Rules);

```

### Ban a IP address

```php
$waf = new \Turbo124\Waf\Waf($api_key, $email, $zone, $account_id);
```

### Unban a IP Address

```php
$waf->unbanIp('103.15.248.112');
```

### Ban a ASN

```php
$waf->banASn('10343');
```

### UnBan a ASN

```php
$waf->unbanAsn('10343');
```

### Ban a country by their ISO 3166 2 country code

```php
$waf->banCountry('DE');
```

### UnBan a country by their ISO 3166 2 country code

```php
$waf->unbanCountry('DE');
```

### Managed challenge a IP Address

```php
$waf->challengeIp('103.15.248.112');
```

### Disable managed challenge on a IP Address

```php
$waf->unchallengeIp('103.15.248.112');
```

## Bonus intelligence helpers.

Cloudflare providers some nifty intelligence metrics on certain domains and IP addresses and can provide risk ratings