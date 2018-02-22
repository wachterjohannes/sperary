<?php

namespace App;

use App\Model\Block;

final class Hash
{
    public static function hashBlock(Block $block): string
    {
        return self::hashBlockData(
            $block->getIndex(),
            $block->getPreviousHash(),
            $block->getTimestamp(),
            $block->getData(),
            $block->getDifficulty(),
            $block->getNonce()
        );
    }

    public static function hashBlockData(
        int $index,
        string $previousHash,
        int $timestamp,
        string $data,
        int $difficulty,
        int $nonce
    ): string {
        return self::hash(serialize([$index, $previousHash, $timestamp, $data, $difficulty, $nonce]));
    }

    public static function hash(string $content): string
    {
        return hash('sha256', $content);
    }

    public static function hash2Binary(string $hash)
    {
        $binary = [];
        for ($i = 0; strlen($hash) > $i; $i++) {
            switch ($hash[$i]) {
                case '0':
                    $binary[] = '0000';
                    break;
                case '1':
                    $binary[] = '0001';
                    break;
                case '2':
                    $binary[] = '0010';
                    break;
                case '3':
                    $binary[] = '0011';
                    break;
                case '4':
                    $binary[] = '0100';
                    break;
                case '5':
                    $binary[] = '0101';
                    break;
                case '6':
                    $binary[] = '0110';
                    break;
                case '7':
                    $binary[] = '0111';
                    break;
                case '8':
                    $binary[] = '1000';
                    break;
                case '9':
                    $binary[] = '1001';
                    break;
                case 'a':
                    $binary[] = '1010';
                    break;
                case 'b':
                    $binary[] = '1011';
                    break;
                case 'c':
                    $binary[] = '1100';
                    break;
                case 'd':
                    $binary[] = '1101';
                    break;
                case 'e':
                    $binary[] = '1110';
                    break;
                case 'f':
                    $binary[] = '1111';
                    break;
            }
        }

        return implode($binary);
    }


    public static function isHashValid(string $hash, int $difficulty): bool
    {
        return substr(Hash::hash2Binary($hash), 0, $difficulty) === str_repeat('0', $difficulty);
    }

    private function __construct()
    {
    }
}
