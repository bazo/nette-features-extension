<?php

namespace Bazo\FeatureToggler\DI;

use Bazo\FeatureToggler\Diagnostics\Panel;
use Bazo\FeatureToggler\Toggler;
use Nette\DI\CompilerExtension;


/**
 * @author Martin Bažík <martin@bazo.sk>
 */
class FeaturesExtension extends CompilerExtension
{

	/**
	 * @var array
	 */
	public $defaults = [
		'features' => [],
		'debugger' => '%debugMode%',
		'backend' => NULL
	];


	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config	 = $this->getConfig($this->defaults);

		if(is_null($config['backend'])) {
			$toggler = $builder->addDefinition($this->prefix('toggler'))
				->setClass(Toggler::class, [$config['features']]);
		} else {
			$toggler = $builder->addDefinition($this->prefix('toggler'))
				->setClass(\Bazo\FeatureToggler\BackendDrivenToggler::class, [$config['backend']]);
		}

		if ($config['debugger']) {
			$builder->addDefinition($this->prefix('panel'))
					->setClass(Panel::class);

			$toggler->addSetup($this->prefix('@panel') . '::register', ['@self']);
		}
	}





}
