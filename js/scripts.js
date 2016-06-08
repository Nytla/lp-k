$(function($) {

	/**
	 * Fixed header when page scroll
	 */
/*
	$(window).scroll(function() {
	    if ($(this).scrollTop()>0) {
	      $(".navbar-fixed-top").addClass('nav-scroll');
	    } else {
	      $(".navbar-fixed-top").removeClass('nav-scroll');
	    }
	});
*/
	/**
	 * Insert youtube video with iframe
	 */
	$("#btn-video").click(function() {		
		$(this).hide(300);
		$(".jumbotron").addClass('up');
		var win_width = $(window).width();

		// alert(win_width);
		var video_iframe = '<iframe class="embed-responsive-item" width="560" height="250" src="https://www.youtube.com/embed/wNdJt-XyRUg?rel=0&autoplay=1" frameborder="0" allowfullscreen></iframe>';
		//$(this).attr('data-video');
		$("#video-pres").html(video_iframe);
	});

	/**
	 * Menu navigation
	 */
	$("#navbar a").click(function() {
		$("#navbar ul li:not('.navbar-right')").removeClass('active');
	  	$(this).parent().addClass('active');
		$(".navbar-header a").css({
			opacity: '0.6',
			filter: 'alpha(opacity=60)'
		});
		$("html, body").animate({
			scrollTop: $($(this).attr("href")).offset().top - 60 + "px"
		}, {
			duration: 500
		});
		return false;
    });

	/**
	 * Go to top when logo on click 
	 */
    $(".navbar-header a").click(function() {
    	$("#navbar ul li:not('.navbar-right')").removeClass('active');
		$(".navbar-header a").css({
			opacity: '1',
			filter: 'alpha(opacity=100)'
		});
    	$("html, body").animate({
			scrollTop: $($(this).attr("href")).offset().top - 60 + "px"
		}, {
			duration: 500
		});
    	return false;
    })

	/**
	 * Arrow "Go Up"
	 */
	if ($('#go_up').hasClass('go_up')) {
		var linkUp = $('#go_up');
		$(window).load(function () {
			setTimeout(function () {
/*
				currentPosition = $(this).scrollTop();
				posBottom = $('.footer').offset().top - 286;

				if (currentPosition > 300 && currentPosition < posBottom) {
					linkUp.removeAttr('style').css({'display': 'block'});
				} else if (currentPosition > 300 && currentPosition > posBottom) {
					linkUp.removeAttr('style').css({'display': 'block'});
				} else {
					linkUp.removeAttr('style').css({'display': 'none'});
				}
*/
				$(window).scroll(function () {
					currentPosition = $(this).scrollTop();
					posBottom = $('.footer').offset().top - 286;
					if (currentPosition > 300 && currentPosition < posBottom) {
						linkUp.removeAttr('style').css({'display': 'block'});
					} else if (currentPosition > 300 && currentPosition > posBottom) {
						linkUp.removeAttr('style').css({'display': 'block'});
					} else {
						linkUp.removeAttr('style').css({'display': 'none'});
					}
				});
			}, 1000);
			linkUp.click(function () {
				$('body,html').animate({scrollTop: 0}, 700);
				return false;
			});
		});
	}

/*
// Cache selectors
var lastId,
    topMenu = $("#navbar ul li:not('.navbar-right')"),
    topMenuHeight = topMenu.outerHeight()+15,
    // All list items
    menuItems = topMenu.find("a"),
    // Anchors corresponding to menu items
    scrollItems = menuItems.map(function(){
      var item = $($(this).attr("href"));
      if (item.length) { return item; }
    });
*/

/*
// Bind click handler to menu items
// so we can get a fancy scroll animation
menuItems.click(function(e){
  var href = $(this).attr("href"),
      offsetTop = href === "#" ? 0 : $(href).offset().top-topMenuHeight+1;
  $('html, body').stop().animate({ 
      scrollTop: offsetTop
  }, 300);
  e.preventDefault();
});
*/

/*
// Bind to scroll
$(window).scroll(function(){
   // Get container scroll position
   var fromTop = $(this).scrollTop()+topMenuHeight;
   
   // Get id of current scroll item
   var cur = scrollItems.map(function(){
     if ($(this).offset().top < fromTop)
       return this;
   });
   // Get the id of the current element
   cur = cur[cur.length-1];
   var id = cur && cur.length ? cur[0].id : "";
   
   if (lastId !== id) {
       lastId = id;
       // Set/remove active class
       menuItems
         .parent().removeClass("active")
         .end().filter("[href='#"+id+"']").parent().addClass("active");
   }                   
});
*/
});