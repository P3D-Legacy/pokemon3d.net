<?php

namespace App\Support;

class WikiDiff
{
    /**
     * @return list<array{type: string, text: string}>
     */
    public static function lines(?string $from, ?string $to): array
    {
        $fromLines = preg_split("/\r\n|\n|\r/", (string) $from) ?: [''];
        $toLines = preg_split("/\r\n|\n|\r/", (string) $to) ?: [''];

        $fromCount = count($fromLines);
        $toCount = count($toLines);
        $lcs = [];

        for ($i = 0; $i <= $fromCount; $i++) {
            $lcs[$i] = array_fill(0, $toCount + 1, 0);
        }

        for ($i = $fromCount - 1; $i >= 0; $i--) {
            for ($j = $toCount - 1; $j >= 0; $j--) {
                $lcs[$i][$j] = $fromLines[$i] === $toLines[$j]
                    ? $lcs[$i + 1][$j + 1] + 1
                    : max($lcs[$i + 1][$j], $lcs[$i][$j + 1]);
            }
        }

        $diff = [];
        $i = 0;
        $j = 0;

        while ($i < $fromCount && $j < $toCount) {
            if ($fromLines[$i] === $toLines[$j]) {
                $diff[] = ['type' => 'equal', 'text' => $fromLines[$i]];
                $i++;
                $j++;

                continue;
            }

            if ($lcs[$i + 1][$j] >= $lcs[$i][$j + 1]) {
                $diff[] = ['type' => 'remove', 'text' => $fromLines[$i]];
                $i++;

                continue;
            }

            $diff[] = ['type' => 'add', 'text' => $toLines[$j]];
            $j++;
        }

        while ($i < $fromCount) {
            $diff[] = ['type' => 'remove', 'text' => $fromLines[$i]];
            $i++;
        }

        while ($j < $toCount) {
            $diff[] = ['type' => 'add', 'text' => $toLines[$j]];
            $j++;
        }

        return $diff;
    }
}
