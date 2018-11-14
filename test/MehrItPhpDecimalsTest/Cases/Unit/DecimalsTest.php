<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 12.11.18
	 * Time: 14:36
	 */

	namespace MehrItPhpDecimalsTest\Cases\Unit;

	use MehrIt\PhpDecimals\Decimals;
	use MehrItPhpDecimalsTest\Cases\TestCase;


	class DecimalsTest extends TestCase
	{
		public function testNorm() {
			$this->assertSame('0.123', Decimals::norm('+0.123'));
			$this->assertSame('0.123', Decimals::norm('0.123'));
			$this->assertSame('-0.123', Decimals::norm('-0.123'));
			$this->assertSame('123.123', Decimals::norm('+123.123'));
			$this->assertSame('123.123', Decimals::norm('123.123'));
			$this->assertSame('-123.123', Decimals::norm('-123.123'));
			$this->assertSame('0.123', Decimals::norm('+0.1230'));
			$this->assertSame('0.123', Decimals::norm('0.1230'));
			$this->assertSame('-0.123', Decimals::norm('-0.1230'));
			$this->assertSame('123.123', Decimals::norm('+123.1230'));
			$this->assertSame('123.123', Decimals::norm('123.1230'));
			$this->assertSame('-123.123', Decimals::norm('-123.1230'));
			$this->assertSame('123', Decimals::norm('+123.0'));
			$this->assertSame('123', Decimals::norm('123.0'));
			$this->assertSame('-123', Decimals::norm('-123.0'));
			$this->assertSame('123', Decimals::norm('+123.'));
			$this->assertSame('123', Decimals::norm('123.'));
			$this->assertSame('-123', Decimals::norm('-123.'));
			$this->assertSame('0', Decimals::norm('+0'));
			$this->assertSame('0', Decimals::norm('0'));
			$this->assertSame('0', Decimals::norm('-0'));
			$this->assertSame('0', Decimals::norm('+0.0'));
			$this->assertSame('0', Decimals::norm('0.0'));
			$this->assertSame('0', Decimals::norm('-0.0'));
			$this->assertSame('0', Decimals::norm('.'));
			$this->assertSame('0.45', Decimals::norm('+.45'));
			$this->assertSame('0.45', Decimals::norm('.45'));
			$this->assertSame('-0.45', Decimals::norm('-.45'));
			$this->assertSame('0.4', Decimals::norm('.4'));
			$this->assertSame('-0.4', Decimals::norm('-.4'));
			$this->assertSame('0', Decimals::norm(''));
			$this->assertSame('-10', Decimals::norm('-10'));
			$this->assertSame('-20', Decimals::norm('-20'));
			$this->assertSame('20', Decimals::norm('20.0'));
			$this->assertSame('20', Decimals::norm('00020.0'));
			$this->assertSame('20.01', Decimals::norm('20.0100'));
			$this->assertSame('0.0123', Decimals::norm('.0123'));
			$this->assertSame('0.0123', Decimals::norm('0.0123'));
			$this->assertSame('0.0123', Decimals::norm('00.0123'));
		}

		public function testAbs() {
			$this->assertSame('123123123123.234234', Decimals::abs('123123123123.234234'));
			$this->assertSame('123123123123.234234', Decimals::abs('-123123123123.234234'));
			$this->assertSame('123123123123', Decimals::abs('123123123123'));
			$this->assertSame('123123123123', Decimals::abs('-123123123123'));
			$this->assertSame('0.123', Decimals::abs('0.123'));
			$this->assertSame('0.123', Decimals::abs('-0.123'));
			$this->assertSame('0', Decimals::abs('0'));
			$this->assertSame('0', Decimals::abs('0.0'));
		}

		public function testDecimals() {
			$this->assertSame(3, Decimals::decimals('123.455'));
			$this->assertSame(3, Decimals::decimals('123.400'));
			$this->assertSame(3, Decimals::decimals('-123.455'));
			$this->assertSame(3, Decimals::decimals('-123.400'));

			$this->assertSame(1, Decimals::decimals('123.4'));
			$this->assertSame(1, Decimals::decimals('123.0'));
			$this->assertSame(1, Decimals::decimals('-123.4'));
			$this->assertSame(1, Decimals::decimals('-123.0'));

			$this->assertSame(0, Decimals::decimals('123'));
			$this->assertSame(0, Decimals::decimals('123.'));
			$this->assertSame(0, Decimals::decimals('-123'));
			$this->assertSame(0, Decimals::decimals('-123.'));
		}

		public function testTruncate() {

			// positive
			$this->assertSame('123.45', Decimals::truncate('123.4567', 2));
			$this->assertSame('123.44', Decimals::truncate('123.444', 2));
			$this->assertSame('123.4', Decimals::truncate('123.4567', 1));
			$this->assertSame('123.4', Decimals::truncate('123.445', 1));
			$this->assertSame('123', Decimals::truncate('123.5', 0));
			$this->assertSame('123', Decimals::truncate('123.4', 0));
			$this->assertSame('123', Decimals::truncate('123', 0));
			$this->assertSame('123', Decimals::truncate('123', 0));
			$this->assertSame('123', Decimals::truncate('123', 5));
			$this->assertSame('123', Decimals::truncate('123', 5));
			$this->assertSame('123', Decimals::truncate('123.00', 5));
			$this->assertSame('123', Decimals::truncate('123.00', 5));


			// negative
			$this->assertSame('-123.45', Decimals::truncate('-123.4567', 2));
			$this->assertSame('-123.44', Decimals::truncate('-123.444', 2));
			$this->assertSame('-123.4', Decimals::truncate('-123.4567', 1));
			$this->assertSame('-123.4', Decimals::truncate('-123.445', 1));
			$this->assertSame('-123', Decimals::truncate('-123.5', 0));
			$this->assertSame('-123', Decimals::truncate('-123.4', 0));
			$this->assertSame('-123', Decimals::truncate('-123', 0));
			$this->assertSame('-123', Decimals::truncate('-123', 0));
			$this->assertSame('-123', Decimals::truncate('-123', 5));
			$this->assertSame('-123', Decimals::truncate('-123', 5));
			$this->assertSame('-123', Decimals::truncate('-123.00', 5));
			$this->assertSame('-123', Decimals::truncate('-123.00', 5));

		}

		public function testRound_halfUp() {

			// positive
			$this->assertSame('123.46', Decimals::round('123.4567', 2));
			$this->assertSame('123.44', Decimals::round('123.444', 2));
			$this->assertSame('123.5', Decimals::round('123.4567', 1));
			$this->assertSame('123.4', Decimals::round('123.445', 1));
			$this->assertSame('124', Decimals::round('123.5', 0));
			$this->assertSame('123', Decimals::round('123.4', 0));
			$this->assertSame('123', Decimals::round('123', 0));
			$this->assertSame('123', Decimals::round('123', 0));
			$this->assertSame('123', Decimals::round('123', 5));
			$this->assertSame('123', Decimals::round('123', 5));
			$this->assertSame('123', Decimals::round('123.00', 5));
			$this->assertSame('123', Decimals::round('123.00', 5));


			// negative
			$this->assertSame('-123.46', Decimals::round('-123.4567', 2));
			$this->assertSame('-123.44', Decimals::round('-123.444', 2));
			$this->assertSame('-123.5', Decimals::round('-123.4567', 1));
			$this->assertSame('-123.4', Decimals::round('-123.445', 1));
			$this->assertSame('-124', Decimals::round('-123.5', 0));
			$this->assertSame('-123', Decimals::round('-123.4', 0));
			$this->assertSame('-123', Decimals::round('-123', 0));
			$this->assertSame('-123', Decimals::round('-123', 0));
			$this->assertSame('-123', Decimals::round('-123', 5));
			$this->assertSame('-123', Decimals::round('-123', 5));
			$this->assertSame('-123', Decimals::round('-123.00', 5));
			$this->assertSame('-123', Decimals::round('-123.00', 5));

		}

		public function testRound_halfDown() {

			// positive
			$this->assertSame('123.46', Decimals::round('123.4567', 2, PHP_ROUND_HALF_DOWN));
			$this->assertSame('123.45', Decimals::round('123.4557', 2, PHP_ROUND_HALF_DOWN));
			$this->assertSame('123.44', Decimals::round('123.444', 2, PHP_ROUND_HALF_DOWN));
			$this->assertSame('123.5', Decimals::round('123.4667', 1, PHP_ROUND_HALF_DOWN));
			$this->assertSame('123.4', Decimals::round('123.4567', 1, PHP_ROUND_HALF_DOWN));
			$this->assertSame('123.4', Decimals::round('123.445', 1, PHP_ROUND_HALF_DOWN));
			$this->assertSame('124', Decimals::round('123.6', 0, PHP_ROUND_HALF_DOWN));
			$this->assertSame('123', Decimals::round('123.5', 0, PHP_ROUND_HALF_DOWN));
			$this->assertSame('123', Decimals::round('123.4', 0, PHP_ROUND_HALF_DOWN));
			$this->assertSame('123', Decimals::round('123', 0, PHP_ROUND_HALF_DOWN));
			$this->assertSame('123', Decimals::round('123', 0, PHP_ROUND_HALF_DOWN));
			$this->assertSame('123', Decimals::round('123', 5, PHP_ROUND_HALF_DOWN));
			$this->assertSame('123', Decimals::round('123', 5, PHP_ROUND_HALF_DOWN));
			$this->assertSame('123', Decimals::round('123.00', 5, PHP_ROUND_HALF_DOWN));
			$this->assertSame('123', Decimals::round('123.00', 5, PHP_ROUND_HALF_DOWN));


			// negative
			$this->assertSame('-123.46', Decimals::round('-123.4567', 2, PHP_ROUND_HALF_DOWN));
			$this->assertSame('-123.45', Decimals::round('-123.4557', 2, PHP_ROUND_HALF_DOWN));
			$this->assertSame('-123.44', Decimals::round('-123.444', 2, PHP_ROUND_HALF_DOWN));
			$this->assertSame('-123.5', Decimals::round('-123.4667', 1, PHP_ROUND_HALF_DOWN));
			$this->assertSame('-123.4', Decimals::round('-123.4567', 1, PHP_ROUND_HALF_DOWN));
			$this->assertSame('-123.4', Decimals::round('-123.445', 1, PHP_ROUND_HALF_DOWN));
			$this->assertSame('-124', Decimals::round('-123.6', 0, PHP_ROUND_HALF_DOWN));
			$this->assertSame('-123', Decimals::round('-123.5', 0, PHP_ROUND_HALF_DOWN));
			$this->assertSame('-123', Decimals::round('-123.4', 0, PHP_ROUND_HALF_DOWN));
			$this->assertSame('-123', Decimals::round('-123', 0, PHP_ROUND_HALF_DOWN));
			$this->assertSame('-123', Decimals::round('-123', 0, PHP_ROUND_HALF_DOWN));
			$this->assertSame('-123', Decimals::round('-123', 5, PHP_ROUND_HALF_DOWN));
			$this->assertSame('-123', Decimals::round('-123', 5, PHP_ROUND_HALF_DOWN));
			$this->assertSame('-123', Decimals::round('-123.00', 5, PHP_ROUND_HALF_DOWN));
			$this->assertSame('-123', Decimals::round('-123.00', 5, PHP_ROUND_HALF_DOWN));
		}

		public function testRound_NegativePrecision() {

			$this->expectException(\InvalidArgumentException::class);

			Decimals::round('123', -1);

		}

		public function testRound_InvalidRoundMode() {

			$this->expectException(\InvalidArgumentException::class);

			Decimals::round('123', 0, PHP_ROUND_HALF_EVEN);

		}

		public function testParse() {


			$this->assertSame('123', Decimals::parse('+123'));
			$this->assertSame('123', Decimals::parse('123'));
			$this->assertSame('-123', Decimals::parse('-123'));

			$this->assertSame('123', Decimals::parse('+0123'));
			$this->assertSame('123', Decimals::parse('0123'));
			$this->assertSame('-123', Decimals::parse('-0123'));

			$this->assertSame('123.45', Decimals::parse('+00123.45'));
			$this->assertSame('123.45', Decimals::parse('00123.45'));
			$this->assertSame('-123.45', Decimals::parse('-00123.45'));
			$this->assertSame('123.45', Decimals::parse('+00123,45'));
			$this->assertSame('123.45', Decimals::parse('00123,45'));
			$this->assertSame('-123.45', Decimals::parse('-00123,45'));

			$this->assertSame('123.45', Decimals::parse('+123.45'));
			$this->assertSame('123.45', Decimals::parse('123.45'));
			$this->assertSame('-123.45', Decimals::parse('-123.45'));
			$this->assertSame('123.45', Decimals::parse('+123,45'));
			$this->assertSame('123.45', Decimals::parse('123,45'));
			$this->assertSame('-123.45', Decimals::parse('-123,45'));

			$this->assertSame('123.45', Decimals::parse('+123.450'));
			$this->assertSame('123.45', Decimals::parse('123.450'));
			$this->assertSame('-123.45', Decimals::parse('-123.450'));
			$this->assertSame('123.45', Decimals::parse('+123,450'));
			$this->assertSame('123.45', Decimals::parse('123,450'));
			$this->assertSame('-123.45', Decimals::parse('-123,450'));

			$this->assertSame('0.45', Decimals::parse('+0.45'));
			$this->assertSame('0.45', Decimals::parse('0.45'));
			$this->assertSame('-0.45', Decimals::parse('-0.45'));
			$this->assertSame('0.45', Decimals::parse('+0,45'));
			$this->assertSame('0.45', Decimals::parse('0,45'));
			$this->assertSame('-0.45', Decimals::parse('-0,45'));

			$this->assertSame('0', Decimals::parse('+0.0'));
			$this->assertSame('0', Decimals::parse('0.0'));
			$this->assertSame('0', Decimals::parse('-0.0'));
			$this->assertSame('0', Decimals::parse('+0,0'));
			$this->assertSame('0', Decimals::parse('0,0'));
			$this->assertSame('0', Decimals::parse('-0,0'));

			$this->assertSame('0', Decimals::parse('+0.'));
			$this->assertSame('0', Decimals::parse('0.'));
			$this->assertSame('0', Decimals::parse('-0.'));
			$this->assertSame('0', Decimals::parse('+0,'));
			$this->assertSame('0', Decimals::parse('0,'));
			$this->assertSame('0', Decimals::parse('-0,'));

			$this->assertSame('0.4', Decimals::parse('+.4'));
			$this->assertSame('0.4', Decimals::parse('.4'));
			$this->assertSame('-0.4', Decimals::parse('-.4'));
			$this->assertSame('0.4', Decimals::parse('+,4'));
			$this->assertSame('0.4', Decimals::parse(',4'));
			$this->assertSame('-0.4', Decimals::parse('-,4'));

		}

		public function testParse_InvalidNumber_notNumeric() {

			$this->expectException(\InvalidArgumentException::class);


			Decimals::parse('12a3');
		}

		public function testParse_InvalidNumber_MinusDouble() {

			$this->expectException(\InvalidArgumentException::class);


			Decimals::parse('--123');
		}

		public function testParse_InvalidNumber_PlusDouble() {

			$this->expectException(\InvalidArgumentException::class);


			Decimals::parse('++123');
		}

		public function testParse_InvalidNumber_DoubleDecimal() {

			$this->expectException(\InvalidArgumentException::class);


			Decimals::parse('12.23.2');
		}

		public function testParse_InvalidNumber_DoubleDecimalComma() {

			$this->expectException(\InvalidArgumentException::class);


			Decimals::parse('12,23,2');
		}

		public function testParse_InvalidNumber_ThousandsSep() {

			$this->expectException(\InvalidArgumentException::class);


			Decimals::parse('12,223.2');
		}

		public function testParse_InvalidNumber_ThousandsSepDot() {

			$this->expectException(\InvalidArgumentException::class);


			Decimals::parse('12.223,2');
		}

		public function testParse_Float_en() {
			$this->withLocale('en_US.UTF-8', function () {
				$this->assertSame('123.45', Decimals::parse((string)123.45));
			});
		}

		public function testParse_Float_de() {
			$this->withLocale('de_DE.UTF-8', function () {
				$this->assertSame('123.45', Decimals::parse((string)123.45));
			});
		}

		public function testToNative_en() {
			$this->withLocale('en_US.UTF-8', function () {
				$this->assertSame(123.45, Decimals::toNative('123.45'));
				$this->assertSame(123, Decimals::toNative('123'));
			});

		}

		public function testToNative_de() {
			$this->withLocale('de_DE.UTF-8', function () {
				$this->assertSame(123.45, Decimals::toNative('123.45'));
				$this->assertSame(123, Decimals::toNative('123'));
			});

		}

		public function testAdd() {
			$this->assertSame('3', Decimals::add('1', '2'));
			$this->assertSame('3', Decimals::add('1.0', '2.0'));
			$this->assertSame('3', Decimals::add('1.23', '2.45', 0));
			$this->assertSame('3.68', Decimals::add('1.23', '2.45'));
			$this->assertSame('3.63', Decimals::add('1.23', '2.4'));
			$this->assertSame('3.65', Decimals::add('1.2', '2.45'));
			$this->assertSame('3.6', Decimals::add('1.23', '2.45', 1));
			$this->assertSame('-1.22', Decimals::add('1.23', '-2.45'));
			$this->assertSame('1.22', Decimals::add('-1.23', '2.45'));
			$this->assertSame('3.63', Decimals::add('1.23', '2.40'));
		}

		public function testSub() {
			$this->assertSame('2', Decimals::sub('4', '2'));
			$this->assertSame('2', Decimals::sub('4.0', '2.0'));
			$this->assertSame('1', Decimals::sub('4.23', '2.45', 0));
			$this->assertSame('1.23', Decimals::sub('3.68', '2.45'));
			$this->assertSame('1.23', Decimals::sub('3.63', '2.4'));
			$this->assertSame('1.2', Decimals::sub('3.65', '2.45'));
			$this->assertSame('1.1', Decimals::sub('3.6', '2.45', 1));
			$this->assertSame('1.23', Decimals::sub('-1.22', '-2.45'));
			$this->assertSame('-1.23', Decimals::sub('1.22', '2.45'));
			$this->assertSame('1.23', Decimals::sub('3.63', '2.40'));
		}
	}