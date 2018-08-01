<main class="sidebar-left">
	<div class="container">

		<ol class="breadcrumb">
			<li><a href="{{ HOST_NAME }}">{{ lang.page_breadcrumb }}</a></li>
			<li class="active text-uppercase">{{ lang.order_title }}</li>
        </ol>

		<h1>{{ lang.order_title }}</h1>

		<div class="row">
			{{ perfomens_col }}

			<div class="col-md-6">

				<form action="{{ HOST_NAME }}/order/" method="post" class="ajax_form" enctype="multipart/form-data">
					<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
					<input type="hidden" name="save" value="1">
					<input type="hidden" name="order_id" value="{{ order_info.order_id }}">
					<input type="hidden" name="order_status" value="{{ order_info.order_status }}">

					<div class="content">
						<div class="row change_skill">
							{% for key, skill in lang.lk_skill_order_array %}
								<div class="col-md-4">
				                  	<a href="#" class="btn btn-block btn-adv {% if key == order_info.order_skill %}active{% endif %}" data-id="{{ key }}" data-book="{{ book_link[key] }}">{{ skill|escape|stripslashes }}</a>
				                </div>
							{% endfor %}
							<input type="hidden" name="order_skill" value="{{ order_info.order_skill|default('1') }}">
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<select class="form-control selectpicker" name="order_service">
										{% for service in service_list %}
											<option value="{{ service.id }}" {% if service.id == order_info.order_service.id %}selected{% endif %}>{{ service.title|escape|stripslashes }}</option>
										{% endfor %}
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<select class="form-control selectpicker" name="order_lang_from" data-live-search="true">
										<option value="" class="first">{{ lang.lk_lang_1 }}</option>
										{% for lang in lang_list %}
											<option value="{{ lang.id }}" {% if lang.id == order_info.order_lang_from.id %}selected{% endif %}>{{ lang.title|escape|stripslashes }}</option>
										{% endfor %}
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<select class="form-control selectpicker" name="order_lang_to" data-live-search="true">
										<option value="" class="first">{{ lang.lk_lang_2 }}</option>
										{% for lang in lang_list %}
											<option value="{{ lang.id }}" {% if lang.id == order_info.order_lang_to.id %}selected{% endif %}>{{ lang.title|escape|stripslashes }}</option>
										{% endfor %}
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<select class="form-control selectpicker load_select" name="order_country" data-target="order_city" data-live-search="true">
										<option value="">{{ lang.lk_country }}</option>
										{% for country in country_list %}
											<option value="{{ country.id|escape|stripslashes }}" {% if country.id == order_info.order_country %}selected{% endif %}>{{ country.title|escape|stripslashes }}</option>
										{% endfor %}
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<select class="form-control selectpicker" name="order_city" data-default="{{ lang.lk_city }}" data-live-search="true">
										<option value="">{{ lang.lk_city }}</option>
										{% for city in city_list %}
											<option value="{{ city.id|escape|stripslashes }}" {% if city.id == order_info.order_city.id %}selected{% endif %}>{{ city.title|escape|stripslashes }}</option>
										{% endfor %}
									</select>
								</div>
							</div>

							<div class="col-md-12 hidden_lvl {% if order_info.order_skill != 2 %}hidden{% endif %}">
								<div class="form-group">
									<select class="form-control selectpicker" name="order_level">
										<option value="">{{ lang.order_level }}</option>
										{% for level in level_list %}
											<option value="{{ level.id }}" {% if level.id == order_info.order_level.id %}selected{% endif %}>{{ level.title|escape|stripslashes }}</option>
										{% endfor %}
									</select>
								</div>
							</div>

							<div class="col-md-12 hidden_theme {% if order_info.order_skill != 1 %}hidden{% endif %}">
								<div class="form-group">
									<select class="form-control selectpicker" name="order_theme">
										<option value="">{{ lang.order_theme }}</option>
										{% for theme in theme_list %}
											<option value="{{ theme.id }}" {% if order_info.order_theme.id == theme.id %}selected{% endif %}>{{ theme.title|escape|stripslashes }}</option>
										{% endfor %}
									</select>
								</div>
							</div>

							<div class="col-md-12 date_ranger {% if order_info.order_skill == 2 %}hidden{% endif %}">
								<div class="calendar">
									<input type="hidden" class="form-control order_graph" placeholder="{{ lang.search_form_date_search }}" style="width:250px;">
									<input type="hidden" name="order_start" value="{% if order_info.order_skill != 2 %}{{ order_info.order_start_time|escape|stripslashes }}{% endif %}">
									<input type="hidden" name="order_end" value="{% if order_info.order_skill != 2 %}{{ order_info.order_end_time|escape|stripslashes }}{% endif %}">
								</div>
							</div>


							<div class="col-md-3">
								<strong>{{ lang.order_budget }}</strong>
							</div>
							<div class="col-md-2 left-pad-0">
								<input type="text" class="form-control" placeholder="100" name="order_budget_start" value="{{ order_info.order_budget_start|escape|stripslashes }}" placeholder="{{ lang.lk_budget_from }}">
							</div>
							<div class="col-md-2 left-pad-0">
								<input type="text" class="form-control" placeholder="100" name="order_budget_end" value="{{ order_info.order_budget_end|escape|stripslashes }}" placeholder="{{ lang.lk_budget_to }}">
							</div>
							<div class="col-md-2 left-pad-0">
								<select class="form-control" name="order_currency">
									{% for currency in currency_list %}
										<option value="{{ currency.id }}" {% if currency.id == order_info.order_currency.id %}selected{% endif %}>{{ currency.title|escape|stripslashes }}</option>
									{% endfor %}
								</select>
							</div>
							<div class="col-md-3 left-pad-0">
								<select class="form-control" name="order_currency_time">
									{% for time in time_list %}
										{% if time.id != 70 and time.id != 71 %}
											<option value="{{ time.id }}" {% if time.id == order_info.order_currency_time.id %}selected{% endif %} class="show_time_{{ time.id }}">{{ time.title|escape|stripslashes }}</option>
										{% endif %}
									{% endfor %}
								</select>
							</div>

							<div class="clearfix"></div>

							<div class="col-md-12">
								<div class="form-group">
									<textarea class="form-control" placeholder="{{ lang.order_desc }}" rows="6" name="order_desc">{{ order_info.order_desc|escape|stripslashes }}</textarea>
								</div>
							</div>
							<div class="col-md-12">
								<div class="dress form-group">{{ lang.order_dress }}

									<ul class="list-inline">
										<li>
											<div class="checkbox">
												<label>
													<input class="radio-input" type="radio" name="order_dress" id="dress-yes" value="1" {% if order_info.order_dress == 1 %}checked{% endif %}>
													<span class="radio-custom"></span>
													<span class="label">{{ lang.page_yes }}</span>
												</label>
											</div>
										</li>
										<li>
											<div class="checkbox">
												<label>
													<input class="radio-input" type="radio" name="order_dress" id="dress-no" value="0" {% if order_info.order_dress != 1 %}checked{% endif %}>
													<span class="radio-custom"></span>
													<span class="label">{{ lang.page_no }}</span>
												</label>
											</div>
										</li>
									</ul>
									
								</div>
							</div>
							<div class="col-md-12 order_dress_desc_block {% if order_info.order_dress != 1 %}hidden{% endif %}">
								<div class="form-group">
									<textarea class="form-control" placeholder="{{ lang.order_dress_desc }}" rows="6" name="order_dress_desc">{{ order_info.order_dress_desc|escape|stripslashes }}</textarea>
								</div>
							</div>
							<div class="col-md-6">
								<button type="submit" class="btn btn-block btn-go-on order_status" data-value="1">{{ lang.order_search }}</button>
							</div>
							<div class="col-md-6">
								<button type="submit" class="btn btn-block btn-go-on order_status" data-value="0">{{ lang.order_draft }}</button>
							</div>

							<div class="col-md-12">
								<div class="error-message-color error-message"></div>
							</div>
						</div>
					</div>
				</form>
			</div>

			<div class="col-md-3">
				<div class="sidebar-right">
					<h3>{{ lang.order_in_work }} <a href="{{ HOST_NAME }}/bank_zakazov/?order_owner={{ SESSION.user_id }}&order_status=1" class="pull-right"><small>{{ lang.order_in_search_all }}</small></a></h3>
					<div>
						{% for work in order_work %}
							<div class="work">
								<p class="pull-left">{{ work.order_add }}</p>
								<p class="pull-right">{{ work.order_city.title|escape|stripslashes }}</p>
								<p class="napravlenie"><a href="{{ HOST_NAME }}/order-{{ work.order_id }}/">{{ work.order_lang_from.title|escape|stripslashes }} - {{ work.order_lang_to.title|escape|stripslashes }}</a></p>
							</div>
						{% endfor %}
					</div>

					<h3>{{ lang.order_in_draft }} <a href="{{ HOST_NAME }}/bank_zakazov/?order_owner={{ SESSION.user_id }}&order_status=0" class="pull-right"><small>{{ lang.order_in_search_all }}</small></a></h3>
					<div>
						{% for draft in order_draft %}
							<div class="work">
								<p class="pull-left">{{ draft.order_add }}</p>
								<p class="pull-right">{{ draft.order_city.title|escape|stripslashes }}</p>
								<p class="napravlenie"><a href="{{ HOST_NAME }}/order-{{ draft.order_id }}/">{{ draft.order_lang_from.title|escape|stripslashes }} - {{ draft.order_lang_to.title|escape|stripslashes }}</a></p>
							</div>
						{% endfor %}
					</div>

					<h3>{{ lang.order_in_close }} <a href="{{ HOST_NAME }}/bank_zakazov/?order_owner={{ SESSION.user_id }}&order_close=1" class="pull-right"><small>{{ lang.order_in_search_all }}</small></a></h3>
					<div>
						{% for close in order_close %}
							<div class="work">
								<p class="pull-left">{{ close.order_add }}</p>
								<p class="pull-right">{{ close.order_city.title|escape|stripslashes }}</p>
								<p class="napravlenie"><a href="{{ HOST_NAME }}/order-{{ close.order_id }}/">{{ close.order_lang_from.title|escape|stripslashes }} - {{ close.order_lang_to.title|escape|stripslashes }}</a></p>
							</div>
						{% endfor %}
					</div>
				</div>
			</div>

		</div>

	</div>
</main>

<script src="{{ ABS_PATH }}vendor/assets/moment-with-locales.js"></script>
<script type="text/javascript" src="{{ ABS_PATH }}vendor/assets/flatpickr/dist/flatpickr.js"></script>
<link rel="stylesheet" type="text/css" href="{{ ABS_PATH }}vendor/assets/flatpickr/dist/themes/airbnb.css" />
<script src="{{ ABS_PATH }}vendor/assets/flatpickr/dist/l10n/{{ SESSION.user_lang|default(app.app_lang) }}.js" charset="UTF-8"></script>
<script>

	var lang_1 = "{{ lang.lk_lang_1 }}";
	var lang_2 = "{{ lang.lk_lang_2 }}";

	var lang_3 = "{{ lang.search_form_field_lang2 }}";
	var lang_4 = "{{ lang.search_form_field_lang2_to }}";

$(function () {
	
	// init
	App.order_core();
	// init

	var start = $("input[name=order_start]").val();
	var end = $("input[name=order_end]").val();
	//$("input[name=order_start]").val(moment(start).format(date_format_js));
	//$("input[name=order_end]").val(moment(end).format(date_format_js));

	if(!start || !end) {
		start = moment();
		end = moment().add(1, 'days');
	}

	$(".order_graph").flatpickr({
		locale: '{{ SESSION.user_lang|default(app.app_lang) }}',
		inline: true,
		enableTime: true,
		dateFormat: 'd.m.Y H:S',
		allowInput: true,
		time_24hr: true,
		mode: "range",
		minDate: "today",
		defaultDate: [start, end],
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
				$("input[name=order_start]").val(date_str[0]+" "+date_str[1]);
				$("input[name=order_end]").val(date_str[3]+" "+date_str[4]);
			}
		},
	});


	$("select[name=order_service]").on("change", function() {
		var check = $(this).val();
		if(check == 55) {
			App.search_writer_core();
		}

	});

	$(".dress input[type=radio]").on('change', function() {
		var this_value = $(this).val();

		if(this_value == 1) {
			$(".order_dress_desc_block").removeClass("hidden");
		} else {
			$(".order_dress_desc_block").addClass("hidden");
		}
	});
});
</script>
