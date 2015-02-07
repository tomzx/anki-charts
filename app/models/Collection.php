<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Collection extends Eloquent
{
	protected $table = 'col';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getCreationTime()
	{
		return $this->crt;
	}

	public function setCreationTime($crt)
	{
		$this->crt = $crt;

		return $this;
	}

	public function getMod()
	{
		return $this->mod;
	}

	public function setMod($mod)
	{
		$this->mod = $mod;

		return $this;
	}

	public function getSchema()
	{
		return $this->scm;
	}

	public function setSchema($schema)
	{
		$this->scm = $schema;

		return $this;
	}

	public function getVersion()
	{
		return $this->ver;
	}

	public function setVersion($version)
	{
		$this->ver = $version;

		return $this;
	}

	public function getDty()
	{
		return $this->dty;
	}

	public function setDty($dty)
	{
		$this->dty = $dty;

		return $this;
	}

	public function getUsn()
	{
		return $this->usn;
	}

	public function setUsn($usn)
	{
		$this->usn = $usn;

		return $this;
	}

	public function getLs()
	{
		return $this->ls;
	}

	public function setLs($ls)
	{
		$this->ls = $ls;

		return $this;
	}

	public function getConfiguration()
	{
		return $this->conf;
	}

	public function setConfiguration($conf)
	{
		$this->conf = $conf;

		return $this;
	}

	public function getModels()
	{
		return $this->models;
	}

	public function setModels($models)
	{
		$this->models = $models;

		return $this;
	}

	public function getDecks()
	{
		return $this->decks;
	}

	public function setDecks($decks)
	{
		$this->decks = $decks;

		return $this;
	}

	public function getDeckConfiguration()
	{
		return $this->d_conf;
	}

	public function setDeckConfiguration($deckConfiguration)
	{
		$this->d_conf = $deckConfiguration;

		return $this;
	}

	public function getTags()
	{
		return array_keys(json_decode($this->tags, true));
	}

	public function setTags($tags)
	{
		$this->tags = $tags;

		return $this;
	}

	/*
	 * @deprecated Use daysSinceCreation instead
	 */
	public function getToday()
	{
		return $this->daysSinceCreation();
	}

	public function daysSinceCreation()
	{
		return (int)floor((time() - $this->getCreationTime()) / 86400);
	}

	public function dayStart(Carbon $date = null)
	{
		if ($date === null) {
			$date = new Carbon();
		}
		$creationTime = Carbon::createFromTimestamp($this->getCreationTime());
		return $date->copy()->setTime($creationTime->hour, 0);
	}
}