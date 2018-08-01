<main class="sidebar-left">
	<div class="container">

		{{ breadcrumb }}

		<h1>{{ lang.lk_title }}</h1>
		<div class="row">

			{{ perfomens_col }}

			<div class="col-md-9">
				<div class="content-right">
					<form action="{{ HOST_NAME }}/profile/" method="post" class="ajax_form" enctype="multipart/form-data">
						<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
						<input type="hidden" name="save" value="1">

						<div class="row">
							<div class="col-md-3">
								<a href="#" class="crop-image-btn" {% if SESSION.user_photo and SESSION.user_photo != 'no-avatar.png' %}data-photo="1"{% endif %} data-img="{{ ABS_PATH }}{{ app.app_upload_dir }}/{{ app.app_users_orig_dir }}/{{ SESSION.user_photo }}" data-type="1">
									{% if SESSION.user_photo %}
										<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/users/{{SESSION.user_photo}}&width=250&height=300" alt="" class="img-responsive">
									{% else %}
										<img src="{{ ABS_PATH }}assets/site/template/images/no-photo.png" alt="" class="img-responsive">
									{% endif %}
								</a>
								<div class="file-upload-profile">
								  <label>
									  <input type="file" name="user_photo" id="upload-photo">
									  <span>{{ lang.lk_upload_photo }}</span>
								  </label>
								</div>
							</div>

							<div class="col-md-9">

								<div class="row">
									<div class="col-md-6">
										<div class="form-group text-login">
											<div class="profile-feedback">
												<div class="left-border">
													
													<input type="text" class="form-control" name="user_login" placeholder="{{ lang.auth_field_login }}" value="{{ profile_info.user_login|escape|stripslashes }}" autocomplete="off" title="{{ lang.lk_login_desc }}"> 
													
												</div>
											</div>
										</div>
		
										<div class="form-group">
											<input type="text" class="form-control" name="user_email" placeholder="{{ lang.auth_field_email }}" value="{{ profile_info.user_email|escape|stripslashes }}">
										</div>
		
										<div class="form-group">
											<input type="text" class="form-control" name="user_firstname" placeholder="{{ lang.profile_firstname }}" value="{{ profile_info.user_firstname|escape|stripslashes }}">
										</div>
		
										<div class="form-group">
											<select class="form-control selectpicker load_select" name="user_country" data-target="user_city" placeholder="{{ lang.profile_country_default }}" data-live-search="true">
												<option value="">{{ lang.profile_country_default }}</option>
												{% for country in country_list %}
													<option value="{{ country.id|escape|stripslashes }}" {% if country.id == profile_info.user_country %}selected{% endif %}>{{ country.title|escape|stripslashes }}</option>
												{% endfor %}
											</select>
										</div>
		
										<div class="form-group">
											<input type="text" class="form-control datetime date_mask" name="user_birthday" placeholder="{{ lang.profile_birthday }}" value="{{ profile_info.user_birthday_clean|escape|stripslashes }}">
										</div>
		
										<div class="form-group">
											<select class="form-control selectpicker" name="user_default_lang" data-live-search="true">
												<option value="">{{ lang.profile_lang_default }}</option>
												{% for lang in lang_list %}
													<option value="{{ lang.id|escape|stripslashes }}" {% if lang.id == profile_info.user_default_lang %}selected{% endif %}>{{ lang.title|escape|stripslashes }}</option>
												{% endfor %}
											</select>
										</div>
									</div>
		
									<div class="col-md-6">
										<div class="profile-feedback text-right right-blue-info">
											<div class="owner">{{ profile_info.full_user_id }}</div>
										</div>
		
										<div class="form-group">
											<input type="text" class="form-control" name="user_lastname" placeholder="{{ lang.profile_lastname }}" value="{{ profile_info.user_lastname|escape|stripslashes }}">
										</div>
		
										<div class="form-group">
											<div class="row">
												<div class="col-md-8">
													<select class="form-control selectpicker" name="user_city" data-default="{{ lang.profile_city_default }}" placeholder="{{ lang.profile_city_default }}" data-live-search="true">
														{% for city in city_list %}
															<option value="{{ city.id|escape|stripslashes }}" {% if city.id == profile_info.user_city %}selected{% endif %} data-utm="{{ city.utm }}">{{ city.title|escape|stripslashes }}</option>
														{% else %}
															<option value="">{{ lang.profile_city_default }}</option>
														{% endfor %}
													</select>
												</div>
												<div class="col-md-4">
													<select name="user_timezone" class="form-control selectpicker" data-live-search="true">
														<option value="0">{{ lang.profile_group_no_select }}</option>
														{% for key, zone in zone %}
															<option value="{{ zone }}" {% if zone == profile_info.user_timezone or (not profile_info.user_timezone and key == 0) %}selected{% endif %}>{{ zone }}</option>
														{% endfor %}
													</select>
												</div>
											</div>
										</div>
		
										<div class="form-group">
											<input type="text" class="form-control phone_mask" name="user_phone" placeholder="{{ lang.auth_field_phone }}" value="{{ profile_info.user_phone|escape|stripslashes }}">
										</div>
		
										<div class="form-group">
											<input type="text" class="form-control" name="user_skype" placeholder="{{ lang.auth_field_skype }}" value="{{ profile_info.user_skype|escape|stripslashes }}">
										</div>
									</div>
								</div>
							</div>

						</div>


						<div class="clearfix"></div>

						<div class="profile-block">
							<div class="info_about_company {% if 1 == profile_info.user_type_form %}hidden{% endif %}">
								<div class="clearfix"></div>
								<h3>{{ lang.lk_user_company_info }}</h3>
								<div class="company-info">
									<div class="row">
										<div class="col-md-3">
											<a href="#" class="crop-image-photo-btn" {% if company_info.company_photo %}data-photo="1"{% endif %} data-img="{{ ABS_PATH }}{{ app.app_upload_dir }}/{{ app.app_company_orig_dir }}/{{ company_info.company_photo }}" data-type="2">
												{% if company_info.company_photo %}
													<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/{{ app.app_company_dir }}/{{ company_info.company_photo }}&width=200&height=200" alt="" class="img-responsive">
												{% else %}
													<img src="" alt="" class="img-responsive hidden">
													<div class="company_photo_empty">{{ lang.lk_user_company_photo_empty }}</div>
												{% endif %}
											</a>
											<div class="file-upload-profile">
											  <label>
												  <input type="file" name="company_photo" id="company-upload-photo">
												  <span>{{ lang.lk_upload_photo }}</span>
											  </label>
											</div>
										</div>
										<div class="col-md-9">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<input type="text" class="form-control" placeholder="{{ lang.lk_user_company_title }}" name="company_title" value="{{ company_info.company_title|escape|stripslashes }}">
													</div>
													<div class="form-group">
														<input type="text" class="form-control" placeholder="{{ lang.lk_user_company_type }}" name="company_type" value="{{ company_info.company_type|escape|stripslashes }}">
													</div>
													<div class="form-group">
														<input type="text" class="form-control" placeholder="{{ lang.lk_user_company_site }}" name="company_site" value="{{ company_info.company_site|escape|stripslashes }}">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<input type="text" class="form-control" placeholder="{{ lang.lk_user_company_group }}" name="company_group" value="{{ company_info.company_group|escape|stripslashes }}">
													</div>
													<div class="form-group">
														<input type="text" class="form-control" placeholder="{{ lang.lk_user_company_email }}" name="company_email" value="{{ company_info.company_email|escape|stripslashes }}">
													</div>
													<div class="form-group">
														<input type="text" class="form-control phone_mask" placeholder="{{ lang.lk_user_company_phone }}" name="company_phone" value="{{ company_info.company_phone|escape|stripslashes }}">
													</div>
												</div>
												<div class="col-md-12">
													<div class="form-group">
														<input type="text" class="form-control" placeholder="{{ lang.lk_user_company_address }}" name="company_address" value="{{ company_info.company_address|escape|stripslashes }}">
													</div>

													<div class="form-group">
														<textarea class="form-control" placeholder="{{ lang.lk_user_company_desc }}" rows="6" name="company_desc">{{ company_info.company_desc|escape|stripslashes }}</textarea>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<h3>{{ lang.lk_pay }}</h3>
							<div class="row text-center pay_variant">
								<div class="checkbox">
									<div class="col-md-6">
										<label>
											<input class="checkbox-input" type="checkbox" name="user_pays[]" value="1" {% if 1 in profile_info.user_pays %}checked{% endif %}>
						                    <span class="checkbox-custom"></span>
											<span class="label">
												<img src="{{ ABS_PATH }}assets/site/template/images/card-{{ SESSION.user_lang|default(app.app_lang) }}.png" alt="" class="img-responsive">
											</span>
										</label>
									</div>
									<div class="col-md-6">
										<label>
											<input class="checkbox-input" type="checkbox" name="user_pays[]" value="2" {% if 2 in profile_info.user_pays %}checked{% endif %}>
											<span class="checkbox-custom"></span>
											<span class="label">
												<img src="{{ ABS_PATH }}assets/site/template/images/bank-{{ SESSION.user_lang|default(app.app_lang) }}.png" alt="" class="img-responsive">
											</span>
										</label>
									</div>
									<div class="col-md-4 hidden">
										<label>
											<input class="checkbox-input" type="checkbox" name="user_pays[]" value="3" {% if 3 in profile_info.user_pays %}checked{% endif %}>
											<span class="checkbox-custom"></span>
											<span class="label">
												<img src="{{ ABS_PATH }}assets/site/template/images/nal-{{ SESSION.user_lang|default(app.app_lang) }}.png" alt="" class="img-responsive">
											</span>
										</label>
									</div>
									<div class="clearfix"></div><br>
									<div class="col-md-6">
										<label>
											<input class="checkbox-input" type="checkbox" name="user_pays[]" value="4" {% if 4 in profile_info.user_pays %}checked{% endif %}>
											<span class="checkbox-custom"></span>
											<span class="label">
												<img src="{{ ABS_PATH }}assets/site/template/images/ymoney.png" alt="" class="img-responsive">
											</span>
											
										</label>
									</div>
									<div class="col-md-6">
										<label>
											<input class="checkbox-input" type="checkbox" name="user_pays[]" value="5" {% if 5 in profile_info.user_pays %}checked{% endif %}>
											<span class="checkbox-custom"></span>
											<span class="label">
												<img src="{{ ABS_PATH }}assets/site/template/images/paypal.png" alt="" class="img-responsive">
											</span>
										</label>
									</div>
								</div>
							</div>

							<h3>{{ lang.lk_photoalbum }}</h3>
							<div class="row">
								<div class="col-md-4">

									<div class="filer-uploader minimal-uploader">
										<input type="file" name="file_path">
									</div>

								</div>
								<div class="col-md-8 text-center">
									{% if album_list %}
										<div id="carousel_album" class="owl-carousel album-carousel">
											{% for file in album_list %}
												<div>
													<a href="{{ ABS_PATH }}{{ app.app_upload_dir }}/{{ app.app_album_dir }}/{{ profile_info.id }}/{{ file.file_path|escape|stripslashes }}" data-lightbox="image-2">
														<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/{{ app.app_album_dir }}/{{ profile_info.id }}/{{ file.file_path|escape|stripslashes }}&width=100&height=100" style="height:100px;width:100px;" alt="">
													</a>

													<div class="checkbox">
														<label>
															<input class="checkbox-input" type="checkbox" name="delete_file_attach[]" value="{{ file.file_id }}">
															<span class="checkbox-custom"></span>
															<span class="label">{{ lang.save_delete }}</span>
														</label>
													</div>
												</div>
											{% endfor %}
										</div>
									{% else %}
										<div class="album_empty">{{ lang.lk_album_empty }}</div>
									{% endif %}
								</div>
							</div>


							<div class="clearfix"></div>
							<h3>{{ lang.lk_notice }}</h3>
							<div class="row">
								<div class="col-md-6 check_notice">
									{% for key, notice in lang.lk_notice_array %}
										<div class="col-md-6">
											<div class="radio">
												<label>
													<input class="radio-input main-variant" type="radio" name="user_notice" value="{{ key }}" {% if profile_info.user_notice == key %}checked{% endif %}>
													<span class="radio-custom"></span>
													<span class="label">{{ notice.title }}</span>
												</label>
											</div>

											{% if notice.var %}
												<ul class="list-unstyled">
													{% for key2, var2 in notice.var %}
														<li>
															<div class="checkbox">
																<label>
																	<input class="checkbox-input more_variant" type="checkbox" name="user_notice_var[]" value="{{ key2 }}" {% if profile_info.user_notice == 1 and key2 in profile_info.user_notice_var %}checked{% endif %} {% if profile_info.user_notice == 2 %}disabled{% endif %}>
																	<span class="checkbox-custom"></span>
																	<span class="label">{{ var2 }}</span>
																</label>
															</div>
														</li>
													{% endfor %}
												</ul>
											{% endif %}
										</div>
									{% endfor %}
								</div>
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<input type="password" class="form-control" name="user_password_old" placeholder="{{ lang.lk_password_old }}">
											</div>

											<div class="error-message-color error-message-password"></div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<input type="password" class="form-control" name="user_password_new" placeholder="{{ lang.lk_password_new }}">
											</div>

											<div class="form-group">
												<input type="password" class="form-control" name="user_password_new_copy" placeholder="{{ lang.lk_password_new_copy }}">
											</div>

											<button type="submit" class="btn btn-block btn-go-on btn-update-password">{{ lang.btn_update }}</button>
										</div>
									</div>
								</div>
							</div>


							<div class="row">
								<div class="col-md-offset-3 col-md-6">
									<button type="submit" class="btn btn-block btn-search">{{ lang.btn_save }}</button>

									<div class="error-message-color error-message"></div>
								</div>
							</div>

						</div>
					</form>

				</div>
			</div>
		</div>
	</div>
</main>


<link rel="stylesheet" href="{{ ABS_PATH }}vendor/assets/fileuploader/css/jquery-filer.css" type="text/css">
<link rel="stylesheet" href="{{ ABS_PATH }}vendor/assets/fileuploader/css/jquery.filer.css" type="text/css">
<link rel="stylesheet" href="{{ ABS_PATH }}vendor/assets/fileuploader/css/jquery.filer-dragdropbox-theme.css" type="text/css">
<script src="{{ ABS_PATH }}vendor/assets/fileuploader/js/jquery.filer.min.js"></script>
<script type="text/javascript">

	$(document).ready(function() {

	// enable fileuploader plugin
	$('.filer-uploader input[type="file"]').fileuploader({
		extensions: [{% for ext in app_allow_img %}'{{ ext }}',{% endfor %}],
		changeInput: '<div class="fileuploader-input">' +
						  '<div class="fileuploader-input-inner">' +
							  '<img src="{{ ABS_PATH }}vendor/assets/fileuploader/images/fileuploader-dragdrop-icon.png">' +
							  '<h3 class="fileuploader-input-caption"><span>{{ lang.storage_add_desc }}<br>{{ lang.storage_add_desc_allow }} {% for ext in app_allow_img %}{{ ext }} {% endfor %}</span></h3>' +
							  '<div class="fileuploader-input-button"><span>{{ lang.storage_add_desc_select }}</span></div>' +
						  '</div>' +
					  '</div>',
		theme: 'dragdrop',
		upload: {
			url: '{{ HOST_NAME }}/profile/album/',
			data: {csrf_token: csrf_token},
			type: 'POST',
			enctype: 'multipart/form-data',
			start: true,
			synchron: true,
			beforeSend: null,
			onSuccess: function(data, item) {
				/*var data = {};

				try {
					data = JSON.parse(result);
				} catch (e) {
					data.hasWarnings = true;
				}*/

				// if success
				if (data.isSuccess && data.files[0]) {
					item.name = data.files[0].name;
					item.html.find('.column-title > div:first-child').text(data.files[0].name).attr('title', data.files[0].name);
				}

				// if warnings
				if (data.hasWarnings) {

					for (var warning in data.warnings) {
						alert(data.warnings);
					}

					item.html.removeClass('upload-successful').addClass('upload-failed');
					// go out from success function by calling onError function
					// in this case we have a animation there
					// you can also response in PHP with 404
					return this.onError ? this.onError(item) : null;
				}

				item.html.find('.column-actions').append('<a class="fileuploader-action fileuploader-action-remove fileuploader-action-success" title="{{ lang.save_delete }}"><i></i></a>');
				setTimeout(function() {
					item.html.find('.progress-bar2').fadeOut(400);
				}, 400);
			},
			onError: function(item) {
				var progressBar = item.html.find('.progress-bar2');

				if(progressBar.length > 0) {
					progressBar.find('span').html(0 + "%");
					progressBar.find('.fileuploader-progressbar .bar').width(0 + "%");
					item.html.find('.progress-bar2').fadeOut(400);
				}

				item.upload.status != 'cancelled' && item.html.find('.fileuploader-action-retry').length == 0 ? item.html.find('.column-actions').prepend(
					'<a class="fileuploader-action fileuploader-action-retry" title="{{ lang.save_repeat }}"><i></i></a>'
				) : null;
			},
			onProgress: function(data, item) {
				var progressBar = item.html.find('.progress-bar2');

				if(progressBar.length > 0) {
					progressBar.show();
					progressBar.find('span').html(data.percentage + "%");
					progressBar.find('.fileuploader-progressbar .bar').width(data.percentage + "%");
				}
			},
			onComplete: null,
		},
		captions: {
			feedback: '{{ lang.storage_add_desc }}',
			feedback2: '{{ lang.storage_add_desc }}',
			drop: '{{ lang.storage_add_desc }}',
			cancel: '{{ lang.save_reset }}',
			confirm: '{{ lang.save_close }}',
			name: '{{ lang.storage_file_name }}',
			type: '{{ lang.storage_file_type }}',
			size: '{{ lang.storage_file_size }}',
			dimensions: '{{ lang.storage_file_dimensions }}',
			remove: '{{ lang.save_delete }}',
		},
	});

});
</script>


<script src="{{ ABS_PATH }}vendor/assets/moment-with-locales.js"></script>
<script type="text/javascript" src="{{ ABS_PATH }}vendor/assets/flatpickr/dist/flatpickr.js"></script>
<link rel="stylesheet" type="text/css" href="{{ ABS_PATH }}vendor/assets/flatpickr/dist/themes/airbnb.css" />
<script src="{{ ABS_PATH }}vendor/assets/flatpickr/dist/l10n/{{ SESSION.user_lang|default(app.app_lang) }}.js" charset="UTF-8"></script>

<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/assets/lightbox/css/lightbox.css" />
<script src="{{ ABS_PATH }}assets/site/assets/lightbox/js/lightbox.min.js"></script>

<script src="{{ ABS_PATH }}assets/site/assets/jquery-mousewheel/jquery.mousewheel.min.js"></script>

<script>
$(function () {
	// init
	App.profile_core();
	// init
});
</script>
<script type="text/javascript">
	  $(function () {
		  var owl_album = $('#carousel_album');
		  owl_album.owlCarousel({
			  items:3,
			  autoplay:true,
			  nav:true,
			  autoWidth:true,
			  margin:0,
			  loop:false,
			  responsive:{
	                        0:{
	                            items:1
	                        },
	                        600:{
	                            items:2
	                        },
	                        1000:{
	                            items:4
	                        }
	                      },
			  navText:["<img src='{{ ABS_PATH }}assets/site/template/images/prev.png'>","<img src='{{ ABS_PATH }}assets/site/template/images/next.png'>"]
		  });
		  owl_album.on('mousewheel', '.owl-stage', function (e) {
			    if (e.deltaY>0) {
			        owl_album.trigger('next.owl');
			    } else {
			        owl_album.trigger('prev.owl');
			    }
			    e.preventDefault();
			});

		  $('#carouselFour').owlCarousel({
	          items:4,
	          autoplay:false,
	          nav:true,
	          autoWidth:true,
	          margin:50,
	          loop:true,
	          responsive:{
	                        0:{
	                            items:1
	                        },
	                        600:{
	                            items:2
	                        },
	                        1000:{
	                            items:4
	                        }
	                      },
	          navText:["<img src='{{ ABS_PATH }}assets/site/template/images/prev.png'>","<img src='{{ ABS_PATH }}assets/site/template/images/next.png'>"]
	        });

	  });
</script>


<script src="{{ ABS_PATH }}assets/site/assets/jquery.imgareaselect-0.9.10/scripts/jquery.imgareaselect.js"></script>
<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/assets/jquery.imgareaselect-0.9.10/css/imgareaselect-default.css" />

<div class="modal fade" id="crop-image" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-close">
				<button type="button" class="close pull-right" data-dismiss="modal" aria-hidden="true">{{ lang.btn_close }} x</button>
			</div>
			<p class="img-title">{{ lang.lk_crop_photo }}</p>

			<div class="align_center">
				<div class="align_center_to_left">
					<div class="crop_container align_center_to_right">
						<span id="thumbnail" style="display: inline-block;">
							<img src="{{ ABS_PATH }}{{ app.app_upload_dir }}/{{ app.app_users_orig_dir }}/{{ SESSION.user_photo }}" alt="" id="crop_photo" >
						</span>
					</div>
				</div>
			</div>

			<form action="{{ HOST_NAME }}/profile/" method="post">
				<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
				<input type="hidden" name="crop_photo" value="1" id="crop_photo_type">
				<input id="x1" type="hidden" name="x1" value="0">
				<input id="y1" type="hidden" name="y1" value="0">
				<input id="x2" type="hidden" name="x2" value="200">
				<input id="y2" type="hidden" name="y2" value="250">

				<div class="form-group text-center">
					<div class="col-md-6">
						<input type="submit" class="btn btn-block btn-search" name="save" value="{{ lang.btn_save_and_work }}">
					</div>
					<div class="col-md-6">
						<a href="{{ HOST_NAME }}/profile/" class="btn btn-block btn-search">{{ lang.btn_return }}</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
$(function () {

	$(".datetime").flatpickr({
		locale: '{{ SESSION.user_lang|default(app.app_lang) }}',
		enableTime: false,
		dateFormat: 'd.m.Y',
		allowInput: true,
		time_24hr: true,
	});

	$('.date_mask').mask('##.##.####', {
		byPassKeys: '',
		translation: {
			'#': {pattern: /[0-9]/}
		}
	});

	$('.phone_mask').mask('+7##################', {
		byPassKeys: '',
		translation: {
			'#': {pattern: /[0-9]/}
		}
	});

	$('input[name=user_login]').mask('#', {
		byPassKeys: '',
		translation: {
			'#': {pattern: /[a-zA-Z0-9_.-]/, recursive: true}
		}
	});

	$('input[name=user_login]').tooltip({
		placement: "bottom",
		trigger: "focus"
	});

	$("select[name=user_city]").on("change",function() {
		var utm = $(this).find("option:selected").attr("data-utm");
		$("select[name=user_timezone]").selectpicker('val', utm);
	});

});
</script>
