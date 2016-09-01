<?php

define('MAX_LEVEL',4);

function tagTree($t, $level, $half) {

	echo "<ul>";
	
	for ($i = $half * 2; $i < $half * 2 + 2; $i++) {
	
		echo "<li><p>" . $t[pow(2, $level) + $i] . "</p>";
	
		if ($level < MAX_LEVEL) {
		
			tagTree($t, $level + 1, $i);
			
		}	
		
		echo "</li>";
	
	}
	
	echo "</ul>";


}

?>

<div id="c-tags">
	<ul class="c-tags-right">
		<li>
			<p><?php echo $t[2]; ?></p>
			<ul>
				<li>
					<p><?php echo $t[9]; ?></p>
				</li>
				<li>
					<p><?php echo $t[10]; ?></p>
				</li>
			</ul>
		</li>
		<li>
			<p><?php echo $t[3]; ?></p>
			<ul>
				<li>
					<p><?php echo $t[11]; ?></p>
				</li>
				<li>						
					<p><?php echo $t[12]; ?></p>
				</li>
			</ul>
		</li>
		<li>
			<p><?php echo $t[4]; ?></p>
			<ul>
				<li>
					<p><?php echo $t[13]; ?></p>
				</li>
				<li>						
					<p><?php echo $t[14]; ?></p>
				</li>
			</ul>
		</li>
	</ul>
	
	<ul class="c-tags-left">
		<li>
			<p><?php echo $t[0]; ?></p>
			<ul>
				<li>
					<p><?php echo $t[5]; ?></p>
				</li>
				<li>
					<p><?php echo $t[6]; ?></p>
				</li>
			</ul>
		</li>
		<li>
			<p><?php echo $t[1]; ?></p>
			<ul>
				<li>
					<p><?php echo $t[7]; ?></p>
				</li>
				<li>
					<p><?php echo $t[8]; ?></p>
				</li>
			</ul>
		</li>
	</ul>
</div>
	
<?php

}
	
?>