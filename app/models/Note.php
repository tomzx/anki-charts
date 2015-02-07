<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Note extends Eloquent
{
	protected $table = 'notes';

	public function cards()
	{
		return $this->hasMany('Card', 'nid', 'id');
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

	public function getGuid()
	{
		return $this->guid;
	}

	public function setGuid($guid)
	{
		$this->guid = $guid;

		return $this;
	}

	public function getModelId()
	{
		return $this->mid;
	}

	public function setModelId($modelId)
	{
		$this->mid = $modelId;

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

	public function getTags()
	{
		return $this->tags;
	}

	public function setTags($tags)
	{
		$this->tags = $tags;

		return $this;
	}

	public function getFields()
	{
		return $this->flds;
	}

	public function setFields($fields)
	{
		$this->flds = $fields;

		return $this;
	}

	public function getSfld()
	{
		return $this->sfld;
	}

	public function setSfld($sfld)
	{
		$this->sfld = $sfld;

		return $this;
	}

	public function getChecksum()
	{
		return $this->csum;
	}

	public function setChecksum($checksum)
	{
		$this->csum = $checksum;

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