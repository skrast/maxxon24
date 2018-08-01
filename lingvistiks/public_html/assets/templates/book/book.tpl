<div class="menubar">
    <div class="page-title">
        <h1>{{ lang.book_name }}</h1>

		<div class="clearfix"></div>
        <ul class="page-title-menu list-inline">
            <li>
                <a class="btn-flat gray get_ajax_form" href="" data-void="" data-essense="{{book_id|escape|stripslashes}}" data-type="book" data-sub="book_work" data-ajax="1"><i class="fa fa-plus-circle" aria-hidden="true"></i> {{ lang.book_add }}</a>
            </li>
        </ul>
    </div>
</div>

<div class="datatables">
    <div class="content-wrapper sendmail">

		<div class="row">
			<div class="col-md-3">
				<div class="block-margin-n20">
					<ul class="nav nav-pills nav-stacked nav-sm">
						{% for key, book in book_type_array %}
		                    <li {% if book_id == key %}class="active"{% endif %}><a href="{{ ABS_PATH_ADMIN_LINK }}?do=book&book_id={{ key }}">{{ book.title }}</a></li>
		                {% endfor %}
					</ul>
				</div>
			</div>

			<div class="col-md-9">

				{% if not book_id %}
					<p>{{ lang.book_all }}</p>
				{% endif %}

		        {% if book %}
	                <div class="dd" id="nestable" data-essense="{{book_id|escape|stripslashes}}">
	                    <ul class="dd-list">
	                        {% for book in book %}
	                            <li class="dd-item" data-id="{{book.id}}">
	                                <div class="dd-handle dd3-handle"><div class="grippy_large"></div></div>
	                                <div class="dd3-content">
	                                    <table class="datatables">
	                                        <tr>
	                                            <td class="col-md-11">
	                                                <div class="color-block-left label" style="background:{{book.color|escape|stripslashes}}" title="{{book.title|escape|stripslashes}}" data-toggle="tooltip" data-placement="right">&nbsp</div>

													{{book.title|escape|stripslashes}}
	                                            </td>
	                                            <td class="col-md-1">
													<ul class="list-inline">
														<li data-toggle="tooltip" data-placement="top" title="{{ lang.save_edit }}">
															<a href="#" class="get_ajax_form" href="#" data-void="{{ book.id }}" data-essense="{{book_id|escape|stripslashes}}" data-type="book" data-sub="book_work" data-ajax="1">
																<i class="fa fa-pencil"></i>
															</a>
														</li>
			                                            <li>
			                                                <a href="{{ ABS_PATH_ADMIN_LINK }}?do=book&sub=book_delete&book_item_id={{book.id}}&book_id={{ book_id }}" class="confirm" data-toggle="tooltip" data-placement="top" title="{{ lang.save_delete }}"><i class="fa fa-times"></i></a>
			                                            </li>
				                                    </ul>
	                                            </td>
	                                        </tr>
	                                    </table>
	                                </div>
	                            </li>
	                        {% endfor %}
	                    </ul>
	                </div>
		        {% else %}
		            <p>{{ lang.empty_data }}</p>
		        {% endif %}
    		</div>
    	</div>
    </div>
</div>

<script>
    $(function(){
        var updateOutput = function (e) {
             var list = e.length ? e : $(e.target),
                     output = list.data('output');
             if (window.JSON) {
                var nest = window.JSON.stringify(list.nestable('serialize'));

                $.ajax({
                    url: ave_path+'?do=book&sub=book_nest',
                    data: {'nest':nest, book_id: {{ book_id }}, csrf_token: csrf_token},
                    success: function( data ) {
                        //alert('test');
                    },
                });
             }
        };

        $('#nestable').nestable({
             group: 1,
             expandBtnHTML: '',
             collapseBtnHTML: '',
             listNodeName: 'ul',
        }).on('change', updateOutput);
    });
</script>
