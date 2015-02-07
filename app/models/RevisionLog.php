<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class RevisionLog extends Eloquent
{
	protected $table = 'revlog';

	public function card()
	{
		return $this->belongsTo('Card', 'cid', 'id');
	}

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getCid()
	{
		return $this->cid;
	}

	public function setCid($cid)
	{
		$this->cid = $cid;

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

	public function getEase()
	{
		return $this->ease;
	}

	public function setEase($ease)
	{
		$this->ease = $ease;

		return $this;
	}

	public function getIlvl()
	{
		return $this->ilvl;
	}

	public function setIlvl($ilvl)
	{
		$this->ilvl = $ilvl;

		return $this;
	}

	public function getLastIvl()
	{
		return $this->last_ivl;
	}

	public function setLastIvl($lastIvl)
	{
		$this->last_ivl = $lastIvl;

		return $this;
	}

	public function getFactor()
	{
		return $this->factor;
	}

	public function setFactor($factor)
	{
		$this->factor = $factor;

		return $this;
	}

	public function getTime()
	{
		return $this->time;
	}

	public function setTime($time)
	{
		$this->time = $time;

		return $this;
	}

	public function getType()
	{
		return $this->type;
	}

	public function setType($type)
	{
		$this->type = $type;

		return $this;
	}
}