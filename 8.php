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
}

$metaSum = 0;

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

print_r($metaSum);