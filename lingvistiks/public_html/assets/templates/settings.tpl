<div class="menubar">
    <ul class="list-inline pull-right dropdown-header-right">
        <li class="dropdown">
            <a aria-expanded="false" href="#" data-toggle="dropdown" class="dropdown-toggle">
                <i class="fa fa-cog"></i>
            </a>
            <ul class="dropdown-menu dropdown-notification pull-right">
                <li class="notification-item">
                    <a class="confirm" href="{{ ABS_PATH_ADMIN_LINK }}?do=settings&sub=cache">{{lang.settings_cache}}</a>
                </li>
            </ul>
        </li>
    </ul>

	<div class="page-title">
		<h1>{{lang.settings_name}}</h1>
	</div>
</div>

<div class="datatables">
	<div class="content-wrapper">
		{% if REQUEST.status == 'success' %}
			<div class="alert alert-info">{{lang.settings_info_error}}</div>
		{% endif %}

        <ul class="nav nav-tabs" role="tablist">
            <li class="active">
                <a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">{{lang.settings_setting}}</a>
            </li>
            <li class="">
                <a href="#api" aria-controls="api" role="tab" data-toggle="tab">{{lang.settings_api}}</a>
            </li>
            <li class="">
                <a href="#api_error" aria-controls="api_error" role="tab" data-toggle="tab">{{lang.settings_api_error}}</a>
            </li>
            <li class="">
                <a href="#cron" aria-controls="cron" role="tab" data-toggle="tab">{{lang.settings_cron}}</a>
            </li>
            <li class="">
                <a href="#security" aria-controls="security" role="tab" data-toggle="tab">{{lang.settings_security}}</a>
            </li>
        </ul>

        <div class="tab-content">

            <div class="tab-pane active" id="settings">
                <div class="content-in-tab">
        	        <form role="form" enctype="multipart/form-data" method="post">
        	            <input type="hidden" name="save" value="1">
        	            <input type="hidden" name="csrf_token" value="{{ csrf_token }}">

        	            <div class="content-in-tab-block">
    	                    {% for key,str in main_settings %}
								<div class="form-group">
									<label for="">{{ str.DESCR }} <small>{{ key }}</small></label>

                                    {% if str.TYPE=="string" %}
                                        <input class="form-control" name="setting[{{ key }}]" type="text" value="{{ str.value|stripslashes }}" size="100" />
                                    {% endif %}
                                    {% if str.TYPE=="text" %}
                                        <textarea class="form-control" name="setting[{{ key }}]" cols="30" rows="5">{{ str.value|stripslashes }}</textarea>
                                    {% endif %}
                                    {% if str.TYPE=="integer" %}
                                        <input class="form-control" name="setting[{{ key }}]" type="text" value="{{ str.value|stripslashes }}" size="100" />
                                    {% endif %}
                                    {% if str.TYPE=="bool" %}
                                        <div class="radio radio-primary">
                                            <input id="checkbox{{ key }}_1" type="radio" name="setting[{{ key }}]" value="1" {% if str.value == 1 %}checked{% endif %} />
                                            <label for="checkbox{{ key }}_1" class="radio">{{lang.btn_yes}}</label>
                                        </div>

                                        <div class="radio radio-primary">
                                            <input id="checkbox{{ key }}_0" type="radio" name="setting[{{ key }}]" value="0" {% if str.value == 0 %}checked{% endif %} />
                                            <label for="checkbox{{ key }}_0" class="radio">{{lang.btn_no}}</label>
                                        </div>
                                    {% endif %}
                                </div>
    	                    {% endfor %}
        	            </div>

                        <div class="actions">
                            <input type="submit" class="btn-flat gray" value="{{lang.save_data}}">
                        </div>
        	        </form>
                </div>
            </div>

            <div class="tab-pane" id="api">
                <div class="content-in-tab">
                    <p>{{ lang.settings_info }}</p>
                    
					<div class="row">
                        <div class="col-md-6">
<pre>
## api
$data = array(
"api_key"=>"{{ app_key_access }}",
"execute"=>"",
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $URL_PUT);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_HEADER, false);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'X-HTTP-Method-Override: PUT',
));

$response = curl_exec($ch);
## api
</pre>
                        </div>
                        <div class="col-md-6">
							<h2>{{lang.settings_lvl4}}</h2>
                            <div class="table-responsive">
                                <table class="datatables table-striped">
                                    <tbody>
                                        <tr>
                                            <td>$URL_PUT</td>
                                            <td>{{lang.settings_lvl4_text1}}</td>
                                        </tr>
                                        <tr>
                                            <td>api_key</td>
                                            <td>{{lang.settings_lvl4_text2}}</td>
                                        </tr>
                                        <tr>
                                            <td>execute</td>
                                            <td>{{lang.settings_lvl4_text3}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="tab-pane" id="api_error">
                <div class="content-in-tab">
                    <div class="content-in-tab-block">
                        <div class="row">
                            <div class="col-md-2">{{ lang.settings_api_error_code }}</div>
                            <div class="col-md-10">{{ lang.settings_api_error_desc }}</div>
                        </div>
                        {% for key, value in api_error %}
                            <div class="row">
                                <div class="col-md-2">{{ key }}</div>
                                <div class="col-md-10">{{ value }}</div>
                            </div>
                        {% endfor %}
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="cron">
                <div class="content-in-tab">
                    <div class="content-in-tab-block">
						<table class="datatables table-striped">
							<tbody>
								<tr>
									<td class="col-md-8">{{ HOST }}cron.php?do=cron&key={{ app_key_access }}</td>
									<td class="col-md-2"></td>
	                            	<td class="col-md-2"><a href="{{ HOST }}cron.php?do=cron&key={{ app_key_access }}" target="_blank">{{ lang.settings_cron_run }}</a></td>
								</tr>

								{% if cron_task %}
									<tr>
										<td colspan="4">{{ lang.settings_cron_single_task }}</td>
									</tr>
									{% for cron in cron_task %}
										<tr>
											<td>{{ HOST }}cron.php?do=cron&single=task&open={{ cron.link }}&key={{ app_key_access }}</td>
											<td>{{ cron.date }}</td>
											<td><a href="{{ HOST }}cron.php?do=cron&single=task&open={{ cron.link }}&key={{ app_key_access }}" target="_blank">{{ lang.settings_cron_run }}</a></td>
										</tr>
									{% endfor %}
								{% endif %}

								{% if cron_module %}
									<tr>
										<td colspan="4">{{ lang.settings_cron_single_module }}</td>
									</tr>
									{% for cron in cron_module %}
										<tr>
											<td>{{ HOST }}cron.php?do=cron&single=module&open={{ cron.link }}&key={{ app_key_access }}</td>
											<td>{{ cron.date }}</td>
											<td><a href="{{ HOST }}cron.php?do=cron&single=module&open={{ cron.link }}&key={{ app_key_access }}" target="_blank">{{ lang.settings_cron_run }}</a></td>
										</tr>
									{% endfor %}
								{% endif %}

							</tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="security">
                <div class="content-in-tab">

                    <div class="row">
                        <div class="col-md-6">
                            <form role="form" enctype="multipart/form-data" method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=settings&sub=security&save=white">
                                <input type="hidden" name="csrf_token" value="{{ csrf_token }}">

                                <div class="form-group">
									<label for="">{{ lang.settings_security_white_title }}</label>
                                    <textarea name="white_ip" cols="30" rows="10" placeholder="{{ lang.settings_security_white_title }}" class="form-control">{{security[1].ip|escape|stripslashes}}</textarea>

                                    <div class="checkbox checkbox-primary">
                                        <input id="white_ip_active" name="white_ip_active" value="1" type="checkbox" {% if security[1].status == 1 %}checked{% endif %} >
                                        <label for="white_ip_active">
                                            {{ lang.settings_security_active }}
                                        </label>
                                    </div>
                                </div>

                                <div class="actions">
                                    <input type="submit" class="btn-flat gray" value="{{ lang.save_data }}">
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            {{ lang.settings_security_white_desc }}
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <form role="form" enctype="multipart/form-data" method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=settings&sub=security&save=black">
                                <input type="hidden" name="csrf_token" value="{{ csrf_token }}">

                                <div class="form-group">
									<label for="">{{ lang.settings_security_black_title }}</label>
                                    <textarea name="black_ip" cols="30" rows="10" placeholder="{{ lang.settings_security_black_title }}" class="form-control">{{security[2].ip|escape|stripslashes}}</textarea>

                                    <div class="checkbox checkbox-primary">
                                        <input id="black_ip_active" name="black_ip_active" value="1" type="checkbox" {% if security[2].status == 1 %}checked{% endif %} >
                                        <label for="black_ip_active">
                                            {{ lang.settings_security_active }}
                                        </label>
                                    </div>
                                </div>

                                <div class="actions">
                                    <input type="submit" class="btn-flat gray" value="{{ lang.save_data }}">
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            {{ lang.settings_security_black_desc }}
                        </div>
                    </div>


                </div>
            </div>
        </div>

	</div>
</div>

<script type="text/javascript">
    $(function () {
        var url_hash = window.location.toString();
        var url_split = url_hash.replace(/&/g,";").split("#");
        if(url_split[1]) {
            $(".nav-tabs li a[href='#"+url_split[1]+"']").click();
        }
    });
</script>
