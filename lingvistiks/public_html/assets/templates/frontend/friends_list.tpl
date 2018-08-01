<main class="sidebar-left">
	<div class="container">

		<ol class="breadcrumb">
			<li><a href="{{ HOST_NAME }}">{{ lang.page_breadcrumb }}</a></li>
			<li class="active text-uppercase">{{ lang.friends_list }}</li>
		</ol>

		<h1>{{ lang.friends_list }}</h1>
		<div class="row">

			{{ perfomens_col }}

			<div class="col-md-{% if perfomens_col %}9{% else %}12{% endif %} col-sm-{% if perfomens_col %}9{% else %}12{% endif %}">
				<div class="content-right">
					<div class="profile-block">
						<div class="order-nav">
							<ul class="list-inline">
								<li>
									<a href="{{ HOST_NAME }}/customers-partners/" {% if not REQUEST.user_skill and not REQUEST.user_group %}class="active"{% endif %}>{{ lang.page_all_show }}</a>
								</li>

								<li>
									<a href="{{ HOST_NAME }}/customers-partners/?user_group=3" {% if 3 == REQUEST.user_group %}class="active"{% endif %}>{{ lang.lk_perfomers }}</a>
								</li>

								<li>
									<a href="{{ HOST_NAME }}/customers-partners/?user_group=4" {% if 4 == REQUEST.user_group %}class="active"{% endif %}>{{ lang.lk_owners }}</a>
								</li>
							</ul>
						</div>
					</div>
				
					<div class="partners-in">
						<div class="">

							{% for friends in friends_list %}
								<div class="row">
									<div class="partners-block">
										<div class="col-md-3">
											<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/users/{{ friends.user_photo }}&width=250&height=300" alt="" class="img-responsive">
										</div>

										<div class="col-md-9 partner-info">

											<div class="pull-right">
												{{ profile_info.user_rating_tpl }}
											</div>

											<div class="pull-left">
												<ul class="list-inline left-border">
													<li>{{ friends.user_login|escape|stripslashes }}</li>
													<li>{{ friends.full_user_id|escape|stripslashes }}</li>
													<li>
														{% if friends.user_online_status == 1 %}
															<span class="status-p status-on">{{ lang.lk_online }}</span>
														{% else %}
															<span class="status-p status-busy">{{ lang.lk_offline }}</span>
														{% endif %}
													</li>
												</ul>

												<div class="user_country"><strong>
													{% for country in country_list %}{% if country.id == friends.user_country %}{{ country.title|escape|stripslashes }}{% endif %}{% endfor %}{% for city in friends.city_list %}{% if city.id == friends.user_city %}, {{ city.title|escape|stripslashes }}{% endif %}{% endfor %}
												</strong></div>
											</div>

											

											<div class="row">

												<div class="col-md-9">
													<div class="right-orange-info">
														<div class="owner">
															{% for skill in friends.skill_list %}
																{{ lang.lk_skill_lang_array[friends.user_type_form][skill.skill_type]|escape|stripslashes }}<br>
															{% endfor %}
														</div>
													</div>

													<div class="right-blue-info"><div class="owner">{{ friends.user_company.company_title|escape|stripslashes }}</div></div>
												</div>
												

												<div class="col-md-3">
													<a href="{{ HOST_NAME }}/message/?message_to={{ friends.id|escape|stripslashes }}" class="btn btn-block btn-go-on">{{ lang.btn_write }}</a>
													<a href="{{ HOST_NAME }}{{ link_lang_pref }}/profile-{{ friends.id|escape|stripslashes }}/" class="btn btn-block btn-search">{{ lang.btn_profile }}</a>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
								</div>
							{% else %}
								<div class="album_empty serv_var_empty">{{ lang.friends_list_empty }}</div>
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
