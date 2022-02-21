<?php

	namespace MehrIt\PhpDecimals;


	use DivisionByZeroError;
	use InvalidArgumentException;

	class Decimals
	{
		const MUL_DEFAULT_SCALE = 16;

		const ALLOWED_EXPRESSION_OPERATORS = [
			'+'   => true,
			'-'   => true,
			'*'   => true,
			'/'   => true,
			'<'   => true,
			'<='  => true,
			'>='  => true,
			'>'   => true,
			'=='  => true,
			'='   => true,
			'!='  => true,
			'<=>' => true,
			'%'   => true,
			'**'  => true,
		];

		const ALLOWED_EXPRESSION_OPERATORS_AFTER_OPERATOR = [
			'+'   => [
				'+'   => true,
				'-'   => true,
				'<'   => true,
				'<='  => true,
				'>='  => true,
				'>'   => true,
				'=='  => true,
				'='   => true,
				'!='  => true,
				'<=>' => true,
			],
			'-'   => [
				'+'   => true,
				'-'   => true,
				'<'   => true,
				'<='  => true,
				'>='  => true,
				'>'   => true,
				'=='  => true,
				'='   => true,
				'!='  => true,
				'<=>' => true,
			],
			'*'   => [
				'+'   => true,
				'-'   => true,
				'*'   => true,
				'/'   => true,
				'<'   => true,
				'<='  => true,
				'>='  => true,
				'>'   => true,
				'=='  => true,
				'='   => true,
				'!='  => true,
				'<=>' => true,
				'%'   => true,
			],
			'/'   => [
				'+'   => true,
				'-'   => true,
				'*'   => true,
				'/'   => true,
				'<'   => true,
				'<='  => true,
				'>='  => true,
				'>'   => true,
				'=='  => true,
				'='   => true,
				'!='  => true,
				'<=>' => true,
				'%'   => true,
			],
			'%'   => self::ALLOWED_EXPRESSION_OPERATORS,
			'**'  => self::ALLOWED_EXPRESSION_OPERATORS,
			'<'   => [],
			'<='  => [],
			'>='  => [],
			'>'   => [],
			'=='  => [],
			'='   => [],
			'!='  => [],
			'<=>' => [],
		];

		/**
		 * Evaluates the given expression. Expressions are only evaluated left to right. Expressions for which left to right evaluation is not logically correct will throw an exception
		 * @param mixed ...$args The expression operands and expression.
		 * @return bool|int|string The expression result.
		 */
		public static function expr(...$args) {
			$left  = array_shift($args);

			$lastOperator     = null;
			$allowedOperators = self::ALLOWED_EXPRESSION_OPERATORS;

			while (($op = array_shift($args)) !== null) {

				if (!($allowedOperators[$op] ?? false)) {

					if (self::ALLOWED_EXPRESSION_OPERATORS[$op] ?? false)
						throw new InvalidArgumentException("Failed to evaluate expression. Operator \"{$lastOperator}\" followed by operator \"{$op}\" is not supported.");
					else
						throw new InvalidArgumentException("Invalid operator \"{$op}\"");
				}

				$right  = array_shift($args);

				if ($right === null)
					throw new InvalidArgumentException("Right operand missing after {$op}");

				switch($op) {
					case '+':
						$left = Decimals::add($left, $right);
						break;
					case '-':
						$left = Decimals::sub($left, $right);
						break;
					case '*':
						$left = Decimals::mul($left, $right);
						break;
					case '/':
						$left = Decimals::div($left, $right);
						break;
					case '<':
						$left = Decimals::isLessThan($left, $right);
						break;
					case '<=':
						$left = Decimals::isLessThanOrEqual($left, $right);
						break;
					case '>=':
						$left = Decimals::isGreaterThanOrEqual($left, $right);
						break;
					case '>':
						$left = Decimals::isGreaterThan($left, $right);
						break;
					case '==':
					case '=':
						$left = Decimals::isEqual($left, $right);
						break;
					case '!=':
						$left = Decimals::isNotEqual($left, $right);
						break;
					case '<=>':
						$left = Decimals::comp($left, $right);
						break;
					case '%':
						$left = Decimals::mod($left, $right);
						break;
					case '**':
						$left = Decimals::pow($left, $right);
						break;
				}

				$allowedOperators = self::ALLOWED_EXPRESSION_OPERATORS_AFTER_OPERATOR[$op];
				$lastOperator     = $op;
			}

			return $left;
		}

		/**
		 * Parses a BCMath compatible number from the given string. The decimal separator is automatically detected if not passed. If auto detection is not possible,
		 * the locale value will be used. The input value must not contain any thousands separators! Only decimal representations are accepted.
		 * @param string $value The value to parse. It must not contain any thousand separators! Only decimal representations are accepted.
		 * @param string|null $decimalPoint The decimal point. If omitted auto detection is used.
		 * @return string The BCMath compatible number
		 * @throws InvalidArgumentException
		 */
		public static function parse(string $value, string $decimalPoint = null): string {

			// remove whitespaces
			$parsedValue = trim($value);
			if ($value === '' || $value === '-' || $value === '+')
				throw new InvalidArgumentException("\"$value\" is not a valid number");

			// remove plus sign
			if (($parsedValue[0] ?? null) === '+')
				$parsedValue = substr($parsedValue, 1);

			// remove preceding zeros
			if (($parsedValue[0] ?? null) === '-')
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
					// there are no decimals, so we assume "." as decimal separator which requires no further action
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
				if ($replaceCount > 1 ||
				    !in_array(str_replace(array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9'), '', ($parsedValue[0] == '-' ? substr($parsedValue, 1) : $parsedValue)), ['', '.'], true) ||
				    ($replaceCount === 0 && strpos($parsedValue, '.') !== false)
				) {
					throw new InvalidArgumentException("\"$value\" is not a valid number");
				}
			}


			return static::norm($parsedValue);
		}

		/**
		 * Rounds the given number
		 * @param string $number The BCMath compatible number to round
		 * @param int $precision The precision to round to
		 * @param int $roundMode PHP_ROUND_HALF_UP and PHP_ROUND_HALF_DOWN are supported.
		 * @return string The rounded number
		 * @throws InvalidArgumentException
		 */
		public static function round(string $number, int $precision = 0, $roundMode = PHP_ROUND_HALF_UP): string {

			if ($precision < 0)
				throw new InvalidArgumentException('Precision must not be negative');
			if ($roundMode !== PHP_ROUND_HALF_UP && $roundMode != PHP_ROUND_HALF_DOWN)
				throw new InvalidArgumentException('Invalid round mode "' . $roundMode . '"');

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
		public static function truncate(string $number, int $precision = 0): string {

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
		public static function norm(string $number): string {

			if ($number === '')
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
				$number = rtrim(rtrim($number, '0'), '.');

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
		public static function abs(string $number): string {
			if ($number[0] == '-')
				$number = substr($number, 1);

			return static::norm($number);
		}

		/**
		 * Gets the number of decimals of the given number
		 * @param string $number The BCMath compatible number to get decimals for
		 * @return int The number of decimals
		 */
		public static function decimals(string $number): int {
			
			return ($decimalPos = strpos($number, '.')) !== false ?
				strlen($number) - $decimalPos - 1 :
				0;
		}

		/**
		 * Outputs the number as native data type.
		 * @param string $number The BCMath compatible number to output
		 * @return int|float The number as native data type
		 */
		public static function toNative(string $number) {
			return $number * 1; // we are using '.' as decimal point, which PHP always converts correctly to float or int, so we simply let PHP convert the string to a number
		}

		/**
		 * Adds two numbers
		 * @param string $leftOperand The BCMath compatible left operand
		 * @param string $rightOperand The BCMath compatible right operand
		 * @param int|null $scale The scale to use for calculation. If omitted, the greater scale of both operands will be used
		 * @return string The sum
		 */
		public static function add(string $leftOperand, string $rightOperand, int $scale = null): string {

			$scale = $scale !== null ?
				$scale :
				max(
					($decimalPos = strpos($leftOperand, '.')) !== false ? strlen($leftOperand) - $decimalPos - 1 : 0,
					($decimalPos = strpos($rightOperand, '.')) !== false ? strlen($rightOperand) - $decimalPos - 1 : 0
				);

			$res = bcadd($leftOperand, $rightOperand, $scale);
			
			// bcadd does not remove unneeded decimals => let's do it here
			if ($scale > 0) 
				$res = rtrim(rtrim($res, '0'), '.');
			
			return $res;
		}

		/**
		 * Subtracts two numbers
		 * @param string $leftOperand The BCMath compatible left operand
		 * @param string $rightOperand The BCMath compatible right operand
		 * @param int|null $scale The scale to use for calculation. If omitted, the greater scale of both operands will be used
		 * @return string The subtraction result
		 */
		public static function sub(string $leftOperand, string $rightOperand, int $scale = null): string {

			$scale = $scale !== null ?
				$scale :
				max(
					($decimalPos = strpos($leftOperand, '.')) !== false ? strlen($leftOperand) - $decimalPos - 1 : 0,
					($decimalPos = strpos($rightOperand, '.')) !== false ? strlen($rightOperand) - $decimalPos - 1 : 0
				);

			$res = bcsub($leftOperand, $rightOperand, $scale);

			// bcsub does not remove unneeded decimals => let's do it here
			if ($scale > 0)
				$res = rtrim(rtrim($res, '0'), '.');
			
			return $res;
		}

		/**
		 * Multiplies two numbers
		 * @param string $leftOperand The BCMath compatible left operand
		 * @param string $rightOperand The BCMath compatible right operand
		 * @param int|null $scale The scale to use for calculation. If omitted the double of the greater scale of both operands will be used - but at least MUL_DEFAULT_SCALE
		 * incremented by one will be used - but at least MUL_DEFAULT_SCALE
		 * @return string The multiplication result
		 */
		public static function mul(string $leftOperand, string $rightOperand, int $scale = null): string {

			$scale = $scale !== null ?
				$scale :
				max(
					(($decimalPos = strpos($leftOperand, '.')) !== false ? strlen($leftOperand) - $decimalPos - 1 : 0) * 2,
					(($decimalPos = strpos($rightOperand, '.')) !== false ? strlen($rightOperand) - $decimalPos - 1 : 0) *2,
					static::MUL_DEFAULT_SCALE
				);

			$res = bcmul($leftOperand, $rightOperand, $scale);

			// bcmul does not remove unneeded decimals => let's do it here
			if ($scale > 0)
				$res = rtrim(rtrim($res, '0'), '.');

			return $res;
		}

		/**
		 * Divides two numbers
		 * @param string $leftOperand The BCMath compatible left operand
		 * @param string $rightOperand The BCMath compatible right operand
		 * @param int|null $scale The scale to use for calculation. If omitted the double of the greater scale of both operands will be used - but at least MUL_DEFAULT_SCALE
		 * @return string The division result
		 * @throws DivisionByZeroError
		 */
		public static function div(string $leftOperand, string $rightOperand, int $scale = null): string {

			$scale = $scale !== null ?
				$scale :
				max(
					(($decimalPos = strpos($leftOperand, '.')) !== false ? strlen($leftOperand) - $decimalPos - 1 : 0) * 2,
					(($decimalPos = strpos($rightOperand, '.')) !== false ? strlen($rightOperand) - $decimalPos - 1 : 0) * 2,
					static::MUL_DEFAULT_SCALE
				);
			
			$res = @bcdiv($leftOperand, $rightOperand, $scale);

			if ($res === null)
				throw new DivisionByZeroError('Divisor is 0');

			// bcdiv does not remove unneeded decimals => let's do it here
			if ($scale > 0)
				$res = rtrim(rtrim($res, '0'), '.');

			return $res;

		}

		/**
		 * Calculates the modulus of two numbers
		 * @param string $leftOperand The BCMath compatible left operand
		 * @param string $rightOperand The BCMath compatible right operand
		 * @param int|null $scale The scale to use for calculation. If omitted, the greater scale of both operands will be used
		 * @return string The modulus
		 */
		public static function mod(string $leftOperand, string $rightOperand, int $scale = null): string {

			$scale = $scale !== null ?
				$scale :
				max(
					(($decimalPos = strpos($leftOperand, '.')) !== false ? strlen($leftOperand) - $decimalPos - 1 : 0) * 2,
					(($decimalPos = strpos($rightOperand, '.')) !== false ? strlen($rightOperand) - $decimalPos - 1 : 0) * 2,
					static::MUL_DEFAULT_SCALE
				);
			
			$res = @bcmod($leftOperand, $rightOperand, $scale);

			if ($res === null)
				throw new DivisionByZeroError('Divisor is 0');

			// bcmod does not remove unneeded decimals => let's do it here
			if ($scale > 0)
				$res = rtrim(rtrim($res, '0'), '.');
			
			return $res;
		}

		/**
		 * Calculates the power of two numbers
		 * @param string $base The BCMath compatible base
		 * @param string $exponent The BCMath compatible exponent
		 * @param int|null $scale The scale to use for calculation. If omitted, the greater scale of both operands will be used
		 * @return string The power
		 */
		public static function pow(string $base, string $exponent, int $scale = null): string {
			
			if ((($decimalPos = strpos($exponent, '.')) !== false ? strlen($exponent) - $decimalPos - 1 : 0) > 0)
				throw new InvalidArgumentException('Exponent must not be fractional. This is not supported by BCMath.');

			$scale = $scale !== null ?
				$scale :
				max(
					(($decimalPos = strpos($base, '.')) !== false ? strlen($base) - $decimalPos - 1 : 0) * 2,
					static::MUL_DEFAULT_SCALE
				);
			

			$res = bcpow($base, $exponent, $scale);
			
			// bcpow does not remove unneeded decimals => let's do it here
			if ($scale > 0)
				$res = rtrim(rtrim($res, '0'), '.');
			
			return $res;
		}

		/**
		 * Compares two numbers
		 * @param string $leftOperand The BCMath compatible left operand
		 * @param string $rightOperand The BCMath compatible right operand
		 * @param int|null $scale The scale to use for comparison. If omitted, the greater scale of both operands will be used. If operands have more decimals than the scale, they are ignored.
		 * @return int 0 if both numbers are equal. 1 if the left operand is greater than the right operand. Else -1
		 */
		public static function comp(string $leftOperand, string $rightOperand, int $scale = null): int {

			$scale = $scale !== null ?
				$scale :
				max(
					($decimalPos = strpos($leftOperand, '.')) !== false ? strlen($leftOperand) - $decimalPos - 1 : 0,
					($decimalPos = strpos($rightOperand, '.')) !== false ? strlen($rightOperand) - $decimalPos - 1 : 0
				);

			return bccomp($leftOperand, $rightOperand, $scale);
		}

		/**
		 * Returns whether the left operand is greater than the right operand
		 * @param string $leftOperand The BCMath compatible left operand
		 * @param string $rightOperand The BCMath compatible right operand
		 * @param int|null $scale The scale to use for comparison. If omitted, the greater scale of both operands will be used. If operands have more decimals than the scale, they are ignored.
		 * @return bool True if the left operand is greater than the right operand. Else false.
		 */
		public static function isGreaterThan(string $leftOperand, string $rightOperand, int $scale = null): bool {

			$scale = $scale !== null ?
				$scale :
				max(
					($decimalPos = strpos($leftOperand, '.')) !== false ? strlen($leftOperand) - $decimalPos - 1 : 0,
					($decimalPos = strpos($rightOperand, '.')) !== false ? strlen($rightOperand) - $decimalPos - 1 : 0
				);
			
			return bccomp($leftOperand, $rightOperand, $scale) > 0;
		}

		/**
		 * Returns whether the left operand is greater than or equal to the right operand
		 * @param string $leftOperand The BCMath compatible left operand
		 * @param string $rightOperand The BCMath compatible right operand
		 * @param int|null $scale The scale to use for comparison. If omitted, the greater scale of both operands will be used. If operands have more decimals than the scale, they are ignored.
		 * @return bool True if the left operand is greater than the or equal to right operand. Else false.
		 */
		public static function isGreaterThanOrEqual(string $leftOperand, string $rightOperand, int $scale = null): bool {

			$scale = $scale !== null ?
				$scale :
				max(
					($decimalPos = strpos($leftOperand, '.')) !== false ? strlen($leftOperand) - $decimalPos - 1 : 0,
					($decimalPos = strpos($rightOperand, '.')) !== false ? strlen($rightOperand) - $decimalPos - 1 : 0
				);
			
			return bccomp($leftOperand, $rightOperand, $scale) >= 0;
		}

		/**
		 * Returns whether the left operand is less than the right operand
		 * @param string $leftOperand The BCMath compatible left operand
		 * @param string $rightOperand The BCMath compatible right operand
		 * @param int|null $scale The scale to use for comparison. If omitted, the greater scale of both operands will be used. If operands have more decimals than the scale, they are ignored.
		 * @return bool True if the left operand is less than the right operand. Else false.
		 */
		public static function isLessThan(string $leftOperand, string $rightOperand, int $scale = null): bool {

			$scale = $scale !== null ?
				$scale :
				max(
					($decimalPos = strpos($leftOperand, '.')) !== false ? strlen($leftOperand) - $decimalPos - 1 : 0,
					($decimalPos = strpos($rightOperand, '.')) !== false ? strlen($rightOperand) - $decimalPos - 1 : 0
				);
			
			return bccomp($leftOperand, $rightOperand, $scale) < 0;
		}

		/**
		 * Returns whether the left operand is less than or equal to the right operand
		 * @param string $leftOperand The BCMath compatible left operand
		 * @param string $rightOperand The BCMath compatible right operand
		 * @param int|null $scale The scale to use for comparison. If omitted, the greater scale of both operands will be used. If operands have more decimals than the scale, they are ignored.
		 * @return bool True if the left operand is less than or equal to the right operand. Else false.
		 */
		public static function isLessThanOrEqual(string $leftOperand, string $rightOperand, int $scale = null): bool {

			$scale = $scale !== null ?
				$scale :
				max(
					($decimalPos = strpos($leftOperand, '.')) !== false ? strlen($leftOperand) - $decimalPos - 1 : 0,
					($decimalPos = strpos($rightOperand, '.')) !== false ? strlen($rightOperand) - $decimalPos - 1 : 0
				);
			
			return bccomp($leftOperand, $rightOperand, $scale) <= 0;
		}

		/**
		 * Returns whether both operands are equal
		 * @param string $leftOperand The BCMath compatible left operand
		 * @param string $rightOperand The BCMath compatible right operand
		 * @param int|null $scale The scale to use for comparison. If omitted, the greater scale of both operands will be used. If operands have more decimals than the scale, they are ignored.
		 * @return bool True if both operands are equal. Else false.
		 */
		public static function isEqual(string $leftOperand, string $rightOperand, int $scale = null): bool {

			$scale = $scale !== null ?
				$scale :
				max(
					($decimalPos = strpos($leftOperand, '.')) !== false ? strlen($leftOperand) - $decimalPos - 1 : 0,
					($decimalPos = strpos($rightOperand, '.')) !== false ? strlen($rightOperand) - $decimalPos - 1 : 0
				);
			
			return bccomp($leftOperand, $rightOperand, $scale) === 0;
		}

		/**
		 * Returns whether both operands are not equal
		 * @param string $leftOperand The BCMath compatible left operand
		 * @param string $rightOperand The BCMath compatible right operand
		 * @param int|null $scale The scale to use for comparison. If omitted, the greater scale of both operands will be used. If operands have more decimals than the scale, they are ignored.
		 * @return bool True if both operands are not equal. Else false.
		 */
		public static function isNotEqual(string $leftOperand, string $rightOperand, int $scale = null): bool {

			$scale = $scale !== null ?
				$scale :
				max(
					($decimalPos = strpos($leftOperand, '.')) !== false ? strlen($leftOperand) - $decimalPos - 1 : 0,
					($decimalPos = strpos($rightOperand, '.')) !== false ? strlen($rightOperand) - $decimalPos - 1 : 0
				);
			
			return bccomp($leftOperand, $rightOperand, $scale) !== 0;
		}
	}