<div class="menubar">
    <div class="page-title">
    	<a href="{{ ABS_PATH_ADMIN_LINK }}?do=sendmail" class="bread"><i class="fa fa-angle-double-left"></i></a>
        <h1>{% if email_info.mail_folder == 3 %}{{ lang.sendmail_add }}{% else %}{{ email_info.mail_title|escape|stripslashes }}{% endif %}</h1>
    </div>
</div>

<div class="datatables">
    <div class="content-wrapper sendmail">
		<div class="row">

			<div class="col-md-3">
                {{ sendmail_filter }}
			</div>

			<div class="col-md-9">

                <ul class="media-list underline">
                    <li class="media media-sm clearfix">

						<div class="pull-left member">
							{% if email_info.mail_author %}
	                            <a href="{{ ABS_PATH_ADMIN_LINK }}?do=profile&sub=open&user_id={{ email_info.mail_author.id }}">
	                                <img class="media-object img-rounded" src="{{ ABS_PATH_ADMIN_LINK }}?thumb=uploads/{{ app.app_users_dir }}/{{ email_info.mail_author.user_photo }}&width={{ app.AVATAR_WIDTH }}&height={{ app.AVATAR_HEIGHT }}" alt="">
	                            </a>
	                        {% else %}
	                            <img class="img-rounded" src="{{ ABS_PATH_ADMIN_LINK }}?thumb=uploads/{{ app.app_users_dir }}/no-avatar.png&width={{ app.AVATAR_WIDTH }}&height={{ app.AVATAR_HEIGHT }}" alt="">
	                        {% endif %}
						</div>

                        <div class="media-body">
                            <span class="text-inverse">
                                {{ lang.sendmail_open_owner }} {% if email_info.mail_author %}
                                    <a href="{{ ABS_PATH_ADMIN_LINK }}?do=profile&sub=open&user_id={{ email_info.mail_author.id }}">{{ email_info.mail_author.user_name|escape|stripslashes }}</a>
                                {% else %}
                                    {{ lang.sendmail_open_owner_default }}
                                {% endif %}
                            </span>
                            <span class="text-muted mghh"><i class="fa fa-clock-o fa-fw"></i> {{ email_info.mail_save|escape|stripslashes }}</span><br>
                            <span class="">
                                {{ lang.sendmail_open_target }}

                                <ul class="list-inline inverse tagit list_tagit">
									{% if email_info.mail_email_user_explode or email_info.mail_email_company_explode or email_info.mail_email_dop %}
										{% for email, mail_email in email_info.mail_email_user_explode %}
											<li>
												{{ mail_email.user_name }}: {{ email }}
												<input value="{{ email }}" name="user_email[{{ mail_email.id }}][]" type="hidden">
											</li>
										{% endfor %}

										{% for email, mail_email in email_info.mail_email_company_explode %}
											<li>
												{{ mail_email.company_title }}: {{ email }}
												<input value="{{ email }}" name="company_email[{{ mail_email.company_id }}][]" type="hidden">
											</li>
										{% endfor %}

                                        {% for mail_item in email_info.mail_email_dop %}
                                            <li>
                                                {{ mail_item }}
                                            </li>
                                        {% endfor %}

                                    {% else %}

	                                    {% if email_info.mail_email %}
	                                        {% for mail_item in email_info.mail_email %}
	                                            <li>
	                                                {{ mail_item }}
	                                            </li>
	                                        {% endfor %}
	                                    {% endif %}
									{% endif %}
                                </ul>

                            </span>
                        </div>
                    </li>
                </ul>

                <div class="mg20">{{ email_info.mail_body|stripslashes }}</div>

                <div class="bg-silver-lighter clearfix">
                    {% if SESSION.user_id == email_info.mail_author.id and email_info.mail_track == 1 and email_info.mail_folder == 2 %}
                        <ul class="list-inline pull-left">
                            <li><a href="{{ ABS_PATH_ADMIN_LINK }}?do=sendmail&sub=mail_stat&mail_id={{ email_info.mail_id }}" class="btn-flat gray"><i class="fa fa-stack-overflow"></i> {{ lang.sendmail_stat }}</a></li>
                        </ul>
                    {% endif %}

                    <ul class="list-inline pull-right">
                        <li><a href="{{ ABS_PATH_ADMIN_LINK }}?do=sendmail&sub=change_folder&folder=4&mail_id={{ email_info.mail_id }}" class="btn-flat gray confirm"><i class="fa fa-trash"></i> {{ lang.sendmail_move_to_trash }}</a></li>
                        <li><a href="{{ ABS_PATH_ADMIN_LINK }}?do=sendmail&sub=change_folder&delete=1&mail_id={{ email_info.mail_id }}" class="btn-flat gray confirm" data-toggle="tooltip" data-placement="top" title="{{ lang.save_delete }}"><i class="fa fa-times"></i> {{ lang.sendmail_delete }}</a></li>
                    </ul>
                </div>
			</div>

		</div>
    </div>
</div>
