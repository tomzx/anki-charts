<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Grave extends Eloquent
{
	protected $table = 'graves';

	public function getUsn()
	{
		return $this->usn;
	}

	public function setUsn($usn)
	{
		$this->usn = $usn;

		return $this;
	}

	public function getOldId()
	{
		return $this->oid;
	}

	public function setOldId($oldId)
	{
		$this->oid = $oldId;

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