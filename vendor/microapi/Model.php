<?php
namespace MicroAPI;

class Model
{
	/**
	 * An array represenation of the model properties, including private.
	 *
	 * @return An array represenation of the model
	 */
	public function toArray()
	{
		return get_object_vars($this);
	}
}