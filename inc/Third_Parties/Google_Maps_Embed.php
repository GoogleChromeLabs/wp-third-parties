<?php
/**
 * Class Google_Chrome_Labs\WP_Third_Parties\Third_Parties\Google_Maps_Embed
 *
 * @package   Google_Chrome_Labs/WP_Third_Parties
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace Google_Chrome_Labs\WP_Third_Parties\Third_Parties;

use GoogleChromeLabs\ThirdPartyCapital\Contracts\ThirdParty;
use GoogleChromeLabs\ThirdPartyCapital\ThirdParties\GoogleMapsEmbed;

/**
 * Class representing the Google Maps Embed integration.
 */
class Google_Maps_Embed extends WP_Third_Party_Base {

	/**
	 * Gets the path to the third party data JSON file.
	 *
	 * @param array<string, mixed> $args Input arguments to set.
	 * @return ThirdParty Reference to the WordPress-agnostic third party implementation.
	 */
	protected function create_third_party( array $args ): ThirdParty {
		return new GoogleMapsEmbed( $args );
	}
}
