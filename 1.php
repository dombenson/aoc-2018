<?php
$ctr = 0;
$seenFreqs = [];
while(true)
{
$file = fopen("1.txt", "r");
while($line = fgets($file))
{
    if(preg_match("/^([+-])[\\s]*([0-9]+)/", $line, $matches))
    {
        if($matches[1] == "+") $ctr += $matches[2];
        else $ctr -= $matches[2];

        if(array_key_exists($ctr, $seenFreqs)) {
            echo "$ctr\n";
            break(2);
        }
        $seenFreqs[$ctr] = 1;
    }
}
fclose($file);
}

