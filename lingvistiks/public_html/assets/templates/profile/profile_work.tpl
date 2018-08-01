<div class="menubar">
    <div class="page-title">
        {% if SESSION.alles %}<a href="{{ ABS_PATH_ADMIN_LINK }}?do=profile" class="bread"><i class="fa fa-angle-double-left"></i></a>{% endif %}

        <h1>
            {% if user_info %}
                <a href="{{ ABS_PATH_ADMIN_LINK }}?do=profile&sub=open&user_id={{ user_info.id }}">{{ user_info.user_name|escape|stripslashes }}</a>
            {% else %}
                {{ lang.profile_add }}
            {% endif %}
        </h1>
    </div>
</div>

<div class="datatables">
    <div class="content-wrapper container">

        <form role="form" enctype="multipart/form-data" method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=profile&sub=profile_work{% if user_info %}&user_id={{user_info.id}}{% endif %}" class="ajax_form" data-ajax="1">
            <input type="hidden" name="save" value="1">
            <input type="hidden" name="csrf_token" value="{{ csrf_token }}">

			<div class="row">
	            <div class="col-md-6">
					{% if user_info.user_photo %}
                        <img src="{{ ABS_PATH_ADMIN_LINK }}?thumb={{ app.app_upload_dir }}/{{ app.app_users_dir }}/{{user_info.user_photo}}&width={{ app.AVATAR_WIDTH }}&height={{ app.AVATAR_HEIGHT }}" class="img-rounded">
                    {% endif %}
                    <div class="clearfix"></div>

                    <div class="fileinput-button">
                        <div class="btn-flat btn-file">
                            <i class="fa fa-fw fa-upload"></i>&nbsp
                            <span>{{ lang.select_file }}</span>
                            <input name="user_photo" type="file">
                         </div>
                         <span class="file_name"></span>
                    </div>

					<div class="form-group">
						<label for="">{{ lang.profile_email }}</label>
						<input placeholder="{{ lang.profile_email }}" class="form-control" type="text" name="user_email" value="{{user_info.user_email|escape|stripslashes|default(REQUEST.user_email)}}" {% if user_info %}readonly{% endif %} >

						{% if SESSION.alles and user_info.user_group != 1 %}
							<div class="checkbox checkbox-primary">
                                <input id="user_status" name="user_status" value="1" type="checkbox" {% if user_info.user_status == 1 or REQUEST.user_status == 1%}checked{% endif %} >
                                <label for="user_status">
                                    {{ lang.profile_status }}
                                </label>
                            </div>
						{% endif %}
					</div>

					<div class="form-group">
						<label for="">{{ lang.profile_password }}</label>
						<input placeholder="{{ lang.profile_password }}" class="form-control" type="password" name="user_password" value="" autocomplete="off">
					</div>
					<div class="form-group">
						<label for="">{{ lang.profile_password_copy }}</label>
						<input placeholder="{{ lang.profile_password_copy }}" class="form-control" type="password" name="user_password_copy" value="" autocomplete="off">
					</div>

	                {% if user_info.id %}
						<div class="form-group">
							<label for="">{{ lang.profile_secret }}</label>
							<input placeholder="{{ lang.profile_secret }}" class="form-control" type="text" name="user_secret" value="{{user_info.user_secret}}" readonly>
                            <div class="checkbox checkbox-primary">
                                <input id="user_secret_reset" name="user_secret_reset" value="1" type="checkbox">
                                <label for="user_secret_reset">
                                    {{ lang.profile_secret_reset }}
                                </label>
                            </div>
						</div>
	                {% endif %}

	                {% if SESSION.alles and user_info.id != 1 %}
						<div class="form-group">
							<label for="">{{ lang.profile_group }}</label>
							<select name="user_group" class="selectpicker">
								<option value=""></option>
								{% for group in groups %}
									<option value="{{group.user_group}}" {% if group.user_group == user_info.user_group or REQUEST.user_group == group.user_group %}selected{% endif %}>{{group.user_group_name}}</option>
								{% endfor %}
							</select>
						</div>

						<div class="form-group">
							<label for="">{{ lang.profile_block }}</label>
							<textarea class="form-control" name="user_block_desc" rows="3" placeholder="{{ lang.profile_block }}">{{user_info.user_block_desc|escape|stripslashes|default(REQUEST.user_block_desc)}}</textarea>
						</div>
					{% endif %}

	                {% if SESSION.alles and user_info.user_group != 1 and project_list %}
						<div class="form-group">
							<label for="">{{ lang.profile_project }}</label>
                            {% for proj in project_list %}
								<div class="checkbox checkbox-primary">
									<input id="user_project{{ proj.project_id }}" name="user_project[]" value="{{ proj.project_id }}" type="checkbox" {% if proj.project_id in user_info.user_project %}checked{% endif %}>
									<label for="user_project{{ proj.project_id }}">
										{{ proj.project_title|escape|stripslashes }}
									</label>
								</div>
                            {% endfor %}
						</div>
	                {% endif %}

					{% if SESSION.alles and user_info.user_group != 1 and user_permissions %}
						<div class="form-group">
							<label for="">{{ lang.profile_permission }}</label>

							<div class="block-margin-n20 pse_link toggle-block">
								{{ lang.profile_permission_show }}
							</div>
							<div class="hidden">
								{% for key, value in user_permissions %}
									<div class="checkbox checkbox-primary">
										<input id="user_permissions{{ key }}" name="user_permissions[]" value="{{ key }}" type="checkbox" {% if key in user_info.user_permissions %}checked{% endif %}>
										<label for="user_permissions{{ key }}">
											{{ value|escape|stripslashes }}
										</label>
									</div>
								{% endfor %}
							</div>

						</div>
					{% endif %}
	            </div>

	            <div class="col-md-6">
					<div class="form-group">
						<label for="">{{ lang.profile_firstname }}</label>
						<input placeholder="{{ lang.profile_firstname }}" class="form-control" type="text" name="user_firstname" value="{{user_info.user_firstname|escape|stripslashes|default(REQUEST.user_firstname)}}">
					</div>

					<div class="form-group">
						<label for="">{{ lang.profile_patronymic }}</label>
						<input placeholder="{{ lang.profile_patronymic }}" class="form-control" type="text" name="user_patronymic" value="{{user_info.user_patronymic|escape|stripslashes|default(REQUEST.user_patronymic)}}" />
					</div>

					<div class="form-group">
						<label for="">{{ lang.profile_lastname }}</label>
						<input placeholder="{{ lang.profile_lastname }}" class="form-control" type="text" name="user_lastname" value="{{user_info.user_lastname|escape|stripslashes|default(REQUEST.user_lastname)}}" >
					</div>

					<div class="form-group">
						<label for="">{{ lang.profile_lang }}</label>
						<select name="user_lang" class="selectpicker">
							{% for key, lang in lang_array %}
								<option value="{{ lang }}" {% if user_info.user_lang == lang or REQUEST.user_lang==lang %}selected{% endif %}>{{ lang }}</option>
							{% endfor %}
						</select>
					</div>

					<div class="form-group">
						<label for="">{{ lang.profile_phone }}</label>
						<input  placeholder="{{ lang.profile_phone }}" class="form-control" type="text" name="user_phone" value="{{user_info.user_phone|escape|stripslashes|default(REQUEST.user_phone)}}" />
					</div>

					<div class="form-group">
						<label for="">{{ lang.profile_skype }}</label>
						<input  placeholder="{{ lang.profile_skype }}" class="form-control" type="text" name="user_skype" value="{{user_info.user_skype|escape|stripslashes|default(REQUEST.user_skype)}}" />
					</div>

	                {{ fields_print_html }}

	                <div class="form-group">
						<ul class="list-unstyled">
							<li>
								<div class="checkbox checkbox-primary">
			                        <input id="user_use_notify" name="user_use_notify" value="1" type="checkbox" {% if user_info.user_use_notify == 1 or REQUEST.user_use_notify == 1 %}checked{% endif %} >
			                        <label for="user_use_notify">
			                            {{ lang.profile_notify }}
			                        </label>
			                    </div>
							</li>
							<li>
								<div class="checkbox checkbox-primary">
			                        <input id="user_get_notify_email" name="user_get_notify_email" value="1" type="checkbox" {% if user_info.user_get_notify_email == 1 or REQUEST.user_get_notify_email == 1%}checked{% endif %} >
			                        <label for="user_get_notify_email">
			                            {{ lang.profile_notify_email }}
			                        </label>
			                    </div>
							</li>
							<li>
								<div class="checkbox checkbox-primary">
			                        <input id="user_get_notify_phone" name="user_get_notify_phone" value="1" type="checkbox" {% if user_info.user_get_notify_phone == 1 or REQUEST.user_get_notify_phone == 1%}checked{% endif %} >
			                        <label for="user_get_notify_phone">
			                            {{ lang.profile_notify_phone }}
			                        </label>
			                    </div>
							</li>
							<li>
								<div class="checkbox checkbox-primary">
			                        <input id="user_get_login" name="user_get_login" value="1" type="checkbox" {% if user_info.user_get_login == 1 or REQUEST.user_get_login == 1%}checked{% endif %} >
			                        <label for="user_get_login">
			                            {{ lang.profile_notify_login }}
			                        </label>
			                    </div>
							</li>
						</ul>
					</div>
					

					{% if user_info.user_group in [3, 4] and user_info.id %}
						<hr>

						<div class="form-group">
							<label for="">{{ lang.profile_billing_date }}</label>
							<input  placeholder="{{ lang.user_billing_date }}" class="form-control datepicker" type="text" name="billing_date" value="{{ user_info.user_billing_date|escape|stripslashes|default(REQUEST.user_billing_date )}}" />
						</div>
						
						<hr>

						<div class="form-group">
							<label for="">{{ lang.profile_billing_add_pay }}</label>
							<input  placeholder="{{ lang.profile_billing_add_pay }}" class="form-control" type="text" name="billing_pay" value="" />
						</div>

						<div class="form-group">
							<label for="">{{ lang.profile_billing_add_pay_type }}</label>
							<select name="pay_type" class="selectpicker">
								<option value=""></option>
								{% for key, pay in lang.billing_pay_type %}
									<option value="{{ key }}">{{ pay }}</option>
								{% endfor %}
							</select>
						</div>

						<div class="form-group">
							<label for="">{{ lang.profile_billing_add_tariff }}</label>
							<select name="pay_tariff" class="selectpicker">
								<option value=""></option>
								{% for key, tariff in app.app_tariff[user_info.user_group][user_info.user_type_form] %}
									{% for month, price in tariff.price %}
										<option value="{{ key }}_{{ month }}">{{ tariff.name }}: {{ price.title }}</option>
									{% endfor %}
								{% endfor %}
							</select>
						</div>
					{% endif %}


	            </div>
	        </div>

            <div class="actions">
                <input type="submit" class="btn-flat gray" value="{{ lang.save_data }}">
            </div>
        </form>
    </div>
</div>
