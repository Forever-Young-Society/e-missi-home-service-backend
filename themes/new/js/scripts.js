// common scripts
base_url="http://dev.toxsl.in/e-missi-home-service-yii2-1843/";
(function() {
	"use strict";

	// Sidebar toggle

	jQuery('.menu-list > a').click(function() {

		var parent = jQuery(this).parent();
		var sub = parent.find('> ul');

		if (!jQuery('body').hasClass('sidebar-collapsed')) {
			if (sub.is(':visible')) {
				sub.slideUp(300, function() {
					parent.removeClass('nav-active');
					jQuery('.body-content').css({
						height : ''
					});
					adjustMainContentHeight();
				});
			} else {
				visibleSubMenuClose();
				parent.addClass('nav-active');
				sub.slideDown(300, function() {
					adjustMainContentHeight();
				});
			}
		}
		return false;
	});

	function visibleSubMenuClose() {

		jQuery('.menu-list').each(function() {
			var t = jQuery(this);
			if (t.hasClass('nav-active')) {
				t.find('> ul').slideUp(300, function() {
					t.removeClass('nav-active');
				});
			}
		});
	}

	function adjustMainContentHeight() {

		// Adjust main content height
		var docHeight = jQuery(document).height();
		if (docHeight > jQuery('.body-content').height())
			jQuery('.body-content').height(docHeight);
	}

	// add class mouse hover

	jQuery('.side-navigation > li').hover(function() {
		jQuery(this).addClass('nav-hover');
	}, function() {
		jQuery(this).removeClass('nav-hover');
	});

	function getCookie(cname) {
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for (var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	}

	// Toggle Menu

	jQuery('.toggle-btn').click(function() {

		var body = jQuery('body');

		var bodyposition = body.css('position');

		if (bodyposition != 'relative') {

			if (!body.hasClass('sidebar-collapsed')) {
				body.addClass('sidebar-collapsed');
				jQuery('.side-navigation ul').attr('style', '');

				$.ajax({
					url : base_url + "user/theme-param",
				})

			} else {
				body.removeClass('sidebar-collapsed chat-view');
				jQuery('.side-navigation li.active ul').css({
					display : 'block'
				});

				$.ajax({
					url : base_url + "user/theme-param",
				})

			}

		} else {

			if (body.hasClass('sidebar-open'))
				body.removeClass('sidebar-open');
			else
				body.addClass('sidebar-open');

			adjustMainContentHeight();
		}

	});

	searchform_reposition();

	jQuery(window).resize(function() {

		if (jQuery('body').css('position') == 'relative') {

			jQuery('body').removeClass('sidebar-collapsed');

		} else {

			jQuery('body').css({
				left : '',
				marginRight : ''
			});
		}

		searchform_reposition();

	});

	function searchform_reposition() {
		if (jQuery('.search-content').css('position') == 'relative') {
			jQuery('.search-content').insertBefore(
					'.sidebar-left-info .search-field');
		} else {
			jQuery('.search-content').insertAfter('.right-notification');
		}
	}

})(jQuery);
