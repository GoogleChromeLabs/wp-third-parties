<?php
/**
 * Tests for Google_Chrome_Labs\WP_Third_Parties\Third_Parties\Google_Maps_Embed
 *
 * @package   Google_Chrome_Labs/WP_Third_Parties
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace Google_Chrome_Labs\WP_Third_Parties\Tests;

use Google_Chrome_Labs\WP_Third_Parties\Test_Utils\Test_Case;
use Google_Chrome_Labs\WP_Third_Parties\Third_Parties\Google_Maps_Embed;

class Google_Maps_Embed_Test extends Test_Case {

	private $default_args = array(
		'key' => 'MY_API_KEY',
		'q'   => 'Space Needle, Seattle WA',
	);

	public function test_add_hooks() {
		$gme = new Google_Maps_Embed( $this->default_args );
		$gme->add_hooks();
		$this->assertFalse( has_action( 'wp_enqueue_scripts', array( $gme, 'enqueue_stylesheets' ) ) );
		$this->assertFalse( has_action( 'wp_enqueue_scripts', array( $gme, 'enqueue_scripts' ) ) );
	}

	public function test_get_html() {
		$gme = new Google_Maps_Embed( $this->default_args );
		$this->assertSame(
			$this->get_html_string(
				'iframe',
				array(
					'loading'         => 'lazy',
					'src'             => 'https://www.google.com/maps/embed/v1/place?key=MY_API_KEY&q=Space+Needle%2C+Seattle+WA',
					'referrerpolicy'  => 'no-referrer-when-downgrade',
					'frameborder'     => '0',
					'style'           => 'border:0',
					'allowfullscreen' => true,
				)
			),
			$gme->get_html()
		);
	}

	public function test_get_style_handles() {
		$gme = new Google_Maps_Embed( $this->default_args );
		$this->assertSame( array(), $gme->get_style_handles() );
	}

	public function test_get_script_handles() {
		$gme = new Google_Maps_Embed( $this->default_args );
		$this->assertSame( array(), $gme->get_script_handles() );
	}

	public function test_enqueue_stylesheets() {
		$gme = new Google_Maps_Embed( $this->default_args );

		$this->expectFunction( 'wp_enqueue_style' )
			->never();

		$gme->enqueue_stylesheets();
	}

	public function test_enqueue_scripts() {
		$gme = new Google_Maps_Embed( $this->default_args );

		$this->expectFunction( 'wp_enqueue_script' )
			->never();

		$this->expectFunction( 'wp_add_inline_script' )
			->never();

		$gme->enqueue_scripts();
	}
}
