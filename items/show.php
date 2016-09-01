<?php echo head(array('title' => metadata('item', array('Dublin Core', 'Title')), 'bodyid' => 'item', 'bodyclass' => 'show')); ?> <!-- 'bodyclass' => 'items show' -->

<div id="primary">
    <?php /* echo custom_show_item_metadata_with_search('Title'); */ ?>
    <div class="element"><h3>Download</h3><div class="element-text">
    <?php
	$files = $item->getFiles();
	$file = $files[0];
	
	$fullsizeFilename = preg_replace('/\.jp2$/','.jpg',$file['filename']);
	$originalFilename = preg_replace('/\.jp2$/','.jpg',$file['original_filename']);
		
    echo '<a href="/items/tags?a=' . $fullsizeFilename . '&o=' . $originalFilename . '">' . $originalFilename . '</a></div></div>';

	$ssMetadata = custom_metadata();

	foreach ($ssMetadata as $ssKey => $ssValues) {
		$ssClass = 'ss-' . strtolower(str_replace(' ','-',$ssKey));	
		echo '<div class="element ' . $ssClass . '"><h3>'. $ssKey .'</h3><p>' . implode('</p><p>',$ssValues) . '</p></div>';	
	}
    
    ?>

    <?php /* echo plugin_append_to_items_show(); */ ?>


</div><!-- end primary -->
<div id="page-nav">
    <ul class="item-pagination navigation">
        <li id="previous-item" class="previous"><?php echo link_to_previous_item_show('&larr;Previous Image'); ?></li>
        <li id="next-item" class="next"><?php echo link_to_next_item_show('Next Image&rarr;'); ?></li>
    </ul>
</div>

<div id="secondary">

<?php

$hasZoomableView = false;

// Get the SSID from the filename

preg_match('/^\d+/',$file['original_filename'],$ssidMatch);

// Search Shared Shelf Commons to retrieve the objectID (not the same as the SSID!)

$sscSearchJson = shell_exec('curl "http://www.sscommons.org/openlibrary/secure/search/6/1/72/0?kw=' . $ssidMatch[0] . '&id=7729815&tn=1"');
$sscSearchInfo = json_decode($sscSearchJson, true);

if (isset($sscSearchInfo['thumbnails']) && count($sscSearchInfo['thumbnails']) > 0) {

	$sscObjectId = $sscSearchInfo['thumbnails'][0]['objectId'];
	
	// Use the ObjectID to retrieve the proper imageUrl, including token
	
	$sscImageJson = shell_exec('curl "http://www.sscommons.org/openlibrary/secure/imagefpx/' . $sscObjectId . '/7729815/5"');
	$sscImageInfo = json_decode($sscImageJson, true);
	
	if (count($sscImageInfo) > 0 && isset($sscImageInfo[0]['imageUrl'])) {
	
		$sscImageServer = $sscImageInfo[0]['imageServer'];
		$sscImageWidth = $sscImageInfo[0]['width'];
		$sscImageHeight = $sscImageInfo[0]['height'];
		$sscImageUrl = $sscImageInfo[0]['imageUrl'];
		
		$hasZoomableView = true;
	}
	
}

// If there is data, show the ARTstor zoomable viewer,
// and use the Omeka image as the fallback

if ($hasZoomableView) {

$url = 'http://viewer2.artstor.org/erez3/fsi4/fsi.swf?&amp;FPXBase=' . $sscImageServer . '&amp;';
$urlParams = 'FPXSrc=' . $sscImageUrl . '&amp;FPXWidth=' . $sscImageWidth . '&amp;FPXHeight=' . $sscImageHeight;

	?>	

<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="100%" height="100%" id="fsiviewer" align="middle" wmode="opaque">
    <param name="movie" value="<?php echo $url . $urlParams; ?>&amp;custombutton_buttons=&amp;NoNav=false"/>
    <param name="wmode" value="opaque">
    <param name="allowScriptAccess" value="always">
    <param name="swliveconnect" value="true">
    <param name="menu" value="false">
    <param name="quality" value="high">
    <param name="scale" value="noscale"><param name="salign" value="LT">
    <param name="bgcolor" value="#000000">   
    <!--[if !IE]>-->
<object type="application/x-shockwave-flash" data="<?php echo $url . $urlParams; ?>&amp;custombutton_buttons=&amp;NoNav=false" width="100%" height="100%">
    <param name="movie" value="<?php echo $url . $urlParams; ?>&amp;custombutton_buttons=&amp;NoNav=false"/>
    <param name="allowScriptAccess" value="always">
    <param name="swliveconnect" value="true">
    <param name="menu" value="false">
    <param name="quality" value="high">
    <param name="scale" value="noscale"><param name="salign" value="LT">
    <param name="bgcolor" value="#000000">   
    <!--<![endif]-->

<?php 	       
} 

// If there is no data, just show the Omeka image
echo item_image('fullsize',array(),0,false);

if ($hasZoomableView) { 
?>

    <!--[if !IE]>-->
    </object>
    <!--<![endif]-->
</object>

<?php } ?>


      





</div>

<?php echo foot(); ?>
