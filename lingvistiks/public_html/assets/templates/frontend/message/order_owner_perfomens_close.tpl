<div class="messages-block {% if not respons_info.response_owner_accept and not respons_info.response_owner_not_accept %}suggestions{% endif %}">
	<div class="messages-header">
		<h4>{{ lang.message_order_close }}</h4>
	</div>
	<div class="messages-content">
		<p>{{ order_info.bot_message }}</p>

		{% if order_info.order_owner_star and order_info.order_owner == SESSION.user_id %}

			{{ lang.message_order_close_star }}{{ order_info.order_perfomens_star }}
			{% if order_info.otziv %}<p>{{ order_info.otziv.otziv_text|escape|ntobr|stripslashes }}</p>{% endif %}

		{% elseif order_info.order_perfomens_star and order_info.order_perfomens == SESSION.user_id %}

			{{ lang.message_order_close_star }} {{ order_info.order_owner_star }}
			{% if order_info.otziv %}<p>{{ order_info.otziv.otziv_text|escape|ntobr|stripslashes }}</p>{% endif %}

		{% else %}
			<form action="{{ HOST_NAME }}/message/?message_to={{ order_info.bot_id }}" method="post" enctype="multipart/form-data" class="ajax_form message_otzive">
				<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
				<input type="hidden" name="otziv" value="1">
				<input type="hidden" name="order_id" value="{{ order_info.order_id }}">
				<input type="hidden" name="otziv_type" value="1">

				<div class="message-admin">
					<div class="message-block">
						<div class="row">
							<div class="col-md-12">
								<div class="star-rating">
									<span>
										{% if SESSION.user_group == 4 %}
											{{ lang.message_order_for_perfomens_vote }}
										{% elseif SESSION.user_group == 3 %}
											{{ lang.message_order_for_owner_vote }}
										{% endif %}
									</span>

									<ul class="unit-rating" style="width: {{ user_profile.full_width }}px;">
										<li class="current-rating bar" style="width: 0px;"></li>
										<li><a href="#" class="r1-unit rater" rel="nofollow" title="1">1</a></li>
										<li><a href="#" class="r2-unit rater" rel="nofollow" title="2">2</a></li>
										<li><a href="#" class="r3-unit rater" rel="nofollow" title="3">3</a></li>
										<li><a href="#" class="r4-unit rater" rel="nofollow" title="4">4</a></li>
										<li><a href="#" class="r5-unit rater" rel="nofollow" title="5">5</a></li>
									</ul>
								</div>

								<input type="hidden" name="otziv_star" value="5">
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<textarea class="form-control" name="otziv_text" id="" rows="3" placeholder="{{ lang.message_order_otzive_desc }}"></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="pull-right">
					<button type="submit" class="btn btn-block btn-search">{{ lang.btn_send }}</button>
				</div>
			</form>
			<div class="clearfix"></div>
		{% endif %}
	</div>
</div>
