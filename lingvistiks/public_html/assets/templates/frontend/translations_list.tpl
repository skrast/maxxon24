<main class="sidebar-left">
	<div class="container">

		<ol class="breadcrumb">
			<li><a href="{{ HOST_NAME }}">{{ lang.page_breadcrumb }}</a></li>
			<li class="active text-uppercase">{{ lang.service_translations }}</li>
		</ol>

		<h1>{{ lang.service_translations }}</h1>
		<div class="row">

			{{ perfomens_col }}

			<div class="col-md-{% if perfomens_col %}9{% else %}12{% endif %} col-sm-{% if perfomens_col %}9{% else %}12{% endif %}">
				<div class="content-right">
					<div class="profile-block">
						<div class="order-nav">
							<ul class="list-inline">
								{% for key, value in lang.service_translations_array %}
								<li>
									<a href="{{ HOST_NAME }}/translations/?status={{ key }}" {% if key == REQUEST.status %}class="active"{% endif %}>{{ value }}</a>
								</li>
								{% endfor %}
							</ul>
						</div>
					</div>
				
					<div class="partners-in">
						<div class="">

							{% for document in document_list %}
								<div class="row">
									<div class="partners-block">
										<div class="col-md-3">
											<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/users/{{ document.document_owner.user_photo }}&width=250&height=300" alt="" class="img-responsive">
										</div>

										<div class="col-md-9 partner-info">

											<div class="pull-right">
												{{ document.document_owner.user_rating_tpl }}
											</div>

											<div class="pull-left">
												<ul class="list-inline left-border">
													<li>{{ document.document_owner.user_login|escape|stripslashes }}</li>
													<li>{{ document.document_owner.full_user_id|escape|stripslashes }}</li>
													<li>
														{% if document.document_owner.user_online_status == 1 %}
															<span class="status-p status-on">{{ lang.lk_online }}</span>
														{% else %}
															<span class="status-p status-busy">{{ lang.lk_offline }}</span>
														{% endif %}
													</li>
												</ul>
											</div>

											<div class="row">
												<div class="col-md-9">
													<ul class="list-unstyled">
														<li><strong>{{ lang.search_writer_lang_default }}</strong> {{ document.document_from_lang.title|escape|stripslashes }}</li>
														<li><strong>{{ lang.search_writer_lang_dest }}</strong> {{ document.document_to_lang.title|escape|stripslashes }}</li>
														<li><strong>{{ lang.search_writer_theme }}</strong> {{ document.document_theme.title|escape|stripslashes }}</li>
														<li><strong>{{ lang.search_writer_date_get }}</strong> {{ document.document_date|escape|stripslashes }}</li>
														<li><strong>{{ lang.search_writer_document_verif }}</strong> {{ lang.search_writer_document_verif_array[document.document_verif] }}</li>
													</ul>
												</div>
												
												<div class="col-md-3">
													<ul class="list-unstyled">
														<li><a href="{{ HOST_NAME }}/translations/document-{{ document.document_id|escape|stripslashes }}/" class="btn btn-block btn-go-on">{{ lang.service_translations_open }}</a></li>
														<li><a href="{{ HOST_NAME }}/message/?message_to={{ document.document_owner.id|escape|stripslashes }}" class="btn btn-block btn-go-on">{{ lang.btn_write }}</a></li>
														<li><a href="{{ HOST_NAME }}{{ link_lang_pref }}/profile-{{ document.document_owner.id|escape|stripslashes }}/" class="btn btn-block btn-go-on">{{ lang.btn_profile }}</a></li>
													</ul>
													
													
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
								</div>
							{% else %}
								<div class="album_empty serv_var_empty">{{ lang.service_translations_empty }}</div>
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
