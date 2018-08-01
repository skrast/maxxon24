<div class="messages-block {% if not respons_info.response_owner_accept and not respons_info.response_owner_not_accept %}suggestions{% endif %}">
	<div class="messages-header">
		<h4>{{ lang.message_resume_quest_accept }}</h4>
	</div>
	<div class="messages-content">
		<p>{{ order_info.bot_message }}</p>
		<div class="messages-footer">
			{% if respons_info.response_owner_accept %}
				<p>{{ lang.bot_resume_owner_appruve }}</p>
				<a href="{{ HOST_NAME }}/message/?message_to={{ respons_info.response_owner }}" class="feedback-with-us">{{ lang.lk_profile_message }}</a>
			{% elseif respons_info.response_owner_not_accept %}
				<p>{{ lang.bot_resume_owner_not_appruve }}</p>
			{% else %}

				<div class="row buttons-block">
					<div class="col-md-6">
						<a href="{{ HOST_NAME }}{{ link_lang_pref }}/resume-{{ SESSION.user_id }}/" class="btn btn-block">{{ lang.jobs_page }}</a>
					</div>
					<div class="col-md-6">
						<a href="{{ HOST_NAME }}/message/?message_to={{ respons_info.response_owner }}" class="btn btn-block">{{ lang.lk_profile_message }}</a>
					</div>
					<div class="col-md-6">
						<a href="{{ HOST_NAME }}/resume/accept-{{ respons_info.response_id }}/?ref=mess" class="btn btn-block">{{ lang.order_accept }}</a>
					</div>
					<div class="col-md-6">
						<a href="{{ HOST_NAME }}/resume/deny-{{ respons_info.response_id }}/?ref=mess" class="btn btn-block">{{ lang.order_deny }}</a>
					</div>
				</div>
				
			{% endif %}
			<div class="clearfix"></div>
		</div>
	</div>
</div>
