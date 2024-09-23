# WordPress Third-Parties Composer package

This package is a collection of classes and utilities that can be used to efficiently load third-party libraries into your WordPress application.

It relies on the platform agnostic solution from [Third Party Capital](https://github.com/GoogleChromeLabs/third-party-capital).

## Usage

### Google Analytics

```php
$ga = new Google_Chrome_Labs\WP_Third_Parties\Third_Parties\Google_Analytics(
	array(
		'id' => 'G-...', // Replace this with your actual Google Analytics ID.
	)
);

add_action( 'wp_loaded', array( $ga, 'add_hooks' ) );
```

See the [Google Analytics JSON schema](https://github.com/GoogleChromeLabs/third-party-capital/blob/main/data/google-analytics.json) for the full list of supported arguments.

### Google Tag Manager

```php
$gtm = new Google_Chrome_Labs\WP_Third_Parties\Third_Parties\Google_Tag_Manager(
	array(
		'id' => 'GTM-...', // Replace this with your actual Google Tag Manager ID.
	)
);

add_action( 'wp_loaded', array( $gtm, 'add_hooks' ) );
```

See the [Google Tag Manager JSON schema](https://github.com/GoogleChromeLabs/third-party-capital/blob/main/data/google-tag-manager.json) for the full list of supported arguments.

### Google Maps Embed

```php
$gme = new Google_Chrome_Labs\WP_Third_Parties\Third_Parties\Google_Maps_Embed(
	array(
		'mode' => '...', // Provide this and other parameters like 'key', 'q', 'center', etc.
	)
);

// To actually render the Google Maps Embed, use this.
echo $gme->get_html();
```

See the [Google Maps Embed JSON schema](https://github.com/GoogleChromeLabs/third-party-capital/blob/main/data/google-maps-embed.json) for the full list of supported arguments.

### YouTube Embed

```php
$yte = new Google_Chrome_Labs\WP_Third_Parties\Third_Parties\YouTube_Embed(
	array(
		'videoid' => '...', // Replace this with your actual YouTube video ID.
	)
);

add_action( 'wp_loaded', array( $yte, 'add_hooks' ) );

// To actually render the YouTube Embed, use this.
echo $yte->get_html();
```

See the [YouTube Embed JSON schema](https://github.com/GoogleChromeLabs/third-party-capital/blob/main/data/youtube-embed.json) for the full list of supported arguments.

## Getting started

```
composer install
```
