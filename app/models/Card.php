<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Card extends Eloquent
{
	protected $table = 'cards';

	public function note()
	{
		return $this->belongsTo('Note', 'nid', 'id');
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

	public function getNoteId()
	{
		return $this->nid;
	}

	public function setNoteId($noteId)
	{
		$this->nid = $noteId;

		return $this;
	}

	public function getDeckId()
	{
		return $this->did;
	}

	public function setDeckId($deckId)
	{
		$this->did = $deckId;

		return $this;
	}

	public function getOrd()
	{
		return $this->ord;
	}

	public function setOrd($ord)
	{
		$this->ord = $ord;

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

	public function getUsn()
	{
		return $this->usn;
	}

	public function setUsn($usn)
	{
		$this->usn = $usn;

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

	public function getQueue()
	{
		return $this->queue;
	}

	public function setQueue($queue)
	{
		$this->queue = $queue;

		return $this;
	}

	public function getDue()
	{
		return $this->due;
	}

	public function setDue($due)
	{
		$this->due = $due;

		return $this;
	}

	public function getInterval()
	{
		return $this->ivl;
	}

	public function setInterval($interval)
	{
		$this->ivl = $interval;

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

	public function getReps()
	{
		return $this->reps;
	}

	public function setReps($reps)
	{
		$this->reps = $reps;

		return $this;
	}

	public function getLapses()
	{
		return $this->lapses;
	}

	public function setLapses($lapses)
	{
		$this->lapses = $lapses;

		return $this;
	}

	public function getLeft()
	{
		return $this->left;
	}

	public function setLeft($left)
	{
		$this->left = $left;

		return $this;
	}

	public function getOldDue()
	{
		return $this->odue;
	}

	public function setOldDue($oldDue)
	{
		$this->odue = $oldDue;

		return $this;
	}

	public function getOldDeckId()
	{
		return $this->odid;
	}

	public function setOldDeckId($oldDeckId)
	{
		$this->odid = $oldDeckId;

		return $this;
	}

	public function getFlags()
	{
		return $this->flags;
	}

	public function setFlags($flags)
	{
		$this->flags = $flags;

		return $this;
	}

	public function getData()
	{
		return $this->data;
	}

	public function setData($data)
	{
		$this->data = $data;

		return $this;
	}
}