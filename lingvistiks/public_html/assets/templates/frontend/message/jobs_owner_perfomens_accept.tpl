<div class="messages-block {% if not respons_info.response_owner_accept and not respons_info.response_owner_not_accept %}suggestions{% endif %}">
	<div class="messages-header">
		<h4>{{ lang.message_jobs_quest_accept }}</h4>
	</div>
	<div class="messages-content">
		<p>{{ order_info.bot_message }}</p>
		<div class="messages-footer">
			{% if respons_info.response_owner_accept %}
				<p>{{ lang.bot_jobs_owner_perfomens_appruve }}</p>
			{% else %}
				<p>{{ lang.bot_jobs_owner_perfomens_not_appruve }}</p>
			{% endif %}
		</div>
	</div>
</div>
