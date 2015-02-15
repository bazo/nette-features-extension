<?php

namespace Bazo\FeatureToggler\Latte;

use Latte\CompileException;
use Latte\Compiler;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\PhpWriter;

/**
 * @author Martin Bažík <martin@bazo.sk>
 */
class FeaturesMacros extends MacroSet
{

	public static function install(Compiler $compiler)
	{
		$me = new static($compiler);
		$me->addMacro('ifEnabled', array($me, 'macroIfEnabled'), array($me, 'macroEndIfEnabled'));
		return $me;
	}


	/**
	 * {if ...}
	 */
	public function macroIfEnabled(MacroNode $node, PhpWriter $writer)
	{
		if ($node->data->capture = ($node->args === '')) {
			return 'ob_start()';
		}
		if ($node->prefix === $node::PREFIX_TAG) {
			return $writer->write($node->htmlNode->closing ? 'if (array_pop($_l->ifs)) {' : 'if ($_l->ifs[] = (%node.args)) {');
		}
		return $writer->write('if ($template->enabled(%node.args)) {');
	}


	/**
	 * {/if ...}
	 */
	public function macroEndIfEnabled(MacroNode $node, PhpWriter $writer)
	{
		if ($node->data->capture) {
			if ($node->args === '') {
				throw new CompileException('Missing condition in {if} macro.');
			}
			return $writer->write('if (%node.args) '
							. (isset($node->data->else) ? '{ ob_end_clean(); ob_end_flush(); }' : 'ob_end_flush();')
							. ' else '
							. (isset($node->data->else) ? '{ $_l->else = ob_get_contents(); ob_end_clean(); ob_end_clean(); echo $_l->else; }' : 'ob_end_clean();')
			);
		}
		return '}';
	}


}
