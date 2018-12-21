<?php

$strData = trim(file_get_contents("12.txt"));

preg_match("/^[\\s]*initial state:[\\s]*([#.]+)[\\s]*(.*)$/s", $strData, $matches);

$currentState = str_split($matches[1]);
$ruleStr = trim($matches[2]);

$lowestId = 0;
$highestId = count($currentState);

preg_match_all("/^([#.]+)[\\s]*=>[\\s]*([#.])[\\s]*$/m", $ruleStr, $ruleMatches, PREG_SET_ORDER);

$rules = [];
foreach ($ruleMatches as $ruleMatch)
{
	$rules[$ruleMatch[1]] = $ruleMatch[2];
}

for($i=0;$i<1000000;$i++)
{
	$nextState = [];
	$prevLowest = $lowestId;
	$prevHighest = $highestId;
	$lowestId-=2;
	$highestId+=2;
	$nextLowest = $highestId;
	$nextHighest = $lowestId;
	for($id=$lowestId;$id<= $highestId; $id++)
	{
		$curStr = "";
		for($j=-2;$j<=2;$j++)
		{
			if(array_key_exists($j+$id, $currentState))
			{
				$curStr .= $currentState[$j+$id];
			}
			else
			{
				$curStr .= ".";
			}
		}
		$newVal = $rules[$curStr];
		if($newVal == "#")
		{
			if($id > $nextHighest)
			{
				$nextHighest = $id;
			}
			if($id < $nextLowest)
			{
				$nextLowest = $id;
			}
		}
		$nextState[$id] = $newVal;
	}

	$lowestId = $nextLowest;
	$highestId = $nextHighest;

	if(($nextHighest-$nextLowest) == ($prevHighest-$prevLowest))
	{
		$oldStr = implode("", array_slice($currentState, $prevLowest, $prevHighest-$prevLowest));
		$newStr = implode("", array_slice($nextState, $nextLowest, $nextHighest-$nextLowest));
		if($oldStr == $newStr)
		{
			// Reached stable state (just possibly striding the whole set along)
			$stepsDone = $i;
			$strideDist = $nextLowest-$prevLowest;
			echo "Stable after $i steps; striding $strideDist\n";
			break;
		}
	}

	$currentState = $nextState;
}

$sumIds = 0;
foreach ($currentState as $id=>$val)
{
	if($val == "#")
	{
		$sumIds += $id;
	}
}

echo "ID sum: $sumIds\n";


$nextSumIds = 0;
foreach ($nextState as $id=>$val)
{
	if($val == "#")
	{
		$nextSumIds += $id;
	}
}

echo "Next ID sum: $nextSumIds\n";

$finalVal = (50000000000-$stepsDone) * ($nextSumIds-$sumIds) + $sumIds;

echo "After 50bn: $finalVal\n";