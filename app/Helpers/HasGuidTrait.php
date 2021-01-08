<?php

namespace App\Helpers;

trait HasGuidTrait
{
	/**
	 * Boot function from Laravel.
	 */
	protected static function boot()
	{
		parent::boot();

		static::creating(function ($event) {
			$event->guid = guid();
		});
	}

	/**
     * Get the route key for the model.
	 *
	 * @return string
	 */
	public function getRouteKeyName()
	{
		return 'guid';
	}
}