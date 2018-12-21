<?php
$allStrings = [];
$file = fopen("2.txt", "r");
while($line = fgets($file)) $allStrings[] = str_split($line, 1);
fclose($file);

$baseIdx = 0;
$checkIdx = 0;
$maxIdx = count($allStrings);

for($baseIdx = 0; $baseIdx < $maxIdx; $baseIdx++)
{
  $lineA = $allStrings[$baseIdx];
  $lineLen = count($lineA);
  for($checkIdx = $baseIdx+1; $checkIdx < $maxIdx; $checkIdx++)
  {
    $diffs = 0;
    $lineB = $allStrings[$checkIdx];
    for($i=0;$i<$lineLen;$i++)
    {
      if($lineA[$i] != $lineB[$i])
      {
        $diffs++;
        if($diffs > 1) continue(2);
      }
    }
    if($diffs == 1)
    {
      print_r([implode("", $lineA), implode("", $lineB)]);
      break(2);
    }
  }
}


