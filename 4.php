<?php

$arrEventsByDay = [];

$file = fopen("4.txt", "r");
while($line = fgets($file))
{
  if(preg_match("/^\[(([0-9-]*)[\\s]+([0-9]+):([0-9]+))\][\\s]*(.*?)[\\s]*$/", $line, $matches))
  {
     $strDay = $matches[2];
     $hour = $matches[3];
     $minute = $matches[4];
     $evt = $matches[5];
     $ts = strtotime($matches[1]);
     if($hour > 0)
     {
       $ts += ((25-$hour)*3600);
       $strDay = date("Y-m-d", $ts);
       $minute = -1;
     }
     if(!array_key_exists($strDay, $arrEventsByDay))
     {
       $arrEventsByDay[$strDay] = ["guard"=>null,"evts"=>[]];
     }
     if(preg_match("/Guard #([0-9]+) begins shift/i", $evt, $submatches))
     {
       $arrEventsByDay[$strDay]["guard"] = $submatches[1];
     }
     else
     {
       $arrEventsByDay[$strDay]["evts"][intval($minute)]=$evt;
     }
  }
}
fclose($file);

$totalsPerGuard = [];
$daysByGuard = [];
$minutesPerGuard = [];

foreach($arrEventsByDay as $strDay => &$arrInfo)
{
   $sleep=false;
   $guard = $arrInfo["guard"];
   $arrInfo["sleep"] = array_fill(0, 60, 0);
   if(!array_key_exists($guard, $minutesPerGuard))
   {
     $minutesPerGuard[$guard] = array_fill(0, 60, 0);
   }
   for($i=0;$i<60;$i++)
   {
     if(array_key_exists($i, $arrInfo["evts"]))
     {
       if($arrInfo["evts"][$i] == "wakes up") $sleep = false;
       if($arrInfo["evts"][$i] == "falls asleep") $sleep = true;
     }
     $arrInfo["sleep"][$i]=$sleep;
     if($sleep) $minutesPerGuard[$guard][$i]++;
   }
   $arrInfo["numasleep"] = array_sum($arrInfo["sleep"]);
   if(!array_key_exists($guard, $totalsPerGuard))
   {
     $totalsPerGuard[$guard] = 0;
   }
   $totalsPerGuard[$guard] += $arrInfo["numasleep"];
   $daysByGuard[$guard][$strDay] = 1;
}

arsort($totalsPerGuard);
$mostMins = reset($totalsPerGuard);
$mostSleepy = key($totalsPerGuard);

$sleepiestGuardMins = $minutesPerGuard[$mostSleepy];
arsort($sleepiestGuardMins);
$minCount = reset($sleepiestGuardMins);
$sleepiestMin = key($sleepiestGuardMins);

echo "Guard: $mostSleepy ($mostMins total)\nBest minute $sleepiestMin ($minCount times)\nResult: ".($mostSleepy*$sleepiestMin)."\n";


$sleepiestMinute = -1;
$selectedGuard = -1;
$sleepiestScore = 0;
foreach($minutesPerGuard as $guard=>$mins)
{
  arsort($mins);
  $minCount = reset($mins);
  $sleepiestMin = key($mins);
  if($minCount > $sleepiestScore)
  {
    $selectedGuard = $guard;
    $sleepiestMinute=$sleepiestMin;
    $sleepiestScore = $minCount;
  }
}


echo "Guard: $selectedGuard is asleep $sleepiestScore times at minute $sleepiestMinute\nResult: ".( $selectedGuard * $sleepiestMinute)."\n";
