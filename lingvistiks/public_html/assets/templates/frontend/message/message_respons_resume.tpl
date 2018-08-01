<li class="message_simple message_simple_{{ message.message_id }} {% if message.message_from.id != SESSION.user_id %}hidden{% else %}message_from_me{% endif %} suggestions messages-block message-resume" data-message="{{ message.message_id|escape|stripslashes }}">
	<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/users/{{ message.message_from.user_photo|default(message.message_to.user_photo) }}&width=50&height=50" alt="" class="img-circle">

	<div class="message_simple_info">
		<ul class="list-inline">
			{% if message.message_from.id == SESSION.user_id %}
				<li class="is_seen" data-open="{{ message.message_open }}" data-seen="{{ lang.message_is_seen }}" data-unseen="{{ lang.message_is_send }}">{% if message.message_open == 1 %}{{ lang.message_is_seen }}{% else %}{{ lang.message_is_send }}{% endif %}</li>
				<li class="message_date">
					{{ message.message_date }}
				</li>
			{% endif %}
			<li>
				<h4>
					{% if message.message_from %}
						{{ message.message_from.user_name }}
					{% else %}
						{{ message.message_to.user_name }}
					{% endif %}
				</h4>
			</li>
			{% if message.message_from.id != SESSION.user_id %}
				<li class="message_date">
					{{ message.message_date }}
				</li>
				<li class="is_seen" data-open="{{ message.message_open }}" data-seen="{{ lang.message_is_seen }}" data-unseen="{{ lang.message_is_send }}">{% if message.message_open == 1 %}{{ lang.message_is_seen }}{% else %}{{ lang.message_is_send }}{% endif %}</li>
			{% endif %}
		</ul>
	</div>

	<div class="clearfix"></div>

	<div class="messages-header">
		<h4>
			{% if message.message_resume != SESSION.user_id and message.message_from.id == SESSION.user_id %}
				{{ lang.resume_respons_title }}
			{% else %}
				{{ lang.resume_respons_title_to }}
			{% endif %} #{{ message.message_resume }}
		</h4>
	</div>
	<div class="messages-content text-left">
		
		{{ message.message_str_block }} <br><br>

		<div class="messages-footer">
			<strong>
				{% if message.message_resume != SESSION.user_id and message.message_from.id == SESSION.user_id %}
					{{ lang.resume_respons_desc_from }}
				{% else %}
					{{ lang.resume_respons_desc_to }}	
				{% endif %}
			</strong><br>
			{{ message.message_desc }}
		</div>
	</div>

	<div class="clearfix"></div>
	<div class="last_scroll"></div>
</li>
