
jQuery(document).ready(function() {
	
    /*
        Fullscreen background
    */
    $.backstretch("assets/img/backgrounds/1.jpg");
    
    $('#top-navbar-1').on('shown.bs.collapse', function(){
    	$.backstretch("resize");
    });
    $('#top-navbar-1').on('hidden.bs.collapse', function(){
    	$.backstretch("resize");
    });
    
    /*
        Form
    */
    $('.registration-form fieldset:first-child').fadeIn('slow');
    
    $('.registration-form input[type="text"], .registration-form input[type="password"], .registration-form textarea').on('focus', function() {
    	$(this).removeClass('input-error');
    });
    
    // next step
    $('.registration-form .btn-next').on('click', function() {
    	var parent_fieldset = $(this).parents('fieldset');
    	var next_step = true;
    	var feildList = 'input[name=first_name], input[name=gender], input[name=contact_phone], input[name=registered_email], input[name=father_first_name], input[name=current_address_line1], input[name=current_address_pincode], input[name=admission_dt], input[name=student_status]';
    	//alert(feildList);
    	parent_fieldset.find(feildList).each(function() {
    		if( $(this).val() == "" ) {
    			$(this).addClass('input-error');
    			next_step = false;
    		}
    		else {
    			$(this).removeClass('input-error');
    		}
    	});
    	
    	if( next_step ) {
    		parent_fieldset.fadeOut(400, function() {
	    		$(this).next().fadeIn();
	    	});
    	}
    	
    });
    
    // previous step
    $('.registration-form .btn-previous').on('click', function() {
    	$(this).parents('fieldset').fadeOut(400, function() {
    		$(this).prev().fadeIn();
    	});
    });
    
    // submit
    $('.registration-form').on('submit', function(e) {
    	
    	$(this).find('input[type="text"], input[type="password"], textarea').each(function() {
    		if( $(this).val() == "" ) {
    			//e.preventDefault();
    			//$(this).addClass('input-error');
    		}
    		else {
    			$(this).removeClass('input-error');
    		}
    	});
    	
    });
    
    
    /*
    	Form
     */
    
 /*   
		$('.registration-form fieldset:first-child').fadeIn('slow');
		
		$('.registration-form input[type="text"], .registration-form input[type="password"], .registration-form textarea').on('focus', function() {
			$(this).removeClass('input-error');
		});
		
		// next step
		$('.registration-form .btn-next').on('click', function() {
			var parent_fieldset = $(this).parents('fieldset');
			var next_step = true;
			parent_fieldset.find('input[type="text"], input[type="password"], input[type="radio"], textarea').each(function() {
				if( $(this).val() == "" ) {
					//$(this).addClass('input-error');
					//next_step = false;
				}
				else {
					$(this).removeClass('input-error');
				}
			});
			
			if( next_step ) {
				parent_fieldset.fadeOut(400, function() {
		    		$(this).next().fadeIn();
		    	});
			}
			
		});
		
		// previous step
		$('.registration-form .btn-previous').on('click', function() {
			$(this).parents('fieldset').fadeOut(400, function() {
				$(this).prev().fadeIn();
			});
		});
		
		// submit
		$('.registration-form').on('submit', function(e) {
			
			$(this).find('input[type="text"], input[type="password"], textarea').each(function() {
				if( $(this).val() == "" ) {
					e.preventDefault();
					//$(this).addClass('input-error');
				}
				else {
					$(this).removeClass('input-error');
				}
			});
			
		});
*/		
		
    
});
