<?php
/**
 * Tests for Google_Chrome_Labs\WP_Third_Parties\Third_Parties\Google_Tag_Manager
 *
 * @package   Google_Chrome_Labs/WP_Third_Parties
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace Google_Chrome_Labs\WP_Third_Parties\Tests;

use Google_Chrome_Labs\WP_Third_Parties\Test_Utils\Test_Case;
use Google_Chrome_Labs\WP_Third_Parties\Third_Parties\Google_Tag_Manager;
use Mockery;

class Google_Tag_Manager_Test extends Test_Case {

	private $default_id   = 'GTM-A1B2C3';
	private $default_args = array( 'id' => 'GTM-A1B2C3' );

	public function test_add_hooks() {
		$gtm = new Google_Tag_Manager( $this->default_args );
		$gtm->add_hooks();
		$this->assertFalse( has_action( 'wp_enqueue_scripts', array( $gtm, 'enqueue_stylesheets' ) ) );
		$this->assertSame( 10, has_action( 'wp_enqueue_scripts', array( $gtm, 'enqueue_scripts' ) ) );
	}

	public function test_get_html() {
		$gtm = new Google_Tag_Manager( $this->default_args );
		$this->assertSame( '', $gtm->get_html() );
	}

	public function test_get_style_handles() {
		$gtm = new Google_Tag_Manager( $this->default_args );
		$this->assertSame( array(), $gtm->get_style_handles() );
	}

	public function test_get_script_handles() {
		$gtm = new Google_Tag_Manager( $this->default_args );
		$this->assertSame( array( 'google-tag-manager-gtm' ), $gtm->get_script_handles() );
	}

	public function test_enqueue_stylesheets() {
		$gtm = new Google_Tag_Manager( $this->default_args );

		$this->expectFunction( 'wp_enqueue_style' )
			->never();

		$gtm->enqueue_stylesheets();
	}

	public function test_enqueue_scripts() {
		$gtm = new Google_Tag_Manager( $this->default_args );

		$this->expectFunction( 'wp_enqueue_script' )
			->once()
			->withArgs(
				array(
					'google-tag-manager-gtm',
					'https://www.googletagmanager.com/gtm.js?id=' . $this->default_id,
					array(),
					null,
					array(),
				)
			);

		$this->expectFunction( 'wp_add_inline_script' )
			->once()
			->withArgs(
				array(
					'google-tag-manager-gtm',
					Mockery::type( 'string' ),
					'after',
				)
			);

		$gtm->enqueue_scripts();
	}
}
