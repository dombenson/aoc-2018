<?php

$players = 478;
$maxMarble = 71240;
$maxMarble = 7124000;

$curMarble = 0;
$marbles = [0];
$curPlayer = 0;

$scores = [];

class node
{
	public $next;
	public $prev;
	public $value;
}

class ring
{
	public $nodes;
	public $idx;

	public function step($steps)
	{
		for($i=0;$i<$steps;$i++)
		{
			$this->idx = $this->nodes[$this->idx]->next;
		}
	}

	public function back($steps)
	{
		for($i=0;$i<$steps;$i++)
		{
			$this->idx = $this->nodes[$this->idx]->prev;
		}
	}

	public function remove()
	{
		$toRemove = $this->nodes[$this->idx];
		$this->nodes[$toRemove->prev]->next = $toRemove->next;
		$this->nodes[$toRemove->next]->prev = $toRemove->prev;
		$this->idx = $toRemove->next;
		return $toRemove->value;
	}

	public function insert($value)
	{
		$oldNext = $this->nodes[$this->idx]->next;
		$this->nodes[$this->nodes[$this->idx]->next]->prev = $value;
		$this->nodes[$this->idx]->next=$value;
		$this->nodes[$value]->prev = $this->idx;
		$this->nodes[$value]->next = $oldNext;
		$this->idx = $value;
	}
}

$ring = new ring;
$ring->idx = 0;
$ring->nodes = [];
for($i=0;$i<=$maxMarble;$i++)
{
	$node = new node;
	$node->value = $i;
	$node->prev = $i;
	$node->next = $i;
	$ring->nodes[$i] = $node;
}

for($marble = 1; $marble <= $maxMarble; $marble++)
{
	$curPlayer++;
	if($curPlayer > $players)
	{
		$curPlayer = 1;
	}

	if(($marble % 23) == 0)
	{
		$ring->back(7);
		if(!array_key_exists($curPlayer, $scores))
		{
			$scores[$curPlayer] = 0;
		}
		$scores[$curPlayer] += $marble + $ring->remove();
		continue;
	}

	$ring->step(1);
	$ring->insert($marble);
}

arsort($scores);
$best = reset($scores);
echo "Best score: ".$best."\n";
