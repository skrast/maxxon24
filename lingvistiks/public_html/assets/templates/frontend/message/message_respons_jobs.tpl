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
			{% if jobs_info.jobs_owner.id != SESSION.user_id and message.message_from.id == SESSION.user_id %}
				{{ lang.jobs_respons_title }}
			{% else %}
				{{ lang.jobs_respons_title_to }}
			{% endif %} #{{ message.message_jobs }}
		</h4>
	</div>

	<div class="messages-content text-left">
		
		{{ message.message_str_block }} <br><br>

		{% if jobs_info.jobs_owner.id != SESSION.user_id and message.message_from.id == SESSION.user_id %}
		
		{% else %}
				<a href="{{ HOST }}jobs-{{ jobs_info.jobs_id|escape|stripslashes }}/" class="upper">{{ jobs_info.jobs_title.title|escape|stripslashes }}</a>
				<div class="jobs_coast_color">
					{% if jobs_info.jobs_coast_start %} {{ lang.lk_budget_from }} {{ jobs_info.jobs_coast_start|escape|stripslashes }} {% endif %}
					{% if jobs_info.jobs_coast_end %} {{ lang.lk_budget_to }} {{ jobs_info.jobs_coast_end|escape|stripslashes }} {% endif %}

					{{ jobs_info.jobs_coast_currency.title|escape|stripslashes }}/{% if jobs_info.jobs_coast_period == 1 %}{{ lang.jobs_coast_month }}{% else %}{{ lang.jobs_coast_year }}{% endif %}
					<span class="jobs_gross">Gross</span>
				</div>
			<br>
		{% endif %}

		<div class="messages-footer">
			<strong>
					{% if jobs_info.jobs_owner.id != SESSION.user_id and message.message_from.id == SESSION.user_id %}
					{{ lang.jobs_respons_desc_from }}
				{% else %}
					{{ lang.jobs_respons_desc_to }}	
				{% endif %}
			</strong><br>
			{{ message.message_desc }}
		</div>
	</div>

	<div class="clearfix"></div>
	<div class="last_scroll"></div>
</li>
