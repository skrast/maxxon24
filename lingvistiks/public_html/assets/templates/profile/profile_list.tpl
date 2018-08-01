<div class="menubar">
    <div class="page-title">
        <h1>{{ lang.profile_name }} <sup>{{ num|default('0') }}</sup></h1>

        {% if SESSION.alles %}
            <ul class="page-title-menu list-inline">
                <li>
                    <a class="btn-flat gray" href="{{ ABS_PATH_ADMIN_LINK }}?do=profile&sub=profile_work"><i class="fa fa-plus-circle" aria-hidden="true"></i> {{ lang.profile_add }}</a>
                </li>
            </ul>
        {% endif %}
    </div>
</div>


<div class="datatables">
    <div class="content-wrapper">
        <div class="filters">
            <ul class="list-inline links">
                <li>
                    <div class="show-filter label label-success"><i class="fa fa-filter"></i></div>
                </li>
            </ul>
        </div>
    </div>

	<div class="content-wrapper search-section">
	    <form method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=profile" class="row">
			<h2>{{ lang.search_filter }}</h2>

	        <input type="hidden" name="csrf_token" value="{{ csrf_token }}">
	        <input type="hidden" name="filter" value="1">

			<div class="col-lg-4 col-md-6 col-sm-6">
				<div class="form-group">
					<label for="">{{ lang.search_user_group }}</label>
					<div class="clearfix"></div>
					<select class="selectpicker" name="user_group">
						<option value=""></option>
						{% for group in groups %}
							<option value="{{group.user_group}}" {% if REQUEST.user_group == group.user_group %}selected{% endif %} >{{group.user_group_name}}</option>
						{% endfor %}
					</select>
				</div>

				<div class="form-group">
					<label for="">{{ lang.search_user_status }}</label>
					<div class="clearfix"></div>
					<select class="selectpicker" name="user_status">
						<option value=""></option>
						<option value="1" {% if REQUEST.user_status == 1 %}selected{% endif %} >{{lang.search_user_status_active}}</option>
						<option value="2" {% if REQUEST.user_status == 2 %}selected{% endif %} >{{lang.search_user_status_achive}}</option>
					</select>
				</div>
			</div>

			<div class="col-lg-4 col-md-6 col-sm-6">

			</div>

			<div class="col-lg-4 col-md-12 col-sm-12">

			</div>

			<div class="clearfix"></div>
	        <div class="actions">
				<input type="submit" class="btn-flat gray" value="{{ lang.search_btn }}">

				{% if REQUEST.filter %}
		            <div class="filter_clear">
		                &nbsp;<a href="{{ ABS_PATH_ADMIN_LINK }}?do=profile"><i class="fa fa-times"></i> {{ lang.search_clear_btn }}</a>
		            </div>
		        {% endif %}
	        </div>
	    </form>
	</div>

    <div class="content-wrapper">
        {% if users %}
            <div id="masonry" class="row project-block">
	            {% for user in users %}
	                {{ user.item_user }}
	            {% endfor %}
			</div>

            {% if page_nav %}
                {{page_nav}}
            {% endif %}

			<script src='{{ ABS_PATH }}assets/js/masonry.min.js'></script>
			<script>
				$(document).ready(function() {
					$('#masonry').masonry({
					  itemSelector: '.masonry-item',
					});
				});
			</script>
        {% else %}
            <p>{{ lang.empty_data }}</p>
        {% endif %}
    </div>
</div>
