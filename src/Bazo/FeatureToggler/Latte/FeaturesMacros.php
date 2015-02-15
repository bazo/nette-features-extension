<?php

use Bazo\FeatureToggler\Toggler;

/**
 * @author Martin Bažík <martin@bazo.sk>
 */
class FeaturesMacros
{

	/** @var Toggler */
	private $toggler;

	public function __construct(Toggler $toggler)
	{
		$this->toggler = $toggler;
	}


}
