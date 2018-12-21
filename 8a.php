<?php

$data = trim(file_get_contents("8.txt"));

$allNumbers = preg_split("/[\\s]+/", $data);

class node
{
	public $numChildren;
	public $numMeta;
	/** @var node[] */
	public $childNodes;
	/** @var int[] */
	public $metadataEntries;

	public function score()
	{
		if($this->numChildren <= 0)
		{
			echo "Leaf node score ".array_sum($this->metadataEntries)."\n";
			return array_sum($this->metadataEntries);
		}
		$score = 0;
		foreach ($this->metadataEntries as $childId)
		{
			echo "Looking for $childId of {$this->numChildren}\n";
			if($childId <= $this->numChildren)
			{
				$offset = $childId - 1;
				echo "Traversing...\n";
				$score += $this->childNodes[$offset]->score();
				echo "Score now $score\n";
			}
			else
			{
				echo "Out of range\n";
			}
		}
		echo "Node score $score\n";
		return $score;
	}
}


function addChildren(node $parentNode)
{
	global $idx, $metaSum, $allNumbers;
	for($child = 0; $child<$parentNode->numChildren; $child++)
	{
		$childNode = new node();
		$childNode->numChildren = $allNumbers[$idx++];
		$childNode->numMeta = $allNumbers[$idx++];
		$parentNode->childNodes[] = $childNode;
		addChildren($childNode);

	}
	for($meta = 0; $meta < $parentNode->numMeta; $meta++)
	{
		$thisMeta = $allNumbers[$idx++];
		$parentNode->metadataEntries[] = $thisMeta;
		$metaSum += $thisMeta;
	}
}

$lastNum = count($allNumbers);
$idx = 0;

$rootNode = new node();
$rootNode->numChildren = $allNumbers[$idx++];
$rootNode->numMeta = $allNumbers[$idx++];


addChildren($rootNode);

print_r($rootNode->score());