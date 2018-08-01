<main class="sidebar-left">
	<div class="container">

		<ol class="breadcrumb">
			<li><a href="{{ HOST_NAME }}">{{ lang.page_breadcrumb }}</a></li>
			<li><a href="{{ HOST_NAME }}/bank_zakazov/">{{ lang.order_list }}</a></li>
			<li class="active text-uppercase">{{ lang.order_page }} №{{ order_info.order_id }}</li>
		</ol>

		<h1>{{ lang.order_page }} №{{ order_info.order_id }}</h1>


		<div class="row">

			{{ perfomens_col }}

			<div class="col-md-9">
				<div class="content-right">

					<h3>{{ lang.order_page_info }}</h3>
					<div class="row zakaz-info">
						<div class="col-md-3 col-sm-3 col-xs-6">
							<p class="text-uppercase">
								{{ order_info.order_service.title|escape|stripslashes }}
							</p>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-6">
							<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/users/{{ order_info.user_photo }}&width=100&height=100" alt="" class="img-circle pull-left avatar-mini">
							<p><strong>{{ lang.order_owner }}</strong></p>
							<p>{{ order_info.user_name|escape|stripslashes }}</p>
							{{ order_info.user_rating_small_tpl }}
						</div>
						<div class="col-md-3 col-sm-3 col-xs-6">
							<div class="row">
								<div class="col-md-6 col-sm-6 col-xs-6">
									<p><strong>{{ lang.lk_country }}</strong></p>
									<p>
										{% for country in country_list %}
											{% if country.id == order_info.order_country %}{{ country.title|escape|stripslashes }}{% endif %}
										{% endfor %}
									</p>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<p><strong>{{ lang.lk_city }}</strong></p>
									<p>
										{{ order_info.order_city.title|escape|stripslashes }}</option>
									</p>
								</div>
							</div>
						</div>

						<div class="col-md-3 col-sm-3 col-xs-6">
							<p><strong>{{ lang.order_start_and_end }}</strong></p>
							{% if order_info.order_start_time %}<p>{{ lang.order_start }} {{ order_info.order_start_time|escape|stripslashes }}</p>{% endif %}
							{% if order_info.order_end_time %}<p>{{ lang.order_end }} {{ order_info.order_end_time|escape|stripslashes }}</p>{% endif %}
						</div>
						
					</div>

					<h3>{{ lang.order_page_info }}</h3>
					<div class="row zakaz-info-2">
						<div class="col-md-5 text-right">
							<p><strong>{{ lang.lk_lang_var }}</strong></p>
						</div>
						<div class="col-md-7">
							<p>{{ order_info.order_lang_from.title|escape|stripslashes }}</p>
							<p>{{ order_info.order_lang_to.title|escape|stripslashes }}</p>
						</div>

						<div class="clearfix"></div>

						{% if order_info.order_skill == 2 %}
							<div class="col-md-5 text-right">
								<p><strong>{{ lang.order_level }}</strong></p>
							</div>
							<div class="col-md-7">
								<p>{{ order_info.order_level.title|escape|stripslashes }}</p>
							</div>

							<div class="clearfix"></div>
						{% endif %}

						<div class="clearfix"></div>

						{% if order_info.order_theme.title %}
							<div class="col-md-5 text-right">
								<p><strong>{{ lang.order_theme }}</strong></p>
							</div>
							<div class="col-md-7">
								<p>{{ order_info.order_theme.title|escape|stripslashes }}</p>
							</div>
						{% endif %}

						{% if order_info.order_desc %}
							<div class="col-md-5 text-right">
								<p><strong>{{ lang.order_desc }}</strong></p>
							</div>
							<div class="col-md-7">
								<p>{{ order_info.order_desc|escape|stripslashes }}</p>
							</div>
						{% endif %}

						<div class="clearfix"></div>

						{% if order_info.order_dress == 1 %}
							<div class="col-md-5 text-right">
								<p><strong>{{ lang.order_dress }}</strong></p>
							</div>
							<div class="col-md-7">
								<p>{% if order_info.order_dress == 1 %}{{ lang.page_yes }}{% else %}{{ lang.page_no }}{% endif %}</p>
							</div>
						{% endif %}
						
						<div class="clearfix"></div>

						{% if order_info.order_dress == 1 and order_info.order_dress_desc %}
							<div class="col-md-5 text-right">
								<p><strong>{{ lang.order_dress_desc }}</strong></p>
							</div>
							<div class="col-md-7">
								<p>{{ order_info.order_dress_desc|escape|stripslashes }}</p>
							</div>

							<div class="clearfix"></div>
						{% endif %}

						{% if order_info.order_owner == SESSION.user_id and order_info.order_close != 1 %}
							<div class="col-md-5 text-right">
								<p><strong>{{ lang.order_page_change_perfomens }}</strong></p>
							</div>
							<div class="col-md-7">
								<div class="form-group">
									<select class="form-control change_perfomens" name="order_perfomens" data-order="{{ order_info.order_id }}">
										<option value="">{{ lang.order_page_change_perfomens }}</option>
										{% for friends in friends_list %}
											<option value="{{ friends.id }}" {% if friends.id == order_info.order_perfomens %}selected{% endif %}>{{ friends.user_name|escape|stripslashes }}, ID {{ friends.id|escape|stripslashes }}</option>
										{% endfor %}
									</select>
								</div>
							</div>

							<div class="clearfix"></div>
						{% endif %}

						{% if SESSION.user_id %}
							<div class="status">
								{% if order_info.order_owner == SESSION.user_id and order_info.order_status == 1 and order_info.order_close != 1 %}
									{% if not order_info.order_perfomens %}
										<div class="col-md-3">
											<a href="{{ HOST_NAME }}/order/edit-{{ order_info.order_id }}/" class="btn btn-block btn-go-on">{{ lang.order_btn_edit }}</a>
										</div>

										<div class="col-md-3">
											<a href="{{ HOST_NAME }}/order/close-{{ order_info.order_id }}/" class="btn btn-block btn-go-on">{{ lang.order_btn_close }}</a>
										</div>
									{% endif %}
									{% if order_info.order_perfomens %}
										<div class="col-md-3">
											<a href="{{ HOST_NAME }}/order/complite-{{ order_info.order_id }}/" class="btn btn-block btn-go-on">{{ lang.order_btn_finish }}</a>
										</div>
									{% endif %}
								{% endif %}

								{% if order_info.order_owner == SESSION.user_id and order_info.order_status != 1 and order_info.order_close != 1 %}
									<div class="col-md-3">
										<a href="{{ HOST_NAME }}/order/edit-{{ order_info.order_id }}/" class="btn btn-block btn-go-on">{{ lang.order_btn_edit }}</a>
									</div>
									<div class="col-md-3">
										<a href="{{ HOST_NAME }}/order/open-{{ order_info.order_id }}/" class="btn btn-block btn-go-on">{{ lang.order_btn_open }}</a>
									</div>
									<div class="col-md-3">
										<a href="{{ HOST_NAME }}/order/delete-{{ order_info.order_id }}/" class="btn btn-block btn-go-on">{{ lang.order_btn_delete }}</a>
									</div>
								{% endif %}

								{% if SESSION.user_group == 3 and order_info.order_status == 1 and order_info.order_close != 1  and not is_respons and order_info.order_perfomens != SESSION.user_id %}
									<div class="col-md-3">
										<a href="{{ HOST_NAME }}/order/respons-{{ order_info.order_id }}/" class="btn btn-block btn-go-on">{{ lang.order_btn_respons }}</a>
									</div>
								{% endif %}

								{% if SESSION.user_group == 3 and order_info.order_status == 1 and order_info.order_close != 1 and is_respons and order_info.order_perfomens != SESSION.user_id %}
									<div class="col-md-3">
										<a href="{{ HOST_NAME }}/order/accept-{{ order_info.order_id }}/" class="btn btn-block btn-go-on">{{ lang.order_btn_accept }}</a>
									</div>
									<div class="col-md-3">
										<a href="{{ HOST_NAME }}/order/deny-{{ order_info.order_id }}/" class="btn btn-block btn-go-on">{{ lang.order_btn_deny }}</a>
									</div>
								{% endif %}

								{% if SESSION.user_group == 3 and order_info.order_status == 1 and order_info.order_close != 1 and order_info.order_perfomens == SESSION.user_id %}
									<div class="col-md-3">
										<a href="{{ HOST_NAME }}/order/complite-{{ order_info.order_id }}/" class="btn btn-block btn-go-on">{{ lang.order_btn_done }}</a>
									</div>
									<div class="col-md-3">
										<a href="{{ HOST_NAME }}/order/deny-{{ order_info.order_id }}/" class="btn btn-block btn-go-on">{{ lang.order_btn_deny }}</a>
									</div>
								{% endif %}

								{% if SESSION.user_group == 3 and order_info.order_status == 1 and order_info.order_close == 1 and order_info.order_perfomens == SESSION.user_id and order_info.order_accept == 1 and not check_otziv %}
									<div class="col-md-3">
										<a href="{{ HOST_NAME }}/message/?message_to={{ order_info.order_owner }}&order_id={{ order_info.order_id }}" class="btn btn-block btn-go-on">{{ lang.order_btn_otvive }}</a>
									</div>
								{% endif %}

								{% if SESSION.user_group == 4 and order_info.order_status == 1 and order_info.order_close == 1 and order_info.order_owner == SESSION.user_id and order_info.order_accept != 1 and not check_otziv %}
									<div class="col-md-3">
										<a href="{{ HOST_NAME }}/message/?message_to={{ order_info.order_perfomens }}" class="btn btn-block btn-go-on">{{ lang.order_btn_otvive }}</a>
									</div>
								{% endif %}
							</div>
						{% endif %}


					</div>
				</div>
			</div>

		</div>

	</div>
</main>

<script>
$(function () {

	// init
	App.order_core();
	// init
});
</script>
