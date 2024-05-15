<?php

namespace UzDevid\WebSocket\Helper;

class HeaderParser {
    /**
     * @param string $headers
     * @return array
     */
    public static function parse(string $headers): array {
        $headersArray = [];
        $lines = explode("\r\n", $headers);

        foreach ($lines as $line) {
            if (str_contains($line, ':')) {
                [$key, $value] = explode(': ', $line, 2);
                $headersArray[$key] = $value;
            }
        }

        return $headersArray;
    }
}