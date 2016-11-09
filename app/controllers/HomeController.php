<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class HomeController extends BaseController {

	protected $from;
	protected $to;

	public function __construct()
	{
		$from = Input::get('from');
		$this->from = $from ? Carbon::parse($from) : null;
		$to = Input::get('to');
		$this->to = $to ? Carbon::parse($to) : null;
	}

	private function myDecks()
	{
		return Config::get('anki.decks');
	}

	private function whereDecks($query, array $decks)
	{
		return $query
		->join('cards', 'revlog.cid', '=', 'cards.id')
		->whereIn('did', $decks);
	}

	private function whereMyDecks($query)
	{
		return $this->whereDecks($query, $this->myDecks());
	}

	private function whereFromTo($query, $from, $to)
	{
		if ($from && $to) {
			return $query->whereBetween('revlog.id', [$from->timestamp*1000, $to->timestamp*1000]);
		} else if ($from) {
			return $query->where('revlog.id', '>', $from->timestamp*1000);
		} else if ($to) {
			return $query->where('revlog.id', '<', $to->timestamp*1000);
		}
	}

	public function decks()
	{
		/* @var $collection Collection */
		$collection = Collection::first();
		$deckData = json_decode($collection->getDecks());
		$results = [];
		foreach ($deckData as $key => $deck) {
			$results[$key] = $deck->name;
		}
		return Response::json($results);
	}

	public function summary()
	{
		$from = $this->from;
		$to = $this->to;

		/* @var $collection Collection */
		$collection = Collection::first();

		$decks = Input::get('decks');
		if ($decks) {
			$decks = explode(',', $decks);
		} else {
			$decks = $this->myDecks();
		}

		$query = DB::table('revlog')
			->select(
				DB::raw('count() as cards'),
				DB::raw('sum(time)/1000 as the_time'),
				DB::raw('sum(case when ease = 1 then 1 else 0 end) as failed'), /* failed */
				DB::raw('sum(case when type = 0 then 1 else 0 end) as learning'), /* learning */
				DB::raw('sum(case when type = 1 then 1 else 0 end) as review'), /* review */
				DB::raw('sum(case when type = 2 then 1 else 0 end) as relearn'), /* relearn */
				DB::raw('sum(case when type = 3 then 1 else 0 end) as filter') /* filter */
			);

		$this->whereFromTo($query, $from, $to);

		$result = $query
			/*->whereIn('cid', function($query) use ($decks) {
				$query->select('id')
					->from('cards')
					->whereIn('did', $decks);
			})*/
			->first();

		$cards = $result->cards ?: 0;
		$the_time = $result->the_time ?: 0;
		$failed = $result->failed ?: 0;
		$learning = $result->learning ?: 0;
		$review = $result->review ?: 0;
		$relearn = $result->relearn ?: 0;
		$filter = $result->filter ?: 0;

		echo '<pre>';
		var_dump($result);
		echo '</pre>';

		echo '<br/>';

		echo 'Studied '.$cards.' card(s) in '.secondsToTime($the_time).'<br/>';
		echo 'Again count: '.$failed;
		if ($cards) {
			echo ' ('.sprintf('%0.1f', ((1-$failed/$cards)*100)).'% correct)';
		}
		echo '<br/>';
		echo 'Learn: '.$learning.', Review: '.$review.', Relearn: '.$relearn.', Filtered: '.$filter.'<br/>';

		$query = DB::table('revlog')
			->select(
				DB::raw('count() as mature_count'),
				DB::raw('sum(case when ease = 1 then 0 else 1 end) as mature_sum')
			);

		$this->whereFromTo($query, $from, $to);
		$this->whereDecks($query, $decks);

		$matureInterval = 21;
		$result = $query
			->where('lastIvl', '>=', $matureInterval)
			->first();

		if ($result->mature_count) {
			echo 'Correct answers on mature cards '.$result->mature_sum.'/'.$result->mature_count.' ('.sprintf('%0.1f', ($result->mature_sum / $result->mature_count * 100)).'%)';
		} else {
			echo 'No mature cards were studied today.';
		}
	}

	public function forecast()
	{
		return Response::json($this->due(0, 31));
	}

	protected function due($start = null, $end = null, $chunk = 1)
	{
		if ($chunk < 1) {
			throw new InvalidArgumentException('$chunk must be 1 or higher');
		}

		/* @var $collection Collection */
		$collection = Collection::first();

		$daysSinceCreation = $collection->daysSinceCreation();

		$query = DB::table('cards')
			->select(
				DB::raw('(due-'.$daysSinceCreation.'/'.$chunk.') as period'),
				DB::raw('sum(case when ivl < 21 then 1 else 0 end) as young'),
				DB::raw('sum(case when ivl >= 21 then 1 else 0 end) as mature')
			)
			->whereIn('did', $this->myDecks())
			->whereIn('queue', [2, 3])
			->where('due', '>', $daysSinceCreation) // Due must be in the future
			->groupBy('period')
			->orderBy('period');

		if ($start !== null) {
			$query->where('due', '>=', $start);
		}

		if ($end !== null) {
			$query->where('due', '<', $daysSinceCreation+$end);
		}

		$results = $query->get();

		foreach ($results as $key => $result) {
			$result->period = (int)$result->period;
			$result->young = (int)$result->young;
			$result->mature = (int)$result->mature;
		}

		return $results;
	}

	public function reviewCount()
	{
		return Response::json($this->done());
	}

	protected function done($chunk = 1)
	{
		$cut = 0;
		$period = 60; // in minutes

		$query = DB::table('revlog')
			->select(
				DB::raw('(cast((revlog.id/1000.0 - '.$cut.') / 86400.0 as int))/'.$chunk.' as period'),
				DB::raw('sum(case when revlog.type = 0 then 1 else 0 end) as learn_count'), //-- lrn count
				DB::raw('sum(case when revlog.type = 1 and revlog.lastIvl < 21 then 1 else 0 end) as young_count'), //-- yng count
				DB::raw('sum(case when revlog.type = 1 and revlog.lastIvl >= 21 then 1 else 0 end) as mature_count'), //-- mtr count
				DB::raw('sum(case when revlog.type = 2 then 1 else 0 end) as lapse_count'), //-- lapse count
				DB::raw('sum(case when revlog.type = 3 then 1 else 0 end) as cram_count'), //-- cram count
				DB::raw('sum(case when revlog.type = 0 then time/1000.0 else 0 end)/'.$period.' as learn_time'), //-- lrn time
				DB::raw('sum(case when revlog.type = 1 and revlog.lastIvl < 21 then time/1000.0 else 0 end)/'.$period.' as young_time'), // yng time
				DB::raw('sum(case when revlog.type = 1 and revlog.lastIvl >= 21 then time/1000.0 else 0 end)/'.$period.' as mature_time'), // mtr time
				DB::raw('sum(case when revlog.type = 2 then time/1000.0 else 0 end)/'.$period.' as lapse_time'),// -- lapse time
				DB::raw('sum(case when revlog.type = 3 then time/1000.0 else 0 end)/'.$period.' as cram_time')// -- cram time
			)
			->groupBy('period')
			->orderBy('period');

		$this->whereMyDecks($query);
		$this->whereFromTo($query, $this->from, $this->to);

		$results = $query->get();

		foreach ($results as $result) {
			$result->period = (int)$result->period;
			$result->learn_count = (int)$result->learn_count;
			$result->young_count = (int)$result->young_count;
			$result->mature_count = (int)$result->mature_count;
			$result->lapse_count = (int)$result->lapse_count;
			$result->cram_count = (int)$result->cram_count;
			$result->learn_time = (int)$result->learn_time;
			$result->young_time = (int)$result->young_time;
			$result->mature_time = (int)$result->mature_time;
			$result->lapse_time = (int)$result->lapse_time;
			$result->cram_time = (int)$result->cram_time;
		}

		return $results;
	}

	public function reviewTime()
	{
		return Response::json($this->done());
	}

	public function intervals()
	{

	}

	public function hourlyBreakdown()
	{

	}

	public function answerButtons()
	{
		return Response::json($this->eases());
	}

	protected function eases()
	{
		$query = $this->whereMyDecks(
			DB::table('revlog')
			->select(
				DB::raw('(case when revlog.type in (0,2) then 0 when lastIvl < 21 then 1 else 2 end) as the_type'),
				DB::raw('(case when revlog.type in (0,2) and ease = 4 then 3 else ease end) as ease'),
				DB::raw('count() as count')
			)
			->groupBy('the_type', 'ease')
			->orderBy('the_type')
			->orderBy('ease'));

		$this->whereFromTo($query, $this->from, $this->to);

		$results = $query->get();

		foreach ($results as $result) {
			$result->the_type = (int)$result->the_type;
			$result->ease = (int)$result->ease;
			$result->count = (int)$result->count;
		}
		return $results;
	}

	public function cardTypes()
	{
		return Response::json($this->cards());
	}

	protected function cards()
	{
		return DB::table('cards')
			->select(
				DB::raw('sum(case when queue=2 and ivl >= 21 then 1 else 0 end) as mature'), //-- mtr
				DB::raw('sum(case when queue in (1,3) or (queue=2 and ivl < 21) then 1 else 0 end) as young_learn'), //-- yng/lrn
				DB::raw('sum(case when queue=0 then 1 else 0 end) as unseen'), //-- new
				DB::raw('sum(case when queue<0 then 1 else 0 end) as suspended')// -- susp
			)
			->whereIn('did', $this->myDecks())
			->first();
	}

	public function reviewDistribution()
	{
		return Response::json($this->reviewDistributionQuery());
	}

	protected function reviewDistributionQuery()
	{
		$subQuery = DB::table('revlog')
			->select(
				DB::raw('count(cid) as reviewCount')
			)
			->join('cards', 'revlog.cid', '=', 'cards.id')
			->whereIn('did', $this->myDecks())
			->groupBy('cid');

		$this->whereFromTo($subQuery, $this->from, $this->to);

		$results = DB::table(
			DB::raw('('.$subQuery->toSql().') as subQuery')
		)
			->select(
				'reviewCount',
				DB::raw('count(1) as cards')
			)
			->groupBy('reviewCount')
			->mergeBindings($subQuery)
			->get();

		foreach ($results as $result) {
			$result->reviewCount = (int)$result->reviewCount;
			$result->cards = (int)$result->cards;
		}
		return $results;
	}

	public function easeOverTime(){

	}

	public function forgettingCurve()
	{
		return Response::json($this->forgettingCurveQuery());
	}

	protected function forgettingCurveQuery()
	{
		$from = $this->from;
		$to = $this->to;

		$query = DB::table('revlog')
			->select(
				'revlog.lastIvl as duration',
				DB::raw('count(1) as count')
			)
			->whereIn('did', $this->myDecks())
			->where('revlog.ease', '=', 1) // again => forgot
			->where('revlog.lastIvl', '>=', 0) // ignore cards not learned yet
			->join('cards', 'revlog.cid', '=', 'cards.id')
			->groupBy('revlog.lastIvl')
			->orderBy('revlog.lastIvl');


		$this->whereFromTo($query, $from, $to);

		$results = $query->get();

		foreach ($results as $result) {
			$result->duration = (int)$result->duration;
			$result->count = (int)$result->count;
		}
		return $results;
	}

	public function repetitionIntervals()
	{
		return Response::json($this->repetitionIntervalsQuery());
	}

	protected function repetitionIntervalsQuery()
	{
		$results = DB::table('revlog')
			->select(
				'revlog.id',
				'revlog.cid',
				'revlog.ivl as interval'
			)
			->where('revlog.ivl', '>', 0)
			->join('cards', 'revlog.cid', '=', 'cards.id')
			->whereIn('did', $this->myDecks())
			->orderBy('revlog.cid')
			->orderBy('revlog.id')
			->get();

		foreach ($results as $result) {
			$result->interval = (int)$result->interval;
		}

		$results = new \Illuminate\Support\Collection($results);
		$results = $results->groupBy('cid');

		return $results;
	}

	public function tags()
	{
		$collection = Collection::first();

		// Get all tags
		$tags = $collection->getTags();
		// Find how many are in the specified decks
		foreach ($tags as $tag) {
			$q = DB::table('cards')
				->select(
					DB::raw('count() as cards_count'),
					DB::raw('avg(ivl) as average_interval')
				)
				->join('notes', 'cards.nid', '=', 'notes.id')
				->where('tags', 'like', '%'.$tag.'%')
				->whereIn('did', $this->myDecks())
				->first();

			if ((int)$q->cards_count === 0) {
				continue;
			}

			$results[$tag] = [
				'tag' => $tag,
				'count' => (int)$q->cards_count,
				'value' => round($q->average_interval, 1),
			];
		}

		return Response::json($results);
	}

}
