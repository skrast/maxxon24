var simple_chart_bar = function (data, target, option_plugins) {
	if(!option_plugins) {
		option_plugins = {
	        plugins: [
				Chartist.plugins.tooltip()
	        ]
	    };
	}
    $.extend(options_bar, option_plugins);
	target = target ? target : '.ct-chart';
    new Chartist.Bar(target, data, options_bar);
},

simple_chart_pie = function (data, target, option_plugins) {
	if(!option_plugins) {
		option_plugins = {
			plugins: [
				Chartist.plugins.legend(),
				Chartist.plugins.tooltip()
			]
		};
	}
	$.extend(options_pie, option_plugins);
	target = target ? target : '.ct-chart';
	new Chartist.Pie(target, data, options_pie, responsiveOptions);
},

simple_chart_line = function (data, target, option_plugins) {
	if(!option_plugins) {
		option_plugins = {
	        plugins: [
			  Chartist.plugins.tooltip()
	        ]
	    };
	}
    $.extend(options_charts, option_plugins);
	target = target ? target : '.ct-chart';
    new Chartist.Line(target, data, options_charts);
},

app_chart = function () {
  	return {
	    simple_chart_bar: function (data, target, option_plugins) {
	    	simple_chart_bar(data, target, option_plugins);
	    },
	    simple_chart_pie: function (data, target, option_plugins) {
	    	simple_chart_pie(data, target, option_plugins);
	    },
	    simple_chart_line: function (data, target, option_plugins) {
	    	simple_chart_line(data, target, option_plugins);
	    },
	}
}();
