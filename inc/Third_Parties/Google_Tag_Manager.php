<?php
/**
 * Class Google_Chrome_Labs\WP_Third_Parties\Third_Parties\Google_Tag_Manager
 *
 * @package   Google_Chrome_Labs/WP_Third_Parties
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace Google_Chrome_Labs\WP_Third_Parties\Third_Parties;

use GoogleChromeLabs\ThirdPartyCapital\Contracts\ThirdParty;
use GoogleChromeLabs\ThirdPartyCapital\ThirdParties\GoogleTagManager;

/**
 * Class representing the Google Tag Manager integration.
 */
class Google_Tag_Manager extends WP_Third_Party_Base {

	/**
	 * Gets the path to the third party data JSON file.
	 *
	 * @param array<string, mixed> $args Input arguments to set.
	 * @return ThirdParty Reference to the WordPress-agnostic third party implementation.
	 */
	protected function create_third_party( array $args ): ThirdParty {
		return new GoogleTagManager( $args );
	}
}
