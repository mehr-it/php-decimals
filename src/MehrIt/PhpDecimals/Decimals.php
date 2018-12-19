<?php

	namespace MehrIt\PhpDecimals;


	class Decimals
	{
		const MUL_DEFAULT_SCALE = 16;


		/**
		 * Parses a BCMath compatible number from the given string. The decimal separator is automatically detected if not passed. If auto detection is not possible,
		 * the locale value will be used. The input value must not contain any thousands separators! Only decimal representations are accepted.
		 * @param string $value The value to parse. It must not contain any thousand separators! Only decimal representations are accepted.
		 * @param string|null $decimalPoint The decimal point. If omitted auto detection is used.
		 * @return string The BCMath compatible number
		 * @throws \InvalidArgumentException
		 */
		public static function parse($value, $decimalPoint = null) {

			// remove whitespaces
			$parsedValue = trim($value);

			// remove plus sign
			if ($parsedValue[0] == '+')
				$parsedValue = substr($parsedValue, 1);

			// remove preceding zeros
			if ($parsedValue[0] == '-')
				$parsedValue = '-' . ltrim(substr($parsedValue, 1), '0');
			else
				$parsedValue = ltrim($parsedValue, '0');


			// autodetect decimal point
			if (!$decimalPoint) {

				// is "." used as decimal separator?
				if (strpos($parsedValue, '.') !== false) {
					$decimalPoint = '.';
				}
				// is "," used as decimal separator?
				elseif (strpos($parsedValue, ',') !== false) {
					$decimalPoint = ',';
				}
				// is an integer passed?
				elseif (((int)$parsedValue) == $parsedValue) {
					// there are no decimals, so we assume "." as decimal separator which requires to further action
					$decimalPoint = '.';
				}
				else {
					// autodetect failed, so we use the locale setting
					$locale_info  = localeconv();
					$decimalPoint = $locale_info['decimal_point'];
				}
			}

			// convert decimal to "."
			$parsedValue = str_replace($decimalPoint, '.', $parsedValue, $replaceCount);

			// check for valid number
			if ($parsedValue !== '') {
				if ($replaceCount > 1 || str_replace(array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.'), '', ($parsedValue[0] == '-' ? substr($parsedValue, 1) : $parsedValue)) !== '')
					throw new \InvalidArgumentException("\"$value\" is not a valid number");
			}


			return static::norm($parsedValue);
		}

		/**
		 * Rounds the given number
		 * @param string $number The BCMath compatible number to round
		 * @param int $precision The precision to round to
		 * @param int $roundMode PHP_ROUND_HALF_UP and PHP_ROUND_HALF_DOWN are supported.
		 * @return string The rounded number
		 * @throws \InvalidArgumentException
		 */
		public static function round($number, $precision = 0, $roundMode = PHP_ROUND_HALF_UP) {

			if ($precision < 0)
				throw new \InvalidArgumentException('Precision must not be negative');
			if ($roundMode !== PHP_ROUND_HALF_UP && $roundMode != PHP_ROUND_HALF_DOWN)
				throw new \InvalidArgumentException('Invalid round mode "' . $roundMode . '"');

			if (strpos($number, '.') !== false) {
				switch ($roundMode) {
					case PHP_ROUND_HALF_DOWN:
						$suffix = '4';
						break;

					default:
					case PHP_ROUND_HALF_UP:
						$suffix = '5';
						break;
				}

				$rightOperand = '0.' . str_repeat('0', $precision) . $suffix;

				if ($number[0] != '-')
					$number = bcadd($number, $rightOperand, $precision);
				else
					$number = bcsub($number, $rightOperand, $precision);
			}

			return static::norm($number);
		}

		/**
		 * Truncates the number to the given number of decimals
		 * @param string $number The BCMath compatible number to truncate
		 * @param int $precision The number of decimals to keep
		 * @return string The truncated number
		 */
		public static function truncate($number, $precision = 0) {

			// get difference in decimals
			$diff = static::decimals($number) - $precision;

			if ($diff > 0) // strip off last decimals
				$number = substr($number, 0, strlen($number) - $diff);

			return static::norm($number);
		}

		/**
		 * Normalizes a given BCMath compatible number string
		 * @param string $number The BCMath compatible number to normalize
		 * @return string The normalized string
		 */
		public static function norm($number) {

			if (!trim($number . ''))
				return '0';

			// remember and strip sign
			$sign = '';
			switch($number[0]) {
				/** @noinspection PhpMissingBreakStatementInspection */
				case '-':
					$sign = '-';
				case '+':
					$number = substr($number, 1);
			}


			// strip off trailing decimal zeros
			$sepPos = strpos($number, '.');
			if ($sepPos !== false)
				$number = substr($number, 0, $sepPos) . rtrim(substr($number, $sepPos), '.0');

			// remove leading zeros
			$number = ltrim($number, '0');

			// all stripped, so this is zero
			if ($number === '')
				return '0';

			// prepend leading decimal separator with zero
			if ($number[0] == '.')
				$number = '0' . $number;

			return $sign . $number;
		}

		/**
		 * Gets the absolute value for a given number
		 * @param string $number The BCMath compatible number to get absolute value for
		 * @return string The absolute value
		 */
		public static function abs($number) {
			if ($number !== null && $number[0] == '-')
				$number = substr($number, 1);

			return static::norm($number);
		}

		/**
		 * Gets the number of decimals of the given number
		 * @param string $number The BCMath compatible number to get decimals for
		 * @return int The number of decimals
		 */
		public static function decimals($number) {

			// get absolute value
			if ($number !== null && $number[0] == '-')
				$number = substr($number, 1);

			// find decimal point pos
			$decimalPos = strpos($number, '.');

			if ($decimalPos !== false)
				return strlen($number) - $decimalPos - 1;
			else
				return 0;
		}

		/**
		 * Outputs the number as native data type.
		 * @param string $number The BCMath compatible number to output
		 * @return int|float The number as native data type
		 */
		public static function toNative($number) {
			return $number * 1; // we are using '.' as decimal point, which PHP always converts correctly to float or int, so we simply let PHP convert the string to a number
		}

		/**
		 * Adds two numbers
		 * @param string $leftOperand The BCMath compatible left operand
		 * @param string $rightOperand The BCMath compatible right operand
		 * @param null $scale The scale to use for calculation. If omitted, the greater scale of both operands will be used
		 * @return string The sum
		 */
		public static function add($leftOperand, $rightOperand, $scale = null) {

			if ($scale === null)
				$scale = max(static::decimals($leftOperand), static::decimals($rightOperand));

			return static::norm(bcadd($leftOperand, $rightOperand, $scale));
		}

		/**
		 * Subtracts two numbers
		 * @param string $leftOperand The BCMath compatible left operand
		 * @param string $rightOperand The BCMath compatible right operand
		 * @param null $scale The scale to use for calculation. If omitted, the greater scale of both operands will be used
		 * @return string The subtraction result
		 */
		public static function sub($leftOperand, $rightOperand, $scale = null) {

			if ($scale === null)
				$scale = max(static::decimals($leftOperand), static::decimals($rightOperand));

			return static::norm(bcsub($leftOperand, $rightOperand, $scale));
		}

		/**
		 * Multiplies two numbers
		 * @param string $leftOperand The BCMath compatible left operand
		 * @param string $rightOperand The BCMath compatible right operand
		 * @param null $scale The scale to use for calculation. If omitted the double of the greater scale of both operands will be used - but at least MUL_DEFAULT_SCALE
		 * incremented by one will be used - but at least MUL_DEFAULT_SCALE
		 * @return string The multiplication result
		 */
		public static function mul($leftOperand, $rightOperand, $scale = null) {

			if ($scale === null)
				$scale = max(static::decimals($leftOperand) * 2, static::decimals($rightOperand) * 2, static::MUL_DEFAULT_SCALE);


			return static::norm(bcmul($leftOperand, $rightOperand, $scale));

		}

		/**
		 * Divides two numbers
		 * @param string $leftOperand The BCMath compatible left operand
		 * @param string $rightOperand The BCMath compatible right operand
		 * @param null $scale The scale to use for calculation. If omitted the double of the greater scale of both operands will be used - but at least MUL_DEFAULT_SCALE
		 * @return string The division result
		 * @throws \DivisionByZeroError
		 */
		public static function div($leftOperand, $rightOperand, $scale = null) {

			if ($scale === null)
				$scale = max(static::decimals($leftOperand) * 2, static::decimals($rightOperand) * 2, static::MUL_DEFAULT_SCALE);

			$res = @bcdiv($leftOperand, $rightOperand, $scale);

			if ($res === null)
				throw new \DivisionByZeroError('Divisor is 0');

			return static::norm($res);

		}

		/**
		 * Compares two numbers
		 * @param string $leftOperand The BCMath compatible left operand
		 * @param string $rightOperand The BCMath compatible right operand
		 * @param null $scale The scale to use for comparison. If omitted, the greater scale of both operands will be used. If operands have more decimals than the scale, they are ignored.
		 * @return int 0 if both numbers are equal. 1 if the left operand is greater than the right operand. Else -1
		 */
		public static function comp($leftOperand, $rightOperand, $scale = null) {

			if ($scale === null)
				$scale = max(static::decimals($leftOperand), static::decimals($rightOperand));

			return bccomp($leftOperand, $rightOperand, $scale);
		}

		/**
		 * Returns whether the left operand is greater than the right operand
		 * @param string $leftOperand The BCMath compatible left operand
		 * @param string $rightOperand The BCMath compatible right operand
		 * @param null $scale The scale to use for comparison. If omitted, the greater scale of both operands will be used. If operands have more decimals than the scale, they are ignored.
		 * @return bool True if the left operand is greater than the right operand. Else false.
		 */
		public static function isGreaterThan($leftOperand, $rightOperand, $scale = null) {
			return static::comp($leftOperand, $rightOperand, $scale) > 0;
		}

		/**
		 * Returns whether the left operand is greater than or equal to the right operand
		 * @param string $leftOperand The BCMath compatible left operand
		 * @param string $rightOperand The BCMath compatible right operand
		 * @param null $scale The scale to use for comparison. If omitted, the greater scale of both operands will be used. If operands have more decimals than the scale, they are ignored.
		 * @return bool True if the left operand is greater than the or equal to right operand. Else false.
		 */
		public static function isGreaterThanOrEqual($leftOperand, $rightOperand, $scale = null) {
			return static::comp($leftOperand, $rightOperand, $scale) >= 0;
		}

		/**
		 * Returns whether the left operand is less than the right operand
		 * @param string $leftOperand The BCMath compatible left operand
		 * @param string $rightOperand The BCMath compatible right operand
		 * @param null $scale The scale to use for comparison. If omitted, the greater scale of both operands will be used. If operands have more decimals than the scale, they are ignored.
		 * @return bool True if the left operand is less than the right operand. Else false.
		 */
		public static function isLessThan($leftOperand, $rightOperand, $scale = null) {
			return static::comp($leftOperand, $rightOperand, $scale) < 0;
		}

		/**
		 * Returns whether the left operand is less than or equal to the right operand
		 * @param string $leftOperand The BCMath compatible left operand
		 * @param string $rightOperand The BCMath compatible right operand
		 * @param null $scale The scale to use for comparison. If omitted, the greater scale of both operands will be used. If operands have more decimals than the scale, they are ignored.
		 * @return bool True if the left operand is less than or equal to the right operand. Else false.
		 */
		public static function isLessThanOrEqual($leftOperand, $rightOperand, $scale = null) {
			return static::comp($leftOperand, $rightOperand, $scale) <= 0;
		}
	}