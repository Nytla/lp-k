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

	/**
	 * Form contacts submit
	 */
	$("#name-msg, #email-msg, #text-msg").keyup(function() {
		if ($("#error-msg").hasClass("show")) {
			$("#error-msg")
			.html('')
			.removeClass("show")
			.addClass("hide")
		}
	});

	$("#robot-msg").change(function() {
		if ($("#error-msg").hasClass("show")) {
			$("#error-msg")
			.html('')
			.removeClass("show")
			.addClass("hide")
		}
	});

	$("#btn-msg").click(function() {
		$("#spam-msg").val('nospam');
		var form = $("#form-msg"),
				name = $("#name-msg").val(),
				email = $("#email-msg").val(),
				text = $("#text-msg").val(),
				name_length = name.length,
				email_length = email.length,
				text_length = text.length,
				spam_ph = $("#phone-msg").val(),
				spam_hi = $("#spam-msg").val(),
				robot = $("#robot-msg").prop("checked"),
				empty = 'Пожалуйста заполните все поля формы.',
				name_max = 'Введённое имя не должно превышать 32 символов.',
				name_min = 'Введённое имя не должно быть менее 2-х символов.',
				name_corr = "Введите пожалуйста корректно Ваше имя.",
				email_max = 'Введённый Email не должен превышать 64 символов.',
				text_max = 'Введённое сообщение не должно превышать 4096 символов.',
				text_min = 'Введённое сообщение не должно быть слишком коротким.',
				email_error = 'Пожалуйста введите корректно Ваш Email.',
				good_msg = 'Спасибо за Ваше сообщение, в ближайшие несколько часов мы свяжемся с Вами.',
				server_msg = 'Сообщение не удалось отправить, повторите попытку позже.',
				spam_msg = 'Наша систем спама определила Вас, как робота, пожалуйста внимательно заполните форму.',
				set_error = function(error) {
					$("#error-msg")
						.removeClass("hide")
						.addClass("show")
						.html(error);
				},
				set_good = function(show, msg) {
					if (show == true) {
						$('#good-msg')
							.removeClass("hide")
							.addClass("show")
							.html(msg);
						} else {
							$('#good-msg')
								.removeClass("show")
								.addClass("hide")
								.html('');
						}
				},
				data_obj = {
					send_msg: {
						name:	name,
						email: email,
						text:	text
					},
					send_g: {
						"entry.1870090211": name,
						"entry.732916390": email,
						"entry.397509613": text,
						"entry.558637815": 'Новое'
					}
				};

		if (name == '' || email == '' || text == '') {
			set_error(empty);
			return false;
		}
		if (name_length > 32) {
			set_error(name_max);
			return false;
		}
		if (!validName(name)) {
			set_error(name_corr);
			return false;
		}
		if (name_length <= 2) {
			set_error(name_min);
			return false;
		}
		if (email_length > 64) {
			set_error(email_max);
			return false;
		}
		if (email_length <= 7) {
			set_error(email_error);
			return false;
		}
		if (text_length > 4096) {
			set_error(text_max);
			return false;
		}
		if (text_length <= 7) {
			set_error(text_min);
			return false;
		}
		if (!validEmail(email)) {
			set_error(email_error);
			return false;
		}
		if (spam_ph != '' || spam_hi != 'nospam' || !robot) {
			set_error(spam_msg);
			return false;
		}

		//Send letter by AJAX in email
		$.ajax({
			type: "POST",
			dataType: "json",
			url: '/php/send-msg.php',
			contentType: "application/x-www-form-urlencoded;charset=utf-8",
			cache: false,
			data: data_obj.send_msg,
			success: function(object) {	
				if (object.flag == true) {
					//Clear form
					form[0].reset();
					
					//Show success message
					set_good(true, good_msg);

					//Hide success message
					setTimeout(set_good, 7000, false, good_msg);
				} else {
					//Show error from server
					set_error(server_msg);
				}
			}
		});

		//Send letter by AJAX in google
		$.ajax({
			type: "POST",
			dataType: "xml",
			url: 'https://docs.google.com/forms/d/1t5t0bNt1PNPl0vygHXcUo0ZwyoSZEgwnG3EJ_n9TSrk/formResponse',
			headers: {
				'Access-Control-Allow-Origin': data_obj.send_g.name
			},
			contentType: "application/x-www-form-urlencoded;charset=utf-8",
			cache: false,
			data: data_obj.send_g
		});
		return false;
	});

	/**
	 * If subscribe click
	 */
	$("#email-subs").keyup(function() {
		if ($("#error-subs").hasClass("show")) {
			$("#error-subs")
			.html('')
			.removeClass("show")
			.addClass("hide")
		}
	});

	$("#btn-subs").click(function() {
		var form_s = $("#form-msg"),
				email_s = $("#email-subs").val(),
				email_s_length = email_s.length,
				email_s_empty = 'Пожалуйста введите Ваш Email.',
				email_s_error = 'Пожалуйста введите корректно Ваш Email.',
				email_s_max = 'Введённый Email не должен превышать 64 символов.',
				email_good = 'Спасибо, Ваш адрес был успешно подписан на новости.',
				email_server = 'Ваш Email не был подписан, повторите попытку позже.',
				email_server_double = 'Ваш Email уже подписан на новости.',
				set_error_s = function(error) {
					$("#error-subs")
						.removeClass("hide")
						.addClass("show")
						.html(error);
				},
				set_good_s = function(show, msg) {
					if (show == true) {
						$('#good-subs')
							.removeClass("hide")
							.addClass("show")
							.html(msg);
						} else {
							$('#good-subs')
								.removeClass("show")
								.addClass("hide")
								.html('');
						}
				},
				data_obj_s = {
					email: email_s
				};

		if (email_s == '') {
			set_error_s(email_s_empty);
			return false;
		}
		if (!validEmail(email_s)) {
			set_error_s(email_s_error);
			return false;
		}
		if (email_s_length <= 7) {
			set_error_s(email_s_error);
			return false;
		}
		if (email_s_length > 64) {
			set_error_s(email_s_max);
			return false;
		}

		//Send letter by AJAX
		$.ajax({
			type: "POST",
			dataType: "json",
			url: '/php/subs.php',
			contentType: "application/x-www-form-urlencoded;charset=utf-8",
			cache: false,
			data: data_obj_s,
			success: function(object) {	
				if (object.flag == true) {
					//Clear form
					form_s[0].reset();
					
					//Show success message
					set_good_s(true, email_good);

					//Hide success message
					setTimeout(set_good_s, 7000, false, email_good);
				} else if (object.flag == "double") {
					//Show double message
					set_error_s(email_server_double);
				} else {
					//Show error from server
					set_error_s(email_server);
				}
			}
		});
		return false;
	});
});


/**
 * Validate Name
 */
/*function validName(name) {
	var re = /^[а-яА-яёЁa-zA-Z\s]{2,32}$/;
	if (re.test(name)) {
	  return true;
	} else {
	  return false;
	}
}
*/
/**
 * Validate Email address
 */
function validEmail(email) {
	var re =  /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
	if (re.test(email)) {
	  return true;
	} else {
	  return false;
	}
}