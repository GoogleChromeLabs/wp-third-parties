<?php
/**
 * Class Google_Chrome_Labs\WP_Third_Parties\Third_Parties\WP_Third_Party_Base
 *
 * @package   Google_Chrome_Labs/WP_Third_Parties
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace Google_Chrome_Labs\WP_Third_Parties\Third_Parties;

use Google_Chrome_Labs\WP_Third_Parties\Contracts\WP_Third_Party;
use GoogleChromeLabs\ThirdPartyCapital\Contracts\ThirdParty;
use GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyScriptData;
use GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyScriptOutput;

/**
 * Base class representing a third party integration for WordPress.
 */
abstract class WP_Third_Party_Base implements WP_Third_Party {

	/**
	 * Reference to the WordPress-agnostic third party implementation.
	 *
	 * @var ThirdParty
	 */
	private $third_party;

	/**
	 * Constructor.
	 *
	 * @param array $args Input arguments to set.
	 */
	public function __construct( array $args ) {
		$this->third_party = $this->create_third_party( $args );
	}

	/**
	 * Sets input arguments for the integration.
	 *
	 * @param array $args Input arguments to set.
	 */
	final public function set_args( array $args ) {
		$this->third_party->setArgs( $args );
	}

	/**
	 * Adds hooks to WordPress to load the integration.
	 *
	 * Must be called anytime before the {@see 'template_redirect'} action hook.
	 */
	final public function add_hooks() {
		if ( ! $this->third_party->getStylesheets() && ! $this->third_party->getScripts() ) {
			return;
		}

		add_action(
			'wp_enqueue_scripts',
			function () {
				$this->enqueue_stylesheets();
				$this->enqueue_scripts();
			}
		);
	}

	/**
	 * Gets the HTML output for the integration.
	 *
	 * Only relevant if the integration provides user-facing output.
	 *
	 * @return string HTML output, or empty string if not applicable.
	 */
	final public function get_html(): string {
		return $this->third_party->getHtml();
	}

	/**
	 * Returns the handles of all stylesheets that are part of the integration.
	 *
	 * These handles can be used as dependencies for other stylesheets registered in WordPress.
	 *
	 * @return string[] List of stylesheet handles, or empty array if there are none.
	 */
	final public function get_style_handles(): array {
		$id = $this->third_party->getId();

		$handles = array();
		foreach ( array_keys( $this->third_party->getStylesheets() ) as $index ) {
			$handles[] = $this->create_style_handle( $id, $index );
		}
		return $handles;
	}

	/**
	 * Returns the handles of all scripts that are part of the integration.
	 *
	 * These handles can be used as dependencies for other scripts registered in WordPress.
	 *
	 * @return string[] List of script handles, or empty array if there are none.
	 */
	final public function get_script_handles(): array {
		$id = $this->third_party->getId();

		$handles = array();
		foreach ( $this->third_party->getScripts() as $index => $script ) {
			if ( isset( $script['code'] ) ) {
				continue;
			}

			$handles[] = $this->create_script_handle( $id, $index, $script );
		}
		return $handles;
	}

	/**
	 * Gets the path to the third party data JSON file.
	 *
	 * @param array $args Input arguments to set.
	 * @return ThirdParty Reference to the WordPress-agnostic third party implementation.
	 */
	abstract protected function create_third_party( array $args ): ThirdParty;

	/**
	 * Enqueues all stylesheets for the third party.
	 */
	private function enqueue_stylesheets() {
		$id = $this->third_party->getId();

		foreach ( $this->third_party->getStylesheets() as $index => $stylesheet ) {
			$handle = $this->create_style_handle( $id, $index );

			wp_enqueue_style(
				$handle,
				$stylesheet,
				array(),
				null // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			);
		}
	}

	/**
	 * Enqueues all scripts for the third party.
	 */
	private function enqueue_scripts() {
		$id = $this->third_party->getId();

		$prev_scripts = array(
			ThirdPartyScriptData::LOCATION_HEAD => array(),
			ThirdPartyScriptData::LOCATION_BODY => array(),
		);
		foreach ( $this->third_party->getScripts() as $index => $script ) {
			if ( isset( $script['code'] ) ) {
				if ( ! $this->enqueue_inline_script( $script, $prev_scripts ) ) {
					$this->enqueue_standalone_inline_script( $script );
				}
				continue;
			}

			$handle = $this->create_script_handle( $id, $index, $script );
			$this->enqueue_external_script( $script, $handle, $prev_scripts );
		}
	}

	/**
	 * Enqueues the given external script.
	 *
	 * @param ThirdPartyScriptOutput $script       Script data.
	 * @param string                 $handle       Script handle to use.
	 * @param array                  $prev_scripts Map of script location to previously enqueued external scripts.
	 *                                             Passed by reference.
	 * @return bool True on success, false on failure.
	 */
	private function enqueue_external_script(
		ThirdPartyScriptOutput $script,
		string $handle,
		array &$prev_scripts
	): bool {
		if ( ThirdPartyScriptData::ACTION_APPEND === $script['action'] ) {
			$dependencies = $prev_scripts[ $script['location'] ];
		} else {
			$dependencies = array();
		}

		$args = ThirdPartyScriptData::LOCATION_BODY === $script['location']
			? array( 'in_footer' => true ) : array();

		// Add handle to the relevant list of already enqueued scripts (passed by reference).
		$prev_scripts[ $script['location'] ][] = $handle;

		wp_enqueue_script(
			$handle,
			$script['url'],
			$dependencies,
			null, // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			$args
		);
		return true;
	}

	/**
	 * Enqueues the given inline script.
	 *
	 * @param ThirdPartyScriptOutput $script       Script data.
	 * @param array                  $prev_scripts Map of script location and previously enqueued external scripts.
	 * @return bool True on success, false on failure.
	 */
	private function enqueue_inline_script(
		ThirdPartyScriptOutput $script,
		array $prev_scripts
	): bool {
		if ( ! $prev_scripts[ $script['location'] ] ) {
			return false;
		}

		if ( ThirdPartyScriptData::ACTION_APPEND === $script['action'] ) {
			$handle   = $prev_scripts[ $script['location'] ][ count( $prev_scripts[ $script['location'] ] ) - 1 ];
			$position = 'after';
		} else {
			$handle   = $prev_scripts[ $script['location'] ][0];
			$position = 'before';
		}

		wp_add_inline_script(
			$handle,
			$script['code'],
			$position
		);
		return true;
	}

	/**
	 * Enqueues the given standalone inline script.
	 *
	 * Other than regular inline scripts, such scripts are not directly attached to or depending on any external script.
	 *
	 * @param ThirdPartyScriptOutput $script Script data.
	 * @return bool True on success, false on failure.
	 */
	private function enqueue_standalone_inline_script( ThirdPartyScriptOutput $script ): bool {
		// If head script to prepend, print immediately.
		if (
			ThirdPartyScriptData::LOCATION_HEAD === $script['location'] &&
			ThirdPartyScriptData::ACTION_PREPEND === $script['action']
		) {
			wp_print_inline_script_tag( $script['code'] );
			return true;
		}

		// Otherwise, add action to print it in roughly the right place.
		add_action(
			ThirdPartyScriptData::LOCATION_BODY === $script['location'] ? 'wp_footer' : 'wp_head',
			static function () use ( $script ) {
				wp_print_inline_script_tag( $script['code'] );
			},
			ThirdPartyScriptData::ACTION_APPEND === $script['action'] ? PHP_INT_MAX : PHP_INT_MIN
		);
		return true;
	}

	/**
	 * Returns the handle to register the given stylesheet with in WordPress.
	 *
	 * @param string $id    Third party identifier.
	 * @param int    $index Index of the stylesheet in the list.
	 * @return string Stylesheet handle.
	 */
	private function create_style_handle( string $id, int $index ): string {
		return $index > 0 ? "{$id}-{$index}" : $id;
	}

	/**
	 * Returns the handle to register the given script with in WordPress.
	 *
	 * @param string                 $id     Third party identifier.
	 * @param int                    $index  Index of the script in the list.
	 * @param ThirdPartyScriptOutput $script Script data.
	 * @return string Script handle.
	 */
	private function create_script_handle( string $id, int $index, ThirdPartyScriptOutput $script ): string {
		if ( isset( $script['key'] ) ) {
			return "{$id}-{$script['key']}";
		}
		return $index > 0 ? "{$id}-{$index}" : $id;
	}
}
