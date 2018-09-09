{% spaceless %}
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>{{ page_info.page_title|escape|stripslashes }}</title>

		<meta name="Keywords" content="{{ page_info.page_meta_keywords|escape|stripslashes }}">
		<meta name="Description" content="{{ page_info.page_meta_description|escape|stripslashes }}">
		<meta name="robots" content="{{ page_info.page_meta_robots|escape|stripslashes }}">

		<!-- Bootstrap styles -->
		<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/assets/bootstrap-v-3.3.2/css/bootstrap.min.css">

		<!-- Main styles -->
		<link rel="stylesheet" href="{{ ABS_PATH }}assets/css/maxxon.css">
		<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/template/styles/style.css">
		<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/template/styles/media.css">
		<link rel="stylesheet" href="{{ ABS_PATH }}vendor/assets/toastr/toastr.min.css">

		<!-- Font-awesome styles -->
		<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/assets/font-awesome-4.7.0/css/font-awesome.min.css">

		<!-- OwlCarusel styles -->
		<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/assets/OwlCarousel2-2.1.6/dist/assets/owl.carousel.min.css">

		<!-- SkyCarusel styles -->
		<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/assets/skycarousel/skin.css">

		<!-- TinyCarusel styles -->
		<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/assets/tinycarousel-2.1.8/tinycarousel.css">

		<link rel="icon" type="image/vnd.microsoft.icon" href="{{ ABS_PATH }}favicon.ico">
   		<link rel="SHORTCUT ICON" href="{{ ABS_PATH }}favicon.ico">

		<script>
            var ave_path = '{{ ABS_PATH }}';
            var ave_host = '{{ HOST_NAME }}';
            var csrf_token = '{{ csrf_token }}';
            var avatar_width = '{{ app.AVATAR_WIDTH }}';
            var avatar_height = '{{ app.AVATAR_HEIGHT }}';
			var date_format_js = "{{ app.app_date_format_js|upper }}";
			var date_short_format_js = "{{ app.app_date_short_format_js }}";
			var date_xml_format_js = "{{ app.app_date_xml_format_js }}";
            var date_xml_short_format_js = "{{ app.app_date_xml_short_format_js }}";

			var alert_title = "{{ lang.alert_title }}";
			var app_lang = "{{ SESSION.user_lang|default(app.app_lang) }}";

			var datetime_settings = {
				locale: '{{ SESSION.user_lang|default(app.app_lang) }}',
				showTodayButton: true,
				stepping: 10,
			};
			var check_message = {% if SESSION.user_id %}1{% else %}0{% endif %};
		</script>

		<link href="//fonts.googleapis.com/css?family=Fira+Sans+Extra+Condensed:300,300i,400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext" rel="stylesheet">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

		<!-- jQuery -->
		<script src="{{ ABS_PATH }}assets/js/jquery.js"></script>

		<!-- Bootstrap js -->
		<script src="{{ ABS_PATH }}assets/site/assets/bootstrap-v-3.3.2/js/bootstrap.min.js"></script>

		<script>
			var datetime_settings = {
				locale: '{{ SESSION.user_lang|default(app.app_lang) }}',
				enableTime: true,
				dateFormat: 'd.m.Y H:i',
				allowInput: true,
				time_24hr: true,
				onOpen: [
					function(selectedDates, dateStr, instance){
						instance;
						if(!dateStr) {
							instance.setDate(new Date());
						}
					}
				],
			};
		</script>

	</head>

	<body>

		<div class="wrapper_main_footer">

			<header>
				<div class="container">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-4">
							<div class="logo">
									<a href="{{ HOST_NAME }}" title="{{ lang.page_name_project }}">
									<img src="{{ ABS_PATH }}assets/site/template/images/logo.png" alt="{{ lang.page_name_project }}">
								</a>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-8">

							{{ bild_login_tpl }}

						</div>
					</div>
				</div>
			</header>

			<section class="nav">
				<div class="container">
					<div class="row">
						<div class="col-md-8">

						<!--	<ul class="nav nav-justified">
								{% set naviitems = navi_2.navi_items %}
								{% include 'page/templates/item_show_site.tpl' with {'navi_items': navi_2.navi_items[0], 'naviitems': naviitems, 'navi_info': navi_2} %}
							</ul>-->


							<ul class="list-inline main-nav">
								<li>
									<div>{{ lang.menu_about }}</div>

									<ul class="list-unstyled">
										<li>
											<a href="{{ HOST_NAME }}{{ link_lang_pref }}/about{{ app.URL_SUFF }}">{{ lang.menu_about_us }}</a>
										</li>
										<li>
											<a href="{{ HOST_NAME }}{{ link_lang_pref }}/menu-tarif{{ app.URL_SUFF }}">{{ lang.menu_tarif }}</a>
										</li>
										<li>
											<a href="#" data-toggle="modal" data-target="#support">{{ lang.menu_message }}</a>
										</li>
									</ul>
								</li>
								<li>
									<div>{{ lang.menu_najti_ispolnitelya }}</div>
									<ul class="list-unstyled">
										<li>
											<a href="{{ HOST_NAME }}{{ link_lang_pref }}/perevodchik/">{{ lang.menu_perevodchiki }}</a>

											<ul class="list-unstyled">
												<li>
													<a href="{{ HOST_NAME }}{{ link_lang_pref }}/perevodchik/?serv_type_service=21">{{ lang.menu_synchronous }}</a>
												</li>
												<li>
													<a href="{{ HOST_NAME }}{{ link_lang_pref }}/perevodchik/?serv_type_service=22">{{ lang.menu_consistent }}</a>
												</li>
												<li>
													<a href="{{ HOST_NAME }}{{ link_lang_pref }}/perevodchik/?serv_type_service=55" class="show_writer_search">{{ lang.menu_writing }}</a>
												</li>
												<li>
													<a href="{{ HOST_NAME }}{{ link_lang_pref }}/perevodchik/?serv_type_service=56">{{ lang.menu_perevodchiki_online }}</a>
												</li>
												<li>
													<a href="{{ HOST_NAME }}{{ link_lang_pref }}/perevodchik/?serv_type_service=57">{{ lang.menu_interpreters }}</a>
												</li>
											</ul>

										</li>
										<li>
											<a href="{{ HOST_NAME }}{{ link_lang_pref }}/prepodavatel/">{{ lang.menu_izuchenie_yazykov }}</a>

											<ul class="list-unstyled">
												<li>
													<a href="{{ HOST_NAME }}{{ link_lang_pref }}/prepodavatel/?serv_type_service=46">{{ lang.menu_izuchenie_yazykov_language_school }}</a>
												</li>
												<li>
													<a href="{{ HOST_NAME }}{{ link_lang_pref }}/prepodavatel/?serv_type_service=47">{{ lang.menu_izuchenie_yazykov_individual_teacher }}</a>
												</li>
												<li>
													<a href="{{ HOST_NAME }}{{ link_lang_pref }}/prepodavatel/?serv_type_service=48">{{ lang.menu_izuchenie_yazykov_practice_with_a_native_speaker }}</a>
												</li>
												<li>
													<a href="{{ HOST_NAME }}{{ link_lang_pref }}/prepodavatel/?serv_type_service=49">{{ lang.menu_izuchenie_yazykov_online }}</a>
												</li>
											</ul>
										</li>
										<li>
											<a href="{{ HOST_NAME }}{{ link_lang_pref }}/gid/">{{ lang.menu_gid_i_soprovozhdenie }}</a>
											<ul class="list-unstyled">
												<li>
													<a href="{{ HOST_NAME }}{{ link_lang_pref }}/gid/?serv_type_service=50">{{ lang.menu_gid_i_soprovozhdenie_sightseeing }}</a>
												</li>
												<li>
													<a href="{{ HOST_NAME }}{{ link_lang_pref }}/gid/?serv_type_service=51">{{ lang.menu_gid_i_soprovozhdenie_shopping_restaurants }}</a>
												</li>
												<li>
													<a href="{{ HOST_NAME }}{{ link_lang_pref }}/gid/?serv_type_service=52">{{ lang.menu_gid_i_soprovozhdenie_sports_entertainment }}</a>
												</li>
											</ul>
										</li>
									</ul>
								</li>
								<li>
									<div>{{ lang.menu_work }}</div>
									<ul class="list-unstyled">
										<li><a {% if SESSION.user_group %}href="{{ HOST_NAME }}/bank_zakazov/"{% else %}href="#" data-toggle="modal" data-target="#registration"{% endif %}>{{ lang.menu_order }}</a></li>
										<li><a {% if SESSION.user_group %}href="{{ HOST_NAME }}/bank_vakansiy/"{% else %}href="#" data-toggle="modal" data-target="#registration"{% endif %}>{{ lang.menu_vacancies }}</a></li>
										<li><a {% if SESSION.user_group %}href="{{ HOST_NAME }}/bank_resume/"{% else %}href="#" data-toggle="modal" data-target="#registration"{% endif %}>{{ lang.menu_resume }}</a></li>
									</ul>
								</li>
							</ul>

							<script>
								$(function () {
									$(".show_writer_search").on("click", function() {
											App.search_writer_core();
											return false;
										});
									});
							</script>

						</div>
						<div class="col-md-4 text-right">
							<div class="language">
								<!--  ul class="list-inline">
									{% for lang in lang_array %}
									<li {% if lang == REQUEST.lang %}class="active"{% endif %}>
										<a href="{{ HOST_NAME }}/{{ lang|escape|stripslashes }}">{{ lang|escape|stripslashes|default(app.app_lang) }}</a>
									</li>
									{% endfor %}
								</ul-->
								<ul class="list-inline">
									{% for key,lang in app_langs %}
									<li {% if key == SESSION.user_lang %}class="active"{% endif %}>
										<a href="{{ HOST_NAME }}/{{ key|escape|stripslashes }}">{{ lang|escape|stripslashes|default(app.app_lang) }}</a>
									</li>
									{% endfor %}
								</ul>
							</div>
						</div>
					</div>
				</div>
			</section>

			{{ content }}

			<footer>
				<div class="container">
					<div class="footer-menu">
						<div class="row">
							<div class="col-md-2 col-sm-6 col-xs-6">

								<div class="title">{{ lang.menu_about }}</div>
								<ul class="list-unstyled">
									<li>
										<a href="{{ HOST_NAME }}{{ link_lang_pref }}/about{{ app.URL_SUFF }}">{{ lang.menu_about_us }}</a>
									</li>
									<li>
										<a href="{{ HOST_NAME }}{{ link_lang_pref }}/menu-tarif{{ app.URL_SUFF }}">{{ lang.menu_tarif }}</a>
									</li>
									<li>
										<a href="#" data-toggle="modal" data-target="#support">{{ lang.menu_message }}</a>
									</li>
									<li>
										<a href="{{ HOST_NAME }}/search">{{ lang.menu_search }}</a>
									</li>
								</ul>

							</div>
							<div class="col-md-2 col-sm-6 col-xs-6">

								<div class="title">{{ lang.menu_najti_ispolnitelya_footer }}</div>
								<ul class="list-unstyled">
									<li>
										<a href="{{ HOST_NAME }}{{ link_lang_pref }}/perevodchik/">{{ lang.menu_perevodchiki_footer }}</a>
									</li>
									<li>
										<a href="{{ HOST_NAME }}{{ link_lang_pref }}/prepodavatel/">{{ lang.menu_izuchenie_yazykov_footer }}</a>
									</li>
									<li>
										<a href="{{ HOST_NAME }}{{ link_lang_pref }}/gid/">{{ lang.menu_gid_i_soprovozhdenie_footer }}</a>
									</li>
								</ul>

							</div>

							<div class="col-md-4 col-sm-12 col-xs-6">
								<div class="social text-center">
									<ul class="list-unstyled list-inline">
										<li><a href="" title=""><img src="{{ ABS_PATH }}assets/site/template/images/fb.png" alt=""></a></li>
										<li><a href="" title=""><img src="{{ ABS_PATH }}assets/site/template/images/vk.png" alt=""></a></li>
									</ul>
								</div>
							</div>

							<div class="col-md-4 col-sm-12 col-xs-12 text-left">
								<div class="title">{{ lang.menu_pay_allow }}</div>
								<img src="{{ ABS_PATH }}assets/site/template/images/pay-icon.png" alt="">
								<img src="{{ ABS_PATH }}assets/site/template/images/paypal-footer.png" alt="">
							</div>
						</div>

						<div class="row copy_footer">
							<div class="col-md-10">{{ lang.menu_footer_copy }}</div>
							<div class="col-md-2">{{ lang.menu_footer_copy_date }}</div>
						</div>
					</div>
				</div>
			</footer>
		</div>

		<!-- OwlCarusel js -->
		<script src="{{ ABS_PATH }}assets/site/assets/OwlCarousel2-2.1.6/dist/owl.carousel.min.js"></script>

		<!-- TinyCarusel js -->
		<script src="{{ ABS_PATH }}assets/site/assets/tinycarousel-2.1.8/jquery.tinycarousel.min.js"></script>

		<!-- SkyCarusel js -->
		<script src="{{ ABS_PATH }}assets/site/assets/skycarousel/custom.js"></script>
		<script src="{{ ABS_PATH }}assets/site/assets/skycarousel/ender.min.js"></script>
		<script src="{{ ABS_PATH }}assets/site/assets/skycarousel/selectnav.js"></script>
		<script src="{{ ABS_PATH }}assets/site/assets/skycarousel/jquery.sky.carousel-1.0.2.min.js"></script>

		<script src="{{ ABS_PATH }}vendor/assets/bootstrap-select/js/bootstrap-select.js" type="text/javascript"></script>
		<script src="{{ ABS_PATH }}vendor/assets/bootstrap-select/js/i18n/defaults-{{ SESSION.user_lang|default(app.app_lang) }}_{{ SESSION.user_lang|default(app.app_lang)|upper }}.js" type="text/javascript"></script>
		<link rel="stylesheet" href="{{ ABS_PATH }}vendor/assets/bootstrap-select/css/bootstrap-select.css">

		<script src="{{ ABS_PATH }}assets/js/site.js"></script>
		<script src="{{ ABS_PATH }}assets/js/maxxon.js"></script>

		<div class="modal fade" id="ajax_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">{{ lang.btn_close }} x</button>
                        <h2 class="modal-title"></h2>
                    </div>
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>

		<div class="modal fade" id="support" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close pull-right" data-dismiss="modal" aria-hidden="true">{{ lang.btn_close }} x</button>
						<h2 class="modal-title">{{ lang.page_support }} <img src="{{ ABS_PATH }}assets/site/template/images/logo-support.png" alt=""></h2>
					</div>
					<div class="modal-body">
						<form action="{{ HOST_NAME }}/support/" method="post" class="ajax_form" data-reset="1">
							<input type="hidden" name="csrf_token" value="{{ csrf_token }}">

							<div class="form-group">
								<ul class="list-inline">
									<li><div class="checkbox"><label><span class="label">{{ lang.page_support_type }}</span></label></div></li>
									<li>
										<div class="checkbox">
											<label>
												<input class="checkbox-input" type="checkbox" name="tiket_type[]" value="1">
												<span class="checkbox-custom"></span>
												<span class="label">{{ lang.page_support_email }}</span>
											</label>
										</div>
									</li>
									<li>
										<div class="checkbox">
											<label>
												<input class="checkbox-input" type="checkbox" name="tiket_type[]" value="2">
												<span class="checkbox-custom"></span>
												<span class="label">{{ lang.page_support_phone }}</span>
											</label>
										</div>
									</li>
									{% if SESSION.user_id %}
									<li>
										<div class="checkbox">
											<label>
												<input class="checkbox-input" type="checkbox" name="tiket_type[]" value="3">
												<span class="checkbox-custom"></span>
												<span class="label">{{ lang.page_support_message }}</span>
											</label>
										</div>
									</li>
									{% endif %}
								</ul>
							</div>

							{% if not SESSION.user_id %}
								<div class="form-group">
									<input type="text" class="form-control-modal" name="user_email" placeholder="{{ lang.auth_field_email }}">
								</div>

								<div class="form-group">
									<input type="text" class="form-control-modal" name="user_name" placeholder="{{ lang.auth_field_name }}">
								</div>

								<div class="form-group">
									<input type="text" class="form-control-modal" name="user_phone" placeholder="{{ lang.auth_field_phone }}">
								</div>
							{% endif %}

							<div class="form-group">
								<select class="form-control-modal" name="tiket_group">
									{% for group in tiket_group %}
										<option value="{{ group.tiket_group_id }}">{{ group.tiket_group_title|escape|stripslashes }}</option>
									{% endfor %}
								</select>
							</div>

							<div class="form-group">
								<textarea class="form-control-modal" placeholder="{{ lang.page_support_desc }}" rows="5" name="tiket_text"></textarea>
							</div>

							<div class="form-group">
								<div class="checkbox">
									<label>
										<input class="checkbox-input" type="checkbox" name="privacy" value="1">
										<span class="checkbox-custom"></span>
										<span class="label">{{ lang.auth_btn_secure }} <a href="{{ HOST_NAME }}{{ link_lang_pref }}/privacy{{ app.URL_SUFF }}">{{ lang.auth_link_secure }}</a> {{ lang.auth_btn_secure2 }}</span>
									</label>
								</div>
							</div>

							<p><button type="submit" class="btn btn-block btn-go-on">{{ lang.btn_send }}</button></p>
							<p class="error-message-color error-message"></p>
						</form>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<div class="modal fade" id="feedback-after" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header feedback-after">
                <button type="button" class="close pull-right" data-dismiss="modal" aria-hidden="true">{{ lang.btn_close }} x</button>
                <h2 class="modal-title">{{ lang.page_support }}</h2>
                <p>{{ lang.page_support_success }}</p>
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

		<script>$(document).ready(function(){
			$('#carouselOne').owlCarousel({
			items:11,
			autoplay:true,
			nav:true,
			autoWidth:true,
			margin:20,
			loop:true,
			navText:["<img src='{{ ABS_PATH }}assets/site/template/images/prev.png'>","<img src='{{ ABS_PATH }}assets/site/template/images/next.png'>"]
			});
			});
		</script>

		<audio id="playAudio" class="hidden">
			<source src="{{ ABS_PATH }}assets/sound/notify.ogg" type="audio/ogg">
			<source src="{{ ABS_PATH }}assets/sound/notify.mp3" type="audio/mpeg">
			<source src="{{ ABS_PATH }}assets/sound/notify.wav" type="audio/wav">
		</audio>

	</body>
</html>

{{ get_statistic }}
{% endspaceless %}
