<?php

$data = trim(file_get_contents("6.txt"));

preg_match_all("/^([0-9]+),[\\s]*([0-9]+)$/m", $data, $matches, PREG_SET_ORDER);

$maxX = $maxY = 0;

$equalCount = 0;
$arrPoints = [];
$arrRegionSizes = [];
foreach($matches as $match)
{
	$x = $match[1];
	$y = $match[2];
	$idx = count($arrPoints);
	$arrPoints[$idx] = [$x,$y];
	$arrRegionSizes[$idx] = 0;
	$maxX = max($maxX, $x);
	$maxY = max($maxY, $y);
}

$useGridX = $maxX + 2;
$useGridY = $maxY + 2;

$infiniteRegions = [];


for($j=0;$j<$useGridY;$j++)
{
	for($i=0;$i<$useGridX; $i++)
	{
		$isInfinite = ($i == 0 || $j == 0 || ($i == ($useGridX - 1) | ($j == ($useGridY - 1))));
		$closest = null;
		$closestDist = $maxX + $maxY;
		foreach($arrPoints as $id => $point)
		{
			$dist = abs($point[0]-$i) + abs($point[1] - $j);
			if($dist < $closestDist)
			{
				$closest = $id;
				$closestDist = $dist;
			}
			elseif($dist == $closestDist)
			{
				$closest = null;
			}
		}
		if(isset($closest))
		{
			if($isInfinite)
			{
				$infiniteRegions[$closest] = 1;
			}
			$arrRegionSizes[$closest]++;
		}
		else
		{
			$equalCount++;
		}
	}
}

echo "Region sum: ".array_sum($arrRegionSizes)."\n";
echo "Grid: $useGridX x $useGridY\nNeutral points: $equalCount\n";

print_r($infiniteRegions);

$finiteRegions = array_diff_key($arrRegionSizes, $infiniteRegions);


arsort($finiteRegions);

print_r($finiteRegions);

