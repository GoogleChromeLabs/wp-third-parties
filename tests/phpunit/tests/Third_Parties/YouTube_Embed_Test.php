<?php
/**
 * Tests for Google_Chrome_Labs\WP_Third_Parties\Third_Parties\YouTube_Embed
 *
 * @package   Google_Chrome_Labs/WP_Third_Parties
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace Google_Chrome_Labs\WP_Third_Parties\Tests;

use Google_Chrome_Labs\WP_Third_Parties\Test_Utils\Test_Case;
use Google_Chrome_Labs\WP_Third_Parties\Third_Parties\YouTube_Embed;

class YouTube_Embed_Test extends Test_Case {

	private $default_id   = 'ogfYd705cRs';
	private $default_args = array(
		'videoid'   => 'ogfYd705cRs',
		'playlabel' => 'Play: Keynote (Google I/O 2018)',
	);

	public function test_add_hooks() {
		$yte = new YouTube_Embed( $this->default_args );
		$yte->add_hooks();
		$this->assertSame( 10, has_action( 'wp_enqueue_scripts', array( $yte, 'enqueue_stylesheets' ) ) );
		$this->assertSame( 10, has_action( 'wp_enqueue_scripts', array( $yte, 'enqueue_scripts' ) ) );
	}

	public function test_get_html() {
		$yte = new YouTube_Embed( $this->default_args );
		$this->assertSame(
			$this->get_html_string(
				'lite-youtube',
				$this->default_args
			),
			$yte->get_html()
		);
	}

	public function test_get_style_handles() {
		$yte = new YouTube_Embed( $this->default_args );
		$this->assertSame( array( 'youtube-embed' ), $yte->get_style_handles() );
	}

	public function test_get_script_handles() {
		$yte = new YouTube_Embed( $this->default_args );
		$this->assertSame( array( 'youtube-embed-lite-yt-embed' ), $yte->get_script_handles() );
	}

	public function test_enqueue_stylesheets() {
		$yte = new YouTube_Embed( $this->default_args );

		$this->expectFunction( 'wp_enqueue_style' )
			->once()
			->withArgs(
				array(
					'youtube-embed',
					'https://cdn.jsdelivr.net/gh/paulirish/lite-youtube-embed@master/src/lite-yt-embed.css',
					array(),
					null,
				)
			);

		$yte->enqueue_stylesheets();
	}

	public function test_enqueue_scripts() {
		$yte = new YouTube_Embed( $this->default_args );

		$this->expectFunction( 'wp_enqueue_script' )
			->once()
			->withArgs(
				array(
					'youtube-embed-lite-yt-embed',
					'https://cdn.jsdelivr.net/gh/paulirish/lite-youtube-embed@master/src/lite-yt-embed.js',
					array(),
					null,
					array(),
				)
			);

		$this->expectFunction( 'wp_add_inline_script' )
			->never();

		$yte->enqueue_scripts();
	}
}
