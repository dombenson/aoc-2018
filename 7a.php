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

$availableWorkers = 5;

$ticksSpent = 0;
$inProgress = [];

while($stepsToAdd)
{
	while($availableWorkers > 0)
	{
		$didStart = false;
		foreach ($stepsToAdd as $step => $ignore)
		{
			if(array_key_exists($step, $inProgress))
			{
				continue;
			}
			$thisPrereqs = $prereqs[$step];
			$ok = true;
			foreach ($thisPrereqs as $p => $ignore)
			{
				if (!array_key_exists($p, $done))
				{
					$ok = false;
					break;
				}
			}
			if ($ok)
			{
				$availableWorkers--;
				echo "Starting step $step at $ticksSpent\n";
				$didStart = true;
				$inProgress[$step] = 60+(ord($step) - ord("A") + 1);
				break;
			}
		}
		if(!$didStart)
		{
			break;
		}
	}
	$ticksSpent++;
	$toDel = [];
	foreach($inProgress as $step=>$remaining)
	{
		$newRemaining = $remaining-1;
		if($newRemaining <= 0)
		{
			$toDel[] = $step;
			unset($stepsToAdd[$step]);
			$done[$step] = true;
			echo "Done step $step at $ticksSpent\n";
			$availableWorkers++;
		}
		else
		{
			$inProgress[$step] = $newRemaining;
		}
	}
	foreach ($toDel as $del)
	{
		unset($inProgress[$del]);
	}
}

echo $ticksSpent."\n";

