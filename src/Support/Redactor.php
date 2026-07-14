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
        return self::apply($data, self::lookup($keys), $replacement);
    }

    /**
     * Replace the values of the given header names, matched
     * case-insensitively, preserving the name => [values] shape.
     */
    public static function redactHeaders(array $headers, array $names, string $replacement): array
    {
        $lookup = self::lookup($names);

        foreach ($headers as $name => $values) {
            if (isset($lookup[strtolower((string) $name)])) {
                $headers[$name] = [$replacement];
            }
        }

        return $headers;
    }

    private static function apply(array $data, array $lookup, string $replacement): array
    {
        foreach ($data as $key => $value) {
            if (isset($lookup[strtolower((string) $key)])) {
                $data[$key] = $replacement;
            } elseif (is_array($value)) {
                $data[$key] = self::apply($value, $lookup, $replacement);
            }
        }

        return $data;
    }

    /**
     * @return array<string, true>
     */
    private static function lookup(array $keys): array
    {
        $lookup = [];

        foreach ($keys as $key) {
            $lookup[strtolower((string) $key)] = true;
        }

        return $lookup;
    }
}
