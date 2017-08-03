<?php echo head(); ?>

<div id="c-filmstrip">
<table cellpadding="0" cellspacing="0">
<tbody>
<tr>
<?php

$tagCounts = array();

$films = get_records('Collection', array('public' => true), 0);
foreach ($films as $film) {

	if (is_object($film)) {

		$items = get_records('Item', array('collection' => $film->id, 'featured' => true, 'public' => true), 1);
		$allItems = get_records('Item', array('collection' => $film->id, 'public' => true));
	
		if (count($items) === 0 && $allItems) {
			$items = array($allItems[0]);
		}
		// Item is case sensitive here because ... ?
	
		if ($items && is_object($items[0])) {
			if ($files = $items[0]->Files) {		
				echo '<td><div class="c-film" onclick="AC.gotoFilm(' . $film->id . ');"';
				if ($imgTag = item_image('fullsize',array(),0,$items[0])) {
					preg_match('/src="([^"]+)"/',$imgTag,$m);				
					echo ' style="background-image: url(\'' . $m[1] . '\');"';
				} 
	
				echo '><div>';
				if($filmTitle = metadata($film, array('Dublin Core', 'Title'))) {
					echo '<h1>' . $filmTitle . '</h1>';
				}	
				if($filmDescription = metadata($film, array('Dublin Core', 'Description'), array('snippet'=>150))) {
					echo '<div>' . $filmDescription . '</div>';
				}
				echo "\n</div></div></td>\n";
			}			
			
			foreach ($allItems as $item) {
				tallyTags($item, $tagCounts);	
			}			
		}
	}
}

?>	 

</tr>
</tbody>
</table>
</div>

<div id="c-info">

<div id="c-metadata">
<h1><?php echo link_to_home_page(theme_logo()); ?></h1>
<?php 
if (get_theme_option('Homepage Text')) {
	echo '<p>' . get_theme_option('Homepage Text') . "</p>\n";
} 
?>
</div>

<div id="c-tags">
<?php tagCloud($tagCounts); ?>
</div>

</div>

<?php echo foot(); ?>
