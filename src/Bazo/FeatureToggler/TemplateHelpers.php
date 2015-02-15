<?php

namespace Bazo\FeatureToggler;


use Bazo\FeatureToggler\Toggler;

/**
 * @author Martin Bažík <martin@bazo.sk>
 */
class TemplateHelpers
{

	/** @var Toggler */
	private $toggler;

	public function __construct(Toggler $toggler)
	{
		$this->toggler = $toggler;
	}


	public function enabled($feature, $context = [])
	{
		return $this->toggler->enabled($feature, $context);
	}


}
