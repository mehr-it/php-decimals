<?php


	namespace MehrItPhpDecimalsTest\Cases\Unit;


	use MehrItPhpDecimalsTest\Cases\TestCase;
	use function MehrIt\PhpDecimals\expr;

	class HelperTest extends TestCase
	{

		public function testExpr() {
			$this->assertSame('79.65', expr('78.2', '+', '1.45'));
		}

	}