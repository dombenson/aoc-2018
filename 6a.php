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

$safePoints = 0;
$cutoff = 10000;


for($j=0;$j<$useGridY;$j++)
{
	for($i=0;$i<$useGridX; $i++)
	{
		$thisDist = 0;
		foreach($arrPoints as $id => $point)
		{
			$dist = abs($point[0]-$i) + abs($point[1] - $j);
			$thisDist+= $dist;
		}
		if($thisDist < $cutoff)
		{
			$safePoints++;
		}
	}
}

echo "Safe region: $safePoints";
