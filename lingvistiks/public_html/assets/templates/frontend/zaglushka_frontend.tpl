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
		<script src="{{ ABS_PATH }}assets/site/assets/jquery-1.11.2/jquery-1.11.2.min.js"></script>

		<!-- Bootstrap js -->
		<script src="{{ ABS_PATH }}assets/site/assets/bootstrap-v-3.3.2/js/bootstrap.min.js"></script>

	</head>

	<body>

		{{ content }}

		<!-- OwlCarusel js -->
		<script src="{{ ABS_PATH }}assets/site/assets/OwlCarousel2-2.1.6/dist/owl.carousel.min.js"></script>

		<!-- TinyCarusel js -->
		<script src="{{ ABS_PATH }}assets/site/assets/tinycarousel-2.1.8/jquery.tinycarousel.min.js"></script>

		<!-- SkyCarusel js -->
		<script src="{{ ABS_PATH }}assets/site/assets/skycarousel/custom.js"></script>
		<script src="{{ ABS_PATH }}assets/site/assets/skycarousel/ender.min.js"></script>
		<script src="{{ ABS_PATH }}assets/site/assets/skycarousel/selectnav.js"></script>
		<script src="{{ ABS_PATH }}assets/site/assets/skycarousel/jquery.sky.carousel-1.0.2.min.js"></script>


		<script src="{{ ABS_PATH }}assets/js/site.js"></script>

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
						<h2 class="modal-title">{{ lang.page_support }}</h2>
					</div>
					<div class="modal-body">
						<form id="feedback" action="{{ HOST_NAME }}/support/" method="post" class="ajax_form" data-reset="1">
							<input type="hidden" name="csrf_token" value="{{ csrf_token }}">

							<input type="hidden" name="tiket_type" value="1">

							<div class="form-group hidden">
								<div class="checkbox">
									<span>{{ lang.page_support_type }}</span>
									<label>
										<input class="checkbox-input" type="checkbox" name="tiket_type[]" value="1">
										<span class="checkbox-custom"></span>
										<span class="label">&nbsp;{{ lang.page_support_email }}</span>
									</label>
									<label>
										<input class="checkbox-input" type="checkbox" name="tiket_type[]" value="2">
										<span class="checkbox-custom"></span>
										<span class="label">&nbsp;{{ lang.page_support_phone }}</span>
									</label>
									{% if SESSION.user_id %}
									<label>
										<input class="checkbox-input" type="checkbox" name="tiket_type[]" value="3">
										<span class="checkbox-custom"></span>
										<span class="label">&nbsp;{{ lang.page_support_message }}</span>
									</label>
									{% endif %}
								</div>
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

							<div class="form-group hidden">
								<select class="form-control" name="tiket_group">
									{% for group in tiket_group %}
										<option value="{{ group.tiket_group_id }}">{{ group.tiket_group_title|escape|stripslashes }}</option>
									{% endfor %}
								</select>
							</div>

							<div class="form-group">
								<textarea class="form-control-modal" placeholder="{{ lang.page_support_desc }}" rows="5" name="tiket_text"></textarea>
							</div>

							<div class="checkbox">
			                  <label>
			                    <input class="checkbox-input" type="checkbox" name="support_secure" value="1">
			                    <span class="checkbox-custom"></span>
			                    <span class="label">&nbsp;{{ lang.auth_btn_secure }} <img src="{{ ABS_PATH }}assets/site/template/images/logo-modal.png" alt=""></a></span>
			                  </label>
			                </div>

							<p><button type="submit" class="btn btn-block btn-go-on">{{ lang.btn_send }}</button></p>
							<p class="error-message-color error-message">{{ lang.btn_field_requered }}</p>
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
