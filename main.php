<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>lab 6 files</title>
</head>
<body>
<?php
function changeCase($word, $letters) {
    $result = '';
    for ($i = 0; $i < mb_strlen($word); $i++) {
        $char = mb_substr($word, $i, 1);
        if (mb_ereg_match('[а-яА-Я]', $char) && !in_array(mb_strtolower($char), $letters)) {
            $result .= mb_strtoupper($char) === $char ? mb_strtolower($char) : mb_strtoupper($char);
        } else {
            $result .= $char;
        }
    }
    return $result;
}

$inputText = file_get_contents('input.txt');

$lines = mb_split("\n", $inputText);

$longestLine = '';
foreach ($lines as $line) {
    if (mb_strlen($line) > mb_strlen($longestLine)) {
        $longestLine = $line;
    }
}

$words = mb_split('[[:punct:][:space:]]+', trim($longestLine));

$lastWord = end($words);

$letters = array_unique(array_map('mb_strtolower', mb_str_split($lastWord)));

$outputText = '';
foreach ($lines as $line) {
    $words = mb_split('[[:punct:][:space:]]+', $line);
    
    foreach ($words as $word) {
        $containsLetters = false;
        foreach ($letters as $letter) {
            if (mb_strpos(mb_strtolower($word), $letter) !== false) {
                $containsLetters = true;
                break;
            }
        }
        
        if (!$containsLetters) {
            $word = changeCase($word, $letters);
        }
        
        $outputText .= $word . ' ';
    }
    
    $outputText = rtrim($outputText) . "\n";
}

file_put_contents('output.txt', $outputText);

echo "Обработка завершена. Результат записан в output.txt.";
?>
</body>
</html>