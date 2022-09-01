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
			$this->assertSame('0', Decimals::parse('0'));

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

		public function testParse_emptyString() {

			$this->expectException(\InvalidArgumentException::class);

			Decimals::parse('');
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

		public function testParse_InvalidNumber_dotButOtherDecimalChar() {

			$this->expectException(\InvalidArgumentException::class);


			Decimals::parse('12.223', ',');
		}

		public function testParse_InvalidNumber_dotTogetherWithOtherDecimalChar() {

			$this->expectException(\InvalidArgumentException::class);


			Decimals::parse('12.223,45', ',');
		}

		public function testParse_Float_en() {
			$this->withLocale('en_US.UTF-8', function () {
				$this->assertSame('123.45', Decimals::parse((string)123.45));
				$this->assertSame('123.45', Decimals::parse(123.45));
				$this->assertSame('0', Decimals::parse((string)0.0));
				$this->assertSame('0', Decimals::parse(0.0));
			});
		}

		public function testParse_Float_de() {
			$this->withLocale('de_DE.UTF-8', function () {
				$this->assertSame('123.45', Decimals::parse((string)123.45));
				$this->assertSame('123.45', Decimals::parse(123.45));
				$this->assertSame('0', Decimals::parse((string)0.0));
				$this->assertSame('0', Decimals::parse(0.0));
			});
		}

		public function testParse_Int() {
			$this->withLocale('de_DE.UTF-8', function () {
				$this->assertSame('123', Decimals::parse((string)123));
				$this->assertSame('123', Decimals::parse(123));
				$this->assertSame('0', Decimals::parse((string)0));
				$this->assertSame('0', Decimals::parse(0));
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
			$this->assertSame('3', Decimals::add('01', '02'));
			$this->assertSame('3', Decimals::add('1.0', '2.0'));
			$this->assertSame('3', Decimals::add('1.23', '2.45', 0));
			$this->assertSame('3.68', Decimals::add('1.23', '2.45'));
			$this->assertSame('3.63', Decimals::add('1.23', '2.4'));
			$this->assertSame('3.65', Decimals::add('1.2', '2.45'));
			$this->assertSame('3.6', Decimals::add('1.23', '2.45', 1));
			$this->assertSame('-1.22', Decimals::add('1.23', '-2.45'));
			$this->assertSame('1.22', Decimals::add('-1.23', '2.45'));
			$this->assertSame('3.63', Decimals::add('1.23', '2.40'));
			$this->assertSame('300', Decimals::add('100', '200'));
			$this->assertSame('-300', Decimals::add('-100', '-200'));
			$this->assertSame('3.05', Decimals::add('1.05', '2'));
			$this->assertSame('0', Decimals::add('-1.5', '1.5'));
		}

		public function testSub() {
			$this->assertSame('2', Decimals::sub('4', '2'));
			$this->assertSame('2', Decimals::sub('04', '02'));
			$this->assertSame('2', Decimals::sub('4.0', '2.0'));
			$this->assertSame('1', Decimals::sub('4.23', '2.45', 0));
			$this->assertSame('1.23', Decimals::sub('3.68', '2.45'));
			$this->assertSame('1.23', Decimals::sub('3.63', '2.4'));
			$this->assertSame('1.2', Decimals::sub('3.65', '2.45'));
			$this->assertSame('1.1', Decimals::sub('3.6', '2.45', 1));
			$this->assertSame('1.23', Decimals::sub('-1.22', '-2.45'));
			$this->assertSame('-1.23', Decimals::sub('1.22', '2.45'));
			$this->assertSame('1.23', Decimals::sub('3.63', '2.40'));
			$this->assertSame('-100', Decimals::sub('100', '200'));
			$this->assertSame('100', Decimals::sub('-100', '-200'));
			$this->assertSame('-0.95', Decimals::sub('1.05', '2'));
			$this->assertSame('0', Decimals::sub('1.5', '1.5'));
		}

		public function testMul() {
			$this->assertSame('8', Decimals::mul('2', '4'));
			$this->assertSame('1.5', Decimals::mul('0.5', '3'));
			$this->assertSame('4.5', Decimals::mul('3', '1.5'));
			$this->assertSame('-10', Decimals::mul('-5', '2'));
			$this->assertSame('10', Decimals::mul('-5', '-2'));
			$this->assertSame('0', Decimals::mul('0', '-2'));
			$this->assertSame('0', Decimals::mul('0', '2'));
			$this->assertSame('0.33333333', Decimals::mul('1', '0.33333333'));
		}

		public function testDiv() {
			$this->assertSame('0.5', Decimals::div('2', '4'));
			$this->assertSame('2', Decimals::div('4', '2'));
			$this->assertSame('2', Decimals::div('5', '2.5'));
			$this->assertSame('0.5', Decimals::div('2.5', '5'));
			$this->assertSame('-1.25', Decimals::div('-2.5', '2'));
			$this->assertSame('1.25', Decimals::div('-2.5', '-2'));
			$this->assertSame('0', Decimals::div('0', '-2'));
			$this->assertSame('0', Decimals::div('0', '1.5'));

			$this->assertSame('0.' . str_repeat('3', Decimals::MUL_DEFAULT_SCALE), Decimals::div('1', '3'));
			$this->assertSame('0.333', Decimals::div('1', '3', 3));
		}

		public function testDiv_byZero() {
			$this->expectException(\DivisionByZeroError::class);

			Decimals::div('1', '0');
		}

		public function testMod() {
			$this->assertSame('9', Decimals::mod('99', '10'));
			$this->assertSame('9.5', Decimals::mod('99.5', '10'));
			$this->assertSame('1.6', Decimals::mod('6.6', '2.5'));
			$this->assertSame('-1.6', Decimals::mod('-6.6', '2.5'));
			$this->assertSame('0', Decimals::mod('0', '2.5'));
			$this->assertSame('0', Decimals::mod('2.5', '2.5'));
		}

		public function testMod_byZero() {
			$this->expectException(\DivisionByZeroError::class);

			Decimals::mod('1', '0');
		}

		public function testPow() {
			$this->assertSame('100', Decimals::pow('10', '2'));
			$this->assertSame('27', Decimals::pow('3', '3'));
			$this->assertSame('2.25', Decimals::pow('1.5', '2'));
			$this->assertSame('2.25', Decimals::pow('-1.5', '2'));
			$this->assertSame('1', Decimals::pow('9', '0'));
		}

		public function testPow_fractional() {

			$this->expectException(\InvalidArgumentException::class);

			Decimals::pow('9', '0.5');
		}

		public function testComp() {
			$this->assertSame(-1, Decimals::comp('2', '4'));
			$this->assertSame(-1, Decimals::comp('2.5', '4.5'));
			$this->assertSame(-1, Decimals::comp('4', '4.5'));
			$this->assertSame(1, Decimals::comp('4', '2'));
			$this->assertSame(1, Decimals::comp('4.5', '2.5'));
			$this->assertSame(1, Decimals::comp('4.5', '4'));
			$this->assertSame(0, Decimals::comp('4', '4'));
			$this->assertSame(-1, Decimals::comp('-4', '4'));
			$this->assertSame(1, Decimals::comp('4', '-4'));
			$this->assertSame(0, Decimals::comp('0', '0'));
			$this->assertSame(0, Decimals::comp('0.5', '0.5'));
			$this->assertSame(0, Decimals::comp('-0.5', '-0.5'));

		}

		public function testIsEqual() {
			$this->assertSame(true, Decimals::isEqual('2', '2'));
			$this->assertSame(true, Decimals::isEqual('02', '2'));
			$this->assertSame(true, Decimals::isEqual('2', '2.00'));
			$this->assertSame(false, Decimals::isEqual('2', '1'));
			$this->assertSame(false, Decimals::isEqual('2', '-2'));
			$this->assertSame(false, Decimals::isEqual('2', '2.01'));
			$this->assertSame(true, Decimals::isEqual('2', '2.01', 1));

		}

		public function testIsNotEqual() {
			$this->assertSame(!true, Decimals::isNotEqual('2', '2'));
			$this->assertSame(!true, Decimals::isNotEqual('02', '2'));
			$this->assertSame(!true, Decimals::isNotEqual('2', '2.00'));
			$this->assertSame(!false, Decimals::isNotEqual('2', '1'));
			$this->assertSame(!false, Decimals::isNotEqual('2', '-2'));
			$this->assertSame(!false, Decimals::isNotEqual('2', '2.01'));
			$this->assertSame(!true, Decimals::isNotEqual('2', '2.01', 1));

		}
		
		public function testIsGreaterThan() {
			$this->assertSame(false, Decimals::isGreaterThan('2', '4'));
			$this->assertSame(false, Decimals::isGreaterThan('2.5', '4.5'));
			$this->assertSame(false, Decimals::isGreaterThan('4', '4.5'));
			$this->assertSame(true, Decimals::isGreaterThan('4', '2'));
			$this->assertSame(true, Decimals::isGreaterThan('4.5', '2.5'));
			$this->assertSame(true, Decimals::isGreaterThan('4.5', '4'));
			$this->assertSame(false, Decimals::isGreaterThan('4', '4'));
			$this->assertSame(false, Decimals::isGreaterThan('-4', '4'));
			$this->assertSame(true, Decimals::isGreaterThan('4', '-4'));
			$this->assertSame(false, Decimals::isGreaterThan('0', '0'));
			$this->assertSame(false, Decimals::isGreaterThan('0.5', '0.5'));
			$this->assertSame(false, Decimals::isGreaterThan('-0.5', '-0.5'));

		}
		
		public function testIsGreaterThanOrEqual() {
			$this->assertSame(false, Decimals::isGreaterThanOrEqual('2', '4'));
			$this->assertSame(false, Decimals::isGreaterThanOrEqual('2.5', '4.5'));
			$this->assertSame(false, Decimals::isGreaterThanOrEqual('4', '4.5'));
			$this->assertSame(true, Decimals::isGreaterThanOrEqual('4', '2'));
			$this->assertSame(true, Decimals::isGreaterThanOrEqual('4.5', '2.5'));
			$this->assertSame(true, Decimals::isGreaterThanOrEqual('4.5', '4'));
			$this->assertSame(true, Decimals::isGreaterThanOrEqual('4', '4'));
			$this->assertSame(false, Decimals::isGreaterThanOrEqual('-4', '4'));
			$this->assertSame(true, Decimals::isGreaterThanOrEqual('4', '-4'));
			$this->assertSame(true, Decimals::isGreaterThanOrEqual('0', '0'));
			$this->assertSame(true, Decimals::isGreaterThanOrEqual('0.5', '0.5'));
			$this->assertSame(true, Decimals::isGreaterThanOrEqual('-0.5', '-0.5'));

		}

		public function testIsLessThan() {
			$this->assertSame(true, Decimals::isLessThan('2', '4'));
			$this->assertSame(true, Decimals::isLessThan('2.5', '4.5'));
			$this->assertSame(true, Decimals::isLessThan('4', '4.5'));
			$this->assertSame(false, Decimals::isLessThan('4', '2'));
			$this->assertSame(false, Decimals::isLessThan('4.5', '2.5'));
			$this->assertSame(false, Decimals::isLessThan('4.5', '4'));
			$this->assertSame(false, Decimals::isLessThan('4', '4'));
			$this->assertSame(true, Decimals::isLessThan('-4', '4'));
			$this->assertSame(false, Decimals::isLessThan('4', '-4'));
			$this->assertSame(false, Decimals::isLessThan('0', '0'));
			$this->assertSame(false, Decimals::isLessThan('0.5', '0.5'));
			$this->assertSame(false, Decimals::isLessThan('-0.5', '-0.5'));

		}

		public function testIsLessThanOrEqual() {
			$this->assertSame(true, Decimals::isLessThanOrEqual('2', '4'));
			$this->assertSame(true, Decimals::isLessThanOrEqual('2.5', '4.5'));
			$this->assertSame(true, Decimals::isLessThanOrEqual('4', '4.5'));
			$this->assertSame(false, Decimals::isLessThanOrEqual('4', '2'));
			$this->assertSame(false, Decimals::isLessThanOrEqual('4.5', '2.5'));
			$this->assertSame(false, Decimals::isLessThanOrEqual('4.5', '4'));
			$this->assertSame(true, Decimals::isLessThanOrEqual('4', '4'));
			$this->assertSame(true, Decimals::isLessThanOrEqual('-4', '4'));
			$this->assertSame(false, Decimals::isLessThanOrEqual('4', '-4'));
			$this->assertSame(true, Decimals::isLessThanOrEqual('0', '0'));
			$this->assertSame(true, Decimals::isLessThanOrEqual('0.5', '0.5'));
			$this->assertSame(true, Decimals::isLessThanOrEqual('-0.5', '-0.5'));

		}

		public function testMax() {

			$this->assertSame('1.45', Decimals::max('-2', '0', '1.45', '1.4', '-1.45'));
			$this->assertSame('1.45', Decimals::max('0', '1.45'));
			$this->assertSame('1.45', Decimals::max('1.45', '1.45'));
			$this->assertSame('1.45', Decimals::max('1.45'));
			$this->assertSame('0', Decimals::max());
		}
		
		public function testMin() {

			$this->assertSame('-1.45', Decimals::min('-1', '0', '1.45', '1.4', '-1.45'));
			$this->assertSame('-1.45', Decimals::min('0', '-1.45'));
			$this->assertSame('-1.45', Decimals::min('-1.45', '-1.45'));
			$this->assertSame('-1.45', Decimals::min('-1.45'));
			$this->assertSame('0', Decimals::min());
		}
		
		public function testSum() {

			$this->assertSame('0.4', Decimals::sum('-1', '0', '1.45', '1.4', '-1.45'));
			$this->assertSame('8.55', Decimals::sum('0', '-1.45', '10'));
			$this->assertSame('-2.9', Decimals::sum('-1.45', '-1.45'));
			$this->assertSame('1.45', Decimals::sum('1.45'));
			$this->assertSame('0', Decimals::sum());
		}

		public function testExpr() {

			$this->assertSame('78.2',  Decimals::expr('78.2'));

			$this->assertSame('79.65',  Decimals::expr('78.2', '+', '1.45'));
			$this->assertSame('79.66',  Decimals::expr('78.2', '+', '1.45', '+', '0.01'));
			$this->assertSame('79.64',  Decimals::expr('78.2', '+', '1.45', '-', '0.01'));
			$this->assertSame(true,  Decimals::expr('78.2', '+', '1.45', '<', '79.66'));
			$this->assertSame(true,  Decimals::expr('78.2', '+', '1.45', '<=', '79.66'));
			$this->assertSame(true,  Decimals::expr('78.2', '+', '1.45', '=', '79.65'));
			$this->assertSame(true,  Decimals::expr('78.2', '+', '1.45', '==', '79.65'));
			$this->assertSame(true,  Decimals::expr('78.2', '+', '1.45', '>=', '79.64'));
			$this->assertSame(true,  Decimals::expr('78.2', '+', '1.45', '>', '79.64'));
			$this->assertSame(1,  Decimals::expr('78.2', '+', '1.45', '<=>', '79.64'));

			$this->assertSame('78.2',  Decimals::expr('79.65', '-', '1.45'));
			$this->assertSame('78.19',  Decimals::expr('79.65', '-', '1.45', '-', '0.01'));
			$this->assertSame('78.21',  Decimals::expr('79.65', '-', '1.45', '+', '0.01'));
			$this->assertSame(true,  Decimals::expr('79.65', '-', '1.45', '<', '78.3'));
			$this->assertSame(true,  Decimals::expr('79.65', '-', '1.45', '<=', '78.3'));
			$this->assertSame(true,  Decimals::expr('79.65', '-', '1.45', '=', '78.2'));
			$this->assertSame(true,  Decimals::expr('79.65', '-', '1.45', '==', '78.2'));
			$this->assertSame(true,  Decimals::expr('79.65', '-', '1.45', '>=', '78.1'));
			$this->assertSame(true,  Decimals::expr('79.65', '-', '1.45', '>', '78.1'));
			$this->assertSame(1,  Decimals::expr('79.65', '-', '1.45', '<=>', '78.1'));

			$this->assertSame('10.25', Decimals::expr('20.5', '*', '0.5'));
			$this->assertSame('30.75', Decimals::expr('20.5', '*', '0.5', '*', '3'));
			$this->assertSame('41', Decimals::expr('20.5', '*', '0.5', '/', '0.25'));
			$this->assertSame('10.35', Decimals::expr('20.5', '*', '0.5', '+', '0.1'));
			$this->assertSame('10.15', Decimals::expr('20.5', '*', '0.5', '-', '0.1'));
			$this->assertSame('0.25', Decimals::expr('20.5', '*', '0.5', '%', '1'));
			$this->assertSame(true, Decimals::expr('20.5', '*', '0.5', '<', '10.3'));
			$this->assertSame(true, Decimals::expr('20.5', '*', '0.5', '<=', '10.3'));
			$this->assertSame(true, Decimals::expr('20.5', '*', '0.5', '=', '10.25'));
			$this->assertSame(true, Decimals::expr('20.5', '*', '0.5', '==', '10.25'));
			$this->assertSame(true, Decimals::expr('20.5', '*', '0.5', '>=', '10.2'));
			$this->assertSame(true, Decimals::expr('20.5', '*', '0.5', '>', '10.2'));
			$this->assertSame(1, Decimals::expr('20.5', '*', '0.5', '<=>', '10.2'));

			$this->assertSame('10.25', Decimals::expr('20.5', '/', '2'));
			$this->assertSame('41', Decimals::expr('20.5', '/', '2', '/', '0.25'));
			$this->assertSame('30.75', Decimals::expr('20.5', '/', '2', '*', '3'));
			$this->assertSame('10.35', Decimals::expr('20.5', '/', '2', '+', '0.1'));
			$this->assertSame('10.15', Decimals::expr('20.5', '/', '2', '-', '0.1'));
			$this->assertSame('0.25', Decimals::expr('20.5', '*', '0.5', '%', '1'));
			$this->assertSame(true, Decimals::expr('20.5', '/', '2', '<', '10.3'));
			$this->assertSame(true, Decimals::expr('20.5', '/', '2', '<=', '10.3'));
			$this->assertSame(true, Decimals::expr('20.5', '/', '2', '=', '10.25'));
			$this->assertSame(true, Decimals::expr('20.5', '/', '2', '==', '10.25'));
			$this->assertSame(true, Decimals::expr('20.5', '/', '2', '>=', '10.2'));
			$this->assertSame(true, Decimals::expr('20.5', '/', '2', '>', '10.2'));
			$this->assertSame(1, Decimals::expr('20.5', '/', '2', '<=>', '10.2'));

			$this->assertSame('100', Decimals::expr('10', '**', '2'));
			$this->assertSame('10000', Decimals::expr('10', '**', '2', '**', '2'));
			$this->assertSame('102', Decimals::expr('10', '**', '2', '+', '2'));
			$this->assertSame('98', Decimals::expr('10', '**', '2', '-', '2'));
			$this->assertSame('200', Decimals::expr('10', '**', '2', '*', '2'));
			$this->assertSame('50', Decimals::expr('10', '**', '2', '/', '2'));
			$this->assertSame('1', Decimals::expr('10', '**', '2', '%', '99'));
			$this->assertSame(true, Decimals::expr('10', '**', '2', '<', '101'));
			$this->assertSame(true, Decimals::expr('10', '**', '2', '<=', '101'));
			$this->assertSame(true, Decimals::expr('10', '**', '2', '=', '100'));
			$this->assertSame(true, Decimals::expr('10', '**', '2', '==', '100'));
			$this->assertSame(true, Decimals::expr('10', '**', '2', '>=', '99'));
			$this->assertSame(true, Decimals::expr('10', '**', '2', '>', '99'));
			$this->assertSame(1, Decimals::expr('10', '**', '2', '<=>', '99'));


			$this->assertSame(true, Decimals::expr('20.5', '<', '20.6'));
			$this->assertSame(false, Decimals::expr('20.5', '<', '20.5'));
			$this->assertSame(false, Decimals::expr('20.5', '<', '20.4'));

			$this->assertSame(true, Decimals::expr('20.5', '<=', '20.6'));
			$this->assertSame(true, Decimals::expr('20.5', '<=', '20.5'));
			$this->assertSame(false, Decimals::expr('20.5', '<=', '20.4'));

			$this->assertSame(true, Decimals::expr('20.5', '>=', '20.4'));
			$this->assertSame(true, Decimals::expr('20.5', '>=', '20.5'));
			$this->assertSame(false, Decimals::expr('20.5', '>=', '20.6'));

			$this->assertSame(true, Decimals::expr('20.5', '>', '20.4'));
			$this->assertSame(false, Decimals::expr('20.5', '>', '20.5'));
			$this->assertSame(false, Decimals::expr('20.5', '>', '20.6'));


			$this->assertSame(false, Decimals::expr('20.5', '=', '20.4'));
			$this->assertSame(true, Decimals::expr('20.5', '=', '20.5'));
			$this->assertSame(false, Decimals::expr('20.5', '=', '20.6'));

			$this->assertSame(false, Decimals::expr('20.5', '==', '20.4'));
			$this->assertSame(true, Decimals::expr('20.5', '==', '20.5'));
			$this->assertSame(false, Decimals::expr('20.5', '==', '20.6'));

			$this->assertSame(true, Decimals::expr('20.5', '!=', '20.4'));
			$this->assertSame(false, Decimals::expr('20.5', '!=', '20.5'));
			$this->assertSame(true, Decimals::expr('20.5', '!=', '20.6'));

			$this->assertSame(1, Decimals::expr('20.5', '<=>', '20.4'));
			$this->assertSame(0, Decimals::expr('20.5', '<=>', '20.5'));
			$this->assertSame(-1, Decimals::expr('20.5', '<=>', '20.6'));


			$this->assertSame('9', Decimals::expr('99', '%', '10'));
			$this->assertSame('1', Decimals::expr('99', '%', '10', '%', '4'));
			$this->assertSame('13', Decimals::expr('99', '%', '10', '+', '4'));
			$this->assertSame('5', Decimals::expr('99', '%', '10', '-', '4'));
			$this->assertSame('18', Decimals::expr('99', '%', '10', '*', '2'));
			$this->assertSame('4.5', Decimals::expr('99', '%', '10', '/', '2'));
			$this->assertSame(true, Decimals::expr('99', '%', '10', '<', '10'));
			$this->assertSame(true, Decimals::expr('99', '%', '10', '<=', '10'));
			$this->assertSame(true, Decimals::expr('99', '%', '10', '=', '9'));
			$this->assertSame(true, Decimals::expr('99', '%', '10', '>=', '8'));
			$this->assertSame(true, Decimals::expr('99', '%', '10', '>', '8'));
			$this->assertSame(1, Decimals::expr('99', '%', '10', '<=>', '8'));
		}

		/**
		 * @doesNotPerformAssertions
		 */
		public function testExpr_invalidOperatorSequence() {

			if (method_exists($this, 'expectNotToPerformAssertions'))
				$this->expectNotToPerformAssertions();

			$args = [
				['5', '+', '9', '*', '2'],
				['5', '+', '9', '/', '2'],

				['5', '<', '9', '/', '2'],
				['5', '<=', '9', '/', '2'],
				['5', '=', '9', '/', '2'],
				['5', '==', '9', '/', '2'],
				['5', '>=', '9', '/', '2'],
				['5', '>', '9', '/', '2'],
				['5', '<=>', '9', '/', '2'],

				['5', '<', '9', '*', '2'],
				['5', '<=', '9', '*', '2'],
				['5', '=', '9', '*', '2'],
				['5', '==', '9', '*', '2'],
				['5', '>=', '9', '*', '2'],
				['5', '>', '9', '*', '2'],
				['5', '<=>', '9', '*', '2'],

				['5', '<', '9', '+', '2'],
				['5', '<=', '9', '+', '2'],
				['5', '=', '9', '+', '2'],
				['5', '==', '9', '+', '2'],
				['5', '>=', '9', '+', '2'],
				['5', '>', '9', '+', '2'],
				['5', '<=>', '9', '+', '2'],

				['5', '<', '9', '-', '2'],
				['5', '<=', '9', '-', '2'],
				['5', '=', '9', '-', '2'],
				['5', '==', '9', '-', '2'],
				['5', '>=', '9', '-', '2'],
				['5', '>', '9', '-', '2'],
				['5', '<=>', '9', '-', '2'],

				['5', '<', '9', '<', '2'],
				['5', '<=', '9', '<', '2'],
				['5', '=', '9', '<', '2'],
				['5', '==', '9', '<', '2'],
				['5', '>=', '9', '<', '2'],
				['5', '>', '9', '<', '2'],
				['5', '<=>', '9', '<', '2'],

				['5', '<', '9', '<=', '2'],
				['5', '<=', '9', '<=', '2'],
				['5', '=', '9', '<=', '2'],
				['5', '==', '9', '<=', '2'],
				['5', '>=', '9', '<=', '2'],
				['5', '>', '9', '<=', '2'],
				['5', '<=>', '9', '<=', '2'],

				['5', '<', '9', '=', '2'],
				['5', '<=', '9', '=', '2'],
				['5', '=', '9', '=', '2'],
				['5', '==', '9', '=', '2'],
				['5', '>=', '9', '=', '2'],
				['5', '>', '9', '=', '2'],
				['5', '<=>', '9', '=', '2'],

				['5', '<', '9', '==', '2'],
				['5', '<=', '9', '==', '2'],
				['5', '=', '9', '==', '2'],
				['5', '==', '9', '==', '2'],
				['5', '>=', '9', '==', '2'],
				['5', '>', '9', '==', '2'],
				['5', '<=>', '9', '==', '2'],

				['5', '<', '9', '>=', '2'],
				['5', '<=', '9', '>=', '2'],
				['5', '=', '9', '>=', '2'],
				['5', '==', '9', '>=', '2'],
				['5', '>=', '9', '>=', '2'],
				['5', '>', '9', '>=', '2'],
				['5', '<=>', '9', '>=', '2'],

				['5', '<', '9', '>', '2'],
				['5', '<=', '9', '>', '2'],
				['5', '=', '9', '>', '2'],
				['5', '==', '9', '>', '2'],
				['5', '>=', '9', '>', '2'],
				['5', '>', '9', '>', '2'],
				['5', '<=>', '9', '>', '2'],

				['5', '<', '9', '<=>', '2'],
				['5', '<=', '9', '<=>', '2'],
				['5', '=', '9', '<=>', '2'],
				['5', '==', '9', '<=>', '2'],
				['5', '>=', '9', '<=>', '2'],
				['5', '>', '9', '<=>', '2'],
				['5', '<=>', '9', '<=>', '2'],
			];

			foreach($args as $currArgs) {
				try {
					Decimals::expr(...$currArgs);

					$this->fail('Expression "' . implode(' ', $currArgs) . '" did not throw the expected exception.');
				}
				catch (\InvalidArgumentException $ex) {

				}
			}

		}

		/**
		 * @doesNotPerformAssertions
		 */
		public function testExpr_invalidOperator() {

			if (method_exists($this, 'expectNotToPerformAssertions'))
				$this->expectNotToPerformAssertions();

			$args = [
				['5', '5.0', '9',],
				['5', '', '9'],
				['5', '&&', '9'],
				['5', '^', '9'],
			];

			foreach($args as $currArgs) {
				try {
					Decimals::expr(...$currArgs);

					$this->fail('Expression "' . implode(' ', $currArgs) . '" did not throw the expected exception.');
				}
				catch (\InvalidArgumentException $ex) {

				}
			}

		}
	}