var data_table = function (table_class, table_sort, table_empty) {
	$('.'+table_class+' table .search th:not(.no_action)').each( function () {
        var title = $(this).text();
        $(this).html( '<div class="form-group"><input type="text" class="form-control input-sm" placeholder="'+title.trim()+'"></div>' );
    });

	var table = $('.'+table_class+' table').DataTable({
		pagingType: 'numbers',
		lengthChange: false,
		stateSave: true,
		"stateSaveParams": function (settings, data) {
			data.columns = false;
		},
		"language": {
			"zeroRecords": table_empty
		},
		autoWidth: false,
		bInfo: false,
		columnDefs: [
			{
				"targets": "no_action",
				"orderable": false,
				"width": "50px"
			},
			{ type: 'natural', targets: "natural" },
			{ "targets": 'no_hidden', "visible": true},
			{ "targets": '_all', "visible": false, "width": "250px" },
		],
		"order": [],
		dom: 'Bfrtip',
		colReorder: true,
		buttons: {

        	dom: {
	            container: {
	                tag: 'ul',
					className: 'list-inline'
	            },

	            button: {
	                tag: 'li'
	            }
	        },

			buttons: [
				/*{
					extend: 'colvis',
					columns: ':not(.no_action)',
					className: '',
					text: '<a href="#"><i class="fa fa-cog"></i></a>',
	            },*/
				/*{
					extend: 'colvisRestore',
					text: '<a href="#">'+search_clear_btn+'</a>',
					className: 'reset-table',
					"action": function ( e, dt, node, config ) {
						table.colReorder.reset();
						table.order(table_sort).draw();
						table.columns().every( function () {
							var that = this;
							$( 'input[type=text]', this.footer() ).val("");
							that.search("").draw();
						});
						//dt.columns( config.show ).visible( true );
					},
	            }*/
			],
	    },

		/*drawCallback: function () {
			var api = this.api();
	        api.columns('.sum').every(function () {
	            var this_column = this;

	            var sum = this_column(1, {page:'current'})
	               .data()
				   .reduce(function (a, b) {
	                   a = parseInt(a, 10);
	                   if(isNaN(a)){ a = 0; }

	                   b = parseInt(b, 10);
	                   if(isNaN(b)){ b = 0; }

	                   return a + b;
	               });

	            $(this_column.footer()).html('Sum: ' + sum);
	        });
	    },*/

		/*drawCallback: function () {
			 var api = this.api();
			 $( api.table().footer() ).html(
			   api.column( 7, {page:'current'} ).data().sum()
			 );
		 }*/

	});
	$('.'+table_class+' .dataTables_paginate').parent().addClass("col-sm-12").removeClass("col-sm-7");
	$('.'+table_class+' .dataTables_paginate').parent().prev().remove();
	$('.'+table_class+' .dataTables_filter').remove();
	$('.'+table_class+' .dataTables_paginate').addClass("text-center").removeClass("dataTables_paginate");
	$('.'+table_class+' .paging_numbers ul').addClass("pagination-sm");
	//$('.'+table_class+' .buttons-colvis').attr("class", "");
	//$('.'+table_class+' .reset-table').attr("class", "reset-table");
	$('.'+table_class+' table').parent().removeClass("col-sm-12");

	table.draw();

	bild_table(table_class);

	table.columns().every( function () {
		var that = this;
		$( 'input[type=text]', this.footer() ).on( 'keyup change', function () {
			if ( that.search() !== this.value ) {
				that.search( this.value ).draw();
			}
		});
	});

	$(".fast_filter").on('submit',function() {
		table.columns().every( function () {
			var that = this;
			$( 'input[type=text]', this.footer() ).val("");
			that.search("").draw();
		});
	});

	table.on( 'draw', function () {
	    $('.'+table_class+' .paging_numbers ul').addClass("pagination-sm");
	});

	table.on( 'buttons-action', function ( e, buttonApi, dataTable, node, config ) {
	    bild_table(table_class);

		$(".pipeline_body").draggable("destroy" );
		bild_draggable();
	} );

	bild_draggable();
},
reset_table = function (table_class) {
	table = $('.'+table_class+' table').DataTable({});
},
bild_table = function (table_class) {
	var column_count = $('.'+table_class+' table thead th').length; // 12
	var col_width = 250;
	var table_width = col_width*column_count;

	//$('.'+table_class+' tfoot .select_all_checkbox').parent().attr("colspan", column_count);
	$('.'+table_class+' tfoot.table-footer th').attr("colspan", column_count);
	//$('.'+table_class+' table tbody th').not("no_action").css("width", col_width+"px");
	//$('.'+table_class+' table').css("width", table_width+"px");
},

bild_draggable = function () {
	var padd = 40;
	var draggable = $('.pipeline_body');
	var viewport = $('.pipeline_scroll');
	var viewportOffset = viewport.offset();
	var box =
		{
			x1: viewportOffset.left + (viewport.outerWidth() - draggable.outerWidth()),
			y1: viewportOffset.top + (viewport.outerHeight() - draggable.outerHeight()),
			x2: viewportOffset.left,
			y2: viewportOffset.top
		};

	$(".pipeline_body").draggable({
		axis: "x",
		cursor: "move",
		containment: [box.x1, box.y1, box.x2, box.y2 ],
		drag: function(event, ui) {
			if(box.x1 < (ui.position.left+padd)) {
				$(".pipeline_border .after").show();
			}
			if(box.x1 == (ui.position.left+padd)) {
				$(".pipeline_border .after").hide();
			}

			if(box.x2 > (ui.position.left+padd)) {
				$(".pipeline_border .before").show();
			}
			if(ui.position.left == 0) {
				$(".pipeline_border .before").hide();
			}
		}
	});

	$(".move-table-list li a").on('click',function() {
		var move = $(this).attr("data-move");
		if(move == "left") {
			$(".ui-draggable").css('left', "0");
			$(".pipeline_border .before").hide();
			$(".pipeline_border .after").show();
		} else {
			$(".ui-draggable").css('left', (box.x1-40));
			$(".pipeline_border .after").hide();
			$(".pipeline_border .before").show();
		}

		return false;
	});
},

// init app
table_app = function () {
  	return {
	    init: function () {

	    },
	    table: function (type, table_sort, table_empty) {
	    	data_table(type, table_sort, table_empty)
	    },
	}
}();
// init app

$(function () {
	table_app.init();
});

jQuery.fn.dataTable.Api.register( 'sum()', function ( ) {
	return this.flatten().reduce( function ( a, b ) {
		if ( typeof a === 'string' ) {
			a = a.replace(/[^\d.-]/g, '') * 1;
		}
		if ( typeof b === 'string' ) {
			b = b.replace(/[^\d.-]/g, '') * 1;
		}

		return a + b;
	}, 0 );
} );
