<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" /> 
<meta charset="utf-8">

<?php 
if ($description = option('description')) {
	echo "<meta name=\"description\" content=\"$description\" />\n";
}         
if (isset($title)) {
    $titleParts[] = strip_formatting($title);
}
$titleParts[] = option('site_title');
echo '<title>' . implode(' &middot; ', $titleParts) . "</title>\n";

fire_plugin_hook('public_head',array('view'=>$this));

queue_css_file('normalize');
queue_css_file('jquery.jscrollpane');
queue_css_file('style');
echo head_css(); 

queue_js_file('vendor/modernizr');
queue_js_file('vendor/jquery.jscrollpane.min');
queue_js_file('vendor/jquery.mousewheel.min');
queue_js_file('main');
echo head_js();

$filmTitles = array();
$filmDirectors = array();
$filmGenres = array();

$tagCountsByFilm = array();
$allItemsByFilm = array();

$films = get_records('Collection');
foreach($films as $film) {
	if (is_object($film)) {
		if ($featuredItems = get_records('Item', array('collection' => $film->id, 'featured' => true), 1)) {
			if($filmTitle = metadata($film, array('Dublin Core', 'Title'), array('snippet' => 32))) {
				$filmTitles[$film->id] = $filmTitle;
			}
			if($filmDirector = metadata($film, array('Dublin Core', 'Creator'), array('snippet' => 32))) {
				$filmDirectors[] = $filmDirector;
			}	
			if($filmGenre = metadata($film, array('Dublin Core', 'Subject'), array('all' => true, 'snippet' => 32))) {
				$filmGenres = array_merge($filmGenres, $filmGenre);
			}
			
			$allItemsByFilm[$film->id] = get_records('Item', array('collection' => $film->id));
			
			foreach ($allItemsByFilm[$film->id] as $item) {
				tallyTags($item, $tagCountsByFilm);	
			}	
			
		}
	}
}


?>
    
</head>
<?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>

<div id="c-title">
<a href="/">Bowdoin <em>Digital</em> <strong>Clip</strong> <span>Archive</span></a>
<div id="c-exhibits-menu"><a href="/exhibits">Exhibits</a> <span>&#8594;</span></div>
</div>

<div id="c-menus">

<select class="c-menu" id="c-directors-menu">
<option disabled="disabled" selected="selected">Directors</option><option>
<?php echo implode("</option>\n<option>", $filmDirectors); ?>
</option></select>

<select class="c-menu" id="c-titles-menu">
<option disabled="disabled" selected="selected">Titles</option>
<?php 
foreach ($filmTitles as $filmId => $filmTitle) {
	echo "<option value=\"$filmId\">$filmTitle</option>\n";	
}
?>
</select>

<!--
<select class="c-menu" id="c-genres-menu">
<option disabled="disabled" selected="selected">Genres</option><option>
<?php echo implode("</option>\n<option>", $filmGenres); ?>
</option></select>
-->

<select class="c-menu" id="c-tags-menu">
<option disabled="disabled" selected="selected">Tags</option>
<?php
$tagList = array_keys($tagCountsByFilm);
sort($tagList);
foreach ($tagList as $t) {
	echo "<option value=\"$t\">$t</option>\n";	
}
?>
</select>

</div>
