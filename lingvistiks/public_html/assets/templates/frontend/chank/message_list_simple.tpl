<li class="message_simple message_simple_{{ message.message_id }} {% if message.message_from.id != SESSION.user_id %}message_to_me{% else %}message_from_me{% endif %}" data-message="{{ message.message_id|escape|stripslashes }}">
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

	{% if message.message_parent %}

		<div class="is_answer">

				<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/users/{{ message.message_parent.message_from.user_photo|default(message.message_parent.message_to.user_photo) }}&width=30&height=30" alt="" class="img-circle">

				<div class="message_simple_info">
					<ul class="list-inline">
						<li>
							<h4>
								{% if message.message_parent.message_from %}
									{{ message.message_parent.message_from.user_name }}
								{% else %}
									{{ message.message_parent.message_to.user_name }}
								{% endif %}
							</h4>
						</li>
						{% if message.message_parent.message_from.id == SESSION.user_id %}
							<li class="is_seen" data-open="{{ message.message_parent.message_open }}" data-seen="{{ lang.message_is_seen }}" data-unseen="{{ lang.message_is_send }}">{% if message.message_parent.message_open == 1 %}{{ lang.message_is_seen }}{% else %}{{ lang.message_is_send }}{% endif %}</li>
							<li class="message_date">
								{{ message.message_parent.message_date }}
							</li>
						{% endif %}
					</ul>
				</div>

				<div class="clearfix"></div>

				<div class="message_simple_text">
					{% if message.message_parent.message_file %}
						<a href="{{ ABS_PATH }}{{ app.app_upload_dir }}/message/{{ message.message_parent.message_to.id|escape|stripslashes }}/{{ message.message_parent.message_file|escape|stripslashes }}" data-lightbox="image-1"><img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/message/{{ message.message_parent.message_to.id|escape|stripslashes }}/{{ message.message_parent.message_file|escape|stripslashes }}&height=80" alt=""></a>
					{% else %}
						{% if message.message_parent.message_from.user_group in [1,2] or message.message_document %}
							<p>{{ message.message_parent.message_desc|ntobr|stripslashes|cito }}</p>
						{% else %}
							<p>{{ message.message_parent.message_desc|escape|ntobr|stripslashes|cito }}</p>
						{% endif %}
					{% endif %}
				</div>
				<div class="clearfix"></div>

		</div>
		<div class="clearfix"></div>
	{% endif %}

	<div class="message_simple_text {% if message.message_from.id != SESSION.user_id %}pull-left text-left{% else %}pull-right text-left{% endif %}">
		{% if message.message_from.user_group in [1,2] or message.message_document %}
			<p>{{ message.message_desc|ntobr|stripslashes|cito }}</p>
		{% else %}
			<p>{{ message.message_desc|escape|ntobr|stripslashes|cito }}</p>
		{% endif %}

		{% if message.message_attach %}
			<ul class="list-inline attach_list">
				{% for message_file in message.message_attach %}
					<li>
						<a href="{{ ABS_PATH }}{{ app.app_upload_dir }}/message/{{ message.message_to.id|escape|stripslashes }}/{{ message_file|escape|stripslashes }}" data-lightbox="image-1"><img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/message/{{ message.message_to.id|escape|stripslashes }}/{{ message_file|escape|stripslashes }}&height=80" alt=""></a>
					</li>
				{% endfor %}
			</ul>
		{% endif %}
	</div>

	<div class="clearfix"></div>
	<div class="last_scroll"></div>
</li>
