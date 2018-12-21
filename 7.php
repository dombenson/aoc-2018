<?php

$data = trim(file_get_contents("7.txt"));

preg_match_all("/^Step ([A-Z]+) must be finished before step ([A-Z]+) can begin./mi", $data, $matches, PREG_SET_ORDER);

$prereqs = [];

foreach ($matches as $match)
{
	$tgtStep = $match[2];
	$prereqStep = $match[1];
	if(!array_key_exists($prereqStep, $prereqs))
	{
		$prereqs[$prereqStep] = [];
	}
	if(!array_key_exists($tgtStep, $prereqs))
	{
		$prereqs[$tgtStep] = [];
	}
	$prereqs[$tgtStep][$prereqStep] = 1;
}

$stepsToAdd = array_fill_keys(array_keys($prereqs), 1);
ksort($stepsToAdd);

$done = [];

while($stepsToAdd)
{
	foreach ($stepsToAdd as $step=>$ignore)
	{
		$thisPrereqs = $prereqs[$step];
		$ok = true;
		foreach($thisPrereqs as $p=>$ignore)
		{
			if(!array_key_exists($p, $done))
			{
				$ok = false;
				break;
			}
		}
		if($ok)
		{
			$done[$step] = 1;
			unset($stepsToAdd[$step]);
			break;
		}
	}
}

echo implode("", array_keys($done));

