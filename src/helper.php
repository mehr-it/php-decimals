<?php

	namespace MehrIt\PhpDecimals;


	/**
	 * Alias for Decimals::expr(). Evaluates the given expression. Expressions are only evaluated left to right. Expressions for which left to right evaluation is not logically correct will throw an exception
	 * @param mixed ...$args The expression operands and expression.
	 * @return bool|int|string The expression result.
	 */
	function expr(...$args) {
		return Decimals::expr(...$args);
	}
