/*global jQuery:false */
jQuery(document).ready(function($) {
"use strict";

	// Inisialisasi tombol mobile menu dan pastikan berfungsi di semua halaman
	$('.mobile-menu-toggle').show();
	
	// Cek apakah ada preferensi sidebar yang tersimpan
	var sidebarCollapsed = localStorage.getItem('sidebarCollapsed');
	if (sidebarCollapsed === 'true') {
		$('.sidebar').addClass('collapsed');
		$('#wrapper').addClass('full-width');
		$('.mobile-menu-toggle').html('<i class="fa fa-bars"></i> Menu');
	} else {
		$('.mobile-menu-toggle').html('<i class="fa fa-times"></i> Tutup Menu');
	}
	
	// Memperbaiki masalah pada halaman admin - memastikan bahwa mobile menu berfungsi
	// dengan benar pada semua halaman termasuk admin services dan admin content
	setTimeout(function() {
		// Reset event handler yang mungkin terduplikasi
		$(document).off('click', '.mobile-menu-toggle');
		$(document).off('click', '.sidebar-overlay');
		
		// Tambahkan event handler yang baru
		$(document).on('click', '.mobile-menu-toggle', function(e) {
			e.preventDefault();
			e.stopPropagation();
			
			// Toggle sidebar untuk semua ukuran layar
			$('.sidebar').toggleClass('collapsed');
			$('#wrapper').toggleClass('full-width');
			
			// Ubah ikon tombol menu
			if ($('.sidebar').hasClass('collapsed')) {
				$(this).html('<i class="fa fa-bars"></i> Menu');
			} else {
				$(this).html('<i class="fa fa-times"></i> Tutup Menu');
			}
			
			// Simpan preferensi sidebar ke localStorage
			localStorage.setItem('sidebarCollapsed', $('.sidebar').hasClass('collapsed'));
			
			// Tambahkan overlay saat sidebar terbuka di mobile
			if ($(window).width() <= 768) {
				if (!$('.sidebar').hasClass('collapsed')) {
					$('.sidebar').addClass('open');
					$('body').append('<div class="sidebar-overlay"></div>');
					setTimeout(function() {
						$('.sidebar-overlay').addClass('active');
					}, 10);
				} else {
					$('.sidebar').removeClass('open');
					$('.sidebar-overlay').removeClass('active');
					setTimeout(function() {
						$('.sidebar-overlay').remove();
					}, 300);
				}
			}
		});
		
		// Tutup sidebar saat overlay diklik
		$(document).on('click', '.sidebar-overlay', function() {
			$('.sidebar').addClass('collapsed').removeClass('open');
			$('#wrapper').addClass('full-width');
			$(this).removeClass('active');
			
			// Ubah ikon tombol menu
			$('.mobile-menu-toggle').html('<i class="fa fa-bars"></i> Menu');
			
			// Simpan preferensi sidebar ke localStorage
			localStorage.setItem('sidebarCollapsed', true);
			
			setTimeout(function() {
				$('.sidebar-overlay').remove();
			}, 300);
		});
	}, 100);
	
	// Tambahkan event handler untuk resize window
	$(window).resize(function() {
		// Jika layar menjadi kecil dan sidebar terbuka, tambahkan overlay
		if ($(window).width() <= 768 && !$('.sidebar').hasClass('collapsed')) {
			if ($('.sidebar-overlay').length === 0) {
				$('body').append('<div class="sidebar-overlay"></div>');
				setTimeout(function() {
					$('.sidebar-overlay').addClass('active');
				}, 10);
			}
		} else if ($(window).width() > 768) {
			// Jika layar menjadi besar, hapus overlay
			$('.sidebar-overlay').removeClass('active');
			setTimeout(function() {
				$('.sidebar-overlay').remove();
			}, 300);
		}
	});
	
	//add some elements with animate effect

		$(".big-cta").hover(
			function () {
			$('.cta a').addClass("animated shake");
			},
			function () {
			$('.cta a').removeClass("animated shake");
			}
		);
		$(".box").hover(
			function () {
			$(this).find('.icon').addClass("animated fadeInDown");
			$(this).find('p').addClass("animated fadeInUp");
			},
			function () {
			$(this).find('.icon').removeClass("animated fadeInDown");
			$(this).find('p').removeClass("animated fadeInUp");
			}
		);
		
		
		$('.accordion').on('show', function (e) {
		
			$(e.target).prev('.accordion-heading').find('.accordion-toggle').addClass('active');
			$(e.target).prev('.accordion-heading').find('.accordion-toggle i').removeClass('icon-plus');
			$(e.target).prev('.accordion-heading').find('.accordion-toggle i').addClass('icon-minus');
		});
		
		$('.accordion').on('hide', function (e) {
			$(this).find('.accordion-toggle').not($(e.target)).removeClass('active');
			$(this).find('.accordion-toggle i').not($(e.target)).removeClass('icon-minus');
			$(this).find('.accordion-toggle i').not($(e.target)).addClass('icon-plus');
		});	

		
		// tooltip
		$('.social-network li a, .options_box .color a').tooltip();

		// fancybox
		$(".fancybox").fancybox({				
				padding : 0,
				autoResize: true,
				beforeShow: function () {
					this.title = $(this.element).attr('title');
					this.title = '<h4>' + this.title + '</h4>' + '<p>' + $(this.element).parent().find('img').attr('alt') + '</p>';
				},
				helpers : {
					title : { type: 'inside' },
				}
			});

		
		//scroll to top
		$(window).scroll(function(){
			if ($(this).scrollTop() > 100) {
				$('.scrollup').fadeIn();
				} else {
				$('.scrollup').fadeOut();
			}
		});
		$('.scrollup').click(function(){
			$("html, body").animate({ scrollTop: 0 }, 1000);
				return false;
		});
    $('#post-slider').flexslider({
        // Primary Controls
        controlNav          : false,              //Boolean: Create navigation for paging control of each clide? Note: Leave true for manualControls usage
        directionNav        : true,              //Boolean: Create navigation for previous/next navigation? (true/false)
        prevText            : "Previous",        //String: Set the text for the "previous" directionNav item
        nextText            : "Next",            //String: Set the text for the "next" directionNav item
         
        // Secondary Navigation
        keyboard            : true,              //Boolean: Allow slider navigating via keyboard left/right keys
        multipleKeyboard    : false,             //{NEW} Boolean: Allow keyboard navigation to affect multiple sliders. Default behavior cuts out keyboard navigation with more than one slider present.
        mousewheel          : false,             //{UPDATED} Boolean: Requires jquery.mousewheel.js (https://github.com/brandonaaron/jquery-mousewheel) - Allows slider navigating via mousewheel
        pausePlay           : false,             //Boolean: Create pause/play dynamic element
        pauseText           : 'Pause',           //String: Set the text for the "pause" pausePlay item
        playText            : 'Play',            //String: Set the text for the "play" pausePlay item
         
        // Special properties
        controlsContainer   : "",                //{UPDATED} Selector: USE CLASS SELECTOR. Declare which container the navigation elements should be appended too. Default container is the FlexSlider element. Example use would be ".flexslider-container". Property is ignored if given element is not found.
        manualControls      : "",                //Selector: Declare custom control navigation. Examples would be ".flex-control-nav li" or "#tabs-nav li img", etc. The number of elements in your controlNav should match the number of slides/tabs.
        sync                : "",                //{NEW} Selector: Mirror the actions performed on this slider with another slider. Use with care.
        asNavFor            : "",                //{NEW} Selector: Internal property exposed for turning the slider into a thumbnail navigation for another slider
    });
	
    $('#main-slider').flexslider({
        namespace           : "flex-",           //{NEW} String: Prefix string attached to the class of every element generated by the plugin
        selector            : ".slides > li",    //{NEW} Selector: Must match a simple pattern. '{container} > {slide}' -- Ignore pattern at your own peril
        animation           : "fade",            //String: Select your animation type, "fade" or "slide"
        easing              : "swing",           //{NEW} String: Determines the easing method used in jQuery transitions. jQuery easing plugin is supported!
        direction           : "horizontal",      //String: Select the sliding direction, "horizontal" or "vertical"
        reverse             : false,             //{NEW} Boolean: Reverse the animation direction
        animationLoop       : true,              //Boolean: Should the animation loop? If false, directionNav will received "disable" classes at either end
        smoothHeight        : false,             //{NEW} Boolean: Allow height of the slider to animate smoothly in horizontal mode
        startAt             : 0,                 //Integer: The slide that the slider should start on. Array notation (0 = first slide)
        slideshow           : true,              //Boolean: Animate slider automatically
        slideshowSpeed      : 7000,              //Integer: Set the speed of the slideshow cycling, in milliseconds
        animationSpeed      : 600,               //Integer: Set the speed of animations, in milliseconds
        initDelay           : 0,                 //{NEW} Integer: Set an initialization delay, in milliseconds
        randomize           : false,             //Boolean: Randomize slide order
         
        // Usability features
        pauseOnAction       : true,              //Boolean: Pause the slideshow when interacting with control elements, highly recommended.
        pauseOnHover        : false,             //Boolean: Pause the slideshow when hovering over slider, then resume when no longer hovering
        useCSS              : true,              //{NEW} Boolean: Slider will use CSS3 transitions if available
        touch               : true,              //{NEW} Boolean: Allow touch swipe navigation of the slider on touch-enabled devices
        video               : false,             //{NEW} Boolean: If using video in the slider, will prevent CSS3 3D Transforms to avoid graphical glitches
         
        // Primary Controls
        controlNav          : true,              //Boolean: Create navigation for paging control of each clide? Note: Leave true for manualControls usage
        directionNav        : true,              //Boolean: Create navigation for previous/next navigation? (true/false)
        prevText            : "Previous",        //String: Set the text for the "previous" directionNav item
        nextText            : "Next",            //String: Set the text for the "next" directionNav item
         
        // Secondary Navigation
        keyboard            : true,              //Boolean: Allow slider navigating via keyboard left/right keys
        multipleKeyboard    : false,             //{NEW} Boolean: Allow keyboard navigation to affect multiple sliders. Default behavior cuts out keyboard navigation with more than one slider present.
        mousewheel          : false,             //{UPDATED} Boolean: Requires jquery.mousewheel.js (https://github.com/brandonaaron/jquery-mousewheel) - Allows slider navigating via mousewheel
        pausePlay           : false,             //Boolean: Create pause/play dynamic element
        pauseText           : 'Pause',           //String: Set the text for the "pause" pausePlay item
        playText            : 'Play',            //String: Set the text for the "play" pausePlay item
         
        // Special properties
        controlsContainer   : "",                //{UPDATED} Selector: USE CLASS SELECTOR. Declare which container the navigation elements should be appended too. Default container is the FlexSlider element. Example use would be ".flexslider-container". Property is ignored if given element is not found.
        manualControls      : "",                //Selector: Declare custom control navigation. Examples would be ".flex-control-nav li" or "#tabs-nav li img", etc. The number of elements in your controlNav should match the number of slides/tabs.
        sync                : "",                //{NEW} Selector: Mirror the actions performed on this slider with another slider. Use with care.
        asNavFor            : "",                //{NEW} Selector: Internal property exposed for turning the slider into a thumbnail navigation for another slider
    });

	/* ----------------------------------------------------------- */
	/*  1. FIXED MENU
	/* ----------------------------------------------------------- */
    
	$(window).on('scroll', function(){
		if( $(window).scrollTop()>50){
			$('.header').addClass('fixed-top');
		} else {
			$('.header').removeClass('fixed-top');
		}
	});
	
	/* ----------------------------------------------------------- */
	/*  2. SCROLL TO
	/* ----------------------------------------------------------- */

	$('.scrollto').click(function(event){
		event.preventDefault();
		var target = $(this).attr('href');
		$('html,body').animate({scrollTop: $(target).offset().top}, "slow");
	});

	/* ----------------------------------------------------------- */
	/*  3. MOBILE MENU TOGGLE
	/* ----------------------------------------------------------- */

	// Tambahkan efek hover pada tombol logout
    $('.logout-link').hover(
        function() {
            $(this).css('transform', 'scale(1.05)');
        },
        function() {
            $(this).css('transform', 'scale(1.0)');
        }
    );

	/* ----------------------------------------------------------- */
	/*  4. ADMIN MENU MANAGEMENT
	/* ----------------------------------------------------------- */
	
    // Cek apakah sudah login
    function checkLogin() {
        const isLoggedIn = sessionStorage.getItem('isLoggedIn') === 'true';
        // Definisikan halaman yang bisa diakses publik
        const publicPages = ['index.php', 'login.php', 'profil.php', 'services.php'];
        const currentPath = window.location.pathname;
        const currentPage = currentPath.split('/').pop() || 'index.php';
        
        // Jika halaman publik, langsung return
        if (publicPages.some(page => currentPath.toLowerCase().endsWith(page.toLowerCase()))) {
            return;
        }
        
        // Jika belum login dan bukan halaman publik, redirect ke halaman login
        if (!isLoggedIn) {
            window.location.href = 'login.php';
            return;
        }
        
        // Jika sudah login, atur menu admin
        if (isLoggedIn) {
            showHideAdminMenu();
        } else {
            // Sembunyikan menu admin untuk user yang belum login
            $('.admin-menu').hide();
        }
    }    // Fungsi untuk menampilkan/menyembunyikan menu admin berdasarkan role
    function showHideAdminMenu() {
        var role = sessionStorage.getItem('userRole');
        if (role === 'admin') {
            // Tampilkan menu admin
            $('.admin-menu').show();
        } else {
            // Sembunyikan menu admin
            $('.admin-menu').hide();
        }
    }
    
    // Inisialisasi: tampilkan menu admin berdasarkan role dari sessionStorage
    showHideAdminMenu();
    
    // Cek login saat halaman dimuat
    checkLogin();
    
    // Handle logout button
    $('#logout-btn').on('click', function(e) {
        e.preventDefault();
        
        // Hapus session storage
        sessionStorage.removeItem('isLoggedIn');
        sessionStorage.removeItem('username');
        sessionStorage.removeItem('userRole');
        
        // Alihkan ke halaman utama
        window.location.href = 'index.html';
    });

	//add some elements with animate effect
	$(".box").hover(
		function () {
			$(this).find('img').addClass("animated pulse");
			$(this).find('.icon').addClass("animated bounce");
		},
		function () {
			$(this).find('img').removeClass("animated pulse");
			$(this).find('.icon').removeClass("animated bounce");
		}
	);
	
	$('.accordion').on('show', function (e) {
		$(e.target).prev('.accordion-heading').find('.accordion-toggle').addClass('active');
		$(e.target).prev('.accordion-heading').find('.accordion-toggle i').removeClass('icon-plus');
		$(e.target).prev('.accordion-heading').find('.accordion-toggle i').addClass('icon-minus');
	});
	
	$('.accordion').on('hide', function (e) {
		$(this).find('.accordion-toggle').not($(e.target)).removeClass('active');
		$(this).find('.accordion-toggle i').not($(e.target)).removeClass('icon-minus');
		$(this).find('.accordion-toggle i').not($(e.target)).addClass('icon-plus');
	});	

	// Create the dropdown base
	$("<select />").appendTo("nav");
	
	// Create default option "Go to..."
	$("<option />", {
		"selected": "selected",
		"value"   : "",
		"text"    : "Go to..."
	}).appendTo("nav select");
	
	// Populate dropdown with menu items
	$("nav a").each(function() {
		var el = $(this);
		$("<option />", {
			"value"   : el.attr("href"),
			"text"    : el.text()
		}).appendTo("nav select");
	});
	
	// To make dropdown actually work
	// To make more unobtrusive: http://css-tricks.com/4064-unobtrusive-page-changer/
	$("nav select").change(function() {
		window.location = $(this).find("option:selected").val();
	});
	
	$("a[data-pretty^='prettyPhoto']").prettyPhoto();
	
	//Navi hover
	$('ul.nav li.dropdown').hover(function () {
		$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn();
	}, function () {
		$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut();
	});
	
	// tooltip
	$('.social-network li a, .options_box .color a').tooltip();

	// fancybox
	$(".fancybox").fancybox({				
		padding : 0,
		autoResize: true,
		beforeShow: function () {
			this.title = $(this.element).attr('title');
			this.title = '<h4>' + this.title + '</h4>' + '<p>' + $(this.element).parent().find('img').attr('alt') + '</p>';
		},
		helpers : {
			title : { type: 'inside' },
		}
	});
			
	//scroll to top
	$(window).scroll(function(){
		if ($(this).scrollTop() > 100) {
			$('.scrollup').fadeIn();
			} else {
			$('.scrollup').fadeOut();
		}
	});
	$('.scrollup').click(function(){
		$("html, body").animate({ scrollTop: 0 }, 1000);
			return false;
	});

	$('#mycarousel').jcarousel();
	$('#mycarousel1').jcarousel();
	
	//flexslider
	$('.flexslider').flexslider();
	
	//nivo slider
	$('.nivo-slider').nivoSlider({
		effect: 'random', // Specify sets like: 'fold,fade,sliceDown'
		slices: 15, // For slice animations
		boxCols: 8, // For box animations
		boxRows: 4, // For box animations
		animSpeed: 500, // Slide transition speed
		pauseTime: 5000, // How long each slide will show
		startSlide: 0, // Set starting Slide (0 index)
		directionNav: true, // Next & Prev navigation
		controlNav: false, // 1,2,3... navigation
		controlNavThumbs: false, // Use thumbnails for Control Nav
		pauseOnHover: true, // Stop animation while hovering
		manualAdvance: false, // Force manual transitions
		prevText: '', // Prev directionNav text
		nextText: '', // Next directionNav text
		randomStart: false, // Start on a random slide
		beforeChange: function(){}, // Triggers before a slide transition
		afterChange: function(){}, // Triggers after a slide transition
		slideshowEnd: function(){}, // Triggers after all slides have been shown
		lastSlide: function(){}, // Triggers when last slide is shown
		afterLoad: function(){} // Triggers when slider has loaded
	});
	
	// Da Sliders
	if( $('#da-slider').length ){
		$('#da-slider').cslider();
	}
	
	//slitslider
	var Page = (function() {

		var $nav = $( '#nav-dots > span' ),
		slitslider = $( '#slider' ).slitslider( {
			onBeforeChange : function( slide, pos ) {
				$nav.removeClass( 'nav-dot-current' );
				$nav.eq( pos ).addClass( 'nav-dot-current' );
			}
		} ),

		init = function() {
			initEvents();
		},
		initEvents = function() {
			$nav.each( function( i ) {
				$( this ).on( 'click', function( event ) {
					var $dot = $( this );
					if( !slitslider.isActive() ) {
						$nav.removeClass( 'nav-dot-current' );
						$dot.addClass( 'nav-dot-current' );
					}
				
					slitslider.jump( i + 1 );
					return false;
				
				} );
			
			} );

		};

		return { init : init };
	})();

	Page.init();
});