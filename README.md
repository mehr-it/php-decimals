# PHP Decimals
[![Latest Version on Packagist](https://img.shields.io/packagist/v/mehr-it/php-decimals.svg?style=flat-square)](https://packagist.org/packages/mehr-it/php-decimals)
[![Build Status](https://travis-ci.org/mehr-it/php-decimals.svg?branch=master)](https://travis-ci.org/mehr-it/php-decimals)

This library is a small wrapper around PHP's **[BCMath](https://www.php.net/manual/en/book.bc.php)** extension. BCMath 
implements arbitrary precision mathematics, it's usage is not very 
comfortable.

This library aims to implement an easy to use interface for the most commonly 
needed mathematical operations using BCMath internally.

## Installation

Install the package using composer:

    composer require mehr-it/php-decimals

## Introduction
Mathematical operations using PHP's built-in number types (float and integer)
suffer from two drawbacks:

* limited range
* floating point inaccuracy

Whenever dealing with numbers out of the build-in number types' range or 
whenever floating point inaccuracy can become a problem (eg. currency 
calculations) an alternative solution is required.

In the same way as the BCMath extension, this library uses strings to represent
numbers. Unlike PHP's float to string conversion (which uses a locale depended
decimal separator), the decimal separator is always `"."`.

One thing you have to worry about when dealing with arbitrary precision 
mathematics is the precision you need. You have to tell how much precision
you need for your results. Whenever possible, this library will do this for
you. But for some operations you might have to manually set the required 
precision when you need extraordinary accuracy.

## Usage

Any operations can be accessed using the static methods of the `Decimals` class:

    Decimals::add('5.78', '7.1');
    
### Basic mathematical operations

    // add: 5.78 + 7.1
    Decimals::add('5.78', '7.1');
    
    // substract: 5.32 - 1.08
    Decimals::sub('5.32', '1.08');
  
    // multiply: 1.1 * 2.87
    Decimals::mul('1.1', '2.87');
    
    // divide: 5 / 2.5
    Decimals::div('5', '2.5');
    
    // modulus: 5 % 2
    Decimals::mod('5', '2');
    
    // pow: 5 ** 2
    Decimals::pow('5', '2');
    
For `add()` and `sub()` the result precision is automatically set to the largest
number of decimals of either operands. This is sufficient for any operation.

For `mul()`, `div()` and `mod()` the result precision is  automatically set to the double
of the largest number of decimals of either operands but at least 16 decimals.
If you need higher precision, you may specify it as third parameter. The 
following example outputs 32 decimals:

    Decimals::div('1', '3', 32);
    
The `sum()` function sums up all given operands:
    
    Decimals::sum('1', '2.1', '-0.3'); 
    
### Converting and parsing
Sometimes you need to convert a floating number to a string. As mentioned
above, casting a floating number to a string uses the locale dependent
decimal separator (e.g. "." for "en" and "," for "de"). This can cause a lot
of problems when you have no control which locale your PHP interpreter uses
(especially when writing libraries).


#### Convert native type to number string
Luckily this library ships with a function, converting these local strings to
the BCMath compatible number strings (with "." as decimal separator). You
may simply pass your number to the `parse()` function:

    $float = 1.5;
    
    $sNumber = Decimals::parse($float);
    
**Always use the `parse()` function when converting native number types
to string to write platform independent code!**

#### Parse string with any decimal separator
The `parse()` function is also useful when you have numbers from external 
sources with (maybe different) decimal separator than the locale. You can
safely convert these strings by passing the decimal separator as second
argument:

    $sNumber = Decimals::parse('1,67', ',');
    
    // $sNumber: '1.67'
    
The parse function also validates the input and throws an 
`InvalidArguemntException` if no a valid number is given. (Whitespaces around
a valid number string are gracefully ignored and number is parsed without
exception)
    
    
#### Convert number string to float or int
In cases when you need to convert the value back to a native data type you
can simply cast the string to the type or use the `toNative()` function
which will return the correct type (float or int) automatically:

    $float = Decimals::toNative('1.56'); 
    
    $int = Decimals::toNative('167'); 

#### Normalize numbers
Often, a number can be represented in multiple forms:

* 0 can be expressed as "0.0", "0", "0." ".0", "-0", ...
* 5.8 can be expressed as "005.8", "5.8000", "+5.8" ...

The `norm()` function strips of any unneeded zeros before and after the number,
remove any "+" signs and represent 0 always as "0":

    Decimals::norm('+045.89');
    
    
### Rounding and truncating numbers

These functions are pretty self explaining. `round()` rounds the number to
the given number of decimals, while `truncate()` simply strips of decimals:

    Decimals::round('5.4591', 0); // = '5'
    Decimals::round('5.4591', 1); // = '5.5'
    Decimals::round('5.4591', 2); // = '5.46'
    
    Decimals::truncate('5.4591', 0); // = '5'
    Decimals::truncate('5.4591', 1); // = '5.4'
    Decimals::truncate('5.4591', 2); // = '5.45'


### Comparison

To compare numbers, the `comp()` function can be used. It acts like the `<=>`
operator, returning `-1` if the left operand is lower, `0` if both operands
are equal and `1` if the right operand is greater:

    Decimals::comp('1.5', '6');   // = -1
    Decimals::comp('1.5', '1.5'); // = 0
    Decimals::comp('6', '1.5');   // = 1
    
To make code more readable, the following functions handle specific
comparison cases:

    Decimals::isEqual('1.5', '6');               // = false
    Decimals::isNotEqual('1.5', '6');            // = true
    Decimals::isGreaterThan('1.5', '6');         // = false
    Decimals::isGreaterThanOrEqual('1.5', '6');  // = false
    Decimals::isLessThan('1.5', '6');            // = true
    Decimals::isLessThanOrEqual('1.5', '6');     // = true

To find the maximum or minimum of a given value set, the `max()` and `min()` functions exist:

    Decimals::max('1', '2', '3');   // '3'
    Decimals::min('1', '2', '3');   // '1'

    
### Mathematical functions

    // returns the absolute value
    Decimals::abs('-1.5'); // = '1.5'
    Decimals::abs('1.5');  // = '1.5'
    
### Other functions    

    // returns the number of decimals of the given number
    Decimals::decimals('78.8');   // = 1
    Decimals::decimals('78.889'); // = 3
    Decimals::decimals('78.800'); // = 3
 

## Using expressions
Applying multiple operations can lead to unreadable code:

    $result = Decimals::add(Decimals::mul($a, $b), $c);
    
The `Decimals::expr()` method (or the  helper `expr()`) can help here. The same operations
as above can be written as follows:

    $result = Decimals::expr($a, '*', $b, '+', $c);
    
    // or using helper
    $result = expr($a, '*', $b, '+', $c);
    
This comes with the drawback of little performance overhead, but is very easy to 
read.

**Expressions are always evaluated from left to right.** If an expression is given
which would break mathematical or logical operator precedence, an exception is thrown. 

    
 
## TODO

* add wrapper for `bcpowmod()` and `bcsqrt()`