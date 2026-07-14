<?php

namespace CodeTech\ApiLogs\Support;

class Redactor
{
    /**
     * Replace the values of the given keys, matched case-insensitively and
     * recursively, with the replacement string.
     */
    public static function redact(array $data, array $keys, string $replacement): array
    {
        $keys = array_map('strtolower', $keys);

        foreach ($data as $key => $value) {
            if (in_array(strtolower((string) $key), $keys, true)) {
                $data[$key] = $replacement;
            } elseif (is_array($value)) {
                $data[$key] = static::redact($value, $keys, $replacement);
            }
        }

        return $data;
    }
}
