<?php

declare(strict_types=1);

function revertCharacters(string $string): string
{
    $reversedParts = [];

    $parts = explode(' ', $string);
    foreach ($parts as $part) {
        $chars = mb_str_split($part);
        $reversedChars = [];

        $normalChars = mb_str_split(preg_replace('/[^a-zа-яё]/iu', null, $part));
        $reversedNormalChars = array_reverse($normalChars);

        foreach ($chars as $number => $char) {
            $key = array_search($char, $normalChars);

            // это спецсимвол
            if ($key === false) {
                $reversedChars[] = $char;

                continue;
            }

            $reversedChar = $reversedNormalChars[$key];

            // выбор регистра буквы
            if (mb_strtolower($char) === $char) {
                $reversedChar = mb_strtolower($reversedChar);
            } else {
                $reversedChar = mb_strtoupper($reversedChar);
            }

            $reversedChars[] = $reversedChar;

            unset($normalChars[$key], $reversedNormalChars[$key]);
        }

        $reversedParts[] = implode('', $reversedChars);
    }

    return implode(' ', $reversedParts);
}

// Тесты

$cases = [
    'Привет' => 'Тевирп',
    'Привет!' => 'Тевирп!',
    'Привет! Давно не виделись.' => 'Тевирп! Онвад ен ьсиледив.',
    'Привет ❤️' => 'Тевирп ❤️',
    "Д'артаньян" => "Н'яьнатрад",
    "Oh, what you're doing motherf***er? 0_o" => "Ho, tahw eru'oy gniod refreht***om? 0_o",
    'ПрЕвеД!!!1' => 'ДеВерП!!!1',
    'Дева4ка' => 'Акав4ед',
    'Что-то не так?' => 'Ото-тч ен кат?',
    '' => '',
    '00' => '00',
    '11 11!' => '11 11!',
];

foreach ($cases as $string => $expected) {
    $actual = revertCharacters($string);

    if ($expected !== $actual) {
        echo '⛔️ Тест провален' . PHP_EOL;
    } else {
        echo '✅  Тест пройден' . PHP_EOL;
    }

    echo "Ожидаемое: $expected. Полученное: $actual" . PHP_EOL . PHP_EOL;
}
