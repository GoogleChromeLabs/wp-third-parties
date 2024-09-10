<?php
/**
 * Class Google_Chrome_Labs\WP_Third_Parties\Test_Utils\Test_Case
 *
 * @package   Google_Chrome_Labs/WP_Third_Parties
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace Google_Chrome_Labs\WP_Third_Parties\Test_Utils;

use Brain\Monkey;
use Brain\Monkey\Expectation\Expectation;
use Brain\Monkey\Expectation\FunctionStub;
use Brain\Monkey\Functions;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase as PHPUnit_Test_Case;

/**
 * Base class for test cases. Used to set up the mock integration with Brain\Monkey package.
 *
 * @see https://github.com/Brain-WP/BrainMonkey/blob/master/docs/functions-testing-tools/functions-setup.md#phpunit-example
 */
abstract class Test_Case extends PHPUnit_Test_Case {
	use MockeryPHPUnitIntegration;

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	protected function whenFunction( string $func ): FunctionStub {
		return Functions\when( $func );
	}

	protected function expectFunction( string $func ): Expectation {
		return Functions\expect( $func );
	}

	/**
	 * Test helper to turn a HTML element name and attributes array into a string.
	 *
	 * @param string                     $element    HTML element name.
	 * @param array<string, string|bool> $attributes Associative array of HTML attributes.
	 * @return string The resulting HTML string.
	 */
	protected function get_html_string( string $element, array $attributes ): string {
		$attr_string = '';
		foreach ( $attributes as $key => $value ) {
			if ( is_bool( $value ) ) {
				if ( $value ) {
					$attr_string .= ' ' . $key;
				}
				continue;
			}
			$attr_string .= ' ' . $key . '="' . $value . '"';
		}
		return '<' . $element . $attr_string . '></' . $element . '>';
	}
}
