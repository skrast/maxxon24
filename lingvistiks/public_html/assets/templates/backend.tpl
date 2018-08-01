<!DOCTYPE html>
<html>
    <head>
		<meta charset="UTF-8" />
		<title>{{MAIN_TITLE}}</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<meta http-equiv="Cache-Control" content="public">
		<meta http-equiv="Cache-Control" content="max-age=3600, must-revalidate">

		<script src="{{ ABS_PATH }}assets/build/js/frontend.min.js" type="text/javascript"></script>
		<link rel="stylesheet" href="{{ ABS_PATH }}assets/build/css/backend.min.css" type="text/css">

		<link rel="shortcut icon" href="favicon.ico">
        <script>
            var ave_path = '{{ ABS_PATH_ADMIN_LINK }}';
            var csrf_token = '{{ csrf_token }}';

            var alert_title = "{{ lang.alert_title }}";
            var alert_file_copy = "{{ lang.alert_file_copy }}";
            var alert_file_copy2 = "{{ lang.alert_file_copy2 }}";
            var phone_format = "{{ app.app_phone_format }}";
            var date_format_js = "{{ app.app_date_format_js }}";
			var date_short_format_js = "{{ app.app_date_short_format_js }}";
            var date_xml_format_js = "{{ app.app_date_xml_format_js }}";
            var date_xml_short_format_js = "{{ app.app_date_xml_short_format_js }}";
            var app_lang = "{{ SESSION.user_lang|default(app.app_lang) }}";
            var app_currency = "{{ DEFAULT_CURRENCY }}";

			{% if SESSION.user_show_project|length == 1 %}
				phone_format = "{{ allow_project[SESSION.user_show_project.0].project_phone_mask }}";
			{% endif %}

			var datetime_settings = {
				locale: '{{ SESSION.user_lang|default(app.app_lang) }}',
				showTodayButton: true,
				stepping: 10,
			};

			// confirm
			$(function () {
				$.ajaxSetup({
					dataType: "json",
					type: "POST"
				});

				$.confirm.options = {
					text: "{{ lang.confirm_text }}",
					title: "{{ lang.confirm_title }}",
					confirmButton: "{{ lang.confirm_yes }}",
					cancelButton: "{{ lang.confirm_no }}",
					post: false,
					confirmButtonClass: "btn-flat success",
					cancelButtonClass: "btn-flat danger",
					dialogClass: "modal-dialog modal-md"
				};
			});
			// confirm
		</script>
		<script src="{{ ABS_PATH }}assets/build/js/backend.min.js" type="text/javascript"></script>
    </head>

    <body class="dashboard">

        <div class="page-container">
            <header class="header navbar navbar-default navbar-fixed-top">
                <div class="container-fluid">

					<div class="navbar-header pull-left">
						<a href="{{ ABS_PATH_ADMIN_LINK }}?do=start" class="navbar-brand">
	                        <i class="icon-rocket"></i></a>
						</a>
					</div>

                    {{ bild_top_link }}

                    {{ bild_profile }}
                </div>
            </header>

            <div class="wrapper">
                <div class="content">
                    <div class="hidden-menu-link">
                        {{ bild_top_link }}

						<ul class="nav navbar-nav nav-top-link visible-xs-block">
							<li>
								<a href="{{ ABS_PATH_ADMIN_LINK }}?do=profile&sub=profile_work&user_id={{ SESSION.user_id }}">
									<i class="fa fa-user"></i> {{ lang.profile_edit_link }}
								</a>
							</li>
							<li>
								<a href="{{ ABS_PATH_ADMIN_LINK }}?do=sendmail">
									<i class="fa fa-inbox"></i> {{ lang.sendmail_name }}
								</a>
							</li>
							<li>
								<a href="{{ ABS_PATH_ADMIN_LINK }}?do=auth&sub=logout" class="confirm">
									<i class="fa fa-sign-out"></i> {{ lang.logout }}
								</a>
							</li>
						</ul>
                    </div>
                    <div class="clearfix"></div>

                    {{ content }}

					{% for hook_name, hook_value in hook_main %}
						{{ hook_value.bild }}
					{% endfor %}

                    {{ get_statistic }}
                    {{ get_statistic2 }}
                </div>
            </div>
        </div>

		<div class="modal fade" id="ajax_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>

		{{ bild_dialog }}
		{{ bild_project }}

		<script src="{{ ABS_PATH }}vendor/assets/bootstrap-select/js/i18n/defaults-{{ SESSION.user_lang|default(app.app_lang) }}_{{ SESSION.user_lang|default(app.app_lang)|upper }}.js" type="text/javascript"></script>
        <script src="{{ ABS_PATH }}vendor/assets/summernote/lang/summernote-{{ SESSION.user_lang|default(app.app_lang) }}-{{ SESSION.user_lang|default(app.app_lang)|upper }}.min.js" charset="UTF-8"></script>

        <link href='//fonts.googleapis.com/css?family=PT+Sans:400,700&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>
    </body>
</html>
