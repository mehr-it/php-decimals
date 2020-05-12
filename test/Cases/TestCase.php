<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 12.11.18
	 * Time: 14:37
	 */

	namespace MehrItPhpDecimalsTest\Cases;


	class TestCase extends \PHPUnit\Framework\TestCase
	{
		/**
		 * Executes the callback with given locale set temporarily
		 * @param string $locale The locale to set
		 * @param callable $callback The callback
		 */
		protected function withLocale($locale, $callback) {
			$currentLocale = setlocale(LC_ALL, 0);

			try {
				if (!@setLocale(LC_ALL, $locale))
					$this->markTestSkipped("Test skipped because locale $locale seams not to exist. Install locale using following commands and run test again:\n  sudo locale-gen $locale\n  sudo update-locale");

				call_user_func($callback);
			}
			finally {
				$this->setLocale(LC_ALL, $currentLocale);
			}
		}
	}