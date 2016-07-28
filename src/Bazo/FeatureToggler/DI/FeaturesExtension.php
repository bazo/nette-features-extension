<?php

namespace Bazo\FeatureToggler\DI;


use Bazo\FeatureToggler\BackendDrivenToggler;
use Bazo\FeatureToggler\Diagnostics\Panel;
use Bazo\FeatureToggler\Latte\FeaturesMacros;
use Bazo\FeatureToggler\TemplateHelpers;
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
		'backend' => NULL,
		'operators' => []
	];

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		if (is_null($config['backend'])) {
			$toggler = $builder->addDefinition($this->prefix('toggler'))
					->setClass(Toggler::class, [$config['features']]);
		} else {
			$toggler = $builder->addDefinition($this->prefix('toggler'))
					->setClass(BackendDrivenToggler::class, [$config['backend']]);
		}

		foreach ($config['operators'] as $operator) {
			$toggler->addSetup('registerOperator', [$operator]);
		}

		if ($config['debugger']) {
			$builder->addDefinition($this->prefix('panel'))
					->setClass(Panel::class);

			$toggler->addSetup($this->prefix('@panel') . '::register', ['@self']);
		}

		$builder->addDefinition($this->prefix('helpers'))
				->setClass(TemplateHelpers::class);

		if ($builder->hasDefinition('nette.latteFactory')) {
			$builder->getDefinition('nette.latteFactory')
					->addSetup(sprintf('?->onCompile[] = function($engine) { %s::install($engine->getCompiler()); }', FeaturesMacros::class), ['@self'])
					->addSetup('addFilter', ['enabled', [$this->prefix('@helpers'), 'enabled']])
			;
		}
	}


}
