<main class="no-sidebar">
	<div class="container">

		<ol class="breadcrumb">
			<li><a href="{{ HOST_NAME }}">{{ lang.page_breadcrumb }}</a></li>
			<li class="active text-uppercase">{{ lang.search_title }}</li>
		</ol>

		<h1>{{ lang.search_title|escape|stripslashes }}</h1>

		<div class="row">
			{{ perfomens_col }}

			<div class="col-md-{% if perfomens_col %}9{% else %}12{% endif %} col-sm-{% if perfomens_col %}9{% else %}12{% endif %}">
				<div class="content-right">
					<div class="profile-block">

						<div class="order-nav">
							<ul class="list-inline order_change">
								{% for key, skill in lang.lk_skill_search_perfomens_array %}
									<li>
										<a href="{{ HOST_NAME }}{{ link_lang_pref }}/{{ skill.url }}/" {% if key == REQUEST.user_type %}class="active"{% endif %}>{{ skill.title|escape|stripslashes }}</a>
									</li>
								{% endfor %}
							</ul>
						</div>

						<form action="{{ HOST_NAME }}{{ link_lang_pref }}/{{ type_search }}/" method="post" class="" enctype="multipart/form-data">
							<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
							<input type="hidden" name="filter" value="1">
							<input type="hidden" name="user_type" value="{{ REQUEST.user_type|escape|stripslashes }}">

							{{ form_filter }}

							<div class="row">
								<div class="col-md-3">
								</div>
								{% if not 0 == REQUEST.user_type %}
									<div class="col-md-6 text-center">
										<button type="button" class="btn btn-link btn-collapse" data-toggle="collapse" data-target="#demo" id="demoD">
											<img src="{{ ABS_PATH }}assets/site/template/images/img-collapse.png" alt="">
										</button>
									</div>
								{% endif %}
								<div class="col-md-3 pull-right">
									<button type="submit" class="btn btn-block btn-go-on">{{ lang.btn_search }}</button>
								</div>
							</div>
						</form>

						<div class="row">
							<div class="performers-review">

								{% for perfomer in perfomer_list %}
									<div class="col-md-6">
										<div class="perfomers-card {% if SESSION.user_id %}normal{% else %}guest{% endif %}">
											<div class="row">
												<div class="col-md-{% if SESSION.user_id %}5{% else %}4{% endif %}">
													<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/users/{{ perfomer.user_photo }}&width=250&height=300" alt="" class="img-responsive">
												</div>
												<div class="col-md-{% if SESSION.user_id %}7{% else %}8{% endif %} perfomers-info">
													{% if not SESSION.user_id %}<div class="pull-right">{{ perfomer.user_rating_small_tpl }}</div>{% endif %}
													<h4>{{ perfomer.user_login|escape|stripslashes }} </h4>

													<div class="clearfix"></div>
													<div class="perfomers-dop-info">
														<p><strong>
															{% for country in country_list %}
																{% if country.id == perfomer.user_country %}{{ country.title|escape|stripslashes }}{% endif %}
															{% endfor %}
															{% for city in perfomer.city_list %}
																{% if city.id == perfomer.user_city %}, {{ city.title|escape|stripslashes }}{% endif %}
															{% endfor %}
														</strong></p>

														<div class="plate1">{{ lang.lk_lang_default }}
															{% if perfomer.user_default_lang %}{{ perfomer.user_default_lang.title|escape|stripslashes }}{% else %}{{ lang.lk_user_default_lang_empty}}{% endif %}
														</div>

														{% if perfomer.lang_var %}
															<div class="plate2">{{ lang.lk_lang_var }}</div>
															<ul class="plate2-list list-unstyled">
																{% for lang in perfomer.lang_var|slice(0, 3) %}
																	<li>
																		{{ lang.serv_lang_from.title|escape|stripslashes }} - {{ lang.serv_lang_to.title|escape|stripslashes }}
																	</li>
																{% endfor %}
															</ul>
														{% endif %}
													</div>

													{% if not SESSION.user_id %}<a href="{{ HOST_NAME }}{{ link_lang_pref }}/profile-{{ perfomer.id|escape|stripslashes }}/" class="btn btn-block btn-go-on">{{ lang.btn_detail }}</a>{% endif %}
													
												</div>
											</div>

											{% if SESSION.user_id %}
												<div class="row">
													<div class="col-md-{% if SESSION.user_id %}5{% else %}4{% endif %}">
														{{ perfomer.user_rating_tpl }}
													</div>
													<div class="col-md-{% if SESSION.user_id %}7{% else %}8{% endif %}">
														<a href="{{ HOST_NAME }}{{ link_lang_pref }}/profile-{{ perfomer.id|escape|stripslashes }}/" class="btn btn-block btn-go-on">{{ lang.btn_detail }}</a>
													</div>
												</div>
											{% endif %}
										</div>
									</div>
								{% else %}
									<div class="col-md-12 album_empty big_font"><p>{{ lang.search_perfomens_empty }}</p></div>
								{% endfor %}

							</div>
							<!-- Pagination -->
							<div class="col-md-12 text-center">
								{% if page_nav %}
									{{ page_nav }}
								{% endif %}
							</div>
						</div>

					</div>
				</div>
			</div>

		</div>

		{{ page_info.page_text|stripslashes }}

	</div>
</main>

<script src="{{ ABS_PATH }}vendor/assets/moment-with-locales.js"></script>
<script type="text/javascript" src="{{ ABS_PATH }}vendor/assets/flatpickr/dist/flatpickr.js"></script>
<link rel="stylesheet" type="text/css" href="{{ ABS_PATH }}vendor/assets/flatpickr/dist/themes/airbnb.css" />
<script src="{{ ABS_PATH }}vendor/assets/flatpickr/dist/l10n/{{ SESSION.user_lang|default(app.app_lang) }}.js" charset="UTF-8"></script>

<script>
$(function () {

	$('.selectpicker').selectpicker({'style':'btn-default', 'size': 5});

	$('.collapse').on('show.bs.collapse', function () {
		$(this).siblings('.row').addClass('active');
	});

	$('.collapse').on('hide.bs.collapse', function () {
		$(this).siblings('.row').removeClass('active');
	});

	$("input[name=serv_type_service]").on("change", function() {
		var check = $(this).val();
		if(check == 55) {
			$("select[name=serv_type_service]").selectpicker('val', '');
			App.search_writer_core();
			
			return false;
		}

		if(check == 47 || check == 48) {
			$(".show_time_26").addClass("hidden");
			$(".show_time_25").attr('selected', true);

			$(".load_place").removeClass("hidden");
		} else {
			$(".show_time_26").removeClass("hidden");
			$(".load_place").addClass("hidden");
		}


	});


	var start = moment();
	var end = moment().add(1, 'days');
	$("input[name=graph_start]").val(moment(start).format(date_format_js));
	$("input[name=graph_end]").val(moment(end).format(date_format_js));

	$(".lk_graph").flatpickr({
		locale: '{{ SESSION.user_lang|default(app.app_lang) }}',
		inline: false,
		enableTime: false,
		dateFormat: 'd.m.Y',
		allowInput: true,
		time_24hr: true,
		mode: "range",
		minDate: "today",
		onOpen: [
			function(selectedDates, dateStr, instance){
				instance;
				if(!dateStr) {
					instance.setDate(new Date());
				}
			}
		],
		onChange: function(selectedDates, dateStr, instance) {
			var date_str = dateStr.split(" ");
			if(date_str[0] && date_str[2]) {
				$("input[name=graph_start]").val(date_str[0]+" 00:00");
				$("input[name=graph_end]").val(date_str[2]+" 23:55");
			}
		},
	});

	// init
	App.search_perfomers_core();
	// init

	$(".load_service input[type=radio]").on("change", function() {
		$(".load_communication").addClass("hidden");
		$(".load_place").addClass("hidden");

		$(".load_service input[type=radio]:checked").each(function( index ) {
			var check = $(this).val();
			if(check == 49 || check == 56) {
				$(".load_communication").removeClass("hidden");
			}

			if(check == 47 || check == 48) {
				$(".load_place").removeClass("hidden");
			}

			if(check == 55) {
				App.search_writer_core();
			}
		});
	});

	$("input[name=graph_start].datepicker, input[name=graph_end].datepicker").flatpickr({
		locale: '{{ SESSION.user_lang|default(app.app_lang) }}',
		enableTime: true,
		dateFormat: 'd.m.Y H:i',
		allowInput: true,
		time_24hr: true,
		minDate: "today",

	});
});
</script>
