<div class="menubar">
    <div class="page-title">
		<a href="{{ ABS_PATH_ADMIN_LINK }}?do=book&sub=custom_book_open&book_id={{ book_info.id }}" class="bread"><i class="fa fa-angle-double-left"></i></a>
        <h1>{{ lang.custom_book_open }}: {{ book_info.title|escape|stripslashes }}</h1>

		<div class="clearfix"></div>
    </div>
</div>

<div class="datatables">
	<div class="content-wrapper sendmail">
		<div class="row">
			<div class="col-md-3">
				<div class="block-margin-n20">
					<ul class="nav nav-pills nav-stacked nav-sm">
					    {% for key, book in custom_book %}
					        <li {% if REQUEST.book_id == key %}class="active"{% endif %}><a href="{{ ABS_PATH_ADMIN_LINK }}?do=book&sub=custom_book&book_id={{ key }}">{{ book.title }}</a></li>
					    {% endfor %}
					</ul>
				</div>
			</div>

			<div class="col-md-9">

				<form role="form" enctype="multipart/form-data" method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=book&sub=custom_book_item_edit&book_id={{ book_info.id }}&item_id={{ item_info.id }}" class="ajax_form" data-ajax="1">
					<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
					<input type="hidden" name="save" value="1">

					<div class="form-group">
						<label for="">{{ lang.custom_book_item_name }}</label>
						<input type="text" name="title" class="form-control" value="{{ item_info.title|escape|stripslashes }}" placeholder="{{ lang.custom_book_item_name }}">
					</div>

					<div class="content-in-tab-block">
						{{ fields_print_html }}
					</div>

					<div class="actions">
	                    <input type="submit" class="btn-flat gray" value="{{ lang.save_data }}">
	                </div>
	            </form>

			</div>
		</div>
	</div>
</div>
