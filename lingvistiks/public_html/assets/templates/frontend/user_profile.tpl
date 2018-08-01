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

						<div class="profile-block">
							<div class="profile-vote">
								{% if SESSION.user_photo %}
									<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/users/{{SESSION.user_photo}}&width=164&height=184" alt="">
								{% else %}
									<img src="{{ ABS_PATH }}assets/site/template/images/no-photo.png" alt="">
								{% endif %}
								<div class="file-upload-profile">
				                  <label>
				                      <input type="file" name="user_photo">
				                      <span>{{ lang.lk_upload_photo }}</span>
				                  </label>
				                </div>
							</div>
							<div class="profile-feedback">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<input type="text" class="form-control" name="user_firstname" placeholder="{{ lang.auth_field_name }}" value="{{ profile_info.user_firstname|escape|stripslashes }}">
										</div>
									</div>
									<div class="col-md-6 text-right">
										<span>{{ SESSION.user_id }}</span>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<input type="text" class="form-control" name="user_login" placeholder="{{ lang.auth_field_login }}" value="{{ profile_info.user_login|escape|stripslashes }}" readonly="true">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<input type="text" class="form-control" name="user_email" placeholder="{{ lang.auth_field_email }}" value="{{ profile_info.user_email|escape|stripslashes }}">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<input type="text" class="form-control" name="user_phone" placeholder="{{ lang.auth_field_phone }}" value="{{ profile_info.user_phone|escape|stripslashes }}">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<input type="text" class="form-control" name="user_skype" placeholder="{{ lang.auth_field_skype }}" value="{{ profile_info.user_skype|escape|stripslashes }}">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<select class="form-control" name="user_country">
												{% for country in country_list %}
													<option value="{{ country.id|escape|stripslashes }}" {% if country.id == profile_info.user_country %}selected{% endif %}>{{ country.title|escape|stripslashes }}</option>
												{% endfor %}
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<select class="form-control" name="user_city">
												{% for city in city_list %}
													<option value="{{ city.id|escape|stripslashes }}" {% if city.id == profile_info.user_city %}selected{% endif %}>{{ city.title|escape|stripslashes }}</option>
												{% endfor %}
											</select>
										</div>
									</div>
									<div class="clearfix"></div>
									<div class="col-md-4">
										<div class="form-group">
											<img src="{{ ABS_PATH }}assets/site/template/images/vk_input.png" alt="">
											<input type="text" class="form-control-1" placeholder="{{ lang.lk_vk }}" name="user_vk" value="{{ profile_info.user_vk|escape|stripslashes }}">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<img src="{{ ABS_PATH }}assets/site/template/images/fb_input.png" alt="">
											<input type="text" class="form-control-1" placeholder="{{ lang.lk_fb }}" name="user_fb" value="{{ profile_info.user_fb|escape|stripslashes }}">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<img src="{{ ABS_PATH }}assets/site/template/images/ib_input.png" alt="">
											<input type="text" class="form-control-1" placeholder="{{ lang.lk_in }}" name="user_in" value="{{ profile_info.user_in|escape|stripslashes }}">
										</div>
									</div>
								</div>
							</div>

							<div class="clearfix"></div>

							<h3>{{ lang.lk_info }}</h3>
							<div class="row">
								<div class="col-md-12">
									<div class="row add-class">
										<div class="col-md-4 col-sm-4">
											<p><strong>{{ lang.lk_lang_default }}</strong></p>
										</div>
										<div class="col-md-8 col-sm-8">
											<div class="form-group">
												<select class="form-control" name="user_lang_default">
													{% for lang in lang_list %}
														<option value="{{ lang.id }}" {% if lang.id == profile_info.user_lang_default %}selected{% endif %}>{{ lang.title|escape|stripslashes }}</option>
													{% endfor %}
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-4">
											<p><strong>{{ lang.lk_lang_var }}</strong></p>
										</div>
										<div class="col-md-8">
											<div class="form-group lang_var">
												{% for lang in lang_var %}
													<p>
														{{ lang.var_lang_from.title|escape|stripslashes }} - {{ lang.var_lang_to.title|escape|stripslashes }} <button type="button" class="close remove_var_lang" data-dismiss="modal" aria-hidden="true">x</button>
														<input type="hidden" name="var_lang_from[]" value="{{ lang.var_lang_from.id }}">
														<input type="hidden" name="var_lang_to[]" value="{{ lang.var_lang_to.id }}">
													</p>
												{% endfor %}
											</div>
										</div>
									</div>
									<div class="row add_lang_var_field">
										<div class="col-md-4">
											<button type="button" class="btn btn-link add_lang_var"><strong>+ {{ lang.btn_add }}</strong></button>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<select class="form-control" name="lang_from_temp">
													{% for lang in lang_list %}
														<option value="{{ lang.id }}">{{ lang.title|escape|stripslashes }}</option>
													{% endfor %}
												</select>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<select class="form-control" name="lang_to_temp">
													{% for lang in lang_list %}
														<option value="{{ lang.id }}">{{ lang.title|escape|stripslashes }}</option>
													{% endfor %}
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
							<h3>{{ lang.lk_info_about }}</h3>
							<div class="perfomer-kabinet-info">
								<div class="row">
									<div class="col-md-6">
										<div class="row">
											<div class="checkbox">
												{% for key, skill in lang.lk_skill_array %}
													<div class="col-md-6">
														<label>
															<input type="checkbox" name="user_skill[]" value="{{ key }}" {% if key in profile_info.user_skill %}checked{% endif %}> {{ skill|escape|stripslashes }}
														</label>
													</div>
												{% endfor %}
											</div>
										</div>
										<div class="row add-class">
											<div class="col-md-4 col-sm-4">
												<p><strong>{{ lang.lk_info_theme }}</strong></p>
											</div>
											<div class="col-md-8 col-sm-8">
												<div class="form-group">
													<select class="form-control" name="user_theme">
														{% for theme in theme_list %}
															<option value="{{ theme.id }}" {% if profile_info.user_theme == theme.id %}selected{% endif %}>{{ theme.title|escape|stripslashes }}</option>
														{% endfor %}
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="pull-right">
											<p>
												<strong>{{ lang.lk_info_experience }}</strong>
												<select class="form-control-1" name="user_experience">
													{% for experience in experience_list %}
														<option value="{{ experience.id }}" {% if profile_info.user_experience == experience.id %}selected{% endif %}>{{ experience.title|escape|stripslashes }}</option>
													{% endfor %}
												</select>
											</p>
										</div>
										<div class="clearfix"></div>
										<p>газ, цветная металлургия, чёрная металлургия, спорт, политика, география,
											геология, бизнес, экономика, финансы, экология, газ, цветная металлургия,
											чёрная металлургия, спорт, политика, география, геология, бизнес,
											экономика, финансы, экология</p>

										<div class="form-group">
											<input type="text" class="form-control" name="user_youtube" placeholder="{{ lang.lk_youtube }}" value="{{ profile_info.user_youtube|escape|stripslashes }}">
										</div>
									</div>
								</div>
							</div>

							<h3>{{ lang.lk_service_variant }}</h3>
							<div class="serv_var">
								{% for serv in serv_list %}
									<div class="row">
										<div class="col-md-3 col-sm-6">
											<p><strong>{{ serv.serv_service.title|escape|stripslashes }}</strong></p>
										</div>
										<div class="col-md-3 col-sm-6">
											<p><strong>{{ serv.serv_lang_from.title|escape|stripslashes }} - {{ serv.serv_lang_to.title|escape|stripslashes }}</strong></p>
										</div>
										<div class="col-md-3 col-sm-6">
											<p><strong>{{ serv.serv_communication.title|escape|stripslashes }}</strong></p>
										</div>
										<div class="col-md-3 col-sm-6 text-right">
											<p><strong>{{ serv.serv_coast }} {{ serv.serv_currency.title|escape|stripslashes }} / {{ serv.serv_time.title|escape|stripslashes }}</strong></p>
											<button type="button" class="close remove_var_services" data-dismiss="modal" aria-hidden="true">x</button>
										</div>
										<input type="hidden" name="serv_lang_from[]" value="{{ serv.serv_lang_from.id }}">
										<input type="hidden" name="serv_lang_to[]" value="{{ serv.serv_lang_to.id }}">
										<input type="hidden" name="serv_service[]" value="{{ serv.serv_service.id }}">
										<input type="hidden" name="serv_type_service[]" value="{{ serv.serv_type_service.id }}">
										<input type="hidden" name="serv_communication[]" value="{{ serv.serv_communication.id }}">
										<input type="hidden" name="serv_currency[]" value="{{ serv.serv_currency.id }}">
										<input type="hidden" name="serv_time[]" value="{{ serv.serv_time.id }}">
										<input type="hidden" name="serv_coast[]" value="{{ serv.serv_coast }}">
									</div>
								{% endfor %}
							</div>
							<hr class="kabinet">
							<div class="row add_serv_field">
								<div class="col-md-6">
									<div class="form-group">
										<select class="form-control" name="serv_service_temp">
											<option>{{ lang.lk_service }}</option>
											{% for service in service_list %}
												<option value="{{ service.id }}">{{ service.title|escape|stripslashes }}</option>
											{% endfor %}
										</select>
									</div>
									<div class="form-group">
										<select class="form-control" name="serv_type_service_temp">
											<option>{{ lang.lk_type_service }}</option>
											{% for service_type in service_type_list %}
												<option value="{{ service_type.id }}">{{ service_type.title|escape|stripslashes }}</option>
											{% endfor %}
										</select>
									</div>
									<div class="form-group">
										<select class="form-control" name="serv_communication_temp">
											<option>{{ lang.lk_communication }}</option>
											{% for communication in communication_list %}
												<option value="{{ communication.id }}">{{ communication.title|escape|stripslashes }}</option>
											{% endfor %}
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="row add-class">
										<div class="col-md-4">
											<p><strong>{{ lang.lk_lang_var }}</strong></p>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<select class="form-control" name="serv_lang_from_temp">
													{% for lang in lang_list %}
														<option value="{{ lang.id }}">{{ lang.title|escape|stripslashes }}</option>
													{% endfor %}
												</select>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<select class="form-control" name="serv_lang_to_temp">
													{% for lang in lang_list %}
														<option value="{{ lang.id }}">{{ lang.title|escape|stripslashes }}</option>
													{% endfor %}
												</select>
											</div>
										</div>
									</div>
									<div class="row add-class">
										<div class="col-md-4">
											<p><strong>{{ lang.lk_info_coast }}</strong></p>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<input type="text" class="form-control" name="serv_coast_temp" placeholder="">
											</div>
										</div>
										<div class="col-md-2 pr-10">
											<div class="form-group">
												<select class="form-control-cur" name="serv_currency_temp">
													{% for currency in currency_list %}
														<option value="{{ currency.id }}">{{ currency.title|escape|stripslashes }}</option>
													{% endfor %}
												</select>
											</div>
										</div>
										<div class="col-md-2 pl-0">
											<div class="form-group">
												<select class="form-control-time" name="serv_time_temp">
													{% for time in time_list %}
														<option value="{{ time.id }}">{{ time.title|escape|stripslashes }}</option>
													{% endfor %}
												</select>
											</div>
										</div>
										<div class="col-md-12">
											<button type="button" class="btn btn-block btn-search add_serv"><strong>{{ lang.btn_add }}</strong></button>
										</div>
									</div>
								</div>
							</div>

							<h3>{{ lang.lk_pay }}</h3>
							<div class="row">
								<div class="checkbox">
									<div class="col-md-4">
										<label>
											<input type="checkbox" name="user_pays[]" value="1" {% if 1 in profile_info.user_pays %}checked{% endif %}><img src="{{ ABS_PATH }}assets/site/template/images/ymoney.png" alt="" class="img-responsive">
										</label>
									</div>
									<div class="col-md-4">
										<label>
											<input type="checkbox" name="user_pays[]" value="2" {% if 2 in profile_info.user_pays %}checked{% endif %}><img src="{{ ABS_PATH }}assets/site/template/images/paypal.png" alt="" class="img-responsive">
										</label>
									</div>
									<div class="col-md-4">
										<label>
											<input type="checkbox" name="user_pays[]" value="3" {% if 3 in profile_info.user_pays %}checked{% endif %}><img src="{{ ABS_PATH }}assets/site/template/images/visa.png" alt="" class="img-responsive">
										</label>
									</div>
								</div>
							</div>

							<h3>{{ lang.lk_graph }}</h3>
							<div class="row">
								<div class="col-md-12">
									<div id="carouselFour" class="owl-carousel">
										{% for graph in graph_var %}
											<div>
												<ul>
													<li>
														<strong>{{ graph.graph_start }} - {{ graph.graph_end }}</strong><br>
														{{ graph.graph_country.title|escape|stripslashes }}, {{ graph.graph_city.title|escape|stripslashes }}<br>
													</li>
												</ul>
											</div>
										{% endfor %}
									</div>
								</div>
							</div>

							<div class="clearfix"></div>

							<div class="row">
								<div class="col-md-8">
									<div class="row">
										<div class="col-md-6">
											<p><strong>{{ lang.lk_graph_start }}</strong></p>
											<div class="form-group">
												<div class="input-group" id="datetimepicker12"> </div>
											</div>
											<div class="form-group">
												<select class="form-control" name="graph_country">
													{% for country in country_list %}
														<option value="{{ country.id|escape|stripslashes }}" {% if country.id == profile_info.user_country %}selected{% endif %}>{{ country.title|escape|stripslashes }}</option>
													{% endfor %}
												</select>
											</div>
										</div>
										<div class="col-md-6">
											<p><strong>{{ lang.lk_graph_end }}</strong></p>
											<div class="form-group">
												<div class="input-group" id="datetimepicker13"> </div>
											</div>
											<div class="form-group">
												<select class="form-control" name="graph_city">
													{% for city in city_list %}
														<option value="{{ city.id|escape|stripslashes }}" {% if city.id == profile_info.user_city %}selected{% endif %}>{{ city.title|escape|stripslashes }}</option>
													{% endfor %}
												</select>
											</div>
										</div>
										<div class="col-md-12">
											<input type="hidden" name="graph_start" value="">
											<input type="hidden" name="graph_end" value="">
											<button type="button" class="btn btn-block btn-go-on add_graph">{{ lang.btn_add }}</button>
										</div>
									</div>
								</div>
								<div class="col-md-4">

									<ul class="list-unstyled get_new_graph"></ul>

								</div>
							</div>

							<h3>{{ lang.lk_notice }}</h3>
							<div class="row check_notice">
								{% for key, notice in lang.lk_notice_array %}
									<div class="col-md-3">
										<label>
											<input type="checkbox" name="user_notice[]" value="{{ key }}" {% if key in profile_info.user_notice %}checked{% endif %} {% if key != 0 and 0 in profile_info.user_notice %}disabled{% endif %}> {{ notice }}
										</label>
									</div>
								{% endfor %}
							</div>

							<h3>{{ lang.lk_password }}</h3>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<input type="password" class="form-control" name="user_password_old" placeholder="{{ lang.lk_password_old }}">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<input type="password" class="form-control" name="user_password_new" placeholder="{{ lang.lk_password_new }}">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<input type="password" class="form-control" name="user_password_new_copy" placeholder="{{ lang.lk_password_new_copy }}">
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<button type="submit" class="btn btn-block btn-search">{{ lang.btn_save }}</button>
							</div>
						</div>
					</form>


					<h3>{{ lang.lk_portfolio }}</h3>
					<div class="row">
						<div class="col-md-6">

<form method="post" action="{{ HOST_NAME }}/profile/upload/" id="upload" enctype="multipart/form-data">
<input type="hidden" name="csrf_token" value="{{ csrf_token }}">

<div class="form-group">
<div class="upload-file-dropbox-zone" id="drop">
	<p class="upload-file-dropbox-zone-hint">
		{{ lang.storage_add_desc }}
	</p>
	<input type="file" name="file_path" multiple>
	<p>{{ lang.storage_add_desc_allow }} {{ app_allow_ext }}</p>
</div>
</div>
<a class="hidden link_go btn-flat gray" href="{{ HOST_NAME }}/profile/"><i class="fa fa-refresh"></i> {{ lang.storage_refresh }}</a>
<div class="clearfix"></div><br>
<ul class="list-unstyled"></ul>
</form>

<link href="{{ ABS_PATH }}vendor/assets/miniupload/css/style.css" rel="stylesheet" />
<script src="{{ ABS_PATH }}vendor/assets/miniupload/js/jquery.knob.js"></script>

<!-- jQuery File Upload Dependencies -->
<script src="{{ ABS_PATH }}vendor/assets/miniupload/js/jquery.ui.widget.js"></script>
<script src="{{ ABS_PATH }}vendor/assets/miniupload/js/jquery.iframe-transport.js"></script>
<script src="{{ ABS_PATH }}vendor/assets/miniupload/js/jquery.fileupload.js"></script>

<!-- Our main JS file -->
<script src="{{ ABS_PATH }}vendor/assets/miniupload/js/script.js"></script>
<script>
$(function () {
$(".link_go").on('click',function() {
	window.location = $(this).attr("href");
	window.location.reload();
	return false;
});
});
</script>

						</div>
						<div class="col-md-6">
							<div class="upload-img">
								<ul class="list-inline">
									{% for file in file_list %}
										{% if file.file_type == 'image' %}
											<li>
												<a href="{{ ABS_PATH }}{{ app.app_upload_dir }}/document/{{ SESSION.user_id }}/{{ file.file_path|escape|stripslashes }}" rel="colorbox"><img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/document/{{ SESSION.user_id }}/{{ file.file_path|escape|stripslashes }}&width=35&height=50" alt=""></a>
												<button type="button" class="close remove_file" data-dismiss="modal" aria-hidden="true" data-file="{{ file.file_id }}">x</button>
											</li>
										{% endif %}
									{% endfor %}
								</ul>
							</div>

							<div class="upload-video">
								<ul class="list-inline">
									{% for file in file_list %}
										{% if file.file_type == 'video' %}
											<li>
												<a href="{{ ABS_PATH }}{{ app.app_upload_dir }}/document/{{ SESSION.user_id }}/{{ file.file_path|escape|stripslashes }}" rel="colorbox"><img src="{{ ABS_PATH }}assets/site/template/images/upload-video.jpg" alt=""></a>
												<button type="button" class="close remove_file" data-dismiss="modal" aria-hidden="true" data-file="{{ file.file_id }}">x</button>
											</li>
										{% endif %}
									{% endfor %}
								</ul>
							</div>
						</div>
					</div>


					<h3>{{ lang.lk_upload_diplom }}</h3>
					<div class="row">
						<div class="col-md-6">

<form method="post" action="{{ HOST_NAME }}/profile/diplom/upload/" id="upload2" enctype="multipart/form-data">
<input type="hidden" name="csrf_token" value="{{ csrf_token }}">

<div class="form-group">
<div class="upload-file-dropbox-zone" id="drop2">
	<p class="upload-file-dropbox-zone-hint">
		{{ lang.storage_add_desc }}
	</p>
	<input type="file" name="file_path" multiple>
	<p>{{ lang.storage_add_desc_allow }} {{ app_allow_ext }}</p>
</div>
</div>
<a class="hidden link_go btn-flat gray" href="{{ HOST_NAME }}/profile/"><i class="fa fa-refresh"></i> {{ lang.storage_refresh }}</a>
<div class="clearfix"></div><br>
<ul class="list-unstyled"></ul>
</form>

<!-- Our main JS file -->
<script src="{{ ABS_PATH }}vendor/assets/miniupload/js/script2.js"></script>

						</div>
						<div class="col-md-6">
							<div class="upload-img">
								<ul class="list-inline">
									{% for file in diplom_list %}
										<li>
											<a href="{{ ABS_PATH }}{{ app.app_upload_dir }}/diplom/{{ SESSION.user_id }}/{{ file.file_path|escape|stripslashes }}" rel="colorbox"><img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/diplom/{{ SESSION.user_id }}/{{ file.file_path|escape|stripslashes }}&width=35&height=50" alt=""></a>
											<button type="button" class="close remove_diplom" data-dismiss="modal" aria-hidden="true" data-file="{{ file.file_id }}">x</button>
										</li>
									{% endfor %}
								</ul>
							</div>

						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</main>

<link rel="stylesheet" href="{{ ABS_PATH }}vendor/assets/colorbox/colorbox.min.css" type="text/css">
<script src="{{ ABS_PATH }}vendor/assets/colorbox/jquery.colorbox-min.js"></script>

<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/assets/datepicker/css/bootstrap-datetimepicker.min.css" type="text/css">
<script src="{{ ABS_PATH }}assets/site/assets/datepicker/js/moment-with-locales.js"></script>

<script src="{{ ABS_PATH }}assets/site/assets/datepicker/js/bootstrap-datetimepicker.min.js"></script>

<script>
$(function () {
	$("a[rel^='colorbox']").colorbox({scalePhotos: true, maxWidth: "100%", maxHeight: "100%", current: "{current} / {total}"});

	// init
	App.profile_core();
	// init
});
</script>
<script type="text/javascript">
	  $(function () {
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

		  $('#datetimepicker12').datetimepicker({
			  inline: true,
			  sideBySide: false,
			  locale: '{{ SESSION.user_lang|default(app.app_lang) }}',
				showTodayButton: true,
				stepping: 10,
				useCurrent: false,
		  });

		  $('#datetimepicker13').datetimepicker({
			  inline: true,
			  sideBySide: false,
			  locale: '{{ SESSION.user_lang|default(app.app_lang) }}',
				showTodayButton: true,
				stepping: 10,
				useCurrent: false,
		  });

		  $("#datetimepicker12").on("dp.change", function (e) {
            $('#datetimepicker13').data("DateTimePicker").minDate(e.date);

			var date_formatted = moment(e.date).format(date_format_js);
			$("input[name=graph_start]").val(date_formatted);
        });
        $("#datetimepicker13").on("dp.change", function (e) {
            $('#datetimepicker12').data("DateTimePicker").maxDate(e.date);

			var date_formatted = moment(e.date).format(date_format_js);
			$("input[name=graph_end]").val(date_formatted);
        });
	  });
</script>
