<?php
/**
 * Tests for Google_Chrome_Labs\WP_Third_Parties\Third_Parties\Google_Analytics
 *
 * @package   Google_Chrome_Labs/WP_Third_Parties
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace Google_Chrome_Labs\WP_Third_Parties\Tests;

use Google_Chrome_Labs\WP_Third_Parties\Test_Utils\Test_Case;
use Google_Chrome_Labs\WP_Third_Parties\Third_Parties\Google_Analytics;
use Mockery;

class Google_Analytics_Test extends Test_Case {

	private $default_id   = 'G-12345678';
	private $default_args = array( 'id' => 'G-12345678' );

	public function test_add_hooks() {
		$ga = new Google_Analytics( $this->default_args );
		$ga->add_hooks();
		$this->assertFalse( has_action( 'wp_enqueue_scripts', array( $ga, 'enqueue_stylesheets' ) ) );
		$this->assertSame( 10, has_action( 'wp_enqueue_scripts', array( $ga, 'enqueue_scripts' ) ) );
	}

	public function test_get_html() {
		$ga = new Google_Analytics( $this->default_args );
		$this->assertSame( '', $ga->get_html() );
	}

	public function test_get_style_handles() {
		$ga = new Google_Analytics( $this->default_args );
		$this->assertSame( array(), $ga->get_style_handles() );
	}

	public function test_get_script_handles() {
		$ga = new Google_Analytics( $this->default_args );
		$this->assertSame( array( 'google-analytics-gtag' ), $ga->get_script_handles() );
	}

	public function test_enqueue_stylesheets() {
		$ga = new Google_Analytics( $this->default_args );

		$this->expectFunction( 'wp_enqueue_style' )
			->never();

		$ga->enqueue_stylesheets();
	}

	public function test_enqueue_scripts() {
		$ga = new Google_Analytics( $this->default_args );

		$this->expectFunction( 'wp_enqueue_script' )
			->once()
			->withArgs(
				array(
					'google-analytics-gtag',
					'https://www.googletagmanager.com/gtag/js?id=' . $this->default_id,
					array(),
					null,
					array(),
				)
			);

		$this->expectFunction( 'wp_add_inline_script' )
			->once()
			->withArgs(
				array(
					'google-analytics-gtag',
					Mockery::type( 'string' ),
					'after',
				)
			);

		$ga->enqueue_scripts();
	}
}
