<main class="sidebar-left">
	<div class="container">

		{% if SESSION.user_group == 3 %}
			<ol class="breadcrumb">
				<li><a href="{{ HOST_NAME }}">{{ lang.page_breadcrumb }}</a></li>
				<li class="active text-uppercase">{{ lang.briefcase_list_perfomens }}</li>
			</ol>
			<h1>{{ lang.briefcase_list_perfomens }}</h1>
		{% endif %}

		{% if SESSION.user_group == 4 %}
			<ol class="breadcrumb">
				<li><a href="{{ HOST_NAME }}">{{ lang.page_breadcrumb }}</a></li>
				<li class="active text-uppercase">{{ lang.briefcase_list_owner }}</li>
			</ol>
			<h1>{{ lang.briefcase_list_owner }}</h1>
		{% endif %}

		<div class="pull-right order-nav">
			<ul class="list-inline">
				{% if SESSION.user_group == 3 %}
					<li>
						<a href="{{ HOST_NAME }}/briefcase/?briefcase_type=1" {% if 1 == REQUEST.briefcase_type or not REQUEST.briefcase_type %}class="active"{% endif %}>{{ lang.briefcase_perfomens_jobs|escape|stripslashes }}</a>
					</li>
					<li>
						<a href="{{ HOST_NAME }}/briefcase/?briefcase_type=2" {% if 2 == REQUEST.briefcase_type %}class="active"{% endif %}>{{ lang.briefcase_perfomens_order|escape|stripslashes }}</a>
					</li>
				{% endif %}

				{% if SESSION.user_group == 4 %}
					<li>
						<a href="{{ HOST_NAME }}/briefcase/?briefcase_type=1" {% if 1 == REQUEST.briefcase_type or not REQUEST.briefcase_type %}class="active"{% endif %}>{{ lang.briefcase_owner_jobs|escape|stripslashes }}</a>
					</li>
					<li>
						<a href="{{ HOST_NAME }}/briefcase/?briefcase_type=2" {% if 2 == REQUEST.briefcase_type %}class="active"{% endif %}>{{ lang.briefcase_owner_order|escape|stripslashes }}</a>
					</li>
				{% endif %}
			</ul>
		</div>


		<div class="row">

			{{ perfomens_col }}

			<div class="col-md-9">
				<div class="content-right">
					<div class="row customer-in">
						<div class="col-md-12">
							<div class="message-info">
								<strong>{{ lang.briefcase_hello }}</strong>
								<p>
								{% if SESSION.user_group == 3 %}
									{% if REQUEST.briefcase_type == 1 or not REQUEST.briefcase_type %}
										{{ lang.briefcase_hello4 }}
									{% elseif REQUEST.briefcase_type == 2 %}
										{{ lang.briefcase_hello3 }}
									{% endif %}
								{% endif %}

								{% if SESSION.user_group == 4 %}
									{% if REQUEST.briefcase_type == 1 or not REQUEST.briefcase_type %}
										{{ lang.briefcase_hello2 }}
									{% elseif REQUEST.briefcase_type == 2 %}
										{{ lang.briefcase_hello1 }}
									{% endif %}
								{% endif %}
								</p>

							</div>
						</div>
						<div class="col-md-12">


							{% for briefcase in respons_list %}

								{% if SESSION.user_group == 3 %}

									{% if briefcase.response_work %}
										<div class="customer-block">
											<p class="text-right order-name"><a href="{{ HOST_NAME }}/order-{{ briefcase.response_work }}/">{{ lang.briefcase_order }} {{ lang.lk_skill_array[briefcase.order_skill]|escape|stripslashes }} #{{ briefcase.response_id }}</a></p>
											<div class="customer-img">
												<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/users/{{ briefcase.user_photo }}&width=150&height=200" alt="">
											</div>
											<div class="customer-info">
												<div class="pull-left">
													<p class="customer-name"><strong>{{ briefcase.order_desc|escape|stripslashes }}</strong></p>
												</div>
												<div class="pull-right">
													{{ briefcase.order_add|escape|stripslashes }}
												</div>
												<div class="clearfix"></div>
												<div class="row">
													<div class="col-md-3">
														{{ briefcase.order_start|escape|stripslashes }} <br>
														{{ briefcase.order_end|escape|stripslashes }}
													</div>
													<div class="col-md-3">
														<p class="customer-country"><strong>
															{% for country in country_list %}
																{% if country.id == briefcase.order_country %}{{ country.title|escape|stripslashes }}{% endif %}
															{% endfor %}
															{% if briefcase.order_city.id %}<br> {{ briefcase.order_city.title|escape|stripslashes }}{% endif %}
														</strong></p>
													</div>
													<div class="col-md-3">
														{{ lang.lk_skill_array[briefcase.order_skill]|escape|stripslashes }}<br>
														{{ briefcase.order_service.title|escape|stripslashes }} <br>

														{{ briefcase.order_budget_start|escape|stripslashes }} - {{ briefcase.order_budget_end|escape|stripslashes }} {{ briefcase.order_currency.title|escape|stripslashes }}

													</div>
													<div class="col-md-3">
														<p>
															{{ briefcase.order_lang_from.title|escape|stripslashes }} <br> {{ briefcase.order_lang_to.title|escape|stripslashes }}
														</p>
													</div>
													<div class="col-md-12">
														{% if briefcase.response_perfomens_accept %}
															<p>{{ lang.bot_order_perfomens_appruve }}</p>
														{% elseif briefcase.response_perfomens_not_accept %}
															<p>{{ lang.bot_order_perfomens_not_appruve }}</p>
														{% else %}
															<div class="row">
																<div class="col-md-4">
																	<a href="{{ HOST_NAME }}/order-{{ briefcase.response_work }}/" class="btn btn-block btn-go-on">{{ lang.btn_open }}</a>
																</div>
																<div class="col-md-4">
																	<a href="{{ HOST_NAME }}/order/accept-{{ briefcase.response_id }}/" class="btn btn-block btn-go-on">{{ lang.btn_accept }}</a>
																</div>
																<div class="col-md-4">
																	<a href="{{ HOST_NAME }}/order/deny-{{ briefcase.response_id }}/" class="btn btn-block btn-go-on">{{ lang.btn_deny }}</a>
																</div>
															</div>
														{% endif %}
													</div>
												</div>
											</div>
										</div>
									{% endif %}


									{% if briefcase.response_resume %}
										<div class="customer-block">
											<p class="text-right order-name"><a href="{{ HOST_NAME }}{{ link_lang_pref }}/profile-{{ briefcase.id|escape|stripslashes }}/">{{ lang.resume_title }} #{{ briefcase.response_id }}</a></p>
											<div class="customer-img">
												<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/users/{{ briefcase.user_photo }}&width=150&height=200" alt="">
											</div>
											<div class="customer-info">
												<div class="pull-left">
													<p class="customer-name"><strong>{{ briefcase.user_name|escape|stripslashes }}</strong></p>
													<p class="customer-country"><strong>
														{% for country in country_list %}
															{% if country.id == briefcase.user_country %}{{ country.title|escape|stripslashes }}{% endif %}
														{% endfor %}
														{% for city in briefcase.city_list %}
															{% if city.id == briefcase.user_city %}, {{ city.title|escape|stripslashes }}{% endif %}
														{% endfor %}
													</strong></p>
												</div>
												<div class="pull-right">
													<div class="profile-raiting">
														{{ briefcase.user_rating_small_tpl }}
													</div>
												</div>
												<div class="clearfix"></div>
												<div class="row">
													<div class="col-md-2">

													</div>
													<div class="col-md-3">

													</div>
													<div class="col-md-7 two-column">

													</div>
													<div class="col-md-12">
														<div class="row">
															<div class="col-md-4">
																<a href="{{ HOST_NAME }}{{ link_lang_pref }}/profile-{{ briefcase.id|escape|stripslashes }}/" class="btn btn-block btn-go-on">{{ lang.btn_open }}</a>
															</div>
															<div class="col-md-4">
																<a href="{{ HOST_NAME }}/message/?message_to={{ briefcase.id }}" class="btn btn-block btn-go-on">{{ lang.btn_write }}</a>
															</div>
															<div class="col-md-4">
																<a href="{{ HOST_NAME }}/resume/deny-{{ briefcase.response_id }}/" class="btn btn-block btn-go-on">{{ lang.btn_deny }}</a>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									{% endif %}
								{% endif %}

								{% if SESSION.user_group == 4 %}
									{% if briefcase.response_work %}
										<div class="customer-block">
											<p class="text-right order-name">{{ lang.briefcase_order }} {{ lang.lk_skill_array[briefcase.order_skill]|escape|stripslashes }} #{{ briefcase.response_id }}</p>
											<div class="customer-img">
												<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/users/{{ briefcase.user_photo }}&width=150&height=200" alt="">
											</div>
											<div class="customer-info">
												<div class="pull-left">
													<p class="customer-name"><strong>{{ briefcase.user_name|escape|stripslashes }}</strong></p>
													<p class="customer-country"><strong>
														{% for country in country_list %}
															{% if country.id == briefcase.user_country %}{{ country.title|escape|stripslashes }}{% endif %}
														{% endfor %}
														{% for city in briefcase.city_list %}
															{% if city.id == briefcase.user_city %}, {{ city.title|escape|stripslashes }}{% endif %}
														{% endfor %}
													</strong></p>
												</div>
												<div class="pull-right">
													<div class="profile-raiting">
														{{ briefcase.user_rating_small_tpl }}
													</div>
												</div>
												<div class="clearfix"></div>
												<div class="row">
													<div class="col-md-2">
														<p><strong>
														{% for key, skill in lang.lk_skill_array %}
															{% if key in briefcase.skill_list|keys %}
							 									{{ skill|escape|stripslashes }}
															{% endif %}
														{% endfor %}
													</strong></p>
													</div>
													<div class="col-md-3">
														{% if briefcase.user_lang_default %}
															<p><strong>{{ lang.lk_lang_default }}</strong></p>
															{% for lang in lang_list %}
																{% if lang.id == briefcase.user_lang_default %}
																	<p>{{ lang.title|escape|stripslashes }}</p>
																{% endif %}
															{% endfor %}
														{% endif %}
													</div>
													<div class="col-md-7 two-column">
														{% if briefcase.lang_var %}
															<p><strong>{{ lang.lk_lang_var }}</strong></p>
															{% for lang in briefcase.lang_var %}
																<p>
																	{{ lang.var_lang_from.title|escape|stripslashes }} - {{ lang.var_lang_to.title|escape|stripslashes }}
																</p>
															{% endfor %}
														{% endif %}
													</div>
													<div class="col-md-12">
														{% if briefcase.response_owner_accept %}
															<p>{{ lang.bot_order_owner_appruve }}</p>
														{% elseif briefcase.response_owner_not_accept %}
															<p>{{ lang.bot_order_owner_not_appruve }}</p>
														{% else %}
															<div class="row">
																<div class="col-md-4">
																	<a href="{{ HOST_NAME }}/order-{{ briefcase.response_work }}/" class="btn btn-block btn-go-on">{{ lang.btn_open }}</a>
																</div>
																<div class="col-md-4">
																	<a href="{{ HOST_NAME }}/order/accept-{{ briefcase.response_id }}/" class="btn btn-block btn-go-on">{{ lang.btn_accept }}</a>
																</div>
																<div class="col-md-4">
																	<a href="{{ HOST_NAME }}/order/deny-{{ briefcase.response_id }}/" class="btn btn-block btn-go-on">{{ lang.btn_deny }}</a>
																</div>
															</div>
														{% endif %}
													</div>
												</div>
											</div>
										</div>
									{% endif %}


									{% if briefcase.response_jobs %}
										<div class="customer-block">
											<p class="text-right order-name">{{ lang.briefcase_jobs }} {{ briefcase.jobs_title|escape|stripslashes }} #{{ briefcase.response_jobs }}</p>
											<div class="customer-img">
												<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/users/{{ briefcase.user_photo }}&width=150&height=200" alt="">
											</div>
											<div class="customer-info">
												<div class="pull-left">
													<p class="customer-name"><strong>{{ briefcase.user_name|escape|stripslashes }}</strong></p>
													<p class="customer-country"><strong>
														{% for country in country_list %}
															{% if country.id == briefcase.user_country %}{{ country.title|escape|stripslashes }}{% endif %}
														{% endfor %}
														{% for city in briefcase.city_list %}
															{% if city.id == briefcase.user_city %}, {{ city.title|escape|stripslashes }}{% endif %}
														{% endfor %}
													</strong></p>
												</div>
												<div class="pull-right">
													<div class="profile-raiting">
														{{ briefcase.user_rating_small_tpl }}
													</div>
												</div>
												<div class="clearfix"></div>
												<div class="row">
													<div class="col-md-2">
														<p><strong>
														{% for key, skill in lang.lk_skill_array %}
															{% if key in briefcase.skill_list|keys %}
							 									{{ skill|escape|stripslashes }}
															{% endif %}
														{% endfor %}
													</strong></p>
													</div>
													<div class="col-md-3">
														{% if briefcase.user_lang_default %}
															<p><strong>{{ lang.lk_lang_default }}</strong></p>
															{% for lang in lang_list %}
																{% if lang.id == briefcase.user_lang_default %}
																	<p>{{ lang.title|escape|stripslashes }}</p>
																{% endif %}
															{% endfor %}
														{% endif %}
													</div>
													<div class="col-md-7 two-column">
														{% if briefcase.lang_var %}
															<p><strong>{{ lang.lk_lang_var }}</strong></p>
															{% for lang in briefcase.lang_var %}
																<p>
																	{{ lang.var_lang_from.title|escape|stripslashes }} - {{ lang.var_lang_to.title|escape|stripslashes }}
																</p>
															{% endfor %}
														{% endif %}
													</div>
													<div class="col-md-12">
														<div class="row">
															<div class="col-md-4">
																<a href="{{ HOST_NAME }}{{ link_lang_pref }}/profile-{{ briefcase.id|escape|stripslashes }}/" class="btn btn-block btn-go-on">{{ lang.btn_open }}</a>
															</div>
															<div class="col-md-4">
																<a href="{{ HOST_NAME }}/message/?message_to={{ briefcase.response_perfomens }}" class="btn btn-block btn-go-on">{{ lang.btn_write }}</a>
															</div>
															<div class="col-md-4">
																<a href="{{ HOST_NAME }}/jobs/deny-{{ briefcase.response_id }}/" class="btn btn-block btn-go-on">{{ lang.btn_deny }}</a>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									{% endif %}
								{% endif %}


							{% endfor %}



						</div>

						{% if page_nav %}
							{{ page_nav }}
						{% endif %}

					</div>
				</div>
			</div>
		</div>

	</div>
</main>
