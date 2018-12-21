<?php

$arrUsed = [];
$arrMultiUsed = [];

$file = fopen("3.txt", "r");
while($line = fgets($file))
{
  if(preg_match("/^#([0-9]*)[\\s]*@[\\s]*([0-9]+),([0-9]+):[\\s]*([0-9]+)x([0-9]+)/", $line, $matches))
  {
    $claimId = $matches[1];
    $left = $matches[2];
    $top = $matches[3];
    $width = $matches[4];
    $height = $matches[5];

    for($xoff = 0;$xoff < $width; $xoff++)
    {
      $thisX = $xoff+$left;
      for($yoff = 0; $yoff < $height; $yoff++)
      {
        $thisY = $yoff + $top;
        $id = "$thisX,$thisY";
        if(array_key_exists($id, $arrUsed))
        {
          $arrMultiUsed[$id] = 1;
        }
        else
        {
          $arrUsed[$id] = 1;
        }
      }
    }
  }
}

echo count($arrMultiUsed)."\n";

