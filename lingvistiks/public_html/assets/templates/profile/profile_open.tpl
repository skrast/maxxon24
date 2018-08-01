<script src="{{ ABS_PATH }}assets/build/js/chart.min.js"></script>

<script type="text/javascript">
$(function () {
    // active
    var data = {
      labels: {{period}},
      series: [{{sum}},]
    };
	app_chart.simple_chart_line(data, '.ct-chart-active');
});
</script>

<div class="menubar">
    <div class="page-title">
        <h1>{{user_info.user_name|escape|stripslashes}}</h1>
    </div>
</div>

<div class="datatables">
    <div class="content-wrapper">

        <ul class="nav nav-tabs" role="tablist">
            <li class="active">
                <a href="#home" aria-controls="home" role="tab" data-toggle="tab">{{ lang.profile_tab1 }}</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="home">
                <div class="content-in-tab">
                    <div class="row">

                    	<div class="project-block">
							{{ user_info.item_user }}

	                        <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12">
	                            <div class="panel panel-inverse">
	                                <div class="panel-heading">
	                                    <h4 class="panel-title">{{ lang.profile_active_chart }}</h4>
	                                </div>
	                                <div class="panel-body">
	                                    {% if sum %}
	                                        <div class="ct-chart ct-chart-active"></div>
	                                    {% else %}
	                                        <p>{{ lang.empty_data }}</p>
	                                    {% endif %}
	                                </div>
	                            </div>
	                        </div>
						</div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
    $(function () {
        var url_hash = window.location.toString();
        var url_split = url_hash.replace(/&/g,";").split("#");
        if(url_split[1]) {
            $(".nav-tabs li a[href='#"+url_split[1]+"']").click();
        }

        $(".fileInfo span").each(function(){
            var match = $(this).attr('data-name').match(/\.([a-zA-Z0-9]{2,4})([#;?]|$)/);
            if(match){
                $(this).addClass("type-" + match[1]);
            }
        });
    });
</script>
