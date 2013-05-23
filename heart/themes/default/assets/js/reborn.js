/*!
 * Reborn JavaScript Object v1.0.0
 * http://www.Reborncms.com/
 *
 * Copyright 2012, Reborn CMS Development Team
 * License: Reborn CMS License
 *
 * Date: Monday August 06 19:14:21 2012
 */
/*!
 * Defined Reborn Object if not set.
 * This is fundemental functions for Reborn CMS
 */
if (typeof(Reborn) == 'undefined') {
	var Reborn = {};
}
jQuery(function($) {

	Reborn.init = function() {

		/* Alert Box Options */
		$('#message-area').animate({opacity: 1.0},4000).fadeOut('slow');

		$('#message-area > div > a.close').live('click', function(){
			$('#message-area').fadeOut('slow');
			return false;
		});

		$('a.close').live('click', function(e){
			e.preventDefault();
			$(this).parent().fadeOut('slow');
		});

		// Dashboard Tab Setting
		$('ul#quick-links li a').on('click', function(){
			var url = $(this).attr('href'),
				first = url.substring(0, 1);
			if(first == '#') {
				$('ul#quick-links li a').removeClass('quick-active');
				$(this).addClass('quick-active');
				$('#update-area div').hide();
				$(url).show();
				return false;
			}
			else {
				return true;
			}
		});

		/* Toggle Navigation for User Profile */
		$("li.dashboard-dropdown").click(function(){
			$("#dashboard-user-meta").toggleClass("open");
			$("li.dashboard-dropdown").toggleClass("active");

			var m = $(this).parent();
			$(m).addClass("afb-dropdown");
		}); // end of Toggle Navigation for User Profile

		// Toggle Navigation Close
		$(document).bind('click', function(e) {
			// Lets hide the menu when the page is clicked anywhere but the menu.
			var jQueryclicked = $(e.target);
			if (! jQueryclicked.parents().hasClass("afb-dropdown")){
				$("#dashboard-user-meta").removeClass("open");
				if($("li.dashboard-dropdown").hasClass("active")) {
					$("li.dashboard-dropdown").removeClass("active");
				}
			}
		}); // end of Toggle Navigation Close

		// Admin Menu has child function
		$('.am_has_child > a:not(.am_has_child ul li a)').live('click', function(){
			var child = $(this).parent().find('ul');
			$(child).slideToggle();
			return false;
		}); // end of Admin Menu hass child

		$('#dashboard-navigation > ul > li > ul > li > a').live('click', function(){
			window.location.href = $(this).attr('href');
		});

		// Delete Confim
		$('.confirm_delete').livequery('click', function(){
			return confirm("Are you sure you want to delete ?");
		});

	} // end of init

	Reborn.slugGenerator = function(inputField, outputField) {

		var inputVal, alphaNum, slug;

		$(inputField).live('keyup blur', function(){
			inputVal = $(inputField).val();

			if ( ! inputVal.length ) return;

			alphaNum = inputVal.toLowerCase().replace(/[^a-zA-Z 0-9-]/g,'');
			slug = alphaNum.split(" ").join("-");

			$(outputField).val(slug);
		});
	} // end of slugGenerator

	// Random Key Generation Function
	Reborn.keyGenerate = function(length) {
		var vowels = 'aeiouyAEIOUY',
			consonants = 'bcdfghjklmnpqrstvwxz1234567890BCDFGHJKLMNPQRSTVWXZ!@#$*^=',
			key = '';
			D = new Date(),
			alt = D.getMilliseconds() % 2;

		for (var i = 0; i < length; i++) {
			if (alt == 1) {
				key += consonants.charAt(Math.floor(Math.random() * consonants.length));
				alt = 0;
			} else {
				key += vowels.charAt(Math.floor(Math.random() * vowels.length));
				alt = 1;
			}
		}

		return key;
	} // end of keyGenerate

	// Table action buttons start out as disabled
	$(".buttons-wrapper .button").attr('disabled', 'disabled');

	// Check all checkboxes in container table or grid
	$(".check-all").live('click', function () {
		var checkAll		= $(this),
			allCheckbox		= $(this).is('.grid-check-all')
				? $(this).parents(".list-items").find(".grid input[type='checkbox']")
				: $(this).parents("table").find("tbody input[type='checkbox']");

		allCheckbox.each(function () {
			if (checkAll.is(":checked") && ! $(this).is(':checked'))
			{
				$(this).click();
			}
			else if ( ! checkAll.is(":checked") && $(this).is(':checked'))
			{
				$(this).click();
			}
		});

		// Check all?
		$(".button-wrapper .button").removeAttr('disabled');
	}); // end of Check all checkboxes

	// Enable/Disable table action buttons
	$('input[name="action_to[]"], .check-all').live('click', function () {

		if( $('input[name="action_to[]"]:checked, .check-all:checked').length >= 1 ){
			$(".button-wrapper .button").removeAttr('disabled');
		} else {
			$(".button-wrapper .button").attr('disabled', 'disabled');
		}
	});

	// For Tab
	$('.tabs').livequery(function() {
		$(this).tabs();
	});

	$('#tabs').livequery(function() {
		$(this).tabs();
	});

	// For Tooltip Tipsy
	$('.tipsy-tip').livequery(function() {
		$(this).tipsy({gravity: 's'});
	});

	// Start the initial function when document ready
	$(document).ready(function() {
		Reborn.init();
	});

}); // end of jQuery
