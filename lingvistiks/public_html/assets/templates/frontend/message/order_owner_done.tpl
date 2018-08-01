<li>
	<form action="{{ HOST_NAME }}/message/?message_to={{ send_message_to }}" method="post" enctype="multipart/form-data" class="ajax_form message_otzive">
		<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
		<input type="hidden" name="otziv" value="1">
		<input type="hidden" name="order_id" value="{{ order_info.order_id }}">

		<div class="message-admin">
			<div class="message-title">{{ lang.message_order_otzive }}</div>
			<div class="message-block">
				<div class="row">
					<div class="col-md-6 col-sm-6">
						<p>
							{% if SESSION.user_group == 4 %}
								{{ lang.message_order_for_perfomens }}
								<input type="hidden" name="otziv_to_id" value="{{ order_info.order_perfomens }}">
							{% elseif SESSION.user_group == 3 %}
								{{ lang.message_order_for_owner }}
								<input type="hidden" name="otziv_to_id" value="{{ order_info.order_owner }}">
							{% endif %}
						</p>
					</div>
					<div class="col-md-6 col-sm-6">

						<div class="star-rating pull-right">
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

					{% if SESSION.user_group == 4 %}
						<div class="col-md-6 col-sm-6">
							<button type="button" class="btn btn-block btn-adv otziv_type" data-value="1">{{ lang.btn_accept }}</button>
						</div>
						<div class="col-md-6 col-sm-6">
							<button type="button" class="btn btn-block btn-adv otziv_type" data-value="0">{{ lang.btn_deny }}</button>
						</div>
						<input type="hidden" name="otziv_type" value="1">
					{% endif %}
				</div>
			</div>
		</div>
		<div class="form-group pull-right">
			<button type="submit" class="btn btn-block btn-search">{{ lang.btn_send }}</button>
		</div>
	</form>
</li>
