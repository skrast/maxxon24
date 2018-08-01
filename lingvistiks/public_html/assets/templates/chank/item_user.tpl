<div class="module-wrapper search-item masonry-item col-lg-4 col-md-6 col-sm-6 col-xs-12">
	<section class="module project-module">
		<div class="module-inner">
			<div class="module-heading text-center">
				<a href="{{ ABS_PATH_ADMIN_LINK }}?do=profile&sub=open&user_id={{user.id}}">
					<img src="{{ ABS_PATH_ADMIN_LINK }}?thumb={{ app.app_upload_dir }}/{{ app.app_users_dir }}/{{ user.user_photo }}&width={{ app.AVATAR_WIDTH }}&height={{ app.AVATAR_HEIGHT }}" alt="" class="img-rounded profile-img img-responsive center-block">
				</a>
				<h3 class="module-title">{{ user.user_name|escape|stripslashes }}</h3>
				<div class="meta">{{ user.user_group_name|escape|stripslashes }}</div>
			</div>

			<div class="module-content">
				<div class="module-content-inner">
					<div class="project-intro">
						<div class="row block-margin-n20">
							<div class="col-md-4">
								{{ lang.profile_email }}
							</div>
							<div class="col-md-8">
								{% if user.user_email %}
									<i class="fa fa-envelope contact-type"></i>
									<a href="mailto:{{ user.user_email|escape|stripslashes }}">{{ user.user_email|escape|stripslashes }}</a>
									{% if user.user_notify == 1 %}
										&nbsp<i class="fa fa-check" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{ lang.profile_notify }}"></i>
									{% endif %}
								{% endif %}
							</div>
						</div>

						<div class="row block-margin-n20">
							<div class="col-md-4">
								{{ lang.profile_phone }}
							</div>
							<div class="col-md-8">
								{% if user.user_phone %}
								<i class="fa fa-phone contact-type"></i> <a href="tel:{{ user.user_phone|escape|stripslashes }}" class="phone_mask">{{ user.user_phone|escape|stripslashes }}</a> {% endif %}
							</div>
						</div>

						<div class="row ">
							<div class="col-md-4">
								{{ lang.profile_skype }}
							</div>
							<div class="col-md-8">
								{% if user.user_skype %}
									<i class="fa fa-skype contact-type"></i>
									<a href="skype:{{ user.user_skype|escape|stripslashes }}?chat">{{ user.user_skype|escape|stripslashes }}</a>
								{% endif %}
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>

		{% if SESSION.alles or SESSION.user_id == user.id %}
			<div class="module-footer text-center">
				<ul class="utilities list-inline">
					<li><a href="{{ ABS_PATH_ADMIN_LINK }}?do=profile&sub=profile_work&user_id={{user.id}}" data-toggle="tooltip" data-placement="top" title="{{ lang.save_edit }}"><i class="fa fa-pencil"></i></a></li>
				</ul>
			</div>
		{% endif %}
	</section>
</div>
