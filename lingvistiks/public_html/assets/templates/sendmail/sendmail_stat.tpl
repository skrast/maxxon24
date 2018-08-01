<div class="menubar">
    <div class="page-title">
    	<a href="{{ ABS_PATH_ADMIN_LINK }}?do=sendmail&sub=open&mail_id={{ email_info.mail_id }}" class="bread"><i class="fa fa-angle-double-left"></i></a>
        <h1>{{ lang.sendmail_open_stat }} №{{ email_info.mail_id }}</h1>
    </div>
</div>

<div class="datatables">
    <div class="content-wrapper sendmail">

		<div class="row">
			<div class="col-md-3">
                {{ sendmail_filter }}
			</div>

			<div class="col-md-9">
				<h4 class="underline">{{ email_info.mail_title|escape|stripslashes }}</h4>

				{% if tracker %}

					<h2>{{ lang.sendmail_open_stat_day }}</h2>
					<script src="{{ ABS_PATH }}assets/build/js/chart.min.js"></script>

					<script type="text/javascript">
					$(function () {
					    var data = {
					      labels: [{{period}}],
					      series: [
					        [{{count}}]
					      ]
					    };
						app_chart.simple_chart_bar(data);
					});
					</script>
					<div class="ct-chart"></div>

					<h2>{{ lang.sendmail_open_stat_conv }}</h2>
					<div class="table-responsive">
						<table class="datatables table-striped">
							<thead>
		                        <tr>
		                            <th class="col-md-4">
		                                {{ lang.sendmail_open_stat_conv_send }}
		                            </th>
		                            <th class="col-md-4">
		                                {{ lang.sendmail_open_stat_conv_open }}
		                            </th>
		                             <th class="col-md-4">
		                                {{ lang.sendmail_open_stat_conv_per }}
		                            </th>
		                        </tr>
		                    </thead>

		                    <tbody>
								<tr>
									<td>{{ email_info.count_email|default('0') }}</td>
									<td>{{ email_info.count_email_open|default('0') }}</td>
									<td>{{ email_info.count_email_conv }}%</td>
								</tr>
							</tbody>
		                </table>
		            </div>


					<h2>{{ lang.sendmail_open_stat_open }}</h2>
					<div class="table-responsive">
						<table class="datatables table-striped">
							<thead>
		                        <tr>
		                            <th class="col-md-4">
		                                {{ lang.sendmail_open_stat_open_date }}
		                            </th>
		                            <th class="col-md-4">
		                                {{ lang.sendmail_open_stat_open_email }}
		                            </th>
		                             <th class="col-md-4">
		                                {{ lang.sendmail_open_stat_open_ip }}
		                            </th>
		                        </tr>
		                    </thead>

		                    <tbody>
								{% for track in tracker %}
									<tr>
										<td>{{ track.track_view_date }}</td>
										<td>{{ track.track_email|escape|stripslashes|default(lang.sendmail_open_stat_open_not_found) }}</td>
										<td>{{ track.track_view_ip|escape|stripslashes|default(lang.sendmail_open_stat_open_not_found) }}</td>
									</tr>
								{% endfor %}
							</tbody>
		                </table>
		            </div>

	            	{% if page_nav %}
	                    {{page_nav}}
	                {% endif %}
				{% else %}
					{{ lang.empty_data }}
				{% endif %}
			</div>

		</div>
    </div>
</div>
