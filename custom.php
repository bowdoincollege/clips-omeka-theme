<?php

function tallyTags($item, &$tagCounts) {
	if (metadata($item, 'has tags')) {
		$itemTags = $item->getTags();
		foreach ($itemTags as $tag) {
			if (!isset($tagCounts[$tag->name])) {
				$tagCounts[$tag->name] = 1;
			} else {
				$tagCounts[$tag->name] += 1;
			}
		}
	}
}

function tagCloud($tagCounts) {

	arsort($tagCounts);
	$t = array_pad(array_keys(array_slice($tagCounts, 0, 16, true)), 16, '');

?>

<div id="c-tags">
	<ul class="c-tags-right">
		<li>
			<h3><?php echo $t[2]; ?></h3>
			<ul>
				<li>
					<h6><?php echo $t[9]; ?></h6>
				</li>
				<li>
					<h6><?php echo $t[10]; ?></h6>
				</li>
			</ul>
		</li>
		<li>
			<h3><?php echo $t[3]; ?></h3>
			<ul>
				<li>
					<h6><?php echo $t[11]; ?></h6>
				</li>
				<li>						
					<h6><?php echo $t[12]; ?></h6>
				</li>
			</ul>
		</li>
		<li>
			<h3><?php echo $t[4]; ?></h3>
			<ul>
				<li>
					<h6><?php echo $t[13]; ?></h6>
				</li>
				<li>						
					<h6><?php echo $t[14]; ?></h6>
				</li>
			</ul>
		</li>
	</ul>
	
	<ul class="c-tags-left">
		<li>
			<h1><?php echo $t[0]; ?></h1>
			<ul>
				<li>
					<h4><?php echo $t[5]; ?></h4>
				</li>
				<li>
					<h4><?php echo $t[6]; ?></h4>
				</li>
			</ul>
		</li>
		<li>
			<h2><?php echo $t[1]; ?></h2>
			<ul>
				<li>
					<h4><?php echo $t[7]; ?></h4>
				</li>
				<li>
					<h4><?php echo $t[8]; ?></h4>
				</li>
			</ul>
		</li>
	</ul>
</div>
	
<?php

}
	
?>