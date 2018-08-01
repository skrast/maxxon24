<div class="menubar">
    <div class="page-title">
		<a href="{{ ABS_PATH_ADMIN_LINK }}?do=book&sub=custom_book&book_id={{ book_info.id }}" class="bread"><i class="fa fa-angle-double-left"></i></a>
        <h1>{{ lang.custom_book_open }}: {{ book_info.title|escape|stripslashes }} <sup>{{ num|default('0') }}</sup></h1>

		<div class="clearfix"></div>
    </div>
</div>

<div class="datatables">
	<div class="content-wrapper sendmail">

		<div class="row">
			<div class="col-md-3">
				<form action="{{ ABS_PATH_ADMIN_LINK }}?do=book&sub=custom_book_open&book_id={{ book_info.id }}" method="post" class="block-margin-n20">
					<input type="hidden" name="csrf_token" value="{{ csrf_token }}">

		            <div class="form-group filter-search">
		                <i class="fa fa-search icon-search"></i>
		                <input class="form-control inline-search" name="custom_book_search" placeholder="{{ lang.custom_book_search }}" type="text" value="{{ REQUEST.custom_book_search|escape|stripslashes  }}">
		            </div>
				</form>

				<div class="block-margin-n20">
					<ul class="nav nav-pills nav-stacked nav-sm">
					    {% for key, book in custom_book %}
					        <li {% if REQUEST.book_id == key %}class="active"{% endif %}><a href="{{ ABS_PATH_ADMIN_LINK }}?do=book&sub=custom_book&book_id={{ key }}">{{ book.title }}</a></li>
					    {% endfor %}
					</ul>
				</div>
			</div>

			<div class="col-md-9">
				<div class="toggle-block block-margin-n20">
					<div class="btn-flat gray"><i class="fa fa-plus-circle" aria-hidden="true"></i> {{ lang.custom_book_item_add }}</div>
				</div>

				<form role="form" enctype="multipart/form-data" method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=book&sub=custom_book_open&book_id={{ book_info.id }}" class="hidden block-margin-n20">
					<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
					<input type="hidden" name="save" value="1">

					<div class="form-group">
						<label for="">{{ lang.custom_book_item_name }}</label>
						<input type="text" name="title" class="form-control" value="" placeholder="{{ lang.custom_book_item_name }}">
					</div>

					<div class="actions">
	                    <input type="submit" class="btn-flat gray" value="{{ lang.save_data }}">
	                </div>
	            </form>

				<h4>{{ lang.custom_book_open }}</h4>
				{% if book_item %}
					<div class="content-in-tab-block">
						{% for key, item in book_item %}
							<div class="row">
								<div class="col-md-11">
									<a href="{{ ABS_PATH_ADMIN_LINK }}?do=book&sub=custom_book_item_edit&book_id={{ book_info.id }}&item_id={{ key }}">{{ item.title|escape|stripslashes }}</a>

									{% if item.field_show_item %}
										<div class="clearfix"></div>
										<ul class="list-unstyled">
											{% for field in item.field_show_item %}
												<li>{{ field.field_title|escape|stripslashes }}: {{ field.item_value|escape|stripslashes }}</li>
											{% endfor %}
										</ul>
									{% endif %}
								</div>
								<div class="col-md-1">
									<ul class="list-inline">
										<li><a href="{{ ABS_PATH_ADMIN_LINK }}?do=book&sub=custom_book_item_delete&book_id={{ book_info.id }}&item_id={{ key }}" class="confirm" data-toggle="tooltip" data-placement="top" title="{{ lang.save_delete }}"><i class="fa fa-times"></i></a></li>
									</ul>
								</div>
							</div>
						{% endfor %}
					</div>

					{% if page_nav %}
				        {{page_nav}}
				    {% endif %}
				{% else %}
					<p>{{ lang.empty_data }}</p>
				{% endif %}
			</div>
		</div>
	</div>
</div>
