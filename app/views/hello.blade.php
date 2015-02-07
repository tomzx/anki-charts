<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Anki Charts</title>
	<style>
		.graph {
			width: 100%;
			height: 100%;
		}

		.fixed {
			position: fixed;
			left: 0;
			top: 0;
			z-index: 1000;
			padding: 10px;
		}
	</style>
	<link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/bootstrap-theme.min.css')}}">
</head>
<body>
	<div class="container-fluid">
		{{ Form::open(['class' => 'form-inline fixed']) }}
			<div class="form-group">
				<label for="from" class="sr-only">From</label>
				<input type="text" name="from" id="from" class="form-control" placeholder="from" value="-30 days"/>
			</div>
			<div class="form-group">
				<label for="to" class="sr-only">To</label>
				<input type="text"  name="to" id="to" class="form-control" placeholder="to" value="now"/>
			</div>
			<button type="button" class="animate">Animate</button>
		{{ Form::close() }}
		<div class="row">
			<div class="col-sm-6"><div class="summary graph" data-url="summary"></div></div>
			<div class="col-sm-6"><div class="forecast graph" data-url="forecast"></div></div>
		</div>
		<div class="row">
			<div class="col-sm-6"><div class="review-count graph" data-url="review-count"></div></div>
			<div class="col-sm-6"><div class="review-time graph" data-url="review-time"></div></div>
		</div>
		<div class="row">
			<div class="col-sm-6"><div class="intervals graph" data-url="intervals"></div></div>
			<div class="col-sm-6"><div class="hourly-breakdown graph" data-url="hourly-breakdown"></div></div>
		</div>
		<div class="row">
			<div class="col-sm-6"><div class="answer-buttons graph" data-url="answer-buttons"></div></div>
			<div class="col-sm-6"><div class="cards-types graph" data-url="cards-types"></div></div>
		</div>
		<div class="row">
			<div class="col-sm-6"><div class="review-distribution graph" data-url="review-distribution"></div></div>
			<div class="col-sm-6"><div class="forgetting-curve graph" data-url="forgetting-curve"></div></div>
		</div>
		{{--<div class="row">--}}
			{{--<div class="col-sm-6"><div class="repetition-intervals graph" data-url="repetition-intervals"></div></div>--}}
			{{--<div class="col-sm-6"><div class="graph"></div></div>--}}
		{{--</div>--}}
		{{--<div class="row">--}}
			{{--<div class="col-sm-12" style="height: 1200px;"><div class="tags graph" data-url="tags"></div></div>--}}
		{{--</div>--}}
	</div>

	<script src="{{asset('assets/js/jquery-2.1.3.js')}}"></script>
	<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
	<script src="{{asset('assets/js/lodash-3.1.0.js')}}"></script>
	<script src="{{asset('assets/js/highcharts.js')}}"></script>
	<script src="{{asset('assets/js/app.js')}}"></script>
</body>
</html>
