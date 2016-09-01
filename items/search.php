<?php
$pageTitle = __('Advanced Search');
echo head(array('title' => $pageTitle,
           'bodyclass' => 'items advanced-search'));
           
// 'bodyid' => 'advanced-search-page'
?>

<h1><?php echo $pageTitle; ?></h1>

<!-- new
<nav class="items-nav navigation secondary-nav">
    <?php /* echo public_nav_items(); */ ?>
</nav>
-->

<?php
if (!empty($formActionUri)):
    $formAttributes['action'] = $formActionUri;
else:
    $formAttributes['action'] = url(array('controller'=>'items',
                                          'action'=>'browse'));
endif;
$formAttributes['method'] = 'GET';

$mapDCtoSS = getMapDCtoSS();
$dcSearch = getDCSearch();

?>


<form <?php echo tag_attributes($formAttributes); ?>>
    <div id="search-keywords" class="field">
        <?php echo $this->formLabel('keyword-search', __('Search for Keywords')); ?>
        <div class="inputs">
         <?php
            echo $this->formText(
                'search',
                @$_REQUEST['search'],
                array('id' => 'keyword-search', 'size' => '40') // 'class' => 'textinput' ?
            );
            
            /* old
            echo text(array(
                    'name' => 'search',
                    'size' => '40',
                    'id' => 'keyword-search',
                    'class' => 'textinput'), @$_REQUEST['search']);
                    */
            
            
        ?>
        </div>
    </div>
    <div id="search-narrow-by-fields" class="field">
        <div class="label"><?php echo __('Narrow by Specific Fields'); ?></div>
        <div class="inputs">
        <?php
        // If the form has been submitted, retain the number of search
        // fields used and rebuild the form
        if (!empty($_GET['advanced'])) {
            $search = $_GET['advanced'];
        } else {
            $search = array(array('field'=>'','type'=>'','value'=>''));
        }
        //Here is where we actually build the search form
        foreach ($search as $i => $rows): ?>
            <div class="search-entry">
                <?php
                //The POST looks like =>
                // advanced[0] =>
                //[field] = 'description'
                //[type] = 'contains'
                //[terms] = 'foobar'
                //etc
                
                echo "<select name=\"advanced[$i][element_id]\" id=\"advanced-$i-element_id\">";
                foreach ($mapDCtoSS as $dcField => $ssFields) {
                	$dcID = $dcSearch[$dcField];
                	echo "<optgroup label=\"$dcField\">";
	                foreach ($ssFields as $ssField) {
		                echo "<option value=\"$dcID\" label=\"$ssField\">$ssField</option>\n";
	                }
	                echo '</optgroup>';
                }
                echo '</select>';
                
                echo '<!--' . $this->formSelect(
                    "advanced[$i][element_id]",
                    @$rows['element_id'],
                    array(),
                    get_table_options('Element', null, array(
                        'record_types' => array('Item', 'All'),
                        'sort' => 'alphaBySet')
                    )
                ) . '-->';
                
                echo $this->formSelect(
                    "advanced[$i][type]",
                    @$rows['type'],
                    array(),
                    label_table_options(array(
                        'contains' => __('contains'),
                        'does not contain' => __('does not contain'),
                        'is exactly' => __('is exactly'),
                        'is empty' => __('is empty'),
                        'is not empty' => __('is not empty'))
                    )
                );
                
                echo '<input type="text" class="ss-term" name="advanced[' . $i . '][terms]" id="advanced-' . $i . '-terms" value="" size="30">';
                
                
/*
                echo $this->formText(
                    "advanced[$i][terms]",
                    @$rows['terms'],
                    array('size' => '20')
                );
*/
                
                
                ?>
                <button type="button" class="remove_search" disabled="disabled" style="display: none;">-</button>
            </div>
        <?php endforeach; ?>
        </div>
        <button type="button" class="add_search"><?php echo __('Add a Field'); ?></button>
    </div>
    
    <?php fire_plugin_hook('public_items_search', array('view' => $this)); ?>
    <div>
        <input type="submit" class="submit" name="submit_search" id="submit_search_advanced" value="<?php echo __('Search'); ?>" />
    </div>
</form>

<?php echo js_tag('items-search'); ?>
<script type="text/javascript">
    jQuery(document).ready(function () {
    	
    	//// http://omeka.org/codex/Recipes/Removing_Fields_from_Advanced_Search
    	
    	// These are fields added by the Dubin Core Extended plugin
    	
		var blackListGroups = [
		    'Item Type Metadata'
		];
		var blackListElements = [
			'Abstract',
			'Access Rights',
			'Alternative Title',
			'Audience Education Level',
			'Bibliographic Citation',
			'Conforms To',
			'Date Accepted',
			'Date Available',
			'Date Copyrighted',
			'Date Created',
			'Date Issued',
			'Date Modified',
			'Date Submitted',
			'Date Valid',
			'Extent',
			'Has Format',
			'Has Part',
			'Has Version',
			'Is Format Of',
			'Is Part Of',
			'Is Referenced By',
			'Is Replaced By',
			'Is Required By',
			'Is Version Of',
			'License',
			'Mediator',
			'Medium',
			'References',
			'Replaces',
			'Requires',
			'Spatial Coverage',
			'Table Of Contents',
			'Temporal Coverage'
		];
		jQuery.each(blackListGroups, function (index, value) {
		    jQuery("#advanced-0-element_id optgroup[label='" + value + "']").remove();
		});
		jQuery.each(blackListElements, function (index, value) {
		    jQuery("#advanced-0-element_id option[label='" + value + "']").remove();
		});
/*		
		jQuery('#submit_search_advanced').click(function() {
		
			var f = jQuery(this).closest('form'),
				q = '/items/browse?search=' + encodeURIComponent(jQuery('#keyword-search').val()) + '&',
				terms = [];

			jQuery('.ss-term').each(function() {

				var e = jQuery(this),
					v = e.val(),
					i = e.attr('name').match(/advanced\[(\d+)\]\[terms\]/)[1],
					t = jQuery("select[name='advanced[" + i + "][type]']"),
					s = jQuery("select[name='advanced[" + i + "][element_id]']"),
					o = s.find('option:selected'),
					dcID = o.val(),
					term = o.html() + ': ' + v,
					a = 'advanced%5B' + i + '%5D';
					
				terms.push(a + '%5Belement_id%5D=' + dcID);
				terms.push(a + '%5Btype%5D=' + encodeURIComponent(t.val()));
				terms.push(a + '%5Bterms%5D=' + encodeURIComponent(term));
	
			});
			
			q += terms.join('&');
			
			window.location = q;
			
			//f.submit();
			
			
		});
*/		
		////
		
        Omeka.Search.activateSearchButtons();
    });
</script>

<?php echo foot(); ?>
