<?php

Route::get('/', function()
{
	return View::make('hello');
});

Route::get('decks', 'HomeController@decks');
Route::get('summary', 'HomeController@summary');
Route::get('forecast', 'HomeController@forecast');
Route::get('review-count', 'HomeController@reviewCount');
Route::get('review-time', 'HomeController@reviewTime');
Route::get('intervals', 'HomeController@intervals');
Route::get('hourly-breakdown', 'HomeController@hourlyBreakdown');
Route::get('answer-buttons', 'HomeController@answerButtons');
Route::get('cards-types', 'HomeController@cardTypes');

Route::get('review-distribution', 'HomeController@reviewDistribution');
Route::get('forgetting-curve', 'HomeController@forgettingCurve');
Route::get('repetition-intervals', 'HomeController@repetitionIntervals');
Route::get('tags', 'HomeController@tags');