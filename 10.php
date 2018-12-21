<?php

$str = trim(file_get_contents("10.txt"));

preg_match_all("/^position=<([ -][0-9]+), ([ -][0-9]+)> velocity=<([ -][0-9]+), ([ -][0-9]+)>/m", $str, $matches, PREG_SET_ORDER);

class point
{
	public $x;
	public $y;
	public $vx;
	public $vy;
	public function step()
	{
		$this->x += $this->vx;
		$this->y += $this->vy;
	}
}

$arrPoints = [];

foreach($matches as $match)
{
	$pt = new point();
	$pt->x = intval($match[1]);
	$pt->y = intval($match[2]);
	$pt->vx = intval($match[3]);
	$pt->vy = intval($match[4]);
	$arrPoints[] = $pt;
}


print_r($arrPoints);

$ticks = 0;
while(true)
{
	$ticks++;
	$maxX = $maxY = -50000;
	$minX = $minY = 50000;
	foreach ($arrPoints as $point)
	{
		$point->step();
		$maxX = max($maxX, $point->x);
		$maxY = max($maxY, $point->y);
		$minX = min($minX, $point->x);
		$minY = min($minY, $point->y);
	}
	if((($maxX-$minX) < 1000) && (($maxY-$minY) < 100))
	{
		echo "\n\nAt t=$ticks:\n\n";
		for($y=$minY;$y<=$maxY;$y++)
		{
			for($x=$minX;$x<=$maxX;$x++)
			{
				$found = false;
				foreach ($arrPoints as $point)
				{
					if ($x == $point->x && $y == $point->y)
					{
						$found = true;
						echo "#";
						break;
					}
				}
				if(!$found)
				{
					echo ".";
				}
			}
			echo "\n";
		}
		echo "\n\n";
	}

	if($ticks > 20000)
	{
		break;
	}
}
