<?php

$basestr = trim(file_get_contents("5.txt"));
$bestlen = strlen($basestr);

for($d = 97; $d < 123; $d++)
{
	$todel = chr($d);
	$todelupper = strtoupper($todel);

	$str = str_replace([$todel, $todelupper], "", $basestr);

	$eliminated = [];
	$max = strlen($str);
	$ptr = 0;

	while ($ptr < $max)
	{
		$next = $ptr + 1;
		while (array_key_exists($next, $eliminated))
		{
			$next++;
		}
		if ($next >= $max)
		{
			break;
		}
		$base = $str[$ptr];
		$cmp = $str[$next];
		if ((strcasecmp($base, $cmp) === 0) && (strcmp($base, $cmp) != 0))
		{
			// same char, different cases. Eliminate both.
			$eliminated[$ptr] = 1;
			$eliminated[$next] = 1;
			while (array_key_exists($ptr, $eliminated))
			{
				$ptr--;
			}
			// In case the beginning has been eliminated, we may need to track forwards
			if ($ptr < 0)
			{
				$ptr = 0;
				while (array_key_exists($ptr, $eliminated))
				{
					$ptr++;
				}
			}
			continue;
		}
		$ptr++;
		while (array_key_exists($ptr, $eliminated))
		{
			$ptr++;
		}
	}


	$numRemaining = $max - count($eliminated);

	if($numRemaining < $bestlen)
	{
		$bestlen = $numRemaining;
	}
}

echo "Best case: $bestlen\n";
