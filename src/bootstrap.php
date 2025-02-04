<?php
/**
 * ItalyStrap Bootstrap File
 *
 * This is the bootstrapping for the ItalyStrap framework.
 *
 *
 * @package ItalyStrap
 * @since 4.0.0
 *
 * @TODO https://github.com/understrap/understrap/issues/585
 */

namespace ItalyStrap;

use Auryn\InjectorException;
use ItalyStrap\Config;
use ItalyStrap\Core;

/**
 * ========================================================================
 *
 * Autoload theme core files.
 *
 * ========================================================================
 */
$autoload_theme_files = [
	'/vendor/autoload.php',
	'/functions/edd.php',
	'/functions/default-constants.php',
	'/functions/general-functions.php',
	'/functions/config-helpers.php',
	'/functions/comments-helpers.php',
	'/functions/italystrap.php',
	'/functions/injector.php',

	'/functions/images.php',
	'/functions/pointer.php',
];

/**
 * ========================================================================
 *
 * Do you want to load deprecated files?
 *
 * ========================================================================
 */
if ( apply_filters( 'italystrap_load_deprecated', true ) ) {
	$autoload_theme_files[] = '/deprecated/autoload.php';
}

foreach ( $autoload_theme_files as $file ) {
	require __DIR__ . '/..' . $file;
}

//d( glob( __DIR__ . '/../functions/*.php' ) );

/**
 * Get the Injector instance
 *
 * @var \Auryn\Injector
 */
$injector = Factory\get_injector();

/**
 * Set the default theme constant
 *
 * @see /config/constants.php
 *
 * @var array $constants
 */
$constants = Core\set_default_constants( Config\get_config_file_content( 'constants' ) );

/**
 * ========================================================================
 *
 * Define CURRENT_TEMPLATE and CURRENT_TEMPLATE_SLUG constant.
 * Make sure Router runs after 99998.
 *
 * @see \ItalyStrap\Core\set_current_template_constants()
 *
 * ========================================================================
 */
add_filter( 'template_include', '\ItalyStrap\Core\set_current_template_constants', 99998 );

try {

	/**
	 * Just in case ACM is not active
	 */
	$injector->share( '\ItalyStrap\Config\Config' );
	$config = $injector->make( '\ItalyStrap\Config\Config' );

	if ( ! isset( $theme_mods ) ) {
		$theme_mods = (array) get_theme_mods();
	}

	$theme_mods = Core\wp_parse_args_recursive(
		$theme_mods,
		Config\get_config_file_content( 'default' )
	);
	$config->merge( $theme_mods );
	$config->merge( $constants );

	$theme_supports = Config\get_config_file_content( 'theme-supports' );
	$config->merge( $theme_supports );

	/**
	 * @var array $dependencies
	 */
	$dependencies = Config\get_config_file_content( 'dependencies' );

	/**
	 * ========================================================================
	 *
	 * Autoload Concrete Classes
	 *
	 * ========================================================================
	 *
	 * @see _init & _init_admin
	 */
	$dependencies_admin = require '_init_admin.php';
	$dependencies_front = require '_init.php';


	$theme_loader = $injector->make( 'ItalyStrap\Theme_Test_Load' );

	$theme_loader->set_dependencies( $dependencies );
	$theme_loader->add_concretes( $dependencies_admin );
	$theme_loader->add_concretes( $dependencies_front );

	add_action( 'italystrap_theme_load', [ $theme_loader, 'load' ] );

} catch ( InjectorException $exception ) {
	_doing_it_wrong( get_class( $injector ), $exception->getMessage(), '4.0.0' );
} catch ( \Exception $exception ) {
	_doing_it_wrong( 'General error.', $exception->getMessage(), '4.0.0' );
}

add_action( 'after_setup_theme', function () {

	/**
	 * Injector from ACM if is active
	 *
	 * @var \Auryn\Injector
	 */
	$injector = Factory\get_injector();

	/**
	 * Fires before ItalyStrap theme load.
	 *
	 * @since 2.0.0
	 */
	do_action( 'italystrap_theme_will_load', $injector );

	/**
	 * Fires once ItalyStrap theme is loading.
	 *
	 * @since 2.0.0
	 */
	do_action( 'italystrap_theme_load', $injector );

	/**
	 * Fires once ItalyStrap theme has loaded.
	 *
	 * @since 2.0.0
	 */
	do_action( 'italystrap_theme_loaded', $injector );

}, 20 );
