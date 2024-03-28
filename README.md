# Web Application Firewall

A wrapper over the Cloudflare WAF.

Stop threats at the network edge, not at your application.

## Ban IP addresses at the network edge

### Initialize the Cloudflare WAF

```
$zone = The Cloudflare Zone ID
$account_id = The Cloudflare Account ID;
$email = The email address associated with the cloudflare account;
$api_key = The cloudflare API key (must have permissions with write Zone Rulesets and Rules);

```

## Init the WAF
```php
$waf = new \Turbo124\Waf\Waf($api_key, $email, $zone, $account_id);
```

### Ban a IP address

```php
$waf->unbanIp('103.15.248.112');
```


### Unban a IP Address

```php
$waf->unbanIp('103.15.248.112');
```

### Ban a ASN

```php
$waf->banAsn('10343');
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


### Get Domain Information

```php
$waf->getDomainInfo('example.com');
```

#### Response

```php
[
  "domain" => "puabook.com",
  "ip" => "51.254.35.55",
  "risk_type_string" => "Parked & For Sale Domains,Security Risks",
  "risk_type" => [
    [
      "id" => 128,
      "super_category_id" => 32,
      "name" => "Parked & For Sale Domains",
    ],
    [
      "id" => 32,
      "name" => "Security Risks",
    ],
  ],
  "content_catgories" => [],
  "content_category_string" => "",
]
```

### Get IP Information

```php
$waf->meta->getIpInfo('210.140.43.55');
```

#### Response

```php
[
  "ip" => "210.140.43.55",
  "asn" => 4694,
  "country" => "JP",
  "risk_types" => [],
]
```