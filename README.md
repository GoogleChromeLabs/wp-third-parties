# WordPress Third-Parties Composer package

This package is a collection of classes and utilities that can be used to efficiently load third-party libraries into your WordPress application.

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

## Getting started

```
composer install
```
