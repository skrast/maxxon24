<li class="message_simple message_simple_{{ message.message_id }} message_to_me message_bot" data-message="{{ message.message_id|escape|stripslashes }}">

	<div class="chat-greetings">
		<div class="greeting-block">

			<div class="maxxon-bot">
				<ul class="list-inline">
					<li>
						<img src="{{ ABS_PATH }}assets/site/template/images/logo_bot.png" alt="">
						<span>{{ message.message_from.user_name|escape|stripslashes }}</span>
					</li>
					<li>{{ message.message_date }}</li>
					<li>{{ lang.message_is_seen }}</li>
				</ul>
			</div>

			{{ message.message_html|stripslashes }}

		</div>
	</div>

	<div class="clearfix"></div>
	<div class="last_scroll"></div>

</li>
