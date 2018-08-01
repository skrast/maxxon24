var submit_ajax_form = 0;
var api_file_uploader = '';
var form_is_show = 0;

// alert show
var show_alert = function (status, message) {
	toastr.options = {
	  "closeButton": true,
	  "debug": false,
	  "newestOnTop": false,
	  "progressBar": false,
	  "positionClass": "toast-top-right",
	  "preventDuplicates": false,
	  "onclick": null,
	  "showDuration": "300",
	  "hideDuration": "1000",
	  "timeOut": "5000",
	  "extendedTimeOut": "1000",
	  "showEasing": "swing",
	  "hideEasing": "linear",
	  "showMethod": "fadeIn",
	  "hideMethod": "fadeOut"
	}

	switch(status) {
		case "success":
			toastr.success(message, alert_title);
		break

		case "warning":
			toastr.warning(message, alert_title);
		break

		case "error":
			toastr.error(message, alert_title);
		break

		case "info":
			toastr.info(message, alert_title);
		break
	}
},
// alert show

writer_core = function () {
	$(".writer_search").on('submit',function() {
		var form_data = $(this).formSerialize();
		var this_form = $(this);
		$(".writer_search .error-message").addClass("hidden");

		if(submit_ajax_form == 1) {
			return true;
		}

		$.ajax({
			url: ave_host + '/search-writer/',
			data: form_data,
			success: function( data ) {

				if(data.upload == true) {
					submit_ajax_form = 1;
					this_form.submit();
					return true;
				}

				if(data.respons) {
					show_alert(data.status, data.respons);
					$(".writer_search .error-message").removeClass("hidden");
					$(".writer_search .error-message").html(data.respons);
				}

			}
		});

		return false;
	});
},

billing_core = function () {
	$(".remove_card").on('click',function() {
		var card_id = $(this).attr("data-id");
		var this_li = $(this).parent();

		$.ajax({
			url: ave_path+'site.php?do=siteBilling&sub=card_delete',
			data: {card_id: card_id, csrf_token: csrf_token},
			success: function( data ) {

				if(data.status == 'success') {
					this_li.fadeOut().remove();
					$("select[name=billing_card] option[value="+card_id+"]").remove();
				}

			}
		});

		return false;
	});

	$(".change_card").on('click',function() {
		card_id = $(this).attr("data-id");
		$("input[name=billing_card]").val(card_id);
		var card_title = $(this).find("a").first().text();
		$(".choose-cart-link").html(card_title+'<span class="caret"></span>');
		return false;
	});

	/*$(".edit_card").on('click',function() {
		var card_id = $(this).attr("data-id");
		var this_li = $(this).parent();

		$.ajax({
			url: ave_path+'site.php?do=siteBilling&sub=edit_card',
			data: {card_id: card_id, csrf_token: csrf_token},
			success: function( data ) {

				if(data.status == 'success') {
					this_li.fadeOut().remove();
				}

			}
		});

		return false;
	});*/

},

reg_core = function () {

	$(".change_type_form").on('click',function() {
		var this_value = $(this).attr("data-type");
		$(".show_skill .change_type_form").removeClass("active");
		$(this).addClass("active");

		$("input[name=user_type_form]").val(this_value);

		return false;
	});

	$(".change_group_register li a").off();
	$(".change_group_register li a").on('click',function() {
		var group = $(this).attr("data-type");

		$(".change_group_register li").removeClass("active");
		$(this).parent().addClass("active");

		$(this).parent().parent().parent().find("input[name=user_group]").val(group);
		return false;
	});

	$("#recoverPassword input[type='text']").off();
	$("#recoverPassword input[type='text']").on('focus click',function() {
		$(this).parent().prev().find("input[type='radio']").click();
	});

	/*$("#recoverPassword input[type='text']").on('change',function() {
		var void_data = $(this).val();
		var void_type = $(this).attr("name");

		$.ajax({
			url: ave_host + '/auth/profile/',
			data: {void_data: void_data, void_type: void_type, csrf_token: csrf_token},
			success: function( data ) {

				if(data.status == 'success') {
					$("#recoverPassword .get_login img").attr("src", ave_path + '?thumb=' + data.respons.photo + '&width=65&height=65');
					$("#recoverPassword .get_login p").text(data.respons.name);
				}

			}
		});

		return false;
	});*/

	$("#recoverPassword form").on('submit',function() {
		var form_data = $(this).formSerialize();
		var this_form = $(this);

		this_form.find(".error-message").html("");

		$.ajax({
			url: ave_host + '/auth/recover/',
			data: form_data,
			success: function( data ) {

				if(data.ref) {
					window.location.href = data.ref;

					var url_hash = window.location.toString();
			        var url_split = url_hash.split("#");
			        if(url_split[1] == 'recover') {
			            window.location.reload();
			        }
					return false;
				}

				show_alert(data.status, data.respons);
			
				if(data.status == 'error') {
					this_form.find(".error-message").html(data.respons);
				}

			}
		});

		return false;
	});

	$("#recoverPasswordRepeat form").on('submit',function() {
		var form_data = $(this).formSerialize();

		$.ajax({
			url: ave_host + '/auth/recover/',
			data: form_data,
			success: function( data ) {

				if(data.ref) {
					window.location.href = data.ref;

					var url_hash = window.location.toString();
			        var url_split = url_hash.split("#");
			        if(url_split[1] == 'recover') {
			            window.location.reload();
			        }
					return false;
				}


			}
		});

		return false;
	});

	$("#vvodCode form").on('submit',function() {
		var form_data = $(this).formSerialize();
		var this_form = $(this);

		this_form.find(".error-message").html("");

		$.ajax({
			url: ave_host + '/auth/recover/verify/',
			data: form_data,
			success: function( data ) {

				show_alert(data.status, data.respons);

				if(data.status == 'error') {
					this_form.find(".error-message").html(data.respons);
				}

			}
		});

		return false;
	});

	var url_hash = window.location.toString();
    var url_split = url_hash.split("#");
    if(url_split[1] == 'recover') {
        $('#vvodCode').modal('show');
    }
    if(url_split[1] == 'login') {
        $('#login').modal('show');
    }
    if(url_split[1] == 'registration') {
        $('#registration').modal('show');
    }
    if(url_split[1] == 'registration_perfomens') {
		$('#registration_perfomens').modal('show');
		
		setCookie('lending', '1', 365);
    }

	$(".recoverPasswordRepeat").on('click',function(event) {
		event.stopPropagation();
		$("#recoverPasswordRepeat form").trigger("submit");
		return false;
	});
},

init_support = function () {
	var url_hash = window.location.toString();
	var url_split = url_hash.split("#");
	if(url_split[1] == 'feedback-after') {
		var code = url_split[0].split("=");
		var text = $('#feedback-after p').html().replace('#LINK#', code[1]);
		$('#feedback-after p').html(text);
		$('#feedback-after').modal('show');
	}
},

search_perfomers_core = function () {
	/*$("select[name=user_country]").on('change',function() {
		var country_id = $(this).val();

		$.ajax({
			url: ave_host+'/city-load/',
			data: {country_id: country_id, csrf_token: csrf_token},
			success: function( data ) {
				$("select[name=user_city]").html("");
				$.each(data.respons, function( index, value ) {
					$("select[name=user_city]").append("<option value='"+value.id+"'  data-utm='"+value.utm+"'>"+value.title+"</option>");
				});
			}
		});
	});*/

	$(".change_skill li a").on('click',function() {
		$(this).parent().parent().find("li a").removeClass("active");
		$(this).addClass("active");
		var type = $(this).attr("data-type");
		$("input[name=user_type]").val(type);

		var book_id = $(this).attr("data-book");

		if(book_id) {
			$.ajax({
				url: ave_host+'/book-load/',
				data: {book_id: book_id, csrf_token: csrf_token},
				success: function( data ) {
					$(".load_service").html("");
					$.each(data.respons, function( index, value ) {
						$(".load_service").append('<div class="col-md-4"><label><input class="checkbox-input" type="checkbox" name="serv_service[]" value="'+value.id+'"><span class="checkbox-custom"></span><span class="label">'+value.title+'</span></label></div>');
					});

					$(".load_service input[type=checkbox]").on("change", function() {
						$(".load_communication").addClass("hidden");

						$(".load_service input[type=checkbox]:checked").each(function( index ) {
							var check = $(this).val();
							if(check == 49 || check == 56) {
								$(".load_communication").removeClass("hidden");
							}
						});
					});
				}
			});
		} else {
			$(".load_service").html("");
		}

		return false;
	});
},

search_resume_core = function () {
	/*$("select[name=user_country]").on('change',function() {
		var country_id = $(this).val();

		$.ajax({
			url: ave_host+'/city-load/',
			data: {country_id: country_id, csrf_token: csrf_token},
			success: function( data ) {
				$("select[name=user_city]").html("");
				$.each(data.respons, function( index, value ) {
					$("select[name=user_city]").append("<option value='"+value.id+"' data-utm='"+value.utm+"'>"+value.title+"</option>");
				});
			}
		});
	});*/

	$(".change_skill li a").on('click',function() {
		$(this).parent().parent().find("li a").removeClass("active");
		$(this).addClass("active");
		var type = $(this).attr("data-type");
		$("input[name=user_type]").val(type);
		return false;
	});

},

search_jobs_core = function () {
	$('.selectpicker').selectpicker({'style':'btn-default', 'size': 5});

	/*$("select[name=user_country]").on('change',function() {
		var country_id = $(this).val();

		$.ajax({
			url: ave_host+'/city-load/',
			data: {country_id: country_id, csrf_token: csrf_token},
			success: function( data ) {
				$("select[name=user_city]").html("");
				$.each(data.respons, function( index, value ) {
					$("select[name=user_city]").append("<option value='"+value.id+"' data-utm='"+value.utm+"'>"+value.title+"</option>");
				});
			}
		});
	});*/
},

order_core = function () {

	$(".change_skill a.btn-adv").on('click',function() {
		var skill_id = $(this).attr("data-id");
		var book_id = $(this).attr("data-book");
		$("input[name=order_skill]").val(skill_id);

		$(".change_skill a.btn-adv").removeClass("active");
		$(this).addClass("active");

		if(skill_id == 2) {
			$(".date_ranger").addClass("hidden");
			//$("input[name=order_start], input[name=order_end]").val("");

			$(".hidden_lvl").removeClass("hidden").show();
		} else {
			$(".date_ranger").removeClass("hidden");

			$(".hidden_lvl").addClass("hidden").hide();
		}


		/*if(skill_id == 1 || skill_id == 2 || skill_id == 3) {
			$(".hidden_theme").removeClass("hidden").show();
		} else {
			$(".hidden_theme").addClass("hidden").hide();
		}*/

		if(skill_id == 2) {
			$(".show_time_26").addClass("hidden");

			$("select[name=order_lang_from] .first").text(lang_3);
			$("select[name=order_lang_to] .first").text(lang_4);
		} else {
			$(".show_time_26").removeClass("hidden");

			$("select[name=order_lang_from] .first").text(lang_1);
			$("select[name=order_lang_to] .first").text(lang_2);
		}

		$("select[name=order_lang_from]").selectpicker("refresh");
		$("select[name=order_lang_to]").selectpicker("refresh");

		$.ajax({
			url: ave_host+'/book-load/',
			data: {book_id: book_id, csrf_token: csrf_token},
			success: function( data ) {
				$("select[name=order_service]").html("");
				$.each(data.respons, function( index, value ) {
					$("select[name=order_service]").append("<option value='"+value.id+"'>"+value.title+"</option>");
				});

				$("select[name=order_service]").selectpicker("refresh");
			}
		});

		return false;
	});

	if($(".change_skill a.active").length==0) {
		$(".change_skill a.btn-adv:first").trigger('click');
	}

	$(".order_status").on('click',function() {
		$("input[name=order_status]").val($(this).attr("data-value"));
	});

	$("select.change_perfomens").on('change',function() {
		var change_perfomens = $(this).val();
		var change_order = $(this).attr("data-order");

		if(change_perfomens) {
			$.ajax({
				url: ave_host+'/order-'+change_order+'/',
				data: {change_perfomens: change_perfomens, csrf_token: csrf_token},
				success: function( data ) {

					if(data.respons) {
						show_alert(data.status, data.respons);
					}

				}
			});
		}

	});
},

config_list_user = {
	scrollButtons:{enable:true},
	theme:"dark",
	axis:"y",
	scrollInertia: 2000,
	/*setHeight: $('.js-chat-management').height(),*/
	scrollbarPosition: "outside",
	autoDraggerLength: true
},
config_list_message = {
	scrollButtons:{enable:true},
	theme:"dark",
	axis:"y",
	/*setHeight: 650,*/
	setTop:"-999999px",
	scrollInertia: 2000,
	scrollbarPosition: "outside",
	callbacks:{
        onTotalScrollBack:function(){
            if($(".load_more_message:visible")) {
				$(".load_more_message").click();
			}
        }
    }
},
message_core = function () {
	

	// init drag and drop
	var block_to_event = document.querySelector("body");
	[ 'dragover', 'dragenter' ].forEach( function( event ) {

		block_to_event.addEventListener( event, function() {
			//
			if(form_is_show != 1) {
				$("button.upload-photo").click();
				form_is_show = 1;
			}
			
		});
		
	});
	// init drag and drop


	/*$("#tinySliderMessage").tinycarousel({
        axis   : "y",
    });*/

	/*var chat_height = $('.js-chat-management').height();*/

	autosize($('textarea:not(.summernote)'));
	if($("#content-4 .contact-item").length > $("#content-4").attr("data-max")) {
		$("#content-4").mCustomScrollbar(config_list_user);
		$("#content-4").height($('.js-chat-management').height()-150);
	}


	$("#content-4-1").mCustomScrollbar(config_list_message);
	if($("#content-4-1 .message_simple").length <= $(".messages-chat ul").attr("data-all")) {
		$("#content-4-1").mCustomScrollbar("scrollTo", ".messages-chat .message_simple:last .last_scroll", {scrollInertia:0});
	} else {
		var last_scroll = $(".messages-chat .message_simple:last .last_scroll");
		$('html, body').animate({
	        scrollTop: last_scroll.offset().top
	    }, 2000);
	}
	$("#content-4-1").height($('.js-chat-management').height()-150);

	$(".message_otzive .otziv_type").on('click',function() {
		var value = $(this).attr("data-value");
		$(".message_otzive .otziv_type").removeClass("active");
		$(this).addClass("active");
		$("input[name=otziv_type]").val(value);
	});

	$(".message_otzive .unit-rating a").on('click',function() {
		var value = $(this).attr("title");
		//$(".message_otzive .raiting .star-small").removeClass("active");
		//$(this).addClass("active");
		$(".message_otzive input[name=otziv_star]").val(value);
		$(".message_otzive .unit-rating .current-rating").css("width", 30*value+"px");
		return false;
	});


	$("#upload-photo").on("change",function(e) {
		e.preventDefault();

		var form = new FormData(this);
		var file=document.getElementById("upload-photo");
		var upload_file=file.files[0];
		form.append("upload_file[]", upload_file);
		form.append("message_to", $(this).attr("data-to"));
		form.append("csrf_token", csrf_token);

		$.ajax({
			url: ave_host+'/message/upload/',
			data: form,
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function( data ) {
				if(data.respons) {
					show_alert(data.status, data.respons);
				}

				//message_core_reload();
			}
		});

		return false;
	});

	$(".ajax_form_message textarea").keydown(function (e) {
		if (e.ctrlKey && e.keyCode == 13) {
			$(".ajax_form_message").trigger("submit");
		}
	});

	$(".ajax_form_message").on('submit',function(e) {
		e.preventDefault();	
		
		var form = $(this).formSerialize();
		var target_form = $(this).attr("action");
		var stop_reset = $(this).attr("data-reset"); // if need reset form
		var this_form = $(this);

		if(submit_ajax_form == 1) {
			return true;
		}

		this_form.find(".error-message").html("");

		$.ajax({
			url: target_form,
			data: form,
			success: function( data ) {

				show_alert(data.status, data.respons);

				if(data.status == 'error') {
					this_form.find(".error-message").html(data.respons);
				}

				if(data.status == 'success' && stop_reset==1) {
					$(".close_option").trigger("click");

					this_form.find("textarea[name=message_desc]").val("");

					$(".chat-offers__footer").removeClass("answer");
					$(".ajax_form_message input[name=parent_id]").val("");
					$(".chat-offers__input-real").css("height", "30px");
					$("#content-4-1 ul").first().show();
					autosize.update($(".chat-offers__input-real"));
				}

				//message_core_reload();

				//this_form.find(".actions input").attr("disabled", false);

				return false;
			}
		});

		

		return false;
	});


	$(".load_more_message").on('click',function() {
		var message_to = $(".messages-chat ul").attr("data-to");
		var message_first = $(".messages-chat ul .message_simple:first").attr("data-message");

		$.ajax({
			url: ave_host+'/message/more/',
			data: {message_to: message_to, message_first: message_first, csrf_token: csrf_token},
			success: function( data ) {

				$(".messages-chat ul").attr("data-all", data.count_all);
				if(data.message_list) {

					$.each(data.message_list, function( index, value ) {
						$(".messages-chat ul:first").prepend(value.message_html_block);
					});

					if(data.count_all == $("#content-4-1 .message_simple").length) {
						$(".load_more_message").hide();
					}


					$("#content-4-1").mCustomScrollbar("update").mCustomScrollbar("scrollTo", $(".message_simple_"+data.message_list[0].message_id), {scrollInertia:0});

					//$("#content-4-1").mCustomScrollbar("scrollTo", ".messages-chat .message_simple:last .last_scroll");
				}

				return false;
			}
		});

		return false;
	});

	init_update_chat_answer();
	init_update_chat();
},


message_uploader_core = function (field_name) {
		
	var filer_uploader = $(field_name).find('input[type="file"]').fileuploader({
		extensions: allow_ext_photo,
		limit: 9,

		changeInput: ' ',
		theme: 'thumbnails',
		enableApi: true,
		addMore: true,

		editor: {
			cropper: {
				ratio: '1:1',
				minWidth: 100,
				minHeight: 100,
				showGrid: true
			}
		},

		thumbnails: {
			box: '<div class="fileuploader-items">' +
					'<ul class="fileuploader-items-list">' +
						'<li class="fileuploader-thumbnails-input"><div class="fileuploader-thumbnails-input-inner"><img src="'+ave_path+'assets/site/template/images/upload_photo_big_active.png"><br>'+$(field_name).attr("data-empty")+'</div></li>' +
					'</ul>' +
				'</div>',
			item: '<li class="fileuploader-item">' +
					'<div class="fileuploader-item-inner">' +
						'<div class="thumbnail-holder">${image}</div>' +
						'<div class="actions-holder">' +
								'<a class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="remove"></i></a>' +
							'<span class="fileuploader-action-popup"></span>' +
						'</div>' +
							'<div class="progress-holder hidden">${progressBar}</div>' +
					'</div>' +
				'</li>',
			item2: '<li class="fileuploader-item">' +
					'<div class="fileuploader-item-inner">' +
						'<div class="thumbnail-holder">${image}</div>' +
						'<div class="actions-holder">' +
							'<a class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="remove"></i></a>' +
							'<span class="fileuploader-action-popup"></span>' +
						'</div>' +
					'</div>' +
				'</li>',

			startImageRenderer: true,
			canvasImage: false,

			_selectors: {
				list: '.fileuploader-items-list',
				item: '.fileuploader-item',
				start: '.fileuploader-action-start',
				retry: '.fileuploader-action-retry',
				remove: '.fileuploader-action-remove'
			},
			onItemShow: function(item, listEl) {
				var plusInput = listEl.find('.fileuploader-thumbnails-input');
				
				plusInput.insertAfter(item.html);
				
				if(item.format == 'image') {
					item.html.find('.fileuploader-item-icon').hide();
				}
			}
		},

		afterRender: function(listEl, parentEl, newInputEl, inputEl) {
			var plusInput = listEl.find('.fileuploader-thumbnails-input'),
				api = $.fileuploader.getInstance(inputEl.get(0));
		
			plusInput.on('click', function() {
				api.open();
			});

			form_is_show = 1;
		},



		//upload: false,
		
		upload: {
			url: $("#ajax_form_message_and_file").attr("action"),
            data: {
				"csrf_token": csrf_token, 
				"no_uploads": 1, 
				"save": 1, 
				"parent_id": $("#ajax_form_message_and_file input[name=parent_id]").val(), 
				"message_to": $("#ajax_form_message_and_file input[name=message_to_hidden]").val()
			},
			type: 'POST',
            enctype: 'multipart/form-data',
            start: false,
            synchron: true,
            beforeSend: function(item, listEl, parentEl, newInputEl, inputEl) {
			
			},
            onSuccess: function(data, item) {
				setTimeout(function() {
					item.html.find('.progress-holder').hide();
					item.renderThumbnail();
				}, 400);
            },
            onError: function(item) {
				item.html.find('.progress-holder').hide();
				item.html.find('.fileuploader-item-icon i').text('Failed!');
            },
            onProgress: function(data, item) {
                var progressBar = item.html.find('.progress-holder');
				
                if(progressBar.length > 0) {
                    progressBar.show();
                    progressBar.find('.fileuploader-progressbar .bar').width(data.percentage + "%");
                }
            }
        },

		dragDrop: {
			container: '.fileuploader-theme-thumbnails'
		},
			
		captions: captions,
	});


	api_file_uploader = filer_uploader;

	setInterval(function(){
		var count_elem = $(".fileuploader-items-list .fileuploader-item").length;
		if(count_elem>=1) {
			$(".fileuploader-thumbnails-input").hide();
		} else {
			$(".fileuploader-thumbnails-input").show();
		}
	 },50);


	$(".more_uploads").on('click',function() {
		$.fileuploader.getInstance(api_file_uploader).open();
		return false;
	});


	$('.modal').on('hidden.bs.modal', function () {
		form_is_show = 0;
	});
	

	$(".ajax_form_message_and_file").off();
	$(".ajax_form_message_and_file").on('submit',function(e) {
		e.preventDefault();	

		
		var message = document.getElementById('ajax_form_message_and_file');
		var form = new FormData(message);
		form.append("message_desc", $(this).find("textarea[name=message_desc]").val());
		form.append("parent_id", $(this).find("input[name=parent_id]").val());
		form.append("csrf_token", csrf_token);
		form.append("need_text", 1);
		form.append("save", 1);


		/*var file=document.getElementById("upload_file");
		var upload_file=file.files[0];
		form.append("user_photo", upload_file);*/

		var api = $.fileuploader.getInstance(api_file_uploader);
		var files = api.getChoosedFiles();
		   
		for(var i = 0; i<files.length; i++) {
			var file = files[i];
			form.append('upload_file[]', file.file);
		}

		if(files.length>=1) {
			form.append("has_file", 1);
		}

		var target_form = $(this).attr("action");
		var stop_reset = $(this).attr("data-reset"); // if need reset form
		var this_form = $(this);

		if(submit_ajax_form == 1) {
			return true;
		}

		this_form.find(".error-message").html("");
		this_form.find(".send-message").removeClass("hidden").show();

		$.ajax({
			url: target_form,
			
			data: form,
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',

			success: function( data ) {
				this_form.find(".send-message").hide();

				show_alert(data.status, data.respons);

				if(data.status == 'error') {
					this_form.find(".error-message").html(data.respons);
				}

				if(data.status == 'success' && stop_reset==1) {
					$(".close_option").trigger("click");

					this_form.find("textarea[name=message_desc]").val("");

					$.fileuploader.getInstance(api_file_uploader).destroy();
					message_uploader_core('.filer-uploader');


					$(".chat-offers__footer").removeClass("answer");
					$(".ajax_form_message input[name=parent_id]").val("");
					$(".chat-offers__input-real").css("height", "30px");
					$("#content-4-1 ul").first().show();
					autosize.update($(".chat-offers__input-real"));

					$(".modal-header .close").click();
				}

				//message_core_reload();

				//this_form.find(".actions input").attr("disabled", false);
				
				return false;
			}
		});

		return false;
	});
},

init_update_chat_answer = function () {

	$("#content-4-1 .message_simple").off();
	$("#content-4-1 .message_simple").not(".message_bot").on('click',function() {
		var this_message = $(this);

		/*if($(this).hasClass("active")) {
			$(this).removeClass("active");
			$(".message_option").hide();
		} else {*/
			$("#content-4-1 .message_simple").removeClass("active");
			$(this).addClass("active");

			$(".message_option").show();

			$(".message_option a").off();
			$(".message_option a").on('click',function() {
				var type = $(this).attr("data-type");

				switch (type) {
					case "1":
						//var text_copy = getSelectionText();
						var text_copy = $(".message_simple.active .message_simple_text").text();
						if(text_copy) {
							$(".chat-offers__input-real").val($(".chat-offers__input-real").val() + "\n``"+text_copy+"``\n");
							autosize.update($(".chat-offers__input-real"));
						}

					break;

					case "2":
						var parent_id = $(".message_simple.active").attr("data-message");
						$(".ajax_form_message input[name=parent_id]").val(parent_id);

						autosize.destroy($(".chat-offers__input-real"));
						$(".chat-offers__input-real").css("height", "84px");

						$(".chat-offers__footer").addClass("answer");
						var name = this_message.find(".message_simple_info h4").first().text();
						var date = this_message.find(".message_simple_info .message_date").first().text();
						$(".chat-offers__footer .message_answer span").html("<strong>"+name + "</strong> " + date);

						$(".chat-offers__input-real").focus();
					break;

					case "3":

						$("#content-4-1 ul").first().hide();

						$(".chat-message-owner .contact-item").off();
						$(".chat-message-owner .contact-item").on('click',function() {

							var parent_id = $(".message_simple.active").attr("data-message");
							$(".ajax_form_message input[name=parent_id]").val(parent_id);

							var save_link = $(this).attr("href");
							$(".ajax_form_message").attr("action", save_link);

							autosize.destroy($(".chat-offers__input-real"));
							$(".chat-offers__input-real").css("height", "84px");

							$(".chat-offers__footer").addClass("answer");
							var name = $(this).attr("data-name");
							$(".chat-offers__footer .message_answer span").html("<strong>"+name + "</strong> ");

							return false;
						});

					break;
				}

				return false;
			});


			$(".close_option").off();
			$(".close_option").on('click',function() {
				$(".message_simple").removeClass("active");
				$(".message_option").hide();
				$(".chat-message-owner .contact-item").off();
				$(".ajax_form_message").attr("action", $(".ajax_form_message").attr("data-default"));
				$(".message_answer a").trigger("click");
				$("#content-4-1 ul").first().show();
				return false;
			});

			$(".message_answer a").off();
			$(".message_answer a").on('click',function() {
				$(".chat-offers__footer").removeClass("answer");
				$(".ajax_form_message input[name=parent_id]").val("");
				autosize($(".chat-offers__input-real"));
				$("#content-4-1 ul").first().show();
				return false;
			});
		/*}*/
	});
}

/*getSelectionText = function () {
	var txt = '';
	if (txt = window.getSelection) { // Не IE, используем метод getSelection
		txt = window.getSelection().toString();
	} else { // IE, используем объект selection
		txt = document.selection.createRange().text;
	}
	return txt;
},*/

// обновление активного чата
update_chatTimeout = null,
init_update_chat = function () {
	update_chatTimeout = null;

	function load_message_in_chat(timestamp) {
		if (update_chatTimeout != null) clearTimeout(update_chatTimeout); // очистка таймера

		update_chatTimeout = setTimeout(function() {
	        update_chatTimeout = null;

			// загрузка новых сообщений в активный чат
			var message_to = $(".messages-chat ul").attr("data-to");
			var message_last = $(".messages-chat ul .message_simple:last").attr("data-message");
			var need_scroll = 0;

			$.ajax({
				url: ave_host+'/message/reload/',
				data: {message_to: message_to, message_last: message_last, csrf_token: csrf_token},
				success: function( data ) {


					$(".messages-chat ul").attr("data-all", data.count_all);
					if(data.message_list) {
						$(".message_empty").remove();

						$.each(data.message_list, function( index, value ) {
							// првоеряем на наличие на странице
							if($(".messages-chat ul:first").find(".message_simple_"+value.message_id).length < 1) {
								$(".messages-chat ul:first").append(value.message_html_block);
								need_scroll = 1;
							}
							// првоеряем на наличие на странице

							// проверяем статус
							if($(".messages-chat ul:first").find(".message_simple_"+value.message_id).find("li.is_seen").attr("data-open") != value.message_open && value.message_open == 1) {

								$(".messages-chat ul:first").find(".message_simple_"+value.message_id).find("li.is_seen").attr("data-open", value.message_open);

								$(".messages-chat ul:first").find(".message_simple_"+value.message_id).find("li.is_seen").text($(".messages-chat ul:first").find(".message_simple_"+value.message_id).find("li.is_seen").attr("data-seen"));
							}
							// проверяем статус
						});

						if(need_scroll == 1) {
							if(data.count_all != $("#content-4-1 .message_simple").length) {
								$(".load_more_message").show();
							}

							$("#content-4-1").mCustomScrollbar("update");
							$("#content-4-1").mCustomScrollbar("scrollTo", ".messages-chat .message_simple:last .last_scroll");
						}

						need_scroll = 0;
						init_update_chat_answer();
					}

					load_message_in_chat(data.timestamp);
				}
			});
			// загрузка новых сообщений в активный чат



		}, 5000);
	}
	load_message_in_chat();
},
// обновление активного чата

init_first_update = 0,
init_message = function (timestamp) {
	globalTimeout = null;

	if(check_message == 1) {

		function load_message(timestamp) {
			if (globalTimeout != null) clearTimeout(globalTimeout); // очистка таймера

			globalTimeout = setTimeout(function() {
		        globalTimeout = null;

				var chat = false;
				var search_message = "";
				if($(".chat-message-owner").length >= 1) {
					chat = true;
					search_message = $("input[name=search_message]").val();
				}
				//console.log(chat);

				$.ajax({
					url: ave_path+'site.php?do=siteMessage&sub=load_new_message',
					data: {'timestamp': timestamp, search_message: search_message, csrf_token: csrf_token},
					success: function( data ) {
						
						if(data.message_count && $('.get_message span').length) {
							globalClear = null;

							var curent_count = $('.get_message span').text();
							if(data.message_count != curent_count) {

								if(init_first_update == 1) {
									$('#playAudio')[0].play();
								} else {
									init_first_update = 1;
								}

								$(".get_message span").text("+"+data.message_count);
							}
						} else {
							$(".get_message span").text("");
						}

						if(chat == true && data.list_user_message) {

							$.each(data.list_user_message, function( index, value ) {
								// првоеряем на наличие на странице
								if($(".chat-message-owner a.contact-item-"+value.message_user.id).length < 1) {
									$(".chat-message-owner").prepend(value.message_html);
								} else {

									var test_count = $(".chat-message-owner a.contact-item-"+value.message_user.id).find(".message-info p strong").attr("data-seen");
									if(value.unseen > 0 && parseInt(test_count) != value.unseen) {
										//$(".chat-message-owner a.contact-item-"+value.message_user.id).find(".message-info p strong").addClass("have_unseen");
										//$(".chat-message-owner a.contact-item-"+value.message_user.id).find(".message-info p span").text("+"+value.unseen);
										$(".chat-message-owner a.contact-item-"+value.message_user.id).remove();
										$(value.message_html).insertBefore(".chat-message-owner a.contact-item:first");
									} else if (parseInt(test_count) == value.unseen) {

									} else {
										$(".chat-message-owner a.contact-item-"+value.message_user.id).find(".message-info p span").text("");

										$(".chat-message-owner a.contact-item-"+value.message_user.id).find(".message-info p strong").removeClass("have_unseen");
									}

									$(".chat-message-owner a.contact-item-"+value.message_user.id).find(".short-msg").text(value.message_desc);
								}
								// првоеряем на наличие на странице
							});

							$("#content-4").mCustomScrollbar("update");
							//$("#content-4").height($('.js-chat-management').height());
						}

						load_message(data.timestamp);
					}
				});
			}, 5000);
		}
		load_message();
	}
},

message_core_reload = function () {



},

search_order_core = function () {
	/*$(".order_change a").on('click',function() {

		var name_field = $(this).attr("data-name");
		var value_field = $(this).attr("data-value");

		$(".hide_search_field input").value("");

		if(name_field && value_field) {
			$(".hide_search_field input[name="+name_field+"]").value(value_field);
		}

		return false;
	});*/
},

translations_core = function () {

	$(".translations_search_perfomens").on('click',function() {

		var document_perfomens = $("select.translations_perfomens").val();
		var document_id = $(this).attr("data-document");
		var this_error = $(this).next();
		this_error.addClass("hidden").text();

		$.ajax({
			url: ave_host+'/translations/document-'+document_id+'/',
			data: {document_perfomens: document_perfomens, document_id: document_id, csrf_token: csrf_token},
			success: function( data ) {
				this_error.removeClass("hidden").text(data.respons);
			}
		});

		return false;
	});

	$(".translations_search_status").on('click',function() {

		var document_status = $("input[name=document_status]:checked").val();
		var document_id = $(this).attr("data-document");
		var this_error = $(this).next();
		this_error.addClass("hidden").text();

		$.ajax({
			url: ave_host+'/translations/document-'+document_id+'/',
			data: {document_status: document_status, document_id: document_id, csrf_token: csrf_token},
			success: function( data ) {
				this_error.removeClass("hidden").text(data.respons);
			}
		});

		return false;
	});

	$(".translations_respons_add").on('click',function() {

		var perfomens_desc = $("textarea[name=perfomens_desc]").val();
		var document_id = $(this).attr("data-document");
		var this_error = $(this).next();
		this_error.addClass("hidden").text();

		$.ajax({
			url: ave_host+'/translations/document-'+document_id+'/',
			data: {perfomens_desc: perfomens_desc, document_id: document_id, csrf_token: csrf_token},
			success: function( data ) {
				this_error.removeClass("hidden").text(data.respons);
			}
		});

		return false;
	});

},

resume_work_core = function () {
	$(".delete_work").off();
	$(".delete_work").on('click',function() {
		$(this).parents(".work").remove();
		resume_work_core_rebild();
		return false;
	});

	$(".delete_edu").off();
	$(".delete_edu").on('click',function() {
		$(this).parents(".edu").remove();
		return false;
	});

	$(".in_now_work").off();
	$(".in_now_work").on('click',function(){
		if($(this).is(":checked")) {
			$(this).parents(".work_start_block").next().find("select, input").prop("disabled", true);
		} else {
			$(this).parents(".work_start_block").next().find("select, input").prop("disabled", false);
		}
	});

	$(".work_start_first input").on('change',function() {
		
		if($(this).is(":checked")) {
			$(".work_list").each(function(index) {
				if(index != 0) {
					$(this).addClass("hidden");
					$(".copy_work, .delete_work").addClass("hidden");
				}
			});
		} else {
			$(".work_list").each(function(index) {
				if(index != 0) {
					$(this).removeClass("hidden");
					$(".copy_work, .delete_work").removeClass("hidden");
				}
			});
		}

		resume_work_core_rebild();
	});

	resume_work_core_rebild();
},

resume_work_core_rebild = function () {
	$(".work_start_first").each(function(index) {
		if(index == 0) {
			$(this).removeClass("hidden");
		} else {
			$(this).addClass("hidden");
		}
	});


	if($(".work_start_first input").eq(0).is(":checked")) {
		$(".work_list").each(function(index) {
			if(index != 0) {
				$(this).addClass("hidden");
				
			}
			$(".copy_work, .delete_work").addClass("hidden");
		});
	} else {
		$(".work_list").each(function(index) {
			if(index != 0) {
				$(this).removeClass("hidden");
				
			}
			$(".copy_work, .delete_work").removeClass("hidden");
		});
	}

},

resume_core = function () {
	$('.selectpicker').selectpicker({'style':'btn-default', 'size': 5});

	/*$("select[name=user_country]").on('change',function() {
		var country_id = $(this).val();

		$.ajax({
			url: ave_host+'/city-load/',
			data: {country_id: country_id, csrf_token: csrf_token},
			success: function( data ) {
				$("select[name=user_city]").html("");
				$.each(data.respons, function( index, value ) {
					$("select[name=user_city]").append("<option value='"+value.id+"' data-utm='"+value.utm+"'>"+value.title+"</option>");
				});
			}
		});
	});*/

	work_country_select_core();

	$(".copy_work").on('click',function() {
		var form_clone = $(".work_copy li").clone().removeClass("work_copy");
		$(".work_list").append(form_clone);

		work_country_select_core();
		$(".work_copy").find("input[type=text], textarea").val("");


		$(".work_insert").prev().find(".delete_work").parent().removeClass("hidden");
		resume_work_core();

		return false;
	});

	$(".copy_edu").on('click',function() {
		var form_clone = $(".edu_copy li").clone().removeClass("edu_copy");
		$(".edu_list").append(form_clone);

		work_country_select_core();
		$(".edu_copy").find("input[type=text], textarea").val("");


		$(".edu_insert").prev().find(".delete_edu").parent().removeClass("hidden");
		resume_work_core();

		return false;
	});

	$("input[name=resume_money_none]").on('change',function() {
		if($(this).is(":checked")) {
			$(".resume_money_none_status").find("input[type=text], select").attr("disabled", true);
		} else {
			$(".resume_money_none_status").find("input[type=text], select").attr("disabled", false);
		}
	});

	$("input[name=resume_ready_to_move]").on('change',function() {
		var status = this.value;
		if(status == 40) {
			$("select[name=user_country], select[name=user_city]").attr("disabled", true);
		} else {
			$("select[name=user_country], select[name=user_city]").attr("disabled", false);
		}
	});

	$(".add_resume_site").on('click',function() {
		var form_clone = $(this).prev().clone().val("");
		form_clone.insertBefore($(this));
		return false;
	});

	$(".crop-image-btn, .crop-image-photo-btn").on('click',function() {
		var check = $(this).attr("data-photo");
		var photo = $(this).attr("data-img");
		var type = $(this).attr("data-type");
		$("#crop-image #thumbnail img").attr("src", photo);
		$("#crop_photo_type").val(type);

		if(check == 1) {
			$("#crop-image").modal('show');

			$("#crop-image").on('shown.bs.modal', function (e) {
				var ias = $('#thumbnail').imgAreaSelect({
					 aspectRatio: "3:4",
					 instance: true,
					 minHeight:250,
					 minWidth:200,
					 show: true,
					 /*maxHeight:250,
					 maxWidth:200,*/
					 parent: ".crop_container",
					 onInit: function (img, selection) {
						 ias.setOptions({ show: true });
						 ias.setSelection(0, 0, 200, 250, false);
						 ias.update();
					},
					 onSelectEnd: function (img, selection) {
						 $('input[name="x1"]').val(selection.x1);
						 $('input[name="y1"]').val(selection.y1);
						 $('input[name="x2"]').val(selection.x2);
						 $('input[name="y2"]').val(selection.y2);
					 }
				});
			});


		}
	});
	$("#upload-photo").on("change",function(e) {
		e.preventDefault();

		var form = new FormData(this);
		var file=document.getElementById("upload-photo");
		var upload_file=file.files[0];
		form.append("user_photo", upload_file);
		form.append("csrf_token", csrf_token);
		form.append("uploads_photo", 1);
		form.append("save", 1);

		$.ajax({
			url: ave_host+'/resume/',
			data: form,
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function( data ) {
				if(data.respons) {

					show_alert(data.status, data.respons);
					$(".crop-image-btn img").attr("src", data.photo_small);
					$(".crop-image-btn").attr("data-img", data.photo_big);
					$(".crop-image-btn").attr("data-photo", 1);
					$(".crop-image-btn").trigger("click");
				}
			}
		});

		return false;
	});
	// загрузка аватара

	$(".add_more_level_list").on('click',function(e) {
		e.stopPropagation();
		$(".not_empty_level_list select").selectpicker('destroy');

		var first_select = $(".empty_level_list .resume_skill_lang").val();
		var two_select = $(".empty_level_list .resume_skill_lang_level").val();

		var empty_level_list = $(".empty_level_list .not_empty_level_list").clone();
		//empty_level_list.addClass("not_empty_level_list");
		empty_level_list.find("select").find("option:first").prop("selected", true);
		empty_level_list.find(".close").removeClass("hidden");
		if(first_select  && two_select) {
			
			if($(".check_"+first_select+"_"+two_select).length == 1) {
			} else {
				empty_level_list.insertBefore($(".add_more_level_list"));

				$(".add_more_level_list").prev().prev().find(".mb-10").addClass("check_"+first_select+"_"+two_select);
		
				$(".not_empty_level_list .close").off();
				$(".not_empty_level_list .close").on('click', function() {
					$(this).parents(".not_empty_level_list").remove();
				});

				$(".not_empty_level_list").each(function() {
					$(this).find(".close").removeClass("hidden");
				});
				$(".not_empty_level_list").last().find(".close").addClass("hidden");
			}
		}
		
		$(".not_empty_level_list select").selectpicker({'style':'btn-default', 'size': 5});

		return false;
	});

	$(".not_empty_level_list .close").on('click', function() {
		$(this).parents(".not_empty_level_list").remove();
	});

	$(".resume_edu_title").on('change',function(e) {
		e.stopPropagation();
		var this_value = $(this).val();

		$(this).parents(".edu").find(".form-group").not(".no_hidden").addClass("hidden");
		$(this).parents(".edu").find(".show_"+this_value).not(".no_hidden").removeClass("hidden");
	});


	resume_work_core();
},

search_writer_core = function () {

	$.ajax({
		url: ave_host+'/search-writer/',
		data: {csrf_token: csrf_token},
		success: function( data ) {

			if(data.status == 'success') {

				$('.modal').modal('hide');

				var modal_clone = $('#ajax_modal').clone().attr("id","");

				modal_clone.modal('show');
				modal_clone.find('.modal-title').html(data.title);
				modal_clone.find('.modal-body').html(data.html);

				$(document).on('hidden.bs.modal', '.modal', function (e) {
					e.preventDefault();
					$('.modal:visible').length && $(document.body).addClass('modal-open');
					$(this).remove();
				});
				modal_clone.insertBefore("#ajax_modal");

				init_refresh_script();
				init_form();

			}

			return false;

		}
	});



},

jobs_core = function () {
	$('.selectpicker').selectpicker({'style':'btn-default', 'size': 5});

	/*$("select[name=jobs_country]").on('change',function() {
		var country_id = $(this).val();

		$.ajax({
			url: ave_host+'/city-load/',
			data: {country_id: country_id, csrf_token: csrf_token},
			success: function( data ) {
				$("select[name=jobs_city]").html("");
				$.each(data.respons, function( index, value ) {
					$("select[name=jobs_city]").append("<option value='"+value.id+"' data-utm='"+value.utm+"'>"+value.title+"</option>");
				});
			}
		});
	});*/

	$("select[name=jobs_level_education]").on("change",function(e) {
		e.stopPropagation();
		var last = $("select[name=jobs_level_education] option").last().val();
		var this_value = $(this).val();

		if(last == this_value) {
			$(".jobs_level_education_ext").removeClass("hidden");
		} else {
			$(".jobs_level_education_ext").addClass("hidden");
		}
		return false;
	});

	$(".add_more_level_list").on('click',function() {
		var empty_level_list = $(".empty_level_list .not_empty_level_list").clone();
		//empty_level_list.addClass("not_empty_level_list");
		empty_level_list.find("select").find("option:first").prop("selected", true);
		empty_level_list.find(".close").removeClass("hidden");
		empty_level_list.insertBefore($(".add_more_level_list"));

		$(".not_empty_level_list .close").off();
		$(".not_empty_level_list .close").on('click', function() {
			$(this).parents(".not_empty_level_list").remove();
		});

		$(".not_empty_level_list").each(function() {
			$(this).find(".close").removeClass("hidden");
		});
		$(".not_empty_level_list").last().find(".close").addClass("hidden");

		return false;
	});

	$(".not_empty_level_list .close").on('click', function() {
		$(this).parents(".not_empty_level_list").remove();
	});
},

work_country_select_core = function () {
	$("select.work_country").off();
	$("select.work_country").on('change',function() {
		var country_id = $(this).val();
		var this_select = $(this);

		$.ajax({
			url: ave_host+'/city-load/',
			data: {country_id: country_id, csrf_token: csrf_token},
			success: function( data ) {
				this_select.parent().parent().parent().next().find("select.work_city").html("");
				$.each(data.respons, function( index, value ) {
					this_select.parent().parent().parent().next().find("select.work_city").append("<option value='"+value.id+"' data-utm='"+value.utm+"'>"+value.title+"</option>");
				});
			}
		});
	});
},

profile_core = function () {
	$('.selectpicker').selectpicker({'style':'btn-default', 'size': 5});

	$(".remove_var_lang").off();
	$(".remove_var_lang").on('click',function() {
		$(this).parent().remove();
		return false;
	});
 
	// загрузка аватара
	$(".crop-image-btn, .crop-image-photo-btn").on('click',function() {
		var check = $(this).attr("data-photo");
		var photo = $(this).attr("data-img");
		var type = $(this).attr("data-type");
		$("#crop-image #thumbnail img").attr("src", photo);
		$("#crop_photo_type").val(type);

		if(check == 1) {
			$("#crop-image").modal('show');

			$("#crop-image").on('shown.bs.modal', function (e) {
				var ias = $('#thumbnail').imgAreaSelect({
					 aspectRatio: "3:4",
					 instance: true,
					 minHeight:250,
					 minWidth:200,
					 show: true,
					 /*maxHeight:250,
					 maxWidth:200,*/
					 parent: ".crop_container",
					 onInit: function (img, selection) {
						 ias.setOptions({ show: true });
						 ias.setSelection(0, 0, 200, 250, false);
						 ias.update();
					},
					 onSelectEnd: function (img, selection) {
						 $('input[name="x1"]').val(selection.x1);
						 $('input[name="y1"]').val(selection.y1);
						 $('input[name="x2"]').val(selection.x2);
						 $('input[name="y2"]').val(selection.y2);
					 }
				});
			});


		}
	});
	$("#upload-photo").on("change",function(e) {
		e.preventDefault();

		var form = new FormData(this);
		var file=document.getElementById("upload-photo");
		var upload_file=file.files[0];
		form.append("user_photo", upload_file);
		form.append("csrf_token", csrf_token);
		form.append("uploads_photo", 1);
		form.append("save", 1);

		$.ajax({
			url: ave_host+'/profile/',
			data: form,
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function( data ) {
				if(data.respons) {

					show_alert(data.status, data.respons);
					$(".crop-image-btn img").attr("src", data.photo_small);
					$(".crop-image-btn").attr("data-img", data.photo_big);
					$(".crop-image-btn").attr("data-photo", 1);
					$(".crop-image-btn").trigger("click");
				}
			}
		});

		return false;
	});
	// загрузка аватара

	// загрузка логотипа компании
	$("#company-upload-photo").on("change",function(e) {
		e.preventDefault();

		var form = new FormData(this);
		var file=document.getElementById("company-upload-photo");
		var upload_file=file.files[0];
		form.append("company_photo", upload_file);
		form.append("csrf_token", csrf_token);
		form.append("company_photo", 1);
		form.append("save", 1);

		$.ajax({
			url: ave_host+'/profile/',
			data: form,
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function( data ) {
				if(data.respons) {
					$(".crop-image-photo-btn .company_photo_empty").hide();

					show_alert(data.status, data.respons);
					$(".crop-image-photo-btn img").attr("src", data.photo_small).removeClass("hidden");
					$(".crop-image-photo-btn").attr("data-img", data.photo_big);
					$(".crop-image-photo-btn").attr("data-photo", 1);
					$(".crop-image-photo-btn").trigger("click");
				}
			}
		});

		return false;
	});
	// загрузка логотипа компании

	$(".add_lang_var").on('click',function() {
		var lang_from = $(".add_lang_var_field select[name=lang_from_temp]").val();
		var lang_from_title = $(".add_lang_var_field select[name=lang_from_temp]").find("option:selected").text();
		var lang_to = $(".add_lang_var_field select[name=lang_to_temp]").val();
		var lang_to_title = $(".add_lang_var_field select[name=lang_to_temp]").find("option:selected").text();

		var lang_arr = [];
		$(".lang_var p").each(function() {
			var var_lang_from = $(this).find("input.var_lang_from").val();
			var var_lang_to = $(this).find("input.var_lang_to").val();

			lang_arr.push([var_lang_from, var_lang_to]);
		});

		for (var i = 0; i < lang_arr.length; i++) {
			if((lang_arr[i][0] == lang_from && lang_arr[i][1] == lang_to) || (lang_arr[i][0] == lang_to && lang_arr[i][1] == lang_from) || lang_from==lang_to) {
				return false;
			}
		}

		$(".lang_var").append('<p>'+lang_from_title+' - '+lang_to_title+'<button type="button" class="close remove_var_lang" data-dismiss="modal" aria-hidden="true">x</button><input type="hidden" name="var_lang_from[]" value="'+lang_from+'"><input type="hidden" name="var_lang_to[]" value="'+lang_to+'"></p>');

		$(".remove_var_lang").off();
		$(".remove_var_lang").on('click',function() {
			$(this).parent().remove();
			return false;
		});
		return false;
	});


	$(".remove_var_services").off();
	$(".remove_var_services").on('click',function() {
		$(this).parent().parent().remove();

		if($(".remove_var_services").length < 1) {
			$(".serv_var_empty").removeClass("hidden");
		}

		return false;
	});

	// форма услуг 1
	$("select[name=serv_type_service_temp].service_temp_1").on('change',function() {
		var this_value = $(this).val();
		if(this_value == 55) {
			$(".dif_time1 select[name=serv_time_temp_1]").addClass("hidden");
			$(".dif_time1 select[name=serv_time_temp_2]").removeClass("hidden");
		} else {
			$(".dif_time1 select[name=serv_time_temp_2]").addClass("hidden");
			$(".dif_time1 select[name=serv_time_temp_1]").removeClass("hidden");
		}
	});
	// форма услуг 1

	// форма услуг 2
	$("select[name=serv_type_service_temp].service_temp_2").on('change',function() {
		var this_value = $(this).val();
		if(this_value == 47 || this_value == 48) {
			$(this).parent().parent().next().removeClass("hidden");
		} else {
			$(this).parent().parent().next().addClass("hidden");
		}
	});
	// форма услуг 2


	$(".add_serv").on('click',function() {
		var this_form = $(this).parent().parent().parent().parent().parent();
		this_form.find(".has-error").removeClass("has-error");
		this_form.find(".error-message .show1, .error-message .show2").addClass("hidden");


		var lang_from = this_form.find(".add_serv_field select[name=serv_lang_from_temp]").val();
		var lang_from_title = this_form.find(".add_serv_field select[name=serv_lang_from_temp]").find("option:selected").text();
		var lang_to = this_form.find(".add_serv_field select[name=serv_lang_to_temp]").val();
		var lang_to_title = this_form.find(".add_serv_field select[name=serv_lang_to_temp]").find("option:selected").text();

		var serv_service = this_form.find(".add_serv_field input[name=serv_service_temp]").val();

		var serv_type_service = this_form.find(".add_serv_field select[name=serv_type_service_temp]").val();
		var serv_type_service_title = this_form.find(".add_serv_field select[name=serv_type_service_temp]").find("option:selected").text();
		var serv_currency = this_form.find(".add_serv_field select[name=serv_currency_temp]").val();
		var serv_currency_title = this_form.find(".add_serv_field select[name=serv_currency_temp]").find("option:selected").text();


		var serv_time = serv_place = 0;
		var serv_time_title = serv_place_title = "";
		if(serv_service == 1) {
			if(serv_type_service == 55) {
				serv_time = this_form.find(".add_serv_field select[name=serv_time_temp_2]").val();
				serv_time_title = this_form.find(".add_serv_field select[name=serv_time_temp_2]").find("option:selected").text();
			} else {
				serv_time = this_form.find(".add_serv_field select[name=serv_time_temp_1]").val();
				serv_time_title = this_form.find(".add_serv_field select[name=serv_time_temp_1]").find("option:selected").text();
			}
		}

		if(serv_service == 2) {
			if(serv_type_service == 47 || serv_type_service == 48) {
				serv_place = this_form.find(".add_serv_field select[name=serv_place_temp]").val();
				if(serv_place) {
					serv_place_title = "<br>" + this_form.find(".add_serv_field select[name=serv_place_temp]").find("option:selected").text();
				}
			}

			serv_time = this_form.find(".add_serv_field select[name=serv_time_temp_1]").val();
			serv_time_title = this_form.find(".add_serv_field select[name=serv_time_temp_1]").find("option:selected").text();
		}

		if(serv_service == 3) {
			serv_time = this_form.find(".add_serv_field select[name=serv_time_temp_1]").val();
			serv_time_title = this_form.find(".add_serv_field select[name=serv_time_temp_1]").find("option:selected").text();
		}

		var serv_coast = this_form.find(".add_serv_field input[name=serv_coast_temp]").val();


		if(lang_from==lang_to || lang_from<1 || lang_to<1) {
			this_form.find(".add_serv_field select[name=serv_lang_from_temp]").parent().addClass("has-error");
			this_form.find(".add_serv_field select[name=serv_lang_to_temp]").parent().addClass("has-error");
			this_form.find(".error-message .show1").removeClass("hidden");
		}
		if(serv_type_service==0) {
			this_form.find(".add_serv_field select[name=serv_type_service_temp]").parent().addClass("has-error");
			this_form.find(".error-message .show1").removeClass("hidden");
		}
		if(serv_currency==0) {
			this_form.find(".add_serv_field select[name=serv_currency_temp]").parent().addClass("has-error");
			this_form.find(".error-message .show1").removeClass("hidden");
		}
		if(serv_time==0) {
			this_form.find(".add_serv_field select[name=serv_time_temp_2]").parent().addClass("has-error");
			this_form.find(".add_serv_field select[name=serv_time_temp_1]").parent().addClass("has-error");
			this_form.find(".error-message .show1").removeClass("hidden");
		}
		if(serv_coast==0) {
			this_form.find(".add_serv_field input[name=serv_coast_temp]").parent().addClass("has-error");
			this_form.find(".error-message .show1").removeClass("hidden");
		}
		/*if(serv_service == 2 && serv_place==0) {
			this_form.find(".add_serv_field select[name=serv_place_temp]").parent().addClass("has-error");
			this_form.find(".error-message .show1").removeClass("hidden");
		}*/

		var control_class = serv_type_service+'_'+serv_place+'_'+lang_from+'_'+lang_to;
		var control_class2 = serv_type_service+'_'+serv_place+'_'+lang_to+'_'+lang_from;

		var check = false;
		if(serv_service == 1) {
			if(
				!this_form.find(".serv_var li").is("."+control_class) && !$(".carousel_lvl_1 li").is("."+control_class) &&
				!this_form.find(".serv_var li").is("."+control_class2) && !$(".carousel_lvl_1 li").is("."+control_class2) 
			) {
				check = true;
			}
		}
		if(serv_service == 2) {
			if(
				(((serv_type_service == 47 || serv_type_service == 48) && serv_place!=0) || serv_place == 0) && !this_form.find(".serv_var li").is("."+control_class) && !$(".carousel_lvl_2 li").is("."+control_class) &&
				(((serv_type_service == 47 || serv_type_service == 48) && serv_place!=0) || serv_place == 0) && !this_form.find(".serv_var li").is("."+control_class2) && !$(".carousel_lvl_2 li").is("."+control_class2) 
			) {
				check = true;
			}
		}
		if(serv_service == 3) {
			if(
				!this_form.find(".serv_var li").is("."+control_class) && !$(".carousel_lvl_3 li").is("."+control_class) &&
				!this_form.find(".serv_var li").is("."+control_class2) && !$(".carousel_lvl_3 li").is("."+control_class2) 
			) {
				check = true;
			}
		}

		if(check === true) {
			if(
				lang_from!=lang_to && serv_service!=0 && serv_type_service!=0 && serv_currency!=0 && serv_time!=0 && serv_coast!=0
			) {
				$(".serv_var_empty").addClass("hidden");
	
				this_form.find(".serv_var_empty").remove();
	
				var insert_html = '<li class="'+control_class+'"><strong>'+serv_type_service_title+' <button type="button" class="close-btn btn-link remove_var_services" data-dismiss="modal" aria-hidden="true"><img src="'+ave_path+'assets/site/template/images/close-button.png" alt=""></button></strong>'+serv_place_title+'<br>'+serv_coast+' '+serv_currency_title+' / '+serv_time_title+'<br>'+lang_from_title+' - '+lang_to_title+'<input type="hidden" name="serv_lang_from[]" value="'+lang_from+'"><input type="hidden" name="serv_lang_to[]" value="'+lang_to+'"><input type="hidden" name="serv_service[]" value="'+serv_service+'"><input type="hidden" name="serv_type_service[]" value="'+serv_type_service+'"><input type="hidden" name="serv_currency[]" value="'+serv_currency+'"><input type="hidden" name="serv_time[]" value="'+serv_time+'"><input type="hidden" name="serv_place[]" value="'+serv_place+'"><input type="hidden" name="serv_coast[]" value="'+serv_coast+'"></li>';
	
				this_form.find(".serv_var").append(insert_html);
			} else {
				this_form.find(".error-message .show1").removeClass("hidden");
			}
		} else {
			this_form.find(".error-message .show2").removeClass("hidden");
		}

		

		$(".remove_var_services").off();
		$(".remove_var_services").on('click',function() {
			$(this).parent().parent().remove();

			if($(".remove_var_services").length < 1) {
				$(".serv_var_empty").removeClass("hidden");
			}

			return false;
		});

		return false;
	});


	$(".add_block_place").on('click',function() {
		/*$('select.user_country_place').selectpicker('destroy');
		$('select.user_city_place').selectpicker('destroy');*/

		var first_block = $(this).parent().find(".place_block:first").clone();

		first_block.find("input").attr("name", "place_text"+count_block+"[]").val("");
		first_block.find(".remove_block_place").removeClass("hidden");
		/*first_block.find("select[name=user_country_place] option:first").prop("change",true);
		first_block.find("select[name=user_city_place]").html("");*/

		first_block.insertBefore($(this));

		var count_block = 1;
		$(".place_block").each(function(  ) {
			$(this).find("select.user_country_place").prop("name", "user_country_place["+count_block+"]");
			$(this).find("select.user_city_place").prop("name", "user_city_place["+count_block+"]");
			$(this).find("input[type=text]").prop("name", "place_text["+count_block+"][]");

			count_block++;
		});

		/*$('select.user_country_place').selectpicker({'style':'btn-default', 'size': 5});
		$('select.user_city_place').selectpicker({'style':'btn-default', 'size': 5});*/

		init_refresh_script();

		$(".remove_block_place").removeClass("hidden");
		if($(".remove_block_place").length<=1) {
			$(".remove_block_place").addClass("hidden");
		}

		$(".remove_block_place").off();
		$(".remove_block_place").on('click',function() {
			$(this).parent().remove();

			$(".remove_block_place").removeClass("hidden");
			if($(".remove_block_place").length<=1) {
				$(".remove_block_place").addClass("hidden");
			}
			return false;
		});

		return false;
	});

	$(".remove_block_place").removeClass("hidden");
	if($(".remove_block_place").length<=1) {
		$(".remove_block_place").addClass("hidden");
	}
	$(".remove_block_place").on('click',function() {
		$(this).parent().remove();

		$(".remove_block_place").removeClass("hidden");
		if($(".remove_block_place").length<=1) {
			$(".remove_block_place").addClass("hidden");
		}
		return false;
	});

	$("select[name=graph_work]").on("change",function() {
		var this_value = $(this).val();

		if(this_value == 1) {
			$("select[name=graph_country], select[name=graph_city]").parent().parent().removeClass("hidden");
		} else {
			$("select[name=graph_country], select[name=graph_city]").parent().parent().addClass("hidden");
		}

		$('.selectpicker').selectpicker('refresh');
	});


	$('.check_notice .main-variant').on('change',function() {
		var data = $(this).val();

		if(data) {
			if(data == 1) {
				$(".check_notice input").prop("disabled", false);
				$('.check_notice input[value=2]').prop("checked", false);
				$("input[type=checkbox].more_variant").prop('checked', true);
			} else if(data == 2) {
				$(".check_notice input").not('.check_notice input[value=1], .check_notice input[value=2]').prop("disabled", true).prop("checked", false);
				$('.check_notice input[value=1]').prop("checked", false);
				$('.check_notice input[value=2]').prop("checked", true);
				$(this).find('input[type="checkbox"]').prop("disabled", false);
			}
		}
	});
	$('.more_variant').on('change',function() {
		var data = $('input[type="radio"].main-variant:checked').val();
		var data2 = $('.more_variant:checked').val();

		
		
		if(data == 1 && !data2) {
			$('.main-variant').first().parent().parent().parent().find("input[type=checkbox].more_variant").first().prop("checked", true);
		}
	});
	/*var data_empty = $('.main_variant input[type="radio"]:checked').val();
	if(!data_empty) {
		$('.check_notice label').first().click();
	}*/


	/*$('.check_notice label input').on('change',function() {
		var data = $(this).val();

	//	alert(data);

	if(data == 1) {
			$(".check_notice input").attr("disabled", true);
			//$(this).find('input[type="checkbox"]').attr("disabled", false);
			//$(this).find('input[type="checkbox"]').attr("checked", false);
			$(".check_notice input[value=0]").attr("disabled", false);
			$(this).find('input[type="checkbox"]:checked').attr("checked", false);
		}

		if(data == 2) {
			$(".check_notice input").attr("disabled", false);
			$(".check_notice input[value=0]").attr("checked", false);
		}

		if(data > 2) {
			$(".check_notice input").attr("disabled", false);
		}

	});*/

	$(".btn-update-password").on('click',function() {
		$(".error-message-password").text("");
		$.ajax({
			url: ave_host+'/profile/',
			data: {"password": 1, "save": 1, "user_password_old": $("input[name=user_password_old]").val(), "user_password_new": $("input[name=user_password_new]").val(), "user_password_new_copy": $("input[name=user_password_new_copy]").val(), csrf_token: csrf_token},
			success: function( data ) {
				show_alert(data.status, data.respons);

				if(data.status == 'error') {
					$(".error-message-password").html(data.respons);
				}
			}
		});

		return false;
	});

	$(".remove_file").on('click',function() {
		var file_id = $(this).attr("data-file");
		var this_li = $(this).parent();
		$.ajax({
			url: ave_host+'/profile/upload/remove/',
			data: {file_id: file_id, csrf_token: csrf_token},
			success: function( data ) {
				show_alert(data.status, data.respons);
				this_li.fadeOut().remove();
			}
		});

		return false;
	});

	$(".remove_diplom").on('click',function() {
		var file_id = $(this).attr("data-file");
		var this_li = $(this).parent();
		$.ajax({
			url: ave_host+'/profile/diplom/upload/remove/',
			data: {file_id: file_id, csrf_token: csrf_token},
			success: function( data ) {
				show_alert(data.status, data.respons);
				this_li.fadeOut().remove();
			}
		});

		return false;
	});

	$(".add_graph").on('click',function() {
		$(".graph_form").find(".has-error").removeClass("has-error");

		var graph_start = $("input[name=graph_start]").val();
		var graph_end = $("input[name=graph_end]").val();

		graph_start = graph_start.split(" ");
		var graph_start_date = graph_start[0];
		graph_start = graph_start[0] + " 00:00";

		graph_end = graph_end.split(" ");
		var graph_end_date = graph_end[0];
		graph_end = graph_end[0] + " 23:55";

		var graph_country = $("select[name=graph_country]").val();
		var graph_city = $("select[name=graph_city]").val();
		var graph_work = $("select[name=graph_work]").val();

		var graph_country_text = $("select[name=graph_country]").find("option:selected").text();
		var graph_city_text = (graph_city > 0) ? ", " + $("select[name=graph_city]").find("option:selected").text() : "";
		var graph_work_text = $("select[name=graph_work]").find("option:selected").text();

		var graph_start_replaced = graph_start.replace(" ", "_").replace(":", "_").replace(/\./g, "_");
		var graph_end_replaced = graph_end.replace(" ", "_").replace(":", "_").replace(/\./g, "_");
		var control_class = graph_work+"_"+graph_start_replaced+'_'+graph_end_replaced+'_'+graph_country;
		var check = false;

		if(
			!$(".get_new_graph li").is("."+control_class)
		) {
			check = true;
		}

		if(graph_work != "1" && graph_work != "0") {
			$("select[name=graph_work]").parent().addClass("has-error");
		}

		if(graph_work == "1" && !graph_country) {
			$("select[name=graph_country]").parent().addClass("has-error");
		}

		if(graph_work == "1" && graph_start && graph_end && graph_country && check == true) {
			$(".graph_empty").addClass("hidden");

			$(".get_new_graph").append('<li class="'+control_class+'"><strong>'+graph_work_text+' <button type="button" class="close-btn btn-link remove_graph" data-dismiss="modal" aria-hidden="true"><img src="'+ave_path+'assets/site/template/images/close-button.png" alt=""></button></strong><br>'+graph_country_text + graph_city_text+'<br>'+graph_start_date+' - '+graph_end_date+'<input type="hidden" name="graph_start[]" value="'+graph_start+'"><input type="hidden" name="graph_end[]" value="'+graph_end+'"><input type="hidden" name="graph_country[]" value="'+graph_country+'"><input type="hidden" name="graph_work[]" value="'+graph_work+'"><input type="hidden" name="graph_city[]" value="'+graph_city+'"></li>');

			//$(".graph_empty").parent().remove();
		}
		if(graph_work == "0" && graph_start && graph_end && check == true) {
			$(".graph_empty").addClass("hidden");

			$(".get_new_graph").append('<li class="'+control_class+'"><strong>'+graph_work_text+' <button type="button" class="close-btn btn-link remove_graph" data-dismiss="modal" aria-hidden="true"><img src="'+ave_path+'assets/site/template/images/close-button.png" alt=""></button></strong><br>'+graph_start_date+' - '+graph_end_date+'<input type="hidden" name="graph_start[]" value="'+graph_start+'"><input type="hidden" name="graph_end[]" value="'+graph_end+'"><input type="hidden" name="graph_country[]" value="0"><input type="hidden" name="graph_work[]" value="'+graph_work+'"><input type="hidden" name="graph_city[]" value="0"></li>');

			//$(".graph_empty").parent().remove();
		}

		$(".remove_graph").on('click',function() {
			$(this).parent().parent().remove();

			if($(".remove_graph").length < 1) {
				$(".graph_empty").removeClass("hidden");
			}
		});

		return false;
	});

	$(".remove_graph").on('click',function() {
		$(this).parent().parent().remove();

		if($(".remove_graph").length < 1) {
			$(".graph_empty").removeClass("hidden");
		}
	});


	/*$("select[name=user_country]").on('change',function() {
		var country_id = $(this).val();

		$.ajax({
			url: ave_host+'/city-load/',
			data: {country_id: country_id, csrf_token: csrf_token},
			success: function( data ) {
				$("select[name=user_city]").html("");
				$.each(data.respons, function( index, value ) {
					$("select[name=user_city]").append("<option value='"+value.id+"'>"+value.title+"</option>");
				});
			}
		});
	});*/

	/*$("select[name=graph_country]").on('change',function() {
		var country_id = $(this).val();

		$.ajax({
			url: ave_host+'/city-load/',
			data: {country_id: country_id, csrf_token: csrf_token},
			success: function( data ) {
				$("select[name=graph_city]").html("");
				$.each(data.respons, function( index, value ) {
					$("select[name=graph_city]").append("<option value='"+value.id+"'>"+value.title+"</option>");
				});
			}
		});
	});*/


	/*$("input[name=user_type_form]").on('change',function() {
		var this_value = $(this).val();

		if(this_value == 2) {
			$(".info_about_company").removeClass("hidden");
		} else {
			$(".info_about_company").addClass("hidden");
		}

		$(".show_skill .btn.active").trigger("click");

		$(".show_skill").hide();
		$(".show_skill_type_"+this_value).show();
	});*/

	//$(".show_skill").hide();
	//$(".show_skill_type_"+$("input[name=user_type_form]:checked").val()).show();

	$(".show_skill .btn").on('click', function() {
		var level = $(this).find("input").val();

		if ($(this).hasClass( "active" )) {
			$(this).find("input").prop("checked", false);
		} else {
			$(this).find("input").prop("checked", true);
		}

		$(this).toggleClass("active");

		$(".block_skill").hide();
		$(".show_skill .btn.active").each(function(  ) {
			var level = $(this).find("input").val();
			$(".block_skill_type_"+level).show();
		});

		$(".block_skill").removeClass("no_border");
		$(".block_skill:visible").last().addClass("no_border");
	});

	$(".block_skill").hide();
	$(".show_skill .btn.active").each(function(  ) {
		var level = $(this).find("input").val();
		$(".block_skill_type_"+level).show();
	});


	$(".select_user_themes").on('change', function() {

		var theme_text = "";
		$(".select_user_themes .dropdown-menu .dropdown-menu li.selected").each(function () {
			var $this = $(this);
			if ($(this).length) {
				theme_text += $this.find(".text").text()+"; ";
			}
		});
		$(".get_user_theme_text").text(theme_text);
	});
},


profile_open_core = function () {
	$(".lets_be_friends").on('click',function() {
		var my_friend = $(this).attr("data-id");
		var this_button = $(this);

		$.ajax({
			url: ave_host+'/lets-be-friends/',
			data: {my_friend: my_friend, csrf_token: csrf_token},
			success: function( data ) {

				if(data.is_friends == 1) {
					this_button.text(this_button.attr("data-yes"));
				} else {
					this_button.text(this_button.attr("data-no"));
				}

				show_alert(data.status, data.respons);

			}
		});

		return false;
	});
},

init_refresh_script = function () {
	$('[data-toggle="tooltip"]').tooltip();

	$(".load_select").on('change',function() {
		var country_id = $(this).val();
		var target_class = $(this).attr("data-target");
		$("select[name="+target_class+"]").html("");

		$("select[name="+target_class+"]").html("<option value=''>"+$("select[name="+target_class+"]").attr("data-default")+"</option>");

		if(country_id) {
			$.ajax({
				url: ave_host+'/city-load/',
				data: {country_id: country_id, csrf_token: csrf_token},
				success: function( data ) {

					$("select[name="+target_class+"]").html(data.respons);
					/*$.each(data.respons, function( index, value ) {
						$("select[name="+target_class+"]").append("<option value='"+value.id+"' data-utm='"+value.utm+"'>"+value.title+"</option>");
					});*/

					$("select[name="+target_class+"] option:first").change();

					//$('.selectpicker').selectpicker('refresh');
					$("select[name="+target_class+"]").selectpicker('refresh');
				}
			});
		}

		$('.selectpicker').selectpicker('refresh');
	});


	$(".load_select_custom").on('change',function(event) {
		event.stopPropagation();

		var country_id = $(this).val();
		var target_block = $(this).parent().next().find("select");

		target_block.html("<option value=''>"+target_block.attr("data-default")+"</option>");

		//$('.selectpicker').selectpicker('refresh');

		if(country_id) {
			$.ajax({
				url: ave_host+'/city-load/',
				data: {country_id: country_id, csrf_token: csrf_token},
				success: function( data ) {

					$.each(data.respons, function( index, value ) {
						target_block.append("<option value='"+value.id+"' data-utm='"+value.utm+"'>"+value.title+"</option>");
					});

					//target_block.change();
					target_block.find("option:first").change();
					
				}
			});
		}
		//$('.selectpicker').selectpicker('refresh');
	});

},

// init form
init_form = function () {
	// ajax form custom
	$('.get_ajax_form').off();
	$(".get_ajax_form").on('click', function(forme) {
		forme.preventDefault();

		var void_id = $(this).attr("data-void"); // id
		var essense_id = $(this).attr("data-essense"); // essense id
		var type = $(this).attr("data-type"); // type essense
		var void_type = $(this).attr("data-void-type"); // type essense
		var sub = $(this).attr("data-sub"); // case request
		var ref = $(this).attr("data-ref"); // add param
		var stay_ajax = $(this).attr("data-ajax"); // if need ajax update
		var request_desc = $(this).attr("data-desc"); // desc
		var stop_reset = $(this).attr("data-reset"); // if need reset form
		var get_respons = $(this).attr("data-get-respons"); // if need reset form
		var close_modal = $(this).attr("data-close"); // if need reset form
		var is_module = $(this).attr("data-module"); // if is module

		var form_address = (is_module == 1) ? ave_path+'?do=module&sub=mod_edit&module_tag='+type+'&module_action='+sub : ave_path+'site.php?do='+type+'&sub='+sub;

		$.ajax({
			url: form_address,
			data: {void_id: void_id, essense_id:essense_id, csrf_token: csrf_token},
			success: function( data ) {

				if(data.status == 'success') {
					var modal_clone = $('#ajax_modal').clone().attr("id","");
					modal_clone.modal('show');
					modal_clone.find('.modal-title').html(data.title);
					modal_clone.find('.modal-body').html(data.html);

					$(document).on('hidden.bs.modal', '.modal', function (e) {
						e.preventDefault();
					    $('.modal:visible').length && $(document.body).addClass('modal-open');
						$(this).remove();
					});
					modal_clone.insertBefore("#ajax_modal");
					init_refresh_script();
				} else {
					if(data.respons) {
						show_alert(data.status, data.respons);
					}
				}

				// валидация формы
				if(stay_ajax) {
					modal_clone.find('.modal-body form').off();
					modal_clone.find('.modal-body form').on('submit',function() {

						var form_data = $(this).formSerialize();
						var target_form = $(this).attr("action");
						var this_form = $(this);
						//this_form.find(".actions input").attr("disabled", true);

						$.ajax({
							url: target_form,
							data: form_data,
							success: function( data ) {
								if(data.ref) {
									window.location.href = data.ref;

									var url_hash = window.location.toString();
							        var url_split = url_hash.replace(/&/g,";").split("#");
							        if(url_split[1]) {
							            window.location.reload();
							        }
									return false;
								} else {
									if(data.respons) {
										show_alert(data.status, data.respons);
									}

									if(get_respons && data.respons_html) {
										$("."+get_respons).append(data.respons_html);
										init_form();
										init_refresh_script();
									}

									if(data.status == 'success' && stop_reset==1) {
										modal_clone.find('.modal-body form').resetForm();
										init_refresh_script();
									}

									if(data.status == 'success' && close_modal==1) {
										modal_clone.find('.modal-header .close').click();
									}
								}

								this_form.find(".actions input").attr("disabled", false);
							}
						});

						return false;
					});
				}
				// валидация формы
			}
		});

		return false;
	});
	// ajax form custom

	// ajax form submit
	$('.ajax_form').off();
	$(".ajax_form").on('submit',function() {
		var form_data = $(this).formSerialize();
		var target_form = $(this).attr("action");
		var stop_reset = $(this).attr("data-reset"); // if need reset form
		var this_form = $(this);

		if(submit_ajax_form == 1) {
			return true;
		}

		this_form.find(".error-message").html("");

		//this_form.find(".actions input").attr("disabled", true);

		$.ajax({
			url: target_form,
			data: form_data,
			success: function( data ) {

				if(data.upload == true) {
					submit_ajax_form = 1;
					this_form.submit();
					return true;
				}

				if(data.ref) {
					window.location.href = data.ref;
					var url_hash = window.location.toString();
			        var url_split = url_hash.replace(/&/g,";").split("#");
			        if(url_split[1]) {
			            window.location.reload();
			        }
					return false;
				}

				if(data.html) {
					/*$('#ajax_modal').modal('show');
					$('#ajax_modal .modal-title').html(data.title);
					$('#ajax_modal .modal-body').html(data.html);*/

					var modal_clone = $('#ajax_modal').clone().attr("id","");
					modal_clone.modal('show');
					modal_clone.find('.modal-title').html(data.title);
					modal_clone.find('.modal-body').html(data.html);

					$(document).on('hidden.bs.modal', '.modal', function (e) {
						e.preventDefault();
						$('.modal:visible').length && $(document.body).addClass('modal-open');
						$(this).remove();
					});
					modal_clone.insertBefore("#ajax_modal");
					init_refresh_script();

				} else {
					show_alert(data.status, data.respons);
				}

				if(data.status == 'error') {
					this_form.find(".error-message").html(data.respons);
				}

				if(data.status == 'success' && stop_reset==1) {
					this_form.resetForm();
				}

				//this_form.find(".actions input").attr("disabled", false);
				return false;
			}
		});

		return false;
	});
	// ajax form submit
},
// init form

// init app
App = function () {

  	return {
	    init: function () {
	    	init_form(),
	    	init_support(),
	    	init_refresh_script()
	    },
	    reg_core: function () {
	    	reg_core()
	    },
	    message_uploader_core: function (field_name) {
	    	message_uploader_core(field_name)
	    },
	    profile_core: function () {
	    	profile_core()
	    },
	    profile_open_core: function () {
	    	profile_open_core()
	    },
	    search_perfomers_core: function () {
	    	search_perfomers_core()
	    },
	    search_resume_core: function () {
	    	search_resume_core()
	    },
	    search_jobs_core: function () {
	    	search_jobs_core()
	    },
	    resume_core: function () {
	    	resume_core()
	    },
	    jobs_core: function () {
	    	jobs_core()
	    },
	    order_core: function () {
	    	order_core()
	    },
	    search_order_core: function () {
	    	search_order_core()
	    },
	    message_core: function () {
			message_core()
		},
		
	    translations_core: function () {
			translations_core()
		},
		
	    init_message: function () {
			init_message()
	    },

	    search_writer_core: function () {
	    	search_writer_core()
	    },
	    writer_core: function () {
	    	writer_core()
	    },
	    billing_core: function () {
	    	billing_core()
	    },
	    message_core_reload: function () {
			message_core_reload()
	    },
	}
}();
// init app


function setCookie(name,value,days) {
	var expires = "";
	if (days) {
		var date = new Date();
		date.setTime(date.getTime() + (days*24*60*60*1000));
		expires = "; expires=" + date.toUTCString();
	}
	document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
function getCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}
function eraseCookie(name) {   
	document.cookie = name+'=; Max-Age=-99999999;';  
}


$(function () {
	$.ajaxSetup({
		dataType: "json",
		type: "POST"
	});

	// init
	App.init();
	// init
});

(function (original) {
  jQuery.fn.clone = function () {
    var result           = original.apply(this, arguments),
        my_textareas     = this.find('textarea').add(this.filter('textarea')),
        result_textareas = result.find('textarea').add(result.filter('textarea')),
        my_selects       = this.find('select').add(this.filter('select')),
        result_selects   = result.find('select').add(result.filter('select'));

    for (var i = 0, l = my_textareas.length; i < l; ++i) $(result_textareas[i]).val($(my_textareas[i]).val());
    for (var i = 0, l = my_selects.length;   i < l; ++i) result_selects[i].selectedIndex = my_selects[i].selectedIndex;

    return result;
  };
}) (jQuery.fn.clone);



/* alert */
!function(e){e(["jquery"],function(e){return function(){function t(e,t,n){return g({type:O.error,iconClass:m().iconClasses.error,message:e,optionsOverride:n,title:t})}function n(t,n){return t||(t=m()),v=e("#"+t.containerId),v.length?v:(n&&(v=u(t)),v)}function i(e,t,n){return g({type:O.info,iconClass:m().iconClasses.info,message:e,optionsOverride:n,title:t})}function o(e){w=e}function s(e,t,n){return g({type:O.success,iconClass:m().iconClasses.success,message:e,optionsOverride:n,title:t})}function a(e,t,n){return g({type:O.warning,iconClass:m().iconClasses.warning,message:e,optionsOverride:n,title:t})}function r(e,t){var i=m();v||n(i),l(e,i,t)||d(i)}function c(t){var i=m();return v||n(i),t&&0===e(":focus",t).length?void h(t):void(v.children().length&&v.remove())}function d(t){for(var n=v.children(),i=n.length-1;i>=0;i--)l(e(n[i]),t)}function l(t,n,i){var o=i&&i.force?i.force:!1;return t&&(o||0===e(":focus",t).length)?(t[n.hideMethod]({duration:n.hideDuration,easing:n.hideEasing,complete:function(){h(t)}}),!0):!1}function u(t){return v=e("<div/>").attr("id",t.containerId).addClass(t.positionClass).attr("aria-live","polite").attr("role","alert"),v.appendTo(e(t.target)),v}function p(){return{tapToDismiss:!0,toastClass:"toast",containerId:"toast-container",debug:!1,showMethod:"fadeIn",showDuration:300,showEasing:"swing",onShown:void 0,hideMethod:"fadeOut",hideDuration:1e3,hideEasing:"swing",onHidden:void 0,closeMethod:!1,closeDuration:!1,closeEasing:!1,extendedTimeOut:1e3,iconClasses:{error:"toast-error",info:"toast-info",success:"toast-success",warning:"toast-warning"},iconClass:"toast-info",positionClass:"toast-top-right",timeOut:5e3,titleClass:"toast-title",messageClass:"toast-message",escapeHtml:!1,target:"body",closeHtml:'<button type="button">&times;</button>',newestOnTop:!0,preventDuplicates:!1,progressBar:!1}}function f(e){w&&w(e)}function g(t){function i(e){return null==e&&(e=""),new String(e).replace(/&/g,"&amp;").replace(/"/g,"&quot;").replace(/'/g,"&#39;").replace(/</g,"&lt;").replace(/>/g,"&gt;")}function o(){r(),d(),l(),u(),p(),c()}function s(){y.hover(b,O),!x.onclick&&x.tapToDismiss&&y.click(w),x.closeButton&&k&&k.click(function(e){e.stopPropagation?e.stopPropagation():void 0!==e.cancelBubble&&e.cancelBubble!==!0&&(e.cancelBubble=!0),w(!0)}),x.onclick&&y.click(function(e){x.onclick(e),w()})}function a(){y.hide(),y[x.showMethod]({duration:x.showDuration,easing:x.showEasing,complete:x.onShown}),x.timeOut>0&&(H=setTimeout(w,x.timeOut),q.maxHideTime=parseFloat(x.timeOut),q.hideEta=(new Date).getTime()+q.maxHideTime,x.progressBar&&(q.intervalId=setInterval(D,10)))}function r(){t.iconClass&&y.addClass(x.toastClass).addClass(E)}function c(){x.newestOnTop?v.prepend(y):v.append(y)}function d(){t.title&&(I.append(x.escapeHtml?i(t.title):t.title).addClass(x.titleClass),y.append(I))}function l(){t.message&&(M.append(x.escapeHtml?i(t.message):t.message).addClass(x.messageClass),y.append(M))}function u(){x.closeButton&&(k.addClass("toast-close-button").attr("role","button"),y.prepend(k))}function p(){x.progressBar&&(B.addClass("toast-progress"),y.prepend(B))}function g(e,t){if(e.preventDuplicates){if(t.message===C)return!0;C=t.message}return!1}function w(t){var n=t&&x.closeMethod!==!1?x.closeMethod:x.hideMethod,i=t&&x.closeDuration!==!1?x.closeDuration:x.hideDuration,o=t&&x.closeEasing!==!1?x.closeEasing:x.hideEasing;return!e(":focus",y).length||t?(clearTimeout(q.intervalId),y[n]({duration:i,easing:o,complete:function(){h(y),x.onHidden&&"hidden"!==j.state&&x.onHidden(),j.state="hidden",j.endTime=new Date,f(j)}})):void 0}function O(){(x.timeOut>0||x.extendedTimeOut>0)&&(H=setTimeout(w,x.extendedTimeOut),q.maxHideTime=parseFloat(x.extendedTimeOut),q.hideEta=(new Date).getTime()+q.maxHideTime)}function b(){clearTimeout(H),q.hideEta=0,y.stop(!0,!0)[x.showMethod]({duration:x.showDuration,easing:x.showEasing})}function D(){var e=(q.hideEta-(new Date).getTime())/q.maxHideTime*100;B.width(e+"%")}var x=m(),E=t.iconClass||x.iconClass;if("undefined"!=typeof t.optionsOverride&&(x=e.extend(x,t.optionsOverride),E=t.optionsOverride.iconClass||E),!g(x,t)){T++,v=n(x,!0);var H=null,y=e("<div/>"),I=e("<div/>"),M=e("<div/>"),B=e("<div/>"),k=e(x.closeHtml),q={intervalId:null,hideEta:null,maxHideTime:null},j={toastId:T,state:"visible",startTime:new Date,options:x,map:t};return o(),a(),s(),f(j),x.debug&&console&&console.log(j),y}}function m(){return e.extend({},p(),b.options)}function h(e){v||(v=n()),e.is(":visible")||(e.remove(),e=null,0===v.children().length&&(v.remove(),C=void 0))}var v,w,C,T=0,O={error:"error",info:"info",success:"success",warning:"warning"},b={clear:r,remove:c,error:t,getContainer:n,info:i,options:{},subscribe:o,success:s,version:"2.1.2",warning:a};return b}()})}("function"==typeof define&&define.amd?define:function(e,t){"undefined"!=typeof module&&module.exports?module.exports=t(require("jquery")):window.toastr=t(window.jQuery)});
/* alert */



/* jquery.form */
(function(e){"use strict";if(typeof define==="function"&&define.amd){define(["jquery"],e)}else{e(typeof jQuery!="undefined"?jQuery:window.Zepto)}})(function(e){"use strict";function r(t){var n=t.data;if(!t.isDefaultPrevented()){t.preventDefault();e(t.target).ajaxSubmit(n)}}function i(t){var n=t.target;var r=e(n);if(!r.is("[type=submit],[type=image]")){var i=r.closest("[type=submit]");if(i.length===0){return}n=i[0]}var s=this;s.clk=n;if(n.type=="image"){if(t.offsetX!==undefined){s.clk_x=t.offsetX;s.clk_y=t.offsetY}else if(typeof e.fn.offset=="function"){var o=r.offset();s.clk_x=t.pageX-o.left;s.clk_y=t.pageY-o.top}else{s.clk_x=t.pageX-n.offsetLeft;s.clk_y=t.pageY-n.offsetTop}}setTimeout(function(){s.clk=s.clk_x=s.clk_y=null},100)}function s(){if(!e.fn.ajaxSubmit.debug){return}var t="[jquery.form] "+Array.prototype.join.call(arguments,"");if(window.console&&window.console.log){window.console.log(t)}else if(window.opera&&window.opera.postError){window.opera.postError(t)}}var t={};t.fileapi=e("<input type='file'/>").get(0).files!==undefined;t.formdata=window.FormData!==undefined;var n=!!e.fn.prop;e.fn.attr2=function(){if(!n){return this.attr.apply(this,arguments)}var e=this.prop.apply(this,arguments);if(e&&e.jquery||typeof e==="string"){return e}return this.attr.apply(this,arguments)};e.fn.ajaxSubmit=function(r){function k(t){var n=e.param(t,r.traditional).split("&");var i=n.length;var s=[];var o,u;for(o=0;o<i;o++){n[o]=n[o].replace(/\+/g," ");u=n[o].split("=");s.push([decodeURIComponent(u[0]),decodeURIComponent(u[1])])}return s}function L(t){var n=new FormData;for(var s=0;s<t.length;s++){n.append(t[s].name,t[s].value)}if(r.extraData){var o=k(r.extraData);for(s=0;s<o.length;s++){if(o[s]){n.append(o[s][0],o[s][1])}}}r.data=null;var u=e.extend(true,{},e.ajaxSettings,r,{contentType:false,processData:false,cache:false,type:i||"POST"});if(r.uploadProgress){u.xhr=function(){var t=e.ajaxSettings.xhr();if(t.upload){t.upload.addEventListener("progress",function(e){var t=0;var n=e.loaded||e.position;var i=e.total;if(e.lengthComputable){t=Math.ceil(n/i*100)}r.uploadProgress(e,n,i,t)},false)}return t}}u.data=null;var a=u.beforeSend;u.beforeSend=function(e,t){if(r.formData){t.data=r.formData}else{t.data=n}if(a){a.call(this,e,t)}};return e.ajax(u)}function A(t){function T(e){var t=null;try{if(e.contentWindow){t=e.contentWindow.document}}catch(n){s("cannot get iframe.contentWindow document: "+n)}if(t){return t}try{t=e.contentDocument?e.contentDocument:e.document}catch(n){s("cannot get iframe.contentDocument: "+n);t=e.document}return t}function k(){function f(){try{var e=T(v).readyState;s("state = "+e);if(e&&e.toLowerCase()=="uninitialized"){setTimeout(f,50)}}catch(t){s("Server abort: ",t," (",t.name,")");_(x);if(w){clearTimeout(w)}w=undefined}}var t=a.attr2("target"),n=a.attr2("action"),r="multipart/form-data",u=a.attr("enctype")||a.attr("encoding")||r;o.setAttribute("target",p);if(!i||/post/i.test(i)){o.setAttribute("method","POST")}if(n!=l.url){o.setAttribute("action",l.url)}if(!l.skipEncodingOverride&&(!i||/post/i.test(i))){a.attr({encoding:"multipart/form-data",enctype:"multipart/form-data"})}if(l.timeout){w=setTimeout(function(){b=true;_(S)},l.timeout)}var c=[];try{if(l.extraData){for(var h in l.extraData){if(l.extraData.hasOwnProperty(h)){if(e.isPlainObject(l.extraData[h])&&l.extraData[h].hasOwnProperty("name")&&l.extraData[h].hasOwnProperty("value")){c.push(e('<input type="hidden" name="'+l.extraData[h].name+'">').val(l.extraData[h].value).appendTo(o)[0])}else{c.push(e('<input type="hidden" name="'+h+'">').val(l.extraData[h]).appendTo(o)[0])}}}}if(!l.iframeTarget){d.appendTo("body")}if(v.attachEvent){v.attachEvent("onload",_)}else{v.addEventListener("load",_,false)}setTimeout(f,15);try{o.submit()}catch(m){var g=document.createElement("form").submit;g.apply(o)}}finally{o.setAttribute("action",n);o.setAttribute("enctype",u);if(t){o.setAttribute("target",t)}else{a.removeAttr("target")}e(c).remove()}}function _(t){if(m.aborted||M){return}A=T(v);if(!A){s("cannot access response document");t=x}if(t===S&&m){m.abort("timeout");E.reject(m,"timeout");return}else if(t==x&&m){m.abort("server abort");E.reject(m,"error","server abort");return}if(!A||A.location.href==l.iframeSrc){if(!b){return}}if(v.detachEvent){v.detachEvent("onload",_)}else{v.removeEventListener("load",_,false)}var n="success",r;try{if(b){throw"timeout"}var i=l.dataType=="xml"||A.XMLDocument||e.isXMLDoc(A);s("isXml="+i);if(!i&&window.opera&&(A.body===null||!A.body.innerHTML)){if(--O){s("requeing onLoad callback, DOM not available");setTimeout(_,250);return}}var o=A.body?A.body:A.documentElement;m.responseText=o?o.innerHTML:null;m.responseXML=A.XMLDocument?A.XMLDocument:A;if(i){l.dataType="xml"}m.getResponseHeader=function(e){var t={"content-type":l.dataType};return t[e.toLowerCase()]};if(o){m.status=Number(o.getAttribute("status"))||m.status;m.statusText=o.getAttribute("statusText")||m.statusText}var u=(l.dataType||"").toLowerCase();var a=/(json|script|text)/.test(u);if(a||l.textarea){var f=A.getElementsByTagName("textarea")[0];if(f){m.responseText=f.value;m.status=Number(f.getAttribute("status"))||m.status;m.statusText=f.getAttribute("statusText")||m.statusText}else if(a){var c=A.getElementsByTagName("pre")[0];var p=A.getElementsByTagName("body")[0];if(c){m.responseText=c.textContent?c.textContent:c.innerText}else if(p){m.responseText=p.textContent?p.textContent:p.innerText}}}else if(u=="xml"&&!m.responseXML&&m.responseText){m.responseXML=D(m.responseText)}try{L=H(m,u,l)}catch(g){n="parsererror";m.error=r=g||n}}catch(g){s("error caught: ",g);n="error";m.error=r=g||n}if(m.aborted){s("upload aborted");n=null}if(m.status){n=m.status>=200&&m.status<300||m.status===304?"success":"error"}if(n==="success"){if(l.success){l.success.call(l.context,L,"success",m)}E.resolve(m.responseText,"success",m);if(h){e.event.trigger("ajaxSuccess",[m,l])}}else if(n){if(r===undefined){r=m.statusText}if(l.error){l.error.call(l.context,m,n,r)}E.reject(m,"error",r);if(h){e.event.trigger("ajaxError",[m,l,r])}}if(h){e.event.trigger("ajaxComplete",[m,l])}if(h&&!--e.active){e.event.trigger("ajaxStop")}if(l.complete){l.complete.call(l.context,m,n)}M=true;if(l.timeout){clearTimeout(w)}setTimeout(function(){if(!l.iframeTarget){d.remove()}else{d.attr("src",l.iframeSrc)}m.responseXML=null},100)}var o=a[0],u,f,l,h,p,d,v,m,g,y,b,w;var E=e.Deferred();E.abort=function(e){m.abort(e)};if(t){for(f=0;f<c.length;f++){u=e(c[f]);if(n){u.prop("disabled",false)}else{u.removeAttr("disabled")}}}l=e.extend(true,{},e.ajaxSettings,r);l.context=l.context||l;p="jqFormIO"+(new Date).getTime();if(l.iframeTarget){d=e(l.iframeTarget);y=d.attr2("name");if(!y){d.attr2("name",p)}else{p=y}}else{d=e('<iframe name="'+p+'" src="'+l.iframeSrc+'" />');d.css({position:"absolute",top:"-1000px",left:"-1000px"})}v=d[0];m={aborted:0,responseText:null,responseXML:null,status:0,statusText:"n/a",getAllResponseHeaders:function(){},getResponseHeader:function(){},setRequestHeader:function(){},abort:function(t){var n=t==="timeout"?"timeout":"aborted";s("aborting upload... "+n);this.aborted=1;try{if(v.contentWindow.document.execCommand){v.contentWindow.document.execCommand("Stop")}}catch(r){}d.attr("src",l.iframeSrc);m.error=n;if(l.error){l.error.call(l.context,m,n,t)}if(h){e.event.trigger("ajaxError",[m,l,n])}if(l.complete){l.complete.call(l.context,m,n)}}};h=l.global;if(h&&0===e.active++){e.event.trigger("ajaxStart")}if(h){e.event.trigger("ajaxSend",[m,l])}if(l.beforeSend&&l.beforeSend.call(l.context,m,l)===false){if(l.global){e.active--}E.reject();return E}if(m.aborted){E.reject();return E}g=o.clk;if(g){y=g.name;if(y&&!g.disabled){l.extraData=l.extraData||{};l.extraData[y]=g.value;if(g.type=="image"){l.extraData[y+".x"]=o.clk_x;l.extraData[y+".y"]=o.clk_y}}}var S=1;var x=2;var N=e("meta[name=csrf-token]").attr("content");var C=e("meta[name=csrf-param]").attr("content");if(C&&N){l.extraData=l.extraData||{};l.extraData[C]=N}if(l.forceSync){k()}else{setTimeout(k,10)}var L,A,O=50,M;var D=e.parseXML||function(e,t){if(window.ActiveXObject){t=new ActiveXObject("Microsoft.XMLDOM");t.async="false";t.loadXML(e)}else{t=(new DOMParser).parseFromString(e,"text/xml")}return t&&t.documentElement&&t.documentElement.nodeName!="parsererror"?t:null};var P=e.parseJSON||function(e){return window["eval"]("("+e+")")};var H=function(t,n,r){var i=t.getResponseHeader("content-type")||"",s=n==="xml"||!n&&i.indexOf("xml")>=0,o=s?t.responseXML:t.responseText;if(s&&o.documentElement.nodeName==="parsererror"){if(e.error){e.error("parsererror")}}if(r&&r.dataFilter){o=r.dataFilter(o,n)}if(typeof o==="string"){if(n==="json"||!n&&i.indexOf("json")>=0){o=P(o)}else if(n==="script"||!n&&i.indexOf("javascript")>=0){e.globalEval(o)}}return o};return E}if(!this.length){s("ajaxSubmit: skipping submit process - no element selected");return this}var i,o,u,a=this;if(typeof r=="function"){r={success:r}}else if(r===undefined){r={}}i=r.type||this.attr2("method");o=r.url||this.attr2("action");u=typeof o==="string"?e.trim(o):"";u=u||window.location.href||"";if(u){u=(u.match(/^([^#]+)/)||[])[1]}r=e.extend(true,{url:u,success:e.ajaxSettings.success,type:i||e.ajaxSettings.type,iframeSrc:/^https/i.test(window.location.href||"")?"javascript:false":"about:blank"},r);var f={};this.trigger("form-pre-serialize",[this,r,f]);if(f.veto){s("ajaxSubmit: submit vetoed via form-pre-serialize trigger");return this}if(r.beforeSerialize&&r.beforeSerialize(this,r)===false){s("ajaxSubmit: submit aborted via beforeSerialize callback");return this}var l=r.traditional;if(l===undefined){l=e.ajaxSettings.traditional}var c=[];var h,p=this.formToArray(r.semantic,c);if(r.data){r.extraData=r.data;h=e.param(r.data,l)}if(r.beforeSubmit&&r.beforeSubmit(p,this,r)===false){s("ajaxSubmit: submit aborted via beforeSubmit callback");return this}this.trigger("form-submit-validate",[p,this,r,f]);if(f.veto){s("ajaxSubmit: submit vetoed via form-submit-validate trigger");return this}var d=e.param(p,l);if(h){d=d?d+"&"+h:h}if(r.type.toUpperCase()=="GET"){r.url+=(r.url.indexOf("?")>=0?"&":"?")+d;r.data=null}else{r.data=d}var v=[];if(r.resetForm){v.push(function(){a.resetForm()})}if(r.clearForm){v.push(function(){a.clearForm(r.includeHidden)})}if(!r.dataType&&r.target){var m=r.success||function(){};v.push(function(t){var n=r.replaceTarget?"replaceWith":"html";e(r.target)[n](t).each(m,arguments)})}else if(r.success){v.push(r.success)}r.success=function(e,t,n){var i=r.context||this;for(var s=0,o=v.length;s<o;s++){v[s].apply(i,[e,t,n||a,a])}};if(r.error){var g=r.error;r.error=function(e,t,n){var i=r.context||this;g.apply(i,[e,t,n,a])}}if(r.complete){var y=r.complete;r.complete=function(e,t){var n=r.context||this;y.apply(n,[e,t,a])}}var b=e("input[type=file]:enabled",this).filter(function(){return e(this).val()!==""});var w=b.length>0;var E="multipart/form-data";var S=a.attr("enctype")==E||a.attr("encoding")==E;var x=t.fileapi&&t.formdata;s("fileAPI :"+x);var T=(w||S)&&!x;var N;if(r.iframe!==false&&(r.iframe||T)){if(r.closeKeepAlive){e.get(r.closeKeepAlive,function(){N=A(p)})}else{N=A(p)}}else if((w||S)&&x){N=L(p)}else{N=e.ajax(r)}a.removeData("jqxhr").data("jqxhr",N);for(var C=0;C<c.length;C++){c[C]=null}this.trigger("form-submit-notify",[this,r]);return this};e.fn.ajaxForm=function(t){t=t||{};t.delegation=t.delegation&&e.isFunction(e.fn.on);if(!t.delegation&&this.length===0){var n={s:this.selector,c:this.context};if(!e.isReady&&n.s){s("DOM not ready, queuing ajaxForm");e(function(){e(n.s,n.c).ajaxForm(t)});return this}s("terminating; zero elements found by selector"+(e.isReady?"":" (DOM not ready)"));return this}if(t.delegation){e(document).off("submit.form-plugin",this.selector,r).off("click.form-plugin",this.selector,i).on("submit.form-plugin",this.selector,t,r).on("click.form-plugin",this.selector,t,i);return this}return this.ajaxFormUnbind().bind("submit.form-plugin",t,r).bind("click.form-plugin",t,i)};e.fn.ajaxFormUnbind=function(){return this.unbind("submit.form-plugin click.form-plugin")};e.fn.formToArray=function(n,r){var i=[];if(this.length===0){return i}var s=this[0];var o=this.attr("id");var u=n?s.getElementsByTagName("*"):s.elements;var a;if(u&&!/MSIE [678]/.test(navigator.userAgent)){u=e(u).get()}if(o){a=e(':input[form="'+o+'"]').get();if(a.length){u=(u||[]).concat(a)}}if(!u||!u.length){return i}var f,l,c,h,p,d,v;for(f=0,d=u.length;f<d;f++){p=u[f];c=p.name;if(!c||p.disabled){continue}if(n&&s.clk&&p.type=="image"){if(s.clk==p){i.push({name:c,value:e(p).val(),type:p.type});i.push({name:c+".x",value:s.clk_x},{name:c+".y",value:s.clk_y})}continue}h=e.fieldValue(p,true);if(h&&h.constructor==Array){if(r){r.push(p)}for(l=0,v=h.length;l<v;l++){i.push({name:c,value:h[l]})}}else if(t.fileapi&&p.type=="file"){if(r){r.push(p)}var m=p.files;if(m.length){for(l=0;l<m.length;l++){i.push({name:c,value:m[l],type:p.type})}}else{i.push({name:c,value:"",type:p.type})}}else if(h!==null&&typeof h!="undefined"){if(r){r.push(p)}i.push({name:c,value:h,type:p.type,required:p.required})}}if(!n&&s.clk){var g=e(s.clk),y=g[0];c=y.name;if(c&&!y.disabled&&y.type=="image"){i.push({name:c,value:g.val()});i.push({name:c+".x",value:s.clk_x},{name:c+".y",value:s.clk_y})}}return i};e.fn.formSerialize=function(t){return e.param(this.formToArray(t))};e.fn.fieldSerialize=function(t){var n=[];this.each(function(){var r=this.name;if(!r){return}var i=e.fieldValue(this,t);if(i&&i.constructor==Array){for(var s=0,o=i.length;s<o;s++){n.push({name:r,value:i[s]})}}else if(i!==null&&typeof i!="undefined"){n.push({name:this.name,value:i})}});return e.param(n)};e.fn.fieldValue=function(t){for(var n=[],r=0,i=this.length;r<i;r++){var s=this[r];var o=e.fieldValue(s,t);if(o===null||typeof o=="undefined"||o.constructor==Array&&!o.length){continue}if(o.constructor==Array){e.merge(n,o)}else{n.push(o)}}return n};e.fieldValue=function(t,n){var r=t.name,i=t.type,s=t.tagName.toLowerCase();if(n===undefined){n=true}if(n&&(!r||t.disabled||i=="reset"||i=="button"||(i=="checkbox"||i=="radio")&&!t.checked||(i=="submit"||i=="image")&&t.form&&t.form.clk!=t||s=="select"&&t.selectedIndex==-1)){return null}if(s=="select"){var o=t.selectedIndex;if(o<0){return null}var u=[],a=t.options;var f=i=="select-one";var l=f?o+1:a.length;for(var c=f?o:0;c<l;c++){var h=a[c];if(h.selected){var p=h.value;if(!p){p=h.attributes&&h.attributes.value&&!h.attributes.value.specified?h.text:h.value}if(f){return p}u.push(p)}}return u}return e(t).val()};e.fn.clearForm=function(t){return this.each(function(){e("input,select,textarea",this).clearFields(t)})};e.fn.clearFields=e.fn.clearInputs=function(t){var n=/^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i;return this.each(function(){var r=this.type,i=this.tagName.toLowerCase();if(n.test(r)||i=="textarea"){this.value=""}else if(r=="checkbox"||r=="radio"){this.checked=false}else if(i=="select"){this.selectedIndex=-1}else if(r=="file"){if(/MSIE/.test(navigator.userAgent)){e(this).replaceWith(e(this).clone(true))}else{e(this).val("")}}else if(t){if(t===true&&/hidden/.test(r)||typeof t=="string"&&e(this).is(t)){this.value=""}}})};e.fn.resetForm=function(){return this.each(function(){if(typeof this.reset=="function"||typeof this.reset=="object"&&!this.reset.nodeType){this.reset()}})};e.fn.enable=function(e){if(e===undefined){e=true}return this.each(function(){this.disabled=!e})};e.fn.selected=function(t){if(t===undefined){t=true}return this.each(function(){var n=this.type;if(n=="checkbox"||n=="radio"){this.checked=t}else if(this.tagName.toLowerCase()=="option"){var r=e(this).parent("select");if(t&&r[0]&&r[0].type=="select-one"){r.find("option").selected(false)}this.selected=t}})};e.fn.ajaxSubmit.debug=false});
/* jquery.form */

/**
*  Ajax Autocomplete for jQuery, version 1.2.24
*  (c) 2014 Tomas Kirda
*
*  Ajax Autocomplete for jQuery is freely distributable under the terms of an MIT-style license.
*  For details, see the web site: https://github.com/devbridge/jQuery-Autocomplete
*/
!function(a){"use strict";"function"==typeof define&&define.amd?define(["jquery"],a):a("object"==typeof exports&&"function"==typeof require?require("jquery"):jQuery)}(function(a){"use strict";function b(c,d){var e=function(){},f=this,g={ajaxSettings:{},autoSelectFirst:!1,appendTo:document.body,serviceUrl:null,lookup:null,onSelect:null,width:"auto",minChars:1,maxHeight:300,deferRequestBy:0,params:{},formatResult:b.formatResult,delimiter:null,zIndex:9999,type:"GET",noCache:!1,onSearchStart:e,onSearchComplete:e,onSearchError:e,preserveInput:!1,containerClass:"autocomplete-suggestions",tabDisabled:!1,dataType:"text",currentRequest:null,triggerSelectOnValidInput:!0,preventBadQueries:!0,lookupFilter:function(a,b,c){return-1!==a.value.toLowerCase().indexOf(c)},paramName:"query",transformResult:function(b){return"string"==typeof b?a.parseJSON(b):b},showNoSuggestionNotice:!1,noSuggestionNotice:"No results",orientation:"bottom",forceFixPosition:!1};f.element=c,f.el=a(c),f.suggestions=[],f.badQueries=[],f.selectedIndex=-1,f.currentValue=f.element.value,f.intervalId=0,f.cachedResponse={},f.onChangeInterval=null,f.onChange=null,f.isLocal=!1,f.suggestionsContainer=null,f.noSuggestionsContainer=null,f.options=a.extend({},g,d),f.classes={selected:"autocomplete-selected",suggestion:"autocomplete-suggestion"},f.hint=null,f.hintValue="",f.selection=null,f.initialize(),f.setOptions(d)}var c=function(){return{escapeRegExChars:function(a){return a.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g,"\\$&")},createNode:function(a){var b=document.createElement("div");return b.className=a,b.style.position="absolute",b.style.display="none",b}}}(),d={ESC:27,TAB:9,RETURN:13,LEFT:37,UP:38,RIGHT:39,DOWN:40};b.utils=c,a.Autocomplete=b,b.formatResult=function(a,b){var d="("+c.escapeRegExChars(b)+")";return a.value.replace(new RegExp(d,"gi"),"<strong>$1</strong>").replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/&lt;(\/?strong)&gt;/g,"<$1>")},b.prototype={killerFn:null,initialize:function(){var c,d=this,e="."+d.classes.suggestion,f=d.classes.selected,g=d.options;d.element.setAttribute("autocomplete","off"),d.killerFn=function(b){0===a(b.target).closest("."+d.options.containerClass).length&&(d.killSuggestions(),d.disableKillerFn())},d.noSuggestionsContainer=a('<div class="autocomplete-no-suggestion"></div>').html(this.options.noSuggestionNotice).get(0),d.suggestionsContainer=b.utils.createNode(g.containerClass),c=a(d.suggestionsContainer),c.appendTo(g.appendTo),"auto"!==g.width&&c.width(g.width),c.on("mouseover.autocomplete",e,function(){d.activate(a(this).data("index"))}),c.on("mouseout.autocomplete",function(){d.selectedIndex=-1,c.children("."+f).removeClass(f)}),c.on("click.autocomplete",e,function(){d.select(a(this).data("index"))}),d.fixPositionCapture=function(){d.visible&&d.fixPosition()},a(window).on("resize.autocomplete",d.fixPositionCapture),d.el.on("keydown.autocomplete",function(a){d.onKeyPress(a)}),d.el.on("keyup.autocomplete",function(a){d.onKeyUp(a)}),d.el.on("blur.autocomplete",function(){d.onBlur()}),d.el.on("focus.autocomplete",function(){d.onFocus()}),d.el.on("change.autocomplete",function(a){d.onKeyUp(a)}),d.el.on("input.autocomplete",function(a){d.onKeyUp(a)})},onFocus:function(){var a=this;a.fixPosition(),0===a.options.minChars&&0===a.el.val().length&&a.onValueChange()},onBlur:function(){this.enableKillerFn()},abortAjax:function(){var a=this;a.currentRequest&&(a.currentRequest.abort(),a.currentRequest=null)},setOptions:function(b){var c=this,d=c.options;a.extend(d,b),c.isLocal=a.isArray(d.lookup),c.isLocal&&(d.lookup=c.verifySuggestionsFormat(d.lookup)),d.orientation=c.validateOrientation(d.orientation,"bottom"),a(c.suggestionsContainer).css({"max-height":d.maxHeight+"px",width:d.width+"px","z-index":d.zIndex})},clearCache:function(){this.cachedResponse={},this.badQueries=[]},clear:function(){this.clearCache(),this.currentValue="",this.suggestions=[]},disable:function(){var a=this;a.disabled=!0,clearInterval(a.onChangeInterval),a.abortAjax()},enable:function(){this.disabled=!1},fixPosition:function(){var b=this,c=a(b.suggestionsContainer),d=c.parent().get(0);if(d===document.body||b.options.forceFixPosition){var e=b.options.orientation,f=c.outerHeight(),g=b.el.outerHeight(),h=b.el.offset(),i={top:h.top,left:h.left};if("auto"===e){var j=a(window).height(),k=a(window).scrollTop(),l=-k+h.top-f,m=k+j-(h.top+g+f);e=Math.max(l,m)===l?"top":"bottom"}if("top"===e?i.top+=-f:i.top+=g,d!==document.body){var n,o=c.css("opacity");b.visible||c.css("opacity",0).show(),n=c.offsetParent().offset(),i.top-=n.top,i.left-=n.left,b.visible||c.css("opacity",o).hide()}"auto"===b.options.width&&(i.width=b.el.outerWidth()-2+"px"),c.css(i)}},enableKillerFn:function(){var b=this;a(document).on("click.autocomplete",b.killerFn)},disableKillerFn:function(){var b=this;a(document).off("click.autocomplete",b.killerFn)},killSuggestions:function(){var a=this;a.stopKillSuggestions(),a.intervalId=window.setInterval(function(){a.visible&&(a.el.val(a.currentValue),a.hide()),a.stopKillSuggestions()},50)},stopKillSuggestions:function(){window.clearInterval(this.intervalId)},isCursorAtEnd:function(){var a,b=this,c=b.el.val().length,d=b.element.selectionStart;return"number"==typeof d?d===c:document.selection?(a=document.selection.createRange(),a.moveStart("character",-c),c===a.text.length):!0},onKeyPress:function(a){var b=this;if(!b.disabled&&!b.visible&&a.which===d.DOWN&&b.currentValue)return void b.suggest();if(!b.disabled&&b.visible){switch(a.which){case d.ESC:b.el.val(b.currentValue),b.hide();break;case d.RIGHT:if(b.hint&&b.options.onHint&&b.isCursorAtEnd()){b.selectHint();break}return;case d.TAB:if(b.hint&&b.options.onHint)return void b.selectHint();if(-1===b.selectedIndex)return void b.hide();if(b.select(b.selectedIndex),b.options.tabDisabled===!1)return;break;case d.RETURN:if(-1===b.selectedIndex)return void b.hide();b.select(b.selectedIndex);break;case d.UP:b.moveUp();break;case d.DOWN:b.moveDown();break;default:return}a.stopImmediatePropagation(),a.preventDefault()}},onKeyUp:function(a){var b=this;if(!b.disabled){switch(a.which){case d.UP:case d.DOWN:return}clearInterval(b.onChangeInterval),b.currentValue!==b.el.val()&&(b.findBestHint(),b.options.deferRequestBy>0?b.onChangeInterval=setInterval(function(){b.onValueChange()},b.options.deferRequestBy):b.onValueChange())}},onValueChange:function(){var b=this,c=b.options,d=b.el.val(),e=b.getQuery(d);return b.selection&&b.currentValue!==e&&(b.selection=null,(c.onInvalidateSelection||a.noop).call(b.element)),clearInterval(b.onChangeInterval),b.currentValue=d,b.selectedIndex=-1,c.triggerSelectOnValidInput&&b.isExactMatch(e)?void b.select(0):void(e.length<c.minChars?b.hide():b.getSuggestions(e))},isExactMatch:function(a){var b=this.suggestions;return 1===b.length&&b[0].value.toLowerCase()===a.toLowerCase()},getQuery:function(b){var c,d=this.options.delimiter;return d?(c=b.split(d),a.trim(c[c.length-1])):b},getSuggestionsLocal:function(b){var c,d=this,e=d.options,f=b.toLowerCase(),g=e.lookupFilter,h=parseInt(e.lookupLimit,10);return c={suggestions:a.grep(e.lookup,function(a){return g(a,b,f)})},h&&c.suggestions.length>h&&(c.suggestions=c.suggestions.slice(0,h)),c},getSuggestions:function(b){var c,d,e,f,g=this,h=g.options,i=h.serviceUrl;if(h.params[h.paramName]=b,d=h.ignoreParams?null:h.params,h.onSearchStart.call(g.element,h.params)!==!1){if(a.isFunction(h.lookup))return void h.lookup(b,function(a){g.suggestions=a.suggestions,g.suggest(),h.onSearchComplete.call(g.element,b,a.suggestions)});g.isLocal?c=g.getSuggestionsLocal(b):(a.isFunction(i)&&(i=i.call(g.element,b)),e=i+"?"+a.param(d||{}),c=g.cachedResponse[e]),c&&a.isArray(c.suggestions)?(g.suggestions=c.suggestions,g.suggest(),h.onSearchComplete.call(g.element,b,c.suggestions)):g.isBadQuery(b)?h.onSearchComplete.call(g.element,b,[]):(g.abortAjax(),f={url:i,data:d,type:h.type,dataType:h.dataType},a.extend(f,h.ajaxSettings),g.currentRequest=a.ajax(f).done(function(a){var c;g.currentRequest=null,c=h.transformResult(a,b),g.processResponse(c,b,e),h.onSearchComplete.call(g.element,b,c.suggestions)}).fail(function(a,c,d){h.onSearchError.call(g.element,b,a,c,d)}))}},isBadQuery:function(a){if(!this.options.preventBadQueries)return!1;for(var b=this.badQueries,c=b.length;c--;)if(0===a.indexOf(b[c]))return!0;return!1},hide:function(){var b=this,c=a(b.suggestionsContainer);a.isFunction(b.options.onHide)&&b.visible&&b.options.onHide.call(b.element,c),b.visible=!1,b.selectedIndex=-1,clearInterval(b.onChangeInterval),a(b.suggestionsContainer).hide(),b.signalHint(null)},suggest:function(){if(0===this.suggestions.length)return void(this.options.showNoSuggestionNotice?this.noSuggestions():this.hide());var b,c=this,d=c.options,e=d.groupBy,f=d.formatResult,g=c.getQuery(c.currentValue),h=c.classes.suggestion,i=c.classes.selected,j=a(c.suggestionsContainer),k=a(c.noSuggestionsContainer),l=d.beforeRender,m="",n=function(a,c){var d=a.data[e];return b===d?"":(b=d,'<div class="autocomplete-group"><strong>'+b+"</strong></div>")};return d.triggerSelectOnValidInput&&c.isExactMatch(g)?void c.select(0):(a.each(c.suggestions,function(a,b){e&&(m+=n(b,g,a)),m+='<div class="'+h+'" data-index="'+a+'">'+f(b,g)+"</div>"}),this.adjustContainerWidth(),k.detach(),j.html(m),a.isFunction(l)&&l.call(c.element,j),c.fixPosition(),j.show(),d.autoSelectFirst&&(c.selectedIndex=0,j.scrollTop(0),j.children("."+h).first().addClass(i)),c.visible=!0,void c.findBestHint())},noSuggestions:function(){var b=this,c=a(b.suggestionsContainer),d=a(b.noSuggestionsContainer);this.adjustContainerWidth(),d.detach(),c.empty(),c.append(d),b.fixPosition(),c.show(),b.visible=!0},adjustContainerWidth:function(){var b,c=this,d=c.options,e=a(c.suggestionsContainer);"auto"===d.width&&(b=c.el.outerWidth()-2,e.width(b>0?b:300))},findBestHint:function(){var b=this,c=b.el.val().toLowerCase(),d=null;c&&(a.each(b.suggestions,function(a,b){var e=0===b.value.toLowerCase().indexOf(c);return e&&(d=b),!e}),b.signalHint(d))},signalHint:function(b){var c="",d=this;b&&(c=d.currentValue+b.value.substr(d.currentValue.length)),d.hintValue!==c&&(d.hintValue=c,d.hint=b,(this.options.onHint||a.noop)(c))},verifySuggestionsFormat:function(b){return b.length&&"string"==typeof b[0]?a.map(b,function(a){return{value:a,data:null}}):b},validateOrientation:function(b,c){return b=a.trim(b||"").toLowerCase(),-1===a.inArray(b,["auto","bottom","top"])&&(b=c),b},processResponse:function(a,b,c){var d=this,e=d.options;a.suggestions=d.verifySuggestionsFormat(a.suggestions),e.noCache||(d.cachedResponse[c]=a,e.preventBadQueries&&0===a.suggestions.length&&d.badQueries.push(b)),b===d.getQuery(d.currentValue)&&(d.suggestions=a.suggestions,d.suggest())},activate:function(b){var c,d=this,e=d.classes.selected,f=a(d.suggestionsContainer),g=f.find("."+d.classes.suggestion);return f.find("."+e).removeClass(e),d.selectedIndex=b,-1!==d.selectedIndex&&g.length>d.selectedIndex?(c=g.get(d.selectedIndex),a(c).addClass(e),c):null},selectHint:function(){var b=this,c=a.inArray(b.hint,b.suggestions);b.select(c)},select:function(a){var b=this;b.hide(),b.onSelect(a)},moveUp:function(){var b=this;if(-1!==b.selectedIndex)return 0===b.selectedIndex?(a(b.suggestionsContainer).children().first().removeClass(b.classes.selected),b.selectedIndex=-1,b.el.val(b.currentValue),void b.findBestHint()):void b.adjustScroll(b.selectedIndex-1)},moveDown:function(){var a=this;a.selectedIndex!==a.suggestions.length-1&&a.adjustScroll(a.selectedIndex+1)},adjustScroll:function(b){var c=this,d=c.activate(b);if(d){var e,f,g,h=a(d).outerHeight();e=d.offsetTop,f=a(c.suggestionsContainer).scrollTop(),g=f+c.options.maxHeight-h,f>e?a(c.suggestionsContainer).scrollTop(e):e>g&&a(c.suggestionsContainer).scrollTop(e-c.options.maxHeight+h),c.options.preserveInput||c.el.val(c.getValue(c.suggestions[b].value)),c.signalHint(null)}},onSelect:function(b){var c=this,d=c.options.onSelect,e=c.suggestions[b];c.currentValue=c.getValue(e.value),c.currentValue===c.el.val()||c.options.preserveInput||c.el.val(c.currentValue),c.signalHint(null),c.suggestions=[],c.selection=e,a.isFunction(d)&&d.call(c.element,e)},getValue:function(a){var b,c,d=this,e=d.options.delimiter;return e?(b=d.currentValue,c=b.split(e),1===c.length?a:b.substr(0,b.length-c[c.length-1].length)+a):a},dispose:function(){var b=this;b.el.off(".autocomplete").removeData("autocomplete"),b.disableKillerFn(),a(window).off("resize.autocomplete",b.fixPositionCapture),a(b.suggestionsContainer).remove()}},a.fn.autocomplete=a.fn.devbridgeAutocomplete=function(c,d){var e="autocomplete";return 0===arguments.length?this.first().data(e):this.each(function(){var f=a(this),g=f.data(e);"string"==typeof c?g&&"function"==typeof g[c]&&g[c](d):(g&&g.dispose&&g.dispose(),g=new b(this,c),f.data(e,g))})}});

// jQuery Mask Plugin v1.14.9
// github.com/igorescobar/jQuery-Mask-Plugin
var $jscomp={scope:{},findInternal:function(a,f,c){a instanceof String&&(a=String(a));for(var l=a.length,g=0;g<l;g++){var b=a[g];if(f.call(c,b,g,a))return{i:g,v:b}}return{i:-1,v:void 0}}};$jscomp.defineProperty="function"==typeof Object.defineProperties?Object.defineProperty:function(a,f,c){if(c.get||c.set)throw new TypeError("ES3 does not support getters and setters.");a!=Array.prototype&&a!=Object.prototype&&(a[f]=c.value)};
$jscomp.getGlobal=function(a){return"undefined"!=typeof window&&window===a?a:"undefined"!=typeof global&&null!=global?global:a};$jscomp.global=$jscomp.getGlobal(this);$jscomp.polyfill=function(a,f,c,l){if(f){c=$jscomp.global;a=a.split(".");for(l=0;l<a.length-1;l++){var g=a[l];g in c||(c[g]={});c=c[g]}a=a[a.length-1];l=c[a];f=f(l);f!=l&&null!=f&&$jscomp.defineProperty(c,a,{configurable:!0,writable:!0,value:f})}};
$jscomp.polyfill("Array.prototype.find",function(a){return a?a:function(a,c){return $jscomp.findInternal(this,a,c).v}},"es6-impl","es3");
(function(a,f,c){"function"===typeof define&&define.amd?define(["jquery"],a):"object"===typeof exports?module.exports=a(require("jquery")):a(f||c)})(function(a){var f=function(b,h,e){var d={invalid:[],getCaret:function(){try{var a,n=0,h=b.get(0),e=document.selection,k=h.selectionStart;if(e&&-1===navigator.appVersion.indexOf("MSIE 10"))a=e.createRange(),a.moveStart("character",-d.val().length),n=a.text.length;else if(k||"0"===k)n=k;return n}catch(A){}},setCaret:function(a){try{if(b.is(":focus")){var p,
d=b.get(0);d.setSelectionRange?d.setSelectionRange(a,a):(p=d.createTextRange(),p.collapse(!0),p.moveEnd("character",a),p.moveStart("character",a),p.select())}}catch(z){}},events:function(){b.on("keydown.mask",function(a){b.data("mask-keycode",a.keyCode||a.which);b.data("mask-previus-value",b.val())}).on(a.jMaskGlobals.useInput?"input.mask":"keyup.mask",d.behaviour).on("paste.mask drop.mask",function(){setTimeout(function(){b.keydown().keyup()},100)}).on("change.mask",function(){b.data("changed",!0)}).on("blur.mask",
function(){c===d.val()||b.data("changed")||b.trigger("change");b.data("changed",!1)}).on("blur.mask",function(){c=d.val()}).on("focus.mask",function(b){!0===e.selectOnFocus&&a(b.target).select()}).on("focusout.mask",function(){e.clearIfNotMatch&&!g.test(d.val())&&d.val("")})},getRegexMask:function(){for(var a=[],b,d,e,k,c=0;c<h.length;c++)(b=m.translation[h.charAt(c)])?(d=b.pattern.toString().replace(/.{1}$|^.{1}/g,""),e=b.optional,(b=b.recursive)?(a.push(h.charAt(c)),k={digit:h.charAt(c),pattern:d}):
a.push(e||b?d+"?":d)):a.push(h.charAt(c).replace(/[-\/\\^$*+?.()|[\]{}]/g,"\\$&"));a=a.join("");k&&(a=a.replace(new RegExp("("+k.digit+"(.*"+k.digit+")?)"),"($1)?").replace(new RegExp(k.digit,"g"),k.pattern));return new RegExp(a)},destroyEvents:function(){b.off("input keydown keyup paste drop blur focusout ".split(" ").join(".mask "))},val:function(a){var d=b.is("input")?"val":"text";if(0<arguments.length){if(b[d]()!==a)b[d](a);d=b}else d=b[d]();return d},calculateCaretPosition:function(a,d){var h=
d.length,e=b.data("mask-previus-value"),k=e.length;8===b.data("mask-keycode")&&e!==d?a-=d.slice(0,a).length-e.slice(0,a).length:e!==d&&(a=a>=k?h:a+(d.slice(0,a).length-e.slice(0,a).length));return a},behaviour:function(e){e=e||window.event;d.invalid=[];var h=b.data("mask-keycode");if(-1===a.inArray(h,m.byPassKeys)){var h=d.getMasked(),c=d.getCaret();setTimeout(function(a,b){d.setCaret(d.calculateCaretPosition(a,b))},10,c,h);d.val(h);d.setCaret(c);return d.callbacks(e)}},getMasked:function(a,b){var c=
[],p=void 0===b?d.val():b+"",k=0,g=h.length,f=0,l=p.length,n=1,v="push",w=-1,r,u;e.reverse?(v="unshift",n=-1,r=0,k=g-1,f=l-1,u=function(){return-1<k&&-1<f}):(r=g-1,u=function(){return k<g&&f<l});for(var y;u();){var x=h.charAt(k),t=p.charAt(f),q=m.translation[x];if(q)t.match(q.pattern)?(c[v](t),q.recursive&&(-1===w?w=k:k===r&&(k=w-n),r===w&&(k-=n)),k+=n):t===y?y=void 0:q.optional?(k+=n,f-=n):q.fallback?(c[v](q.fallback),k+=n,f-=n):d.invalid.push({p:f,v:t,e:q.pattern}),f+=n;else{if(!a)c[v](x);t===x?
f+=n:y=x;k+=n}}p=h.charAt(r);g!==l+1||m.translation[p]||c.push(p);return c.join("")},callbacks:function(a){var f=d.val(),p=f!==c,g=[f,a,b,e],k=function(a,b,d){"function"===typeof e[a]&&b&&e[a].apply(this,d)};k("onChange",!0===p,g);k("onKeyPress",!0===p,g);k("onComplete",f.length===h.length,g);k("onInvalid",0<d.invalid.length,[f,a,b,d.invalid,e])}};b=a(b);var m=this,c=d.val(),g;h="function"===typeof h?h(d.val(),void 0,b,e):h;m.mask=h;m.options=e;m.remove=function(){var a=d.getCaret();d.destroyEvents();
d.val(m.getCleanVal());d.setCaret(a);return b};m.getCleanVal=function(){return d.getMasked(!0)};m.getMaskedVal=function(a){return d.getMasked(!1,a)};m.init=function(c){c=c||!1;e=e||{};m.clearIfNotMatch=a.jMaskGlobals.clearIfNotMatch;m.byPassKeys=a.jMaskGlobals.byPassKeys;m.translation=a.extend({},a.jMaskGlobals.translation,e.translation);m=a.extend(!0,{},m,e);g=d.getRegexMask();if(c)d.events(),d.val(d.getMasked());else{e.placeholder&&b.attr("placeholder",e.placeholder);b.data("mask")&&b.attr("autocomplete",
"off");c=0;for(var f=!0;c<h.length;c++){var l=m.translation[h.charAt(c)];if(l&&l.recursive){f=!1;break}}f&&b.attr("maxlength",h.length);d.destroyEvents();d.events();c=d.getCaret();d.val(d.getMasked());d.setCaret(c)}};m.init(!b.is("input"))};a.maskWatchers={};var c=function(){var b=a(this),c={},e=b.attr("data-mask");b.attr("data-mask-reverse")&&(c.reverse=!0);b.attr("data-mask-clearifnotmatch")&&(c.clearIfNotMatch=!0);"true"===b.attr("data-mask-selectonfocus")&&(c.selectOnFocus=!0);if(l(b,e,c))return b.data("mask",
new f(this,e,c))},l=function(b,c,e){e=e||{};var d=a(b).data("mask"),h=JSON.stringify;b=a(b).val()||a(b).text();try{return"function"===typeof c&&(c=c(b)),"object"!==typeof d||h(d.options)!==h(e)||d.mask!==c}catch(u){}},g=function(a){var b=document.createElement("div"),c;a="on"+a;c=a in b;c||(b.setAttribute(a,"return;"),c="function"===typeof b[a]);return c};a.fn.mask=function(b,c){c=c||{};var e=this.selector,d=a.jMaskGlobals,h=d.watchInterval,d=c.watchInputs||d.watchInputs,g=function(){if(l(this,b,
c))return a(this).data("mask",new f(this,b,c))};a(this).each(g);e&&""!==e&&d&&(clearInterval(a.maskWatchers[e]),a.maskWatchers[e]=setInterval(function(){a(document).find(e).each(g)},h));return this};a.fn.masked=function(a){return this.data("mask").getMaskedVal(a)};a.fn.unmask=function(){clearInterval(a.maskWatchers[this.selector]);delete a.maskWatchers[this.selector];return this.each(function(){var b=a(this).data("mask");b&&b.remove().removeData("mask")})};a.fn.cleanVal=function(){return this.data("mask").getCleanVal()};
a.applyDataMask=function(b){b=b||a.jMaskGlobals.maskElements;(b instanceof a?b:a(b)).filter(a.jMaskGlobals.dataMaskAttr).each(c)};g={maskElements:"input,td,span,div",dataMaskAttr:"*[data-mask]",dataMask:!0,watchInterval:300,watchInputs:!0,useInput:!/Chrome\/[2-4][0-9]|SamsungBrowser/.test(window.navigator.userAgent)&&g("input"),watchDataMask:!1,byPassKeys:[9,16,17,18,36,37,38,39,40,91],translation:{"#":{pattern:/\d/,recursive:!0},A:{pattern:/[a-zA-Z0-9]/},
S:{pattern:/[a-zA-Z]/}}};a.jMaskGlobals=a.jMaskGlobals||{};g=a.jMaskGlobals=a.extend(!0,{},g,a.jMaskGlobals);g.dataMask&&a.applyDataMask();setInterval(function(){a.jMaskGlobals.watchDataMask&&a.applyDataMask()},g.watchInterval)},window.jQuery,window.Zepto);
