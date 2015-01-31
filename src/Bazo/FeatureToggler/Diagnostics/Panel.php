<?php

namespace Bazo\FeatureToggler\Diagnostics;


use Bazo\FeatureToggler\Toggler;
use Nette;
use Nette\Utils\Html;
use Tracy\Debugger;
use Tracy\IBarPanel;

/**
 * @author Martin Bažík <martin@bazo.sk>
 */
class Panel extends Nette\Object implements IBarPanel
{

	/**
	 * @var Toggler
	 */
	private $toggler;

	/** @var array */
	private $evaluatedFeatures = [];

	/**
	 * Renders HTML code for custom tab.
	 *
	 * @return string
	 */
	public function getTab()
	{
		$img = Html::el('img', ['height' => '18px'])
				->src('data:image/svg+xml;base64,' . base64_encode(file_get_contents(__DIR__ . '/logo.svg')));
		$tab = Html::el('span')->title('Features')->add($img);

		$features = $this->toggler->getFeatures();

		$title = Html::el()->setText(sprintf('Features (%d)', count($features)));


		return (string) $tab->add($title);
	}


	/**
	 * @return string
	 */
	public function getPanel()
	{

		dump($this->evaluatedFeatures);exit;

		if (empty($this->evaluatedFeatures)) {
			return NULL;
		}

dump($this->evaluatedFeatures);exit;

		ob_start();

		require __DIR__ . '/panel.phtml';

		return ob_get_clean();
	}


	/**
	 * @param Toggler $toggler
	 */
	public function register(Toggler $toggler)
	{
		$this->toggler						 = $toggler;
		$this->toggler->onFeatureEvaluated[] = function($feature, $context, $result) {
			$this->evaluatedFeatures[$feature] = ['context' => $context, 'result' => $result];
		};
		Debugger::getBar()->addPanel($this);
	}


}