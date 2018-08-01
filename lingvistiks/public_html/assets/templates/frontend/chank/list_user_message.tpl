<a href="{{ HOST_NAME }}/message/?message_to={{ user.message_user.id }}{% if REQUEST.search_message %}&amp;search_message={{ REQUEST.search_message|escape|stripslashes }}{% endif %}" class="contact-item contact-item-{{ user.message_user.id }} {% if user.message_user.id == REQUEST.message_to %}active{% endif %}" data-name="{{ user.message_user.user_name|escape|stripslashes }}">
	<div class="message-img">
		{% if user.message_user.id == 30 %}
			<img src="{{ ABS_PATH }}?thumb=assets/site/template/images/logo_bot.png&width=50&height=50" alt="" class="img-circle">
		{% else %}
			<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/users/{{ user.message_user.user_photo }}&width=50&height=50" alt="" class="img-circle">
		{% endif %}
	</div>
	<div class="message-info">
		<p class="pull-left">
			<strong class="{% if user.unseen > 0 %}have_unseen{% endif %}" data-seen="{{ user.unseen }}">{{ user.message_user.user_name|escape|stripslashes }}</strong>&nbsp;<span>{% if user.unseen > 0 %}+{{ user.unseen }}{% endif %}</span>
		</p>
		<p class="pull-right message-date">{{ user.message_date|escape|stripslashes }}</p>
		<div class="clearfix"></div>
		{% if user.from_user_group != 5 %}
			<p class="short-msg">{{ user.message_desc|striptags|stripslashes|truncate(110) }}</p>
		{% endif %}
	</div>
</a>
