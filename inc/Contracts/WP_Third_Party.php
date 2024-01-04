<?php
/**
 * Interface Google_Chrome_Labs\WP_Third_Parties\Contracts\WP_Third_Party
 *
 * @package   Google_Chrome_Labs/WP_Third_Parties
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace Google_Chrome_Labs\WP_Third_Parties\Contracts;

/**
 * Interface for a class representing a third party integration.
 */
interface WP_Third_Party {

	/**
	 * Sets input arguments for the integration.
	 *
	 * @param array $args Input arguments to set.
	 */
	public function set_args( array $args );

	/**
	 * Adds hooks to WordPress to load the integration.
	 *
	 * Must be called anytime before the {@see 'template_redirect'} action hook.
	 */
	public function add_hooks();

	/**
	 * Gets the HTML output for the integration.
	 *
	 * Only relevant if the integration provides user-facing output.
	 *
	 * @return string HTML output, or empty string if not applicable.
	 */
	public function get_html(): string;

	/**
	 * Returns the handles of all stylesheets that are part of the integration.
	 *
	 * These handles can be used as dependencies for other stylesheets registered in WordPress.
	 *
	 * @return string[] List of stylesheet handles, or empty array if there are none.
	 */
	public function get_style_handles(): array;

	/**
	 * Returns the handles of all scripts that are part of the integration.
	 *
	 * These handles can be used as dependencies for other scripts registered in WordPress.
	 *
	 * @return string[] List of script handles, or empty array if there are none.
	 */
	public function get_script_handles(): array;
}
