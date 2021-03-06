<?php

namespace Bazo\FeatureToggler\Diagnostics;


use Bazo\FeatureToggler\Toggler;
use Nette\Utils\Html;
use Tracy\Debugger;
use Tracy\IBarPanel;

/**
 * @author Martin Bažík <martin@bazo.sk>
 */
class Panel implements IBarPanel
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
		$img = Html::el('img', ['height' => 16, 'width' => 16])
		           ->src('data:image/svg+xml;base64,' . base64_encode(file_get_contents(__DIR__ . '/logo.svg')))
		;
		$tab = Html::el('span')->title('Features')->addHtml($img);

		$features = $this->toggler->getFeatures();

		$title = Html::el()->setText(sprintf('Features (%d)', count($features)));


		return (string)$tab->addHtml($title);
	}


	/**
	 * @return string
	 */
	public function getPanel()
	{
		if (empty($this->evaluatedFeatures)) {
			return NULL;
		}

		$evaluatedFeatures = $this->evaluatedFeatures;
		$click             = class_exists('\Tracy\Dumper') ? function ($o, $c = TRUE, $d = 4) {
			return \Tracy\Dumper::toHtml($o, ['collapse' => $c, 'depth' => $d]);
		} : callback('\Tracy\Helpers::clickableDump');
		ob_start();

		require __DIR__ . '/panel.phtml';

		return ob_get_clean();
	}


	/**
	 * @param Toggler $toggler
	 */
	public function register(Toggler $toggler)
	{
		$this->toggler                       = $toggler;
		$this->toggler->onFeatureEvaluated[] = function ($feature, $context, $conditions, $result) {
			$this->evaluatedFeatures[] = ['feature' => $feature, 'context' => $context, 'conditions' => $conditions, 'result' => $result];
		};
		Debugger::getBar()->addPanel($this);
	}


}
