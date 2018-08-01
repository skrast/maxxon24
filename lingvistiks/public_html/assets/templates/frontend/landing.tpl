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

		<!-- Font-awesome styles -->
		<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/assets/font-awesome-4.7.0/css/font-awesome.min.css">

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
	</head>

	<body>

		{{ content }}

	</body>
</html>

{{ get_statistic }}
{% endspaceless %}
