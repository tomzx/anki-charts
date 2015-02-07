$(function () {
	Number.prototype.round = function(places) {
		return +(Math.round(this + "e+" + places)  + "e-" + places);
	}

	var graphHandlers = {};

	graphHandlers['summary'] = function(data) {
		$('.summary').highcharts({
			title: {
				text: 'Summary',
			},
		});
	};

	graphHandlers['forecast'] = function(data) {
		var cumulative = _.reduce(data, function(result, value) {
			var currentValue = _.last(result) || 0;
			result.push(currentValue + value.young + value.mature);
			return result;
		}, []);
		var young = _.pluck(data, 'young');
		var mature = _.pluck(data, 'mature')

		var $element = $('.forecast');

		if ($element.highcharts()) {
			var chart = $element.highcharts();
			chart.series[0].setData(young);
			chart.series[1].setData(mature);
			chart.series[2].setData(cumulative);
			return;
		}

		$element.highcharts({
			title: {
				text: 'Forecast',
			},

			subtitle: {
				text: 'The number of reviews due in the future.',
			},

			xAxis: {
				title: {
					text: 'Period (days)'
				},
			},

			yAxis: [{
				title: {
					text: 'Cards',
				},
			}, {
				title: {
					text: 'Cumulative Cards',
				},
				opposite: true,
			}],

			plotOptions: {
				column: {
					stacking: 'normal',
				},
			},

			series: [
				{
					type: 'column',
					name: 'Young',
					data: young,
				}, {
					type: 'column',
					name: 'Mature',
					data: mature,
				}, {
					type: 'spline',
					name: 'Cumulative',
					data: cumulative,
					yAxis: 1,
				}
			]
		});
	};

	graphHandlers['review-count'] = function(data) {
		var cumulativeYoung = [];
		var cumulativeMature = [];
		var cumulativeRelearn = [];
		var cumulativeLearn = [];
		var cumulativeCram = [];
		_.each(data, function(value) {
			cumulativeYoung.push((_.last(cumulativeYoung) || 0) + value.young_count);
			cumulativeMature.push((_.last(cumulativeMature) || 0) + value.mature_count);
			cumulativeRelearn.push((_.last(cumulativeRelearn) || 0) + value.lapse_count);
			cumulativeLearn.push((_.last(cumulativeLearn) || 0) + value.learn_count);
			cumulativeCram.push((_.last(cumulativeCram) || 0) + value.cram_count);
		});
		var matureCount = _.pluck(data, 'mature_count');
		var youngCount = _.pluck(data, 'young_count');
		var relearnCount = _.pluck(data, 'lapse_count');
		var learnCount = _.pluck(data, 'learn_count');
		var cramCount = _.pluck(data, 'cram_count');

		var $element = $('.review-count');

		if ($element.highcharts()) {
			var chart = $element.highcharts();
			chart.series[0].setData(matureCount);
			chart.series[1].setData(youngCount);
			chart.series[2].setData(relearnCount);
			chart.series[3].setData(learnCount);
			chart.series[4].setData(cramCount);
			chart.series[5].setData(cumulativeMature);
			chart.series[6].setData(cumulativeYoung);
			chart.series[7].setData(cumulativeRelearn);
			chart.series[8].setData(cumulativeLearn);
			chart.series[9].setData(cumulativeCram);
			return;
		}

		$element.highcharts({
			title: {
				text: 'Review Count',
			},

			subtitle: {
				text: 'The number of questions you have answered.',
			},

			xAxis: {
				title: {
					text: 'Period (days)'
				},
			},

			yAxis: [{
				title: {
					text: 'Cards',
				},
				min: 0,
			}, {
				title: {
					text: 'Cumulative Cards',
				},
				min: 0,
				opposite: true,
			}],

			plotOptions: {
				column: {
					stacking: 'normal',
				},
			},

			series: [
				{
					type: 'column',
					name: 'Mature',
					data: matureCount,
				}, {
					type: 'column',
					name: 'Young',
					data: youngCount,
				}, {
					type: 'column',
					name: 'Relearn',
					data: relearnCount,
				}, {
					type: 'column',
					name: 'Learn',
					data: learnCount,
				}, {
					type: 'column',
					name: 'Cram',
					data: cramCount,
				}, {
					type: 'spline',
					name: 'Cumulative - Mature',
					data: cumulativeMature,
					yAxis: 1,
				}, {
					type: 'spline',
					name: 'Cumulative - Young',
					data: cumulativeYoung,
					yAxis: 1,
				}, {
					type: 'spline',
					name: 'Cumulative - Relearn',
					data: cumulativeRelearn,
					yAxis: 1,
				}, {
					type: 'spline',
					name: 'Cumulative - Learn',
					data: cumulativeLearn,
					yAxis: 1,
				}, {
					type: 'spline',
					name: 'Cumulative - Cram',
					data: cumulativeCram,
					yAxis: 1,
				}
			]
		});
	};

	graphHandlers['review-time'] = function(data) {
		//$('.review-time').highcharts({
		//	title: {
		//		text: 'Review Time',
		//	},
		//});

		var cumulativeYoung = [];
		var cumulativeMature = [];
		var cumulativeRelearn = [];
		var cumulativeLearn = [];
		var cumulativeCram = [];
		_.each(data, function(value) {
			cumulativeYoung.push((_.last(cumulativeYoung) || 0) + value.young_time);
			cumulativeMature.push((_.last(cumulativeMature) || 0) + value.mature_time);
			cumulativeRelearn.push((_.last(cumulativeRelearn) || 0) + value.lapse_time);
			cumulativeLearn.push((_.last(cumulativeLearn) || 0) + value.learn_time);
			cumulativeCram.push((_.last(cumulativeCram) || 0) + value.cram_time);
		});
		var matureTime = _.pluck(data, 'mature_time');
		var youngTime = _.pluck(data, 'young_time');
		var relearnTime = _.pluck(data, 'lapse_time');
		var learnTime = _.pluck(data, 'learn_time');
		var cramTime = _.pluck(data, 'cram_time');

		var $element = $('.review-time');

		if ($element.highcharts()) {
			var chart = $element.highcharts();
			chart.series[0].setData(matureTime);
			chart.series[1].setData(youngTime);
			chart.series[2].setData(relearnTime);
			chart.series[3].setData(learnTime);
			chart.series[4].setData(cramTime);
			chart.series[5].setData(cumulativeMature);
			chart.series[6].setData(cumulativeYoung);
			chart.series[7].setData(cumulativeRelearn);
			chart.series[8].setData(cumulativeLearn);
			chart.series[9].setData(cumulativeCram);
			return;
		}

		$element.highcharts({
			title: {
				text: 'Review Time',
			},

			subtitle: {
				text: 'The time taken to answer the questions.',
			},

			xAxis: {
				title: {
					text: 'Period (days)'
				},
			},

			yAxis: [{
				title: {
					text: 'Minutes',
				},
				min: 0,
			}, {
				title: {
					text: 'Cumulative Minutes',
				},
				min: 0,
				opposite: true,
			}],

			plotOptions: {
				column: {
					stacking: 'normal',
				},
			},

			series: [
				{
					type: 'column',
					name: 'Mature',
					data: matureTime,
				}, {
					type: 'column',
					name: 'Young',
					data: youngTime,
				}, {
					type: 'column',
					name: 'Relearn',
					data: relearnTime,
				}, {
					type: 'column',
					name: 'Learn',
					data: learnTime,
				}, {
					type: 'column',
					name: 'Cram',
					data: cramTime,
				}, {
					type: 'spline',
					name: 'Cumulative - Mature',
					data: cumulativeMature,
					yAxis: 1,
				}, {
					type: 'spline',
					name: 'Cumulative - Young',
					data: cumulativeYoung,
					yAxis: 1,
				}, {
					type: 'spline',
					name: 'Cumulative - Relearn',
					data: cumulativeRelearn,
					yAxis: 1,
				}, {
					type: 'spline',
					name: 'Cumulative - Learn',
					data: cumulativeLearn,
					yAxis: 1,
				}, {
					type: 'spline',
					name: 'Cumulative - Cram',
					data: cumulativeCram,
					yAxis: 1,
				}
			]
		});
	};

	graphHandlers['intervals'] = function(data) {
		$('.intervals').highcharts({
			title: {
				text: 'Intervals',
			},

			subtitle: {
				text: 'Delays until reviews are shown again.',
			},
		});
	};

	graphHandlers['hourly-breakdown'] = function(data) {
		$('.hourly-breakdown').highcharts({
			title: {
				text: 'Hourly Breakdown',
			},

			subtitle: {
				text: 'Review success rate for each hour of the day.',
			},
		});
	};

	graphHandlers['answer-buttons'] = function(data) {
		var l1 = _.pluck(_.where(data, {'ease': 1}), 'count');
		var l2 = _.pluck(_.where(data, {'ease': 2}), 'count');
		var l3 = _.pluck(_.where(data, {'ease': 3}), 'count');
		var l4 = [null].concat(_.pluck(_.where(data, {'ease': 4}), 'count'));

		var $element = $('.answer-buttons')

		if ($element.highcharts()) {
			var chart = $element.highcharts();
			chart.series[0].setData(l1);
			chart.series[1].setData(l2);
			chart.series[2].setData(l3);
			chart.series[3].setData(l4);
			return;
		}

		$element.highcharts({
			chart: {
				type: 'column',
			},

			title: {
				text: 'Answer Buttons',
			},

			subtitle: {
				text: 'The number of times you have pressed each button.',
			},

			xAxis: {
				categories: ['Learning', 'Young', 'Mature']
			},

			yAxis: {
				min: 0,
				title: {
					text: 'Answers',
				},
			},

			series: [
				{
					name: '1',
					data: l1,
				},
				{
					name: '2',
					data: l2,
				},
				{
					name: '3',
					data: l3,
				},
				{
					name: '4',
					data: l4,
				}
			],
		});
	};

	graphHandlers['cards-types'] = function(data) {
		
		var $element = $('.cards-types');

		if ($element.highcharts()) {
			var chart = $element.highcharts();
			// TODO: Refresh "series" <tom@tomrochette.com>
			return;
		}

		$element.highcharts({
			title: {
				text: 'Cards Types'
			},

			subtitle: {
				text: 'The division of cards in your deck(s).',
			},

			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						format: '<b>{point.name}</b>: {point.percentage:.1f} %',
						style: {
							color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
						}
					}
				}
			},

			series: [{
				type: 'pie',
				name: 'Cards Types',
				data: [
					['Mature', parseInt(data.mature, 10)],
					['Young + Learn', parseInt(data.young_learn, 10)],
					['Unseen', parseInt(data.unseen, 10)],
					['Suspended', parseInt(data.suspended, 10)]
				]
			}]
		});
	};

	graphHandlers['review-distribution'] = function(data) {
		var series = _.map(data, function(item) {
			return [item.reviewCount, item.cards];
		});

		var $element = $('.review-distribution');

		if ($element.highcharts()) {
			var chart = $element.highcharts();
			chart.series[0].setData(series);
			return;
		}

		$element.highcharts({
			title: {
				text: 'Review Distribution',
			},

			xAxis: {
				title: {
					text: 'Review #',
				},
				gridLineWidth: 1,
			},

			yAxis: [{
				title: {
					text: 'Cards',
				},
				min: 0,
			}],

			legend: {
				enabled: false,
			},

			series: [
				{
					//name: 'Mature',
					data: series,
				}
			]
		});
	};

	graphHandlers['forgetting-curve'] = function(data) {
		var sum = _.reduce(data, function(sum, item) {
			return sum + item.count;
		}, 0);

		var total = sum;

		var series = _.map(data, function(item) {
			var result = [item.duration, (sum/total*100).round(2)];
			sum -= item.count;
			return result;
		});

		var $element = $('.forgetting-curve');

		if ($element.highcharts()) {
			var chart = $element.highcharts();
			chart.series[0].setData(series);
			return;
		}

		$element.highcharts({
			title: {
				text: 'Forgetting curve',
			},

			xAxis: {
				title: {
					text: 'Elapsed time (days)',
				},
				gridLineWidth: 1,
			},

			yAxis: [{
				title: {
					text: 'Chance of remembering (%)',
				},
				min: 0,
				max: 100,
			}],

			legend: {
				enabled: false,
			},

			series: [
				{
					data: series,
				},
			]
		});
	};

	graphHandlers['repetition-intervals'] = function(data) {
		//var sum = _.reduce(data, function(sum, item) {
		//	return sum + item.count;
		//}, 0);
		//
		//var total = sum;

		var series = _.map(data, function(item) {
			return {
				name: 'cid: '+ item[0].cid,
				data: _.map(item, function(itemData) {
					return itemData.interval;
				})
			};
		});

		$('.repetition-intervals').highcharts({
			chart: {
				type: 'scatter',
				zoomType: 'xy',
			},

			title: {
				text: 'Interval until next review based on repetition number',
			},

			xAxis: {
				title: {
					text: 'Repetition #',
				},
				gridLineWidth: 1,
			},

			yAxis: [{
				title: {
					text: 'Interval (days)',
				},
				min: 0,
			}],

			legend: {
				enabled: false,
			},

			series: series
		});
	};

	graphHandlers['tags'] = function(data) {
		var series = [];
		var i = 0;

		for (var t in data) {
			var tag = data[t];

			if (tag.value === 0) {
				continue;
			}

			series.push({
				name: tag.tag,
				//data: [[~~(Math.random() * 100), 0, ~~(Math.random() * 5000)]]
				data: [[tag.value, (++i * 10) % 100, tag.count]]
			});
		}

		$('.tags').highcharts({
			chart: {
				type: 'bubble',
			},

			title: {
				text: 'Tags',
			},

			legend: {
				enabled: false,
			},

			xAxis: {
				title: {
					text: 'Average interval (in days)',
				},
			},

			yAxis: {
				title: {
					text: '(Nothing)',
				},
			},

			plotOptions: {
				bubble: {
					dataLabels: {
						enabled: true,
						format: '{series.name}'
						/*color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
						style: {
							textShadow: '0 0 3px black, 0 0 3px black'
						},*/
					},
				},
			},

			series: series,
		});
	};

	var refreshGraphs = function() {
		$('[data-url]').each(function() {
			var $this = $(this),
				url = $this.data('url');

			$.ajax({
				url: url,
				data: {
					from: $('#from').val(),
					to: $('#to').val(),
				}
			}).done(function(data) {
				if (typeof graphHandlers[url] !== 'undefined') {
					graphHandlers[url](data);
				}
			});
		});
	};

	refreshGraphs();

	$('#from, #to').change(function() {
		refreshGraphs();
	});

	var animateInterval = null;
	$('.animate').click(function() {
		if (animateInterval) {
			clearInterval(animateInterval);
			animateInterval = null;
			return;
		}

		var value = 0;
		animateInterval = setInterval(function() {
			$('#from').val((value+30)+' days ago').change();
			$('#to').val(value+' days ago').change();
			++value;
		}, 1000);
	});
});
