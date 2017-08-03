<?php echo head(); ?>

<?php
$films = array();
$tagCounts = array();
$clips = array();
$starts = array();
$searchTimecode = '/^\d?(\d:\d\d(:\d\d)?)/';

foreach (loop('items') as $item) {

	if ($files = $item->getFiles()) {	
	
		$film = get_collection_for_item();
		if ($film == null) {
			continue;
		}
		if (metadata('item', 'public') == false) {
			continue;
		}
		if (!isset($films[$film->id])) {
			$films[$film->id] = $film;
		}
	
	
		$clips[$item->id] = '<td><div class="c-film" onclick="AC.playFilm(this,\'' . $files[0]['filename'] . '\');"';
		if ($imgTag = item_image('fullsize',array(),0,$item)) {
			preg_match('/src="([^"]+)"/',$imgTag,$m);				
			$clips[$item->id] .= ' style="background-image: url(\'' . $m[1] . '\');"';
		}
		
		if (($clipDate = metadata('item', array('Dublin Core', 'Date'))) && preg_match($searchTimecode, $clipDate, $m)) {
			$clipDate = $m[1];
			$start = "<h2>$clipDate</h2>\n";
		} else if ($duration = metadata('item', array('Item Type Metadata', 'Duration'))) {
			preg_match($searchTimecode, $duration, $m);
			$clipDate = $m[1];
			$start = "<h2>$clipDate</h2>\n";			
		} else {
			$start = '';
			$starts[$item->id] = 0;
		}
		
		if ($start) {
			$c = explode(':', $clipDate);
			if (count($c) === 3) {
				$starts[$item->id] = $c[0] * 3600 + $c[1] * 60 + $c[2];
			} else {
				$starts[$item->id] = $c[0] * 3600 + $c[1] * 60;
			}			
		}
		
		$clips[$item->id] .= ">\n";
		
		if($filmTitle = metadata($film, array('Dublin Core', 'Title'))) {
			$clips[$item->id] .=  '<h3 class="f-title">' . $filmTitle . "</h3>\n";
		}	
	
		$clips[$item->id] .= "$start<div><h1>" . metadata('item', array('Dublin Core', 'Title')) . "</h1>\n";
		if ($description = metadata('item', array('Dublin Core', 'Description'), array('snippet'=>150))) {
			$clips[$item->id] .= '<div class="f-description">' . $description . "</div>\n";
		}

		$clips[$item->id] .= '<div class="f-tags"><ul>';
		$itemTags = $item->getTags();
		foreach($itemTags as $tag) {
			$clips[$item->id] .= '<li>' . $tag . '</li>';
		}
		$clips[$item->id] .= '</ul></div>';
		$clips[$item->id] .= "\n</div></div></td>\n";
	}
	
	tallyTags($item, $tagCounts);
}

$filmstripClass = (count($films) === 1) ? 'c-single-film' : 'c-multiple-films';

?>

<div class="<?php echo $filmstripClass; ?>" id="c-filmstrip">
<table cellpadding="0" cellspacing="0">
<tbody>
<tr>

<?php

asort($starts);
foreach($starts as $k => $s) {
	echo $clips[$k];
}

?>	 

</tr>
</tbody>
</table>
</div>

<div id="c-player">

</div>

<div id="c-info">
<div id="c-metadata">

<?php

if (isset($_REQUEST['tags'])) {

	echo '<h1>Tag: ' . $_REQUEST['tags'] . "</h1>\n";
	foreach ($films as $film) {
	
		if($filmTitle = metadata($film, array('Dublin Core', 'Title'))) {
			echo '<h4><a href="/items/browse?collection=' . $film->id . '">' . $filmTitle . "</a></h4>\n";
		}
		
		
	}

} else if (isset($_REQUEST['search'])) {

	echo '<h1>Director: ' . urldecode($_REQUEST['advanced'][0]['terms']) . "</h1>\n";
	foreach ($films as $film) {
	
		if($filmTitle = metadata($film, array('Dublin Core', 'Title'))) {
			echo '<h4><a href="/items/browse?collection=' . $film->id . '">' . $filmTitle . "</a></h4>\n";
		}
		
		
	}	
	
} else {
	$film = array_pop($films);
	
	if($filmTitle = metadata($film, array('Dublin Core', 'Title'))) {
		echo '<h1>' . $filmTitle . "</h1>\n";
	}
	
	if($filmYear = metadata($film, array('Dublin Core', 'Date'))) {	
		echo "<h4>Year: <span>$filmYear</span></h4>\n";	
	}
	
	if($filmDirector = metadata($film, array('Dublin Core', 'Creator'))) {
		echo "<h4>Director: <span>$filmDirector</span></h4>\n";
	}
	
	if($filmGenre = metadata($film, array('Dublin Core', 'Subject'), array('all' => true))) {
		$filmGenres = implode(' &sdot; ', $filmGenre);
		echo "<h4>Genre: <span>$filmGenres</span></h4>\n";
	}
	
	if($filmStudio = metadata($film, array('Dublin Core', 'Publisher'))) {
		echo "<h4>Studio: <span>$filmStudio</span></h4>\n";
	}
	
	if($filmContributor = metadata($film, array('Dublin Core', 'Contributor'))) {
		echo "<h4>Contributed by: <span>$filmContributor</span></h4>\n";
	}

	if($filmDescription = metadata($film, array('Dublin Core', 'Description'))) {
		echo '<p>' . $filmDescription . '</p>';
	}	

	
}
?>
</div>

<div id="c-tags">
<?php tagCloud($tagCounts); ?>
</div>

</div>

<?php echo foot(); ?>
