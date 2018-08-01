{% for profile_info in user_list %}
	<div class="checkbox checkbox-search">
		<label>
			<input class="checkbox-input" type="checkbox" name="send_message[]" value="{{ profile_info.id }}">
			<span class="checkbox-custom"></span>
			<div class="label">
				<div class="one-block col-md-5 pd-0">
					<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/users/{{ profile_info.user_photo }}&width=50&height=50" alt="" class="avatar-img img-circle">
					<p>{{ profile_info.user_name|escape|stripslashes }}</p>
					<span>{{ profile_info.full_user_id }}</span>
				</div>
				<div class="sec-block col-md-3 text-center">
					{% if profile_info.user_online_status == 1 %}<p class="status-on">{{ lang.lk_online }}</p>{% else %}<p class="status-busy">{{ lang.lk_offline }}</p>{% endif %}
				</div>
				<div class="thr-block col-md-4 pd-0 text-right">
					{{ profile_info.user_rating_tpl }}
					<p>{{ lang.lk_open_total_work }} {{ profile_info.user_work|escape|stripslashes|default(0) }}</p>
				</div>
			</div>
		</label>
	</div>
{% else %}
	{{ lang.empty_data }}
{% endfor %}
