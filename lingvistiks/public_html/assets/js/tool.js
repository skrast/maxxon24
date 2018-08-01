var custom_mask_options = {
	translation: {
		'#': {pattern: /\d/, recursive: false},
	}
};
var submit_ajax_form = 0;
var globalTimeout = null;

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

// refresh js script
init_refresh_script = function () {
	$('[data-toggle="tooltip"]').tooltip();
	$('.selectpicker').selectpicker({'container': 'body', 'size': 4});
	$('.selectpicker').selectpicker('refresh');
	$(".confirm").confirm();

	$(".tagit-close").off();
	$(".tagit-close").on('click',function() {
		$(this).parent().fadeOut().remove();
		return false;
	});

	// file uploads
	$(".fileinput-button :file").off();
	$(".fileinput-button :file").on('change', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        $(this).parent().parent().find(".file_name").text(label);
    });
	// file uploads

	$(".fast_filter input[type=radio]").off();
	$(".fast_filter input[type=radio]").on('change', function(event) {
		event.stopPropagation();

		$(this).parents("li").find("input[type=hidden]").val($(this).val());
		$(this).parents("form").submit();
	});

	// массовыее операции
	$(".select_all_checkbox").off();
	$(".select_all_checkbox").on('click',function() {
		var parent_data = $(this).attr("data-target");
		$("."+parent_data).find("input[type=checkbox]").click();
		return false;
	});
	// массовыее операции

	var date = new Date();
	date.setDate(date.getDate());

	$(".toggle-block").off("click");
	$(".toggle-block").on('click', function(event) {
		event.stopPropagation();
		var target_block = $(this).attr("data-target");
		if(target_block) {
			$("."+target_block).toggleClass("hidden");
		} else {
			$(this).next().toggleClass("hidden");
		}
	});

	// смена статусов у сущностей
	$(".change-color li").off("click");
	$(".change-color li").on('click', function(event) {
		event.stopPropagation();

		var status = "0";

		if($(this).hasClass("active")) {
			$(this).parent().find("li").removeClass("active");
		} else {
			$(this).parent().find("li").removeClass("active");
			$(this).addClass("active");
			status = $(this).attr("data-id");
		}

		$(this).parent().next().val(status);
	});
	// смена статусов у сущностей

	$('.summernote').summernote(options_editor);

	$("#search_company").autocomplete(search_company_setup);
	$("#search_company2").autocomplete(search_company_setup); // поиск клиента в модальном окне
	$("#search_member").autocomplete(search_member_setup);

	$('.datepicker').datetimepicker(datetime_settings);

	$(".add_field").off();
	$(".add_field").on('keypress', function(e) {
		if (!e) e = window.event;

		if (e.keyCode == '13') {
			var curent_value = $(this).val();
			var get_fields = $(this).parent().find(".get_fields");
			var get_fields_input = $(this).clone().removeClass("add_field");
			if(curent_value) {
				get_fields_input.val(curent_value).appendTo(get_fields);
				/*if($(this).hasClass("phone_mask").not(".no_mask")) {
					get_fields_input.mask(phone_format, custom_mask_options);
				}*/
				$(this).val("");
			}

			$(".check_copy").prev().find("input").off();
			$(".check_copy").prev().find("input").on('change',function() { // редактирование новых добавленных, но не сохраненных
				checkCopy($(this).parent().attr("data-id"), $(this).val(), $(this).parent().attr("data-get"));
				return false;
			});
			if($(this).hasClass("check_copy")) { // добавление нового
				checkCopy($(this).attr("data-id"), curent_value, $(this).attr("data-get"));
			}

			init_change_mask();
			return false;
		}

		$(".check_copy").prev().find("input").off();
		$(".check_copy").prev().find("input").on('change',function() { // редактирование новых добавленных, но не сохраненных
			checkCopy($(this).parent().attr("data-id"), $(this).val(), $(this).parent().attr("data-get"));
			return false;
		});
		if($(this).hasClass("check_copy")) { // добавление нового
			checkCopy($(this).attr("data-id"), curent_value, $(this).attr("data-get"));
		}
	});

	// инлайн строки
	$(".add_field_line input[type=text]").off();
	$(".add_field_line input[type=text]").on('keypress', function(e) {
		if (!e) e = window.event;

		if (e.keyCode == '13') {
			var curent_value = $(this).val();
			var curent_html = $(this).parents(".add_field_line").find("li").clone();
			var get_fields = $(this).parents(".add_field_line").parent().find(".get_fields");
			var get_fields_input = $(this).parents(".add_field_line").find("li").clone().removeClass("add_field_line");
			if(curent_value) {
				get_fields_input.val(curent_html).appendTo(get_fields);
				/*if($(this).hasClass("phone_mask").not(".no_mask")) {
					get_fields_input.find(".phone_mask").not(".no_mask").mask(phone_format, custom_mask_options);
				}*/
				$(this).val("");
			}

			$(".check_copy").parents(".add_field_line").prev().find("input").off();
			$(".check_copy").parents(".add_field_line").prev().find("input").on('change',function() { // редактирование новых добавленных, но не сохраненных
				checkCopy($(this).attr("data-id"), $(this).val(), $(this).attr("data-get"));
				return false;
			});
			if($(this).hasClass("check_copy")) { // добавление нового
				checkCopy($(this).attr("data-id"), curent_value, $(this).attr("data-get"));
			}

			init_change_mask();

			return false;
		}

		$(".check_copy").parents(".add_field_line").prev().find("input").off();
		$(".check_copy").parents(".add_field_line").prev().find("input").on('change',function() { // редактирование новых добавленных, но не сохраненных
			checkCopy($(this).attr("data-id"), $(this).val(), $(this).attr("data-get"));
			return false;
		});
		if($(this).hasClass("check_copy")) { // добавление нового
			checkCopy($(this).attr("data-id"), curent_value, $(this).attr("data-get"));
		}
	});
	// инлайн строки

	$(".add_line").off();
	$(".add_line").on("click",function() {
		var line = $(this).prev().clone();
		line.insertAfter($(this).prev());
		line.find("input").val("");
		//$(this).prev().find("input.phone_mask").not(".no_mask").mask(phone_format, custom_mask_options);
	});

	// смена маски для сохраненных данных
	init_change_mask();
	// смена маски для сохраненных данных

	// маски для телефонов
	init_put_mask();
	// маски для телефонов
},
// refresh js script

// постановка маски для сохраненных данных
init_put_mask = function () {
	// сохранение оригинала телефона
	$("input[name*=phone]").each(function() {
		$(this).attr("data-number-phone", $(this).val());
	});
	$("input[name*=phone]").on('change', function() {
		$(this).attr("data-number-phone", $(this).val());
	});
	$("input[name*=phone]").keyup(function() {
		$(this).attr("data-number-phone", $(this).val());
	});
	// сохранение оригинала телефона

	$("input[name*=phone], .phone_mask").not(".no_mask").not(".stop_mask").mask(phone_format, custom_mask_options);
	$("input[name*=phone].no_mask, .phone_mask.no_mask, .custom_mask").not(".stop_mask").each(function() {
		var mask = $( this ).attr("data-mask");
		$( this ).mask(mask, custom_mask_options);
	});
},
// постановка маски для сохраненных данных

// смена маски для сохраненных данных
init_change_mask = function () {
	$(".change_phone_mask").off();
	$(".change_phone_mask").on("change",function(e) {
		e.preventDefault();

		var mask = $(this).find("option:selected").attr("data-mask");
		var input_phone = $(this).parent().next().find("input[name*=phone]");
		var mask_old = input_phone.attr("data-mask");
		if(mask==mask_old) {
			return false;
		}

		input_phone.attr("data-mask", mask);
		input_phone.val($(this).parent().next().find("input[name*=phone]").cleanVal());

		input_phone.mask(mask, custom_mask_options);
		return false;
	});
},
// смена маски для сохраненных данных

// init script
init_script = function () {
	// поиск
	$('.tm-search').off();
	$('.tm-search').on('click', function(e){
        e.preventDefault();
        $('body').toggleClass('search-toggled');
		$('<div class="search-box-bg"></div>').insertAfter('.top-search-wrap');
    });
	$('.top-search-close').off();
    $('.top-search-close').on('click', function(e){
        e.preventDefault();
        $('body').toggleClass('search-toggled');
		$('.search-box-bg').remove();
    });
    // поиск

	// filter
	$('.show-filter').off();
	$('.show-filter').on('click', function(e){
        e.preventDefault();
        $(this).parents(".datatables").find('.search-section').toggleClass('toggled');
    });
    // filter

	// дополнительные поля
	$(".show_field").fadeOut();
	//$('select[name=operation]').off();
	$('select[name=operation]').on('change',function() {
		var this_value = $(this).val();
		var show_droplist = $(this).find(':selected').attr("data-show");
		$(".show_field").fadeOut();

		if(show_droplist) {
			$("."+show_droplist).fadeIn();
		}
		$('.selectpicker').selectpicker('refresh');
	});
	// дополнительные поля
	// массовыее операции

	// показ статусов для сделок
	$(".show_history_status").off();
	$(".show_history_status").on('click', function(){
		var void_id = $(this).attr("data-void");
		var mode = $(this).attr("data-mode");

		$.ajax({
			url: ave_path+'?do='+mode+'&sub=show_history_status',
			data: {void_id: void_id, mode: mode, csrf_token: csrf_token},
			success: function( data ) {
				$(".list_status").html(data.html);
				$(".show_history_status_clear").removeClass("hidden");
			}
		});
	});
	$(".show_history_status_clear").on('click', function(){
		$(".list_status").html("");
		$(".show_history_status_clear").addClass("hidden");
	});
	// показ статусов для сделок
},
// init script

// init_history
init_history = function () {
	// загрузка истории и комментариев для сущностей

	var elem_limit = $(".history_load_before").attr("data-limit");
	var start = $(".history_list").length;
	if(elem_limit>start) {
		$(".history_load_before, .history_load_more").hide();
	}

	$(".history_load_more").off();
	$(".history_load_more").on('click', function() {
		var elem_limit = $(".history_load_before").attr("data-limit");
		var elem_id = $(this).attr("data-id");
		var type = $(this).attr("data-type");
		var start = $(".history_list").length;

		var history_text = $(".history_filter input[type=text]").val();
		var history_comment_sys = $(".history_filter input[name=history_comment_sys]:checked").val();
		var history_comment_com = $(".history_filter input[name=history_comment_com]:checked").val();

		$(".history_load_before").removeClass("hidden").show();

		$.ajax({
			url: ave_path+'?do=history&sub=history_list_ajax',
			data: {elem_id: elem_id, type: type, start: start, history_text: history_text, history_comment_sys: history_comment_sys, history_comment_com: history_comment_com,  csrf_token: csrf_token},
			success: function( data ) {
				$(".history_load_before").before(data.html);

				if(data.html=='') {
					$(".history_load_more").hide();
				}

				$(".history_load_before").hide();

				init_form();
			}
		});
	});
	// загрузка истории и комментариев для сущностей

	init_history_search();
},
history_timer = null,
history_last_search = '',
init_history_search = function () {

	$(".history_filter input[type=checkbox]").off();
	$(".history_filter input[type=checkbox]").on('change',function() {
		$(this).parents("form").submit();
	});

	$(".history_filter input[type=text]").off();
	$(".history_filter input[type=text]").on('keyup', function() {
		if (history_timer != null) clearTimeout(history_timer); // очистка таймера

		if ($(this).val() != '' && history_last_search == $(this).val()) return false;
		history_last_search = $(this).val();

		var this_form = $(this);
		history_timer = setTimeout(function() {
			history_timer = null;

			this_form.parents("form").submit();
		}, 800);
    });

	$(".history_filter").on('submit', function() {
		var form_data = $(this).formSerialize();

		$(".history_load_before").removeClass("hidden").show();
		$(".history_list").fadeOut().remove();
		$(".history_load_more").show();

		$(".history_load_more").trigger('click');

		return false;
	});


},
// init_history


// init dialog
init_dialog = function () {
	// bild fast_link
	var fastPush = $(".fast_link_for_push");
	fastPush.dockmodal({
		minimizedWidth: 240,
		dialogClass: 'dockmodal_new_message',
		height: 420,
		showClose: false,
		title: function() {
		  return $(".fast_link_for_push").attr("data-title");
		},
		initialState: "minimized"
	});
	// bild fast_link

	// bild dialog
	if($(".show_bild_dialog").attr("data-title")!==undefined) {
		var findPush = $(".show_bild_dialog");
		findPush.dockmodal({
			minimizedWidth: 240,
			dialogClass: 'dockmodal_new_message',
			height: 420,
			showClose: false,
			title: function() {
			  return $(".show_bild_dialog").attr("data-title")+' (<span class="count">0</span>) <span class="vert text-success" style="padding-left:5px"></span></span>';
			},
			initialState: "minimized"
		});

		function load_message(timestamp) {
			if (globalTimeout != null) clearTimeout(globalTimeout); // очистка таймера

			globalTimeout = setTimeout(function() {
		        globalTimeout = null;

				$.ajax({
					url: ave_path+'?do=task&sub=load_new_message',
					data: {'timestamp' : timestamp, csrf_token: csrf_token},
					success: function( data ) {
						if(data.message_count) {
							globalClear = null;

							var curent_count = $('.dockmodal_new_message .title-text .count').text();
							if(data.message_count != curent_count) {

								$('#playAudio')[0].play();

								var vert = data.message_count-curent_count;
								if(vert>0) {
									vert = "+"+vert;
									$('.dockmodal_new_message .title-text .vert').removeClass("text-danger");
								}
								if(vert<0) {
									$('.dockmodal_new_message .title-text .vert').addClass("text-danger");
								}
								$('.dockmodal_new_message .title-text .vert').text(vert);

								// scrool top
								$(".dockmodal_new_message .dockmodal-body").animate({
									scrollTop: $("html").offset().top
								}, 1000);
								// scrool top
							}

							$('.dockmodal_new_message .title-text .count').text(data.message_count);
							$('.dockmodal_new_message .dialog-content-block').html(data.html);

							init_form();

						} else {
							$('.dockmodal_new_message .dialog-content-block').html("");
							$('.dockmodal_new_message .title-text .vert').text("");
							$('.dockmodal_new_message .title-text .count').text("0");
						}

						if($('.dockmodal_new_message .dialog-content-block li').length < 1) {
							$(".dockmodal_new_message .dockmodal-body .empty").show();
						} else {
							$(".dockmodal_new_message .dockmodal-body .empty").hide();
						}

						load_message(data.timestamp);
					}
				});
			}, 5000);
		}
		load_message();
	}
	// bild dialog

	// bild project_link
	var ProjectPush = $(".project_link_for_push");
	ProjectPush.dockmodal({
		minimizedWidth: 240,
		dialogClass: 'dockmodal_project',
		height: 420,
		showClose: false,
		title: function() {
		  return $(".project_link_for_push").attr("data-title");
		},
		initialState: "minimized"
	});
	// bild project_link
},
// init dialog

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

		var form_address = (is_module == 1) ? ave_path+'?do=module&sub=mod_edit&module_tag='+type+'&module_action='+sub : ave_path+'?do='+type+'&sub='+sub;

		$.ajax({
			url: form_address,
			data: {void_id: void_id, essense_id:essense_id, type: type, ref: ref, request_desc: request_desc, void_type: void_type, csrf_token: csrf_token},
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
						this_form.find(".actions input").attr("disabled", true);

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

		this_form.find(".actions input").attr("disabled", true);

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

				if(data.status == 'success' && stop_reset==1) {
					this_form.resetForm();
				}

				this_form.find(".actions input").attr("disabled", false);
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
	    	init_script(),
	    	init_refresh_script(),
	    	init_form(),
			init_dialog()
	    }
	}
}();
// init app


$(function () {
	$.ajaxSetup({
		dataType: "json",
		type: "POST"
	});

	// init
	App.init();
	// init
});

function checkCopy(company_id, check_var, chow_class) {
	$("."+chow_class).addClass("hidden");
	$("."+chow_class+" .project-block").html("");

	$.ajax({
		url: ave_path+'?do=company&sub=check_copy',
		data: {"company_id": company_id, "check_var": check_var, csrf_token: csrf_token},
		success: function( data ) {
			if(data.html) {
				$("."+chow_class).removeClass("hidden");
				$("."+chow_class+" .project-block").html(data.html);
				init_put_mask();
				//$("."+chow_class+" .project-block .phone_mask").mask(phone_format, custom_mask_options);
			}
		}
	});
}


// autocomplete option
var search_company_setup = {
	serviceUrl: ave_path+'?do=company&sub=client_search&outside=1',
	minChars: 2,
	delimiter: /(,|;)\s*/,
	maxHeight: 400,
	width: 300,
	zIndex: 99999,
	deferRequestBy: 500,
	onSelect: function(suggestion) {
		$("input[name=user_company]").val(suggestion.data.company_id);
		$("input[name=show_company]").val(suggestion.data.company_id);
	},
};
var search_member_setup = {
	serviceUrl: ave_path+'?do=ajax&sub=search_member',
	minChars: 2,
	delimiter: /(,|;)\s*/,
	maxHeight: 400,
	width: 300,
	zIndex: 99999,
	deferRequestBy: 500,
	onSelect: function(suggestion) {

		$(".get_member").append('<li>'+suggestion.data.user_link+'<a class="tagit-close"><span class="text-icon">×</span></a><input value="'+suggestion.data.id+'" name="task_member[]" type="hidden"></li>');

		$(".tagit-close").on('click',function() {
			$(this).parent().fadeOut().remove();
			return false;
		});

		return false;
	},
};
// autocomplete option


// editor option
var options_editor = {
	dialogsInBody: true,
	height: 400,
	lang: 'ru-RU',
	toolbar: [
	//[groupname, [button list]]
		['style', ['bold', 'italic', 'underline', 'clear']],
		['table', ['table']],
		['para', ['ul', 'ol', 'paragraph']],
		['insert', ['link', 'picture']],
	//['view', ['codeview']],
	]
};
// editor option

// chart option
var options_charts = {
	lineSmooth: false,
	chartPadding: {
		top: 20,
		right: 80,
		bottom: 0,
		left: 10
	},
	axisY: {
		onlyInteger: true,
		offset: 80,
		labelOffset: {
	        x: -10,
	        y: 10
	      },
	},
	axisX: {
		labelOffset: {
	        x: -10,
	        y: 10
	      },
	},
	low: 0,
	showArea: true,
};
var options_bar = {
	seriesBarDistance: 20,

	axisY: {
		onlyInteger: true,
		offset: 80,
		labelOffset: {
	        x: 0,
	        y: 10
	      },
		  width: 50,
	},
};
var options_pie = {
	donut: true,
	stackBars: true,
	showLabel: false
};
var responsiveOptions = [
  ['screen and (min-width: 540px)', {
    chartPadding: 50,
    labelOffset: 50,
    labelDirection: 'explode',
  }],
  ['screen and (min-width: 1024px)', {
    labelOffset: 50,
    chartPadding: 50
  }]
];
// chart option


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
