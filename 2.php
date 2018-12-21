<?php

$doubles = $triples = 0;

$file = fopen("2.txt", "r");

while($line = fgets($file))
{
  $thisCounts = [];
  $letters = str_split($line, 1);
  foreach($letters as $letter)
  {
     if(!array_key_exists($letter, $thisCounts))
     {
       $thisCounts[$letter] = 1;
     }
     else
     {
       $thisCounts[$letter]++;
     }
  }
  $thisTwo = $thisThree = false;
  foreach($thisCounts as $cnt)
  {
    if($cnt == 2) $thisTwo = true;
    if($cnt == 3) $thisThree = true;
  }
  if($thisTwo) $doubles++;
  if($thisThree) $triples++;
}

echo ($doubles*$triples)."\n";

