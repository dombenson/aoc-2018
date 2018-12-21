<?php

$sn = 3999;

$max = 300;

$grid = [];

$grid10x = [];

class fuelcell
{
	public $x;
	public $y;
	private function rackId()
	{
		return $this->x + 10;
	}
	public function power()
	{
		global $sn;
		$base = $this->y * $this->rackId();
		$base += $sn;
		$pwr = $base * $this->rackId();
		$hnd = $pwr % 1000;
		$res = floor($hnd/100);
		return $res-5;
	}
}

for($i=0;$i<$max;$i++)
{
	for($j=0;$j<$max;$j++)
	{
		$f = new fuelcell();
		$f->x = $i;
		$f->y = $j;
		$grid[$i][$j] = $f->power();
	}
}

for($i=0;$i<=$max-10;$i++)
{
	for($j=0;$j<=$max-10;$j++)
	{
		$pwr = 0;
		for($ox=0;$ox<10;$ox++)
		{
			for($oy = 0;$oy<10;$oy++)
			{
				$pwr += $grid[$i+$ox][$j+$oy];
			}
		}
		$grid10x[$i][$j] = $pwr;
	}
}


$bestX = $bestY = $bestPwr = $bestSz = null;

for($sz=1;$sz<=$max;$sz++)
{
	echo "Checking size $sz\n";
	$thisMax = $max-$sz;
	for ($i = 0; $i <= $thisMax; $i++)
	{
		for ($j = 0; $j <= $thisMax; $j++)
		{
			$pwr = 0;
			if($sz >= 10)
			{
				$offs = $sz%10;
				$offBase = $sz-$offs;
				for ($ox = $offBase; $ox < $sz; $ox++)
				{
					for ($oy = $offBase; $oy < $sz; $oy++)
					{
						$pwr += $grid[$i + $ox][$j + $oy];
					}
				}
				for ($ox = 0; $ox < $offBase; $ox++)
				{
					for ($oy = $offBase; $oy < $sz; $oy++)
					{
						$pwr += $grid[$i + $ox][$j + $oy];
					}
				}
				for ($ox = $offBase; $ox < $sz; $ox++)
				{
					for ($oy = 0; $oy < $offBase; $oy++)
					{
						$pwr += $grid[$i + $ox][$j + $oy];
					}
				}
				for ($ox = 0; $ox < $offBase; $ox+=10)
				{
					for ($oy = 0; $oy < $offBase; $oy+=10)
					{
						$pwr += $grid10x[$i + $ox][$j + $oy];
					}
				}
			}
			else
			{
				for ($ox = 0; $ox < $sz; $ox++)
				{
					for ($oy = 0; $oy < $sz; $oy++)
					{
						$pwr += $grid[$i + $ox][$j + $oy];
					}
				}
			}
			if (!isset($bestPwr) || ($pwr > $bestPwr))
			{
				$bestPwr = $pwr;
				$bestX = $i;
				$bestY = $j;
				$bestSz = $sz;
			}
		}
	}
}

print_r([$bestX, $bestY, $bestSz, $bestPwr]);
