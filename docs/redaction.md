---
title: Redaction
weight: 6
group: Usage
---

Sensitive values are **redacted by default** before a log is stored: common credential
fields (`password`, `token`, `access_token`, …) in the request data, query string and
response data, and sensitive request headers (`Authorization`, `Cookie`, `X-Api-Key`, …)
are replaced with `[REDACTED]`.

To customize the redaction lists or the replacement string, [publish the config
file](configuration.md) and adjust the `redact` options in `config/api-logs.php`:

```php
'redact' => [
    'replacement' => '[REDACTED]',
    'keys' => ['password', 'access_token' /* , ... */],
    'headers' => ['authorization', 'cookie' /* , ... */],
],
```

`keys` are matched case-insensitively and recursively against the request payload,
query string and response data; `headers` are matched against request header names.
