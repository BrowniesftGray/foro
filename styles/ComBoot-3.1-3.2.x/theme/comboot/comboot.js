autosize($('#message'));

$(document).on('pjax:send', function() {
	Pace.restart({
		elements: {
			selectors: ['#wrap']
		}
	});
	$('.tooltip').tooltip('hide');
});

$(document).on('pjax:end', function() {
	$('pre code:not(.hljs)').each(function(i, block) {
		hljs.highlightBlock(block);
	});
});

$(document).on('ready pjax:success', function() {
	var $primaryColor = $('.btn-primary').css('background-color'),
		$footerColor = $('.panel-footer').css('background-color');

	hljs.initHighlighting();

	// Color picker settings
	$('.colour-picker').colorpicker({
		format: 'hex',
		align: 'left'
	});

	// Truncate title on mobile devices
	$(window).on("resize", function() {
		var $buttonWidth = 0;
		$('.navbar-header button').each(function(){
			$buttonWidth = $buttonWidth + $(this).outerWidth();
		});
		var $screenWidth = $('body').width(),
			$navbarWidth = $('.navbar-header').width();
		if($screenWidth < 767) {
			$navbrandWidth = $navbarWidth - $buttonWidth - 2;
		} else {
			$navbrandWidth = 'auto';
		}
		$('.navbar-brand').css('width', $navbrandWidth);
		if ($('#header-nav').outerHeight() > 80) {
			$('#search-menu #search').hide();
		} else {
			$('#search-menu #search').show();
		}
	}).resize();

	// Hide other open menus, when new menu is opened
	$('.navbar-header button').on('click', function() {
		$('.navbar .navbar-collapse.in').collapse('hide');
	});

	// Fix abbc3 select picker issue
	$('#abbc3_buttons .abbc3_select').addClass('no-selectpicker');

	// Fix to long tabs in MCP and UCP
	$('#mcp-container .nav-justified li a, #ucp-container .nav-justified li a').each(function() {
		if ($(this).height() > 20) {
			$(this).parent().parent().removeClass('nav-justified');
		}
	});

	// Selectpicker settings
	$("select").not('.no-selectpicker').addClass("selectpicker");
	$(".selectpicker").attr({"data-width": "auto"});
	$(".selectpicker").selectpicker();

	// Change size of fontsize-picker
	$('.fontsize-picker .btn-group').removeClass('form-control').addClass('btn-group-sm');

	// Add theme color support for chrome browsers
	$('head').append('<meta name="theme-color" content="' + $primaryColor + '">');

	// Progressbar settings
	$('.progress .progress-bar').progressbar({
		percent_format: function(percent) {
			return percent;
		}
	});

	// Fix attach outside quickresponse
	$('#qr_postform #attach-tab').appendTo('#qr_ns_editor_div > .panel-body');

	$('#copyright #author-name').each(function() {
		if ($(this).text() != 'Florian Gareis') {
			$(this).css('color', 'red');
		}
	});

	// Enable tooltip on all buttons
	$(".btn[data-toggle!='dropdown']:not([disabled]):not(.disabled), i.online").attr({
		'data-toggle': 'tooltip',
		'data-container': 'body'
	});
	$('[data-toggle="tooltip"]').tooltip({container: 'body'});

	// Create mobile post toolbar
	$('#posts > div, #message > div').each(function() {
		var $post = $(this),
			$btnGroups = $post.find('.post-content .btn-toolbar .btn-group'),
			$btnGroupsAmount = $btnGroups.length;

		$btnGroups.each(function(index) {
			var $this = $(this),
				$postbody = $this.closest('.post-body');
			if (!$this.is(':empty')) {
				$this.children('a').each(function() {
					var $link = $(this),
						$icon = $link.children('i').attr('class'),
						$title = $link.data('original-title'),
						$href = $link.attr('href'),
						$item = '<li><a href="' + $href + '"><i class="' + $icon + '" aria-hidden="true"></i> ' + $title + '</a></li>';
					$postbody.find('.btn-toolbar-mobile ul').append($item);
				});
				if (index !== $btnGroupsAmount - 1) {
					$postbody.find('.btn-toolbar-mobile ul').append('<li role="separator" class="divider"></li>');
				}
				$postbody.find('.btn-toolbar-mobile').removeClass('hidden').addClass('visible-xs-block');
			}
		});
	});

	// Fix colorpicker
	$('#group_colour_component').on('changeColor', function(ev) {
		$('input[name=group_colour]').val(ev.color.toHex().replace('#', ''));
	});

	$('.posthilit').addClass('text-danger bg-danger');

	// Fix time selector on register page
	$('#register label[for="timezone"]').removeClass('form-label').addClass('control-label');

	// Add responsive class to site logo
	$('#site-logo img').attr('class', 'img-responsive center-block');

	$('.pm-to a:not(.btn)').attr('class', 'btn btn-default btn-sm');

	$('.btn-fix a').attr('class', 'btn btn-primary btn-sm');

	$('.btn-radio-group .btn-radio').click(function() {
		$(this).parents('.btn-radio-group').find('.btn-radio.active').removeClass('active');
		$(this).addClass('active');
		return true;
	});

	$('.btn-radio-group .btn-radio input:checked').each(function() {
		$(this).parents('.btn-radio-group').find('.btn-radio.active').removeClass('active');
		$(this).parent().addClass('active');
	});

	// Scroll to top
	$(window).scroll(function() {
		if ($(this).scrollTop() > 100) {
			$('#back-to-top').fadeIn();
		} else {
			$('#back-to-top').fadeOut();
		}
	});
	$('#back-to-top, .to-top').click(function() {
		$('#back-to-top').tooltip('hide');
		$('body,html').animate({
			scrollTop: 0
		}, 800);
		return false;
	});
	$('#back-to-top').hover(function() {
		$('#back-to-top').tooltip('show');
	});

	// Set data-lightbox
	$('.content img').each(function() {
		var $this = $(this);
		$this.attr('data-lightbox', $this.attr('src'));
	});

	if ($('#footer-nav .nav.navbar-right').length === 0) {
		$('#footer-nav ul.navbar-nav').after('<ul class="nav navbar-nav navbar-right"><li><p class="navbar-text" id="copyright">Design: <a href="http://zoker.me/go/comboot" target="_blank">ComBoot</a> by <a href="http://www.florian-gareis.de" target="_blank" id="author-name">Florian Gareis</a></p></li></ul>');
	}

	var dangercolor = $('.alert-danger').css('color');
	$('#alert-area').css('border-color', dangercolor);

	// post review expander
	$('.expander').click(function() {
		if ($('.expander i').hasClass('fa-expand')) {
			$('.expander i').removeClass('fa-expand').addClass('fa-compress');
		} else {
			$('.expander i').removeClass('fa-compress').addClass('fa-expand');
		}
	});

	var $copy = $('#copyright').html();
	$('#page-footer .copyright').prepend('<div class="visible-xs-block">' + $copy + '</div>');

	// more beautiful quote
	$('blockquote').css('border-left', '3px solid ' + $primaryColor)
		.css('background-color', $footerColor);

	// Live post title display
	$('#subject').on('input', function() {
		if ($('#subject').val().length > 0) {
			$('#live-title').text(': ' + $(this).val());
		} else {
			$('#live-title').empty();
		}
		var value = $('#subject').val();
		$('#live-title').text(value.length > 0 ? ': ' + value : '');
	}).trigger('input');
});

// Smoth scroll #links
$(function() {
	$('a[href*=#]:not([href=#]):not([data-toggle=tab]):not([data-type=char-select])').click(function() {
		if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
			if (target.length) {
				$('html,body').animate({
					scrollTop: target.offset().top
				}, 1000);
				return false;
			}
		}
	});
});

$(document).on('click', '.panel-heading span.clickable', function(e) {
	var $this = $(this);
	if (!$this.hasClass('panel-collapsed')) {
		$this.parents('.panel-collapsible').find('.panel-body, .panel-footer').slideUp();
		$this.addClass('panel-collapsed');
		$this.find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
	} else {
		$this.parents('.panel-collapsible').find('.panel-body, .panel-footer').slideDown();
		$this.removeClass('panel-collapsed');
		$this.find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
	}
});

function isImageFile(fname) {
	return fname.match(/\.(jpg|gif|bmp|jpeg|png)$/);
}
