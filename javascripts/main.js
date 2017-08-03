var AC = {};

jQuery(function() {

	var eFilmstrip = jQuery('#c-filmstrip'),
		eTags = jQuery('#c-tags'),
		ePlayer = jQuery('#c-player'),
		eInfo = jQuery('#c-info'),
		eDirectors = jQuery('#c-directors-menu'),
		eTitles = jQuery('#c-titles-menu'),
		eTagsMenu = jQuery('#c-tags-menu'),
		d, t,
		isTaken = function(x,y,w,h) {
			for (a = x; a < x + w; a++) {
		    	for (b = y; b < y + h; b++) {
			    	if (c[b * cloudWidth + a]) {
				    	return true;
			    	}
				}
			}
			return false;
		};
	
	eFilmstrip.jScrollPane();	
	d = eFilmstrip.data('jsp');
	
	jQuery(window).bind('resize', function() {
		if (!t) {
			t = setTimeout(function() {
				d.reinitialise();
				t = null;			
			}, 50);		
		}
	});
	
	eTags.find('h1,h2,h3,h4,h5,h6').each(function() {
		var e =  jQuery(this);
	    e.click(function() { AC.gotoTag(e.text()); });
    });
    
    AC.playFilm = function(e,u) {
	   
	   jQuery('.c-film').removeClass('playing');
	   jQuery(e).addClass('playing');

		jQuery('#f-tags').remove();
		var filmTags = jQuery(e).find('.f-tags').clone();
		filmTags.attr('id', 'f-tags').show();
		filmTags.find('li').each(function() {
			var e =  jQuery(this);
	    	e.click(function() { AC.gotoTag(e.text()); });
    	});
		jQuery('#c-tags').after(filmTags);

		jQuery('#f-description').remove();
		var filmDescription = jQuery(e).find('.f-description');
		filmDescription = jQuery("<p></p>").text(filmDescription.text());
		filmDescription.attr('id', 'f-description').show();
		jQuery('#c-metadata').append(filmDescription);

	    ePlayer.show().html(
	    	'<div id="c-close"><svg viewBox="0 0 8 8"><path d="M4 0c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm-1.5 1.78l1.5 1.5 1.5-1.5.72.72-1.5 1.5 1.5 1.5-.72.72-1.5-1.5-1.5 1.5-.72-.72 1.5-1.5-1.5-1.5.72-.72z" /></svg></div><video controls="controls"><source src="/files/original/' + u + '" type="video/mp4" />' +
	    '<object width="320" height="240" type="application/x-shockwave-flash" data="flashmediaelement.swf">' +
        '<param name="movie" value="flashmediaelement.swf" />' +
        '<param name="flashvars" value="controls=true&file=/files/original/' + u + '" /></object></video>');
	    ePlayer.find('video').mediaelementplayer({success: function(mediaElement, originalNode) {
    		mediaElement.play();
		}});
		ePlayer.find('svg').click(function() {
			ePlayer.hide().html('');
			jQuery(e).removeClass('playing');
			jQuery('#f-tags').remove();
			jQuery('#f-description').remove();
			eTags.show();
		});
	    eTags.hide();
    };
    
    AC.gotoFilm = function(i) {	    
	    window.location = '/items/browse?collection=' + i;	    
    };
    
    eDirectors.change(function() {
    	window.location = '/items/browse?search=&advanced%5B0%5D%5Belement_id%5D=39&advanced%5B0%5D%5Btype%5D=contains&advanced%5B0%5D%5Bterms%5D=' + eDirectors.find('option:selected').val() + '&submit_search=Search';
    });
    
    eTitles.change(function() {	    
	    window.location = '/items/browse?collection=' + eTitles.find('option:selected').val();
    });
    
    eTagsMenu.change(function() {	    
	    window.location = '/items/browse?tags=' + eTagsMenu.find('option:selected').val();
    });
    
    AC.gotoTag = function(t) {
	    window.location = '/items/browse?tags=' + t;	  
	    
	    
    };
    


	
});
