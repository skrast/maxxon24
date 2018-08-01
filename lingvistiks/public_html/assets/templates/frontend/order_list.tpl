<main class="sidebar-left">
	<div class="container">

		<ol class="breadcrumb">
			<li><a href="{{ HOST_NAME }}">{{ lang.page_breadcrumb }}</a></li>
			<li class="active text-uppercase">{{ lang.order_list }}</li>
		</ol>

		<h1>{{ lang.order_list }}</h1>

		<div class="row">

			{{ perfomens_col }}

			<div class="col-md-{% if perfomens_col %}9{% else %}12{% endif %} col-sm-{% if perfomens_col %}9{% else %}12{% endif %}">
				<div class="content-right">
					<div class="profile-block">
						<div class="order-nav">
							<ul class="list-inline order_change">
								{% if SESSION.user_group == 3 %}
									<li><a href="{{ HOST_NAME }}/bank_zakazov/" {% if not REQUEST.order_respons and not REQUEST.order_status and not REQUEST.order_close %}class="active"{% endif %}>{{ lang.page_all_show }}</a></li>
									<li><a href="{{ HOST_NAME }}/bank_zakazov/?order_respons=1&filter=1" {% if REQUEST.order_respons %}class="active"{% endif %}>{{ lang.order_search_respons }}</a></li>
									<li><a href="{{ HOST_NAME }}/bank_zakazov/?order_status=1&filter=1" {% if REQUEST.order_status == 1 %}class="active"{% endif %}>{{ lang.order_search_in_work }}</a></li>
									<li><a href="{{ HOST_NAME }}/bank_zakazov/?order_close=1&filter=1" {% if REQUEST.order_close == 1 %}class="active"{% endif %}>{{ lang.order_search_in_done }}</a></li>
								{% endif %}
				
								{% if SESSION.user_group == 4 %}
									<li><a href="{{ HOST_NAME }}/bank_zakazov/" {% if not REQUEST.order_owner and not REQUEST.order_status and not REQUEST.order_close %}class="active"{% endif %}>{{ lang.page_all_show }}</a></li>
									<li><a href="{{ HOST_NAME }}/bank_zakazov/?order_owner=1&order_status=1&filter=1" {% if REQUEST.order_owner and REQUEST.order_status == "1" %}class="active"{% endif %}>{{ lang.order_search_my_order }}</a></li>
									<li><a href="{{ HOST_NAME }}/bank_zakazov/?order_owner=1&order_status=2&filter=1" {% if REQUEST.order_status == "2" and not REQUEST.order_close == 1 %}class="active"{% endif %}>{{ lang.order_search_my_draft }}</a></li>
									<li><a href="{{ HOST_NAME }}/bank_zakazov/?order_owner=1&order_close=1&filter=1" {% if REQUEST.order_close == 1 %}class="active"{% endif %}>{{ lang.order_search_close }}</a></li>
								{% endif %}
							</ul>
						</div>
					</div>

					<form action="{{ HOST_NAME }}/bank_zakazov/" method="post" class="" enctype="multipart/form-data">
						<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
						<input type="hidden" name="filter" value="1">
						<input type="hidden" name="order_type" value="{{ REQUEST.user_type|escape|stripslashes }}">

						<input type="hidden" name="order_respons" value="{{ REQUEST.order_respons|escape|stripslashes }}">
						<input type="hidden" name="order_status" value="{{ REQUEST.order_status|escape|stripslashes }}">
						<input type="hidden" name="order_close" value="{{ REQUEST.order_close|escape|stripslashes }}">
						<input type="hidden" name="order_owner" value="{{ REQUEST.order_owner|escape|stripslashes }}">


						<div class="row">
							<div class="col-md-7">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<select class="form-control selectpicker load_select" name="order_country" data-target="order_city" data-live-search="true">
												<option value="">{{ lang.lk_country }}</option>
												{% for country in country_list %}
													<option value="{{ country.id|escape|stripslashes }}" {% if country.id == REQUEST.order_country %}selected{% endif %}>{{ country.title|escape|stripslashes }}</option>
												{% endfor %}
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<select class="form-control selectpicker" name="order_city" data-default="{{ lang.lk_city }}" data-live-search="true">
												<option value="">{{ lang.lk_city }}</option>
												{% for city in city_list %}
													<option value="{{ city.id|escape|stripslashes }}" {% if city.id == REQUEST.order_city %}selected{% endif %}>{{ city.title|escape|stripslashes }}</option>
												{% endfor %}
											</select>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-4">
										<strong>{{ lang.lk_lang_var }}</strong>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<select class="form-control selectpicker" name="order_lang_from" data-live-search="true">
												<option value="">{{ lang.lk_lang_1 }}</option>
												{% for lang in lang_list %}
													<option value="{{ lang.id }}" {% if lang.id == REQUEST.order_lang_from %}selected{% endif %}>{{ lang.title|escape|stripslashes }}</option>
												{% endfor %}
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<select class="form-control selectpicker" name="order_lang_to" data-live-search="true">
												<option value="">{{ lang.lk_lang_2 }}</option>
												{% for lang in lang_list %}
													<option value="{{ lang.id }}" {% if lang.id == REQUEST.order_lang_to %}selected{% endif %}>{{ lang.title|escape|stripslashes }}</option>
												{% endfor %}
											</select>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-5">
								<div class="row change-service">
									{% for key, skill in lang.lk_skill_order_array %}
										<div class="col-md-6">
											<div class="checkbox">
												<label>
													<input class="checkbox-input" type="checkbox" name="order_skill[]" value="{{ key }}" {% if key in REQUEST.order_skill %}checked{% endif %}>
													<span class="checkbox-custom"></span>
													<span class="label">{{ skill|escape|stripslashes }}</span>
												</label>
											</div>
										</div>
									{% endfor %}
								</div>
							</div>
						</div>

						<div class="collapse" id="demo">
							<div class="row">
								<div class="col-md-7">
									<div class="row row-last">
										<div class="col-md-6">
											<div class="form-group">
												<input type="text" class="form-control datepicker" name="order_start" value="{{ REQUEST.order_start|escape|stripslashes }}" placeholder="{{ lang.order_start }}">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<input type="text" class="form-control datepicker" name="order_end" value="{{ REQUEST.order_end|escape|stripslashes }}" placeholder="{{ lang.order_end }}">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<hr>

						<div class="row">
							<div class="col-md-3 pull-left">
								{% if SESSION.user_group == 4 %}
									<a href="{{ HOST_NAME }}/order/" class="btn btn-block btn-search" style="margin-top:0px">{{ lang.order_list_add }}</a>
								{% endif %}
							</div>
							
							<div class="col-md-6 text-center">
								<button type="button" class="btn btn-link btn-collapse" data-toggle="collapse" data-target="#demo" id="demoD">
									<img src="{{ ABS_PATH }}assets/site/template/images/img-collapse.png" alt="">
								</button>
							</div>

							<div class="col-md-3 pull-right">
								<button type="submit" class="btn btn-block btn-go-on">{{ lang.btn_search }}</button>
							</div>
						</div>
					</form>

					<div class="row">

						{% for order in order_list %}
							<div class="col-md-12 orders">
								<p class="pull-right"><strong>{{ lang.order_id }} {{ order.order_id }}</strong></p>
								<div class="order-block">
									<div class="row">
										<div class="col-md-3 col-sm-3 col-xs-6">
											<p><strong>{{ lang.order_list_end }}</strong></p>
											<p>
												{{ order.order_service.title|escape|stripslashes }}
											</p>
										</div>

										<div class="col-md-3 col-sm-3 col-xs-6">
											<p><strong>{{ lang.order_list_country_and_city }}</strong></p>
											<p>
												{% for country in country_list %}
													{% if country.id == order.order_country %}{{ country.title|escape|stripslashes }}{% endif %}
												{% endfor %}
											</p>
											<p>{{ order.order_city.title|escape|stripslashes }}</p>
										</div>

										<div class="col-md-3 col-sm-3 col-xs-6">
											<p><strong>{{ lang.order_start_and_end }}</strong></p>
											{% if order.order_start_time %}<p>{{ lang.order_start }} {{ order.order_start_time|escape|stripslashes }}</p>{% endif %}
											{% if order.order_end_time %}<p>{{ lang.order_end }} {{ order.order_end_time|escape|stripslashes }}</p>{% endif %}
										</div>

										<div class="col-md-3 col-sm-3 col-xs-12">

											<a href="{{ HOST_NAME }}/order-{{ order.order_id }}/" class="btn btn-block btn-go-on">{{ lang.btn_open_1 }}</a>

										</div>
									</div>
								</div>
							</div>
						{% endfor %}

						{% if page_nav %}
							{{ page_nav }}
						{% endif %}

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
$(function () {

	$('.collapse').on('show.bs.collapse', function () {
	$(this).siblings('.row').addClass('active');
  });

  $('.collapse').on('hide.bs.collapse', function () {
	$(this).siblings('.row').removeClass('active');
  });

	// init
	App.search_order_core();
	// init

	$("input[name=order_start].datepicker, input[name=order_end].datepicker").flatpickr({
		locale: '{{ SESSION.user_lang|default(app.app_lang) }}',
		enableTime: true,
		dateFormat: 'd.m.Y H:i',
		allowInput: true,
		time_24hr: true,
		minDate: "today",
	});

});
</script>
