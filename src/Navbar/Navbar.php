<?php
/**
 * Navbar Menu template Class
 *
 * @example http://www.bootply.com/mQh8DyRfWY bootstrap navbar center logo
 *
 * @package ItalyStrap\Core
 * @since 4.0.0
 */

namespace ItalyStrap\Navbar;

use ItalyStrap\Config\Config_Interface;
use ItalyStrap\HTML;
use Walker_Nav_Menu;

/**
 * Template for Navbar like Botstrap CSS
 */
class Navbar {

	/**
	 * Count the number of instance
	 *
	 * @var integer
	 */
	private static $instance_count = 0;

	/**
	 * The number of this instance
	 *
	 * @var integer
	 */
	private $number;

	/**
	 * The ID of the Navbar instance
	 *
	 * @var string
	 */
	private $navbar_id;

	/**
	 * Config instance
	 *
	 * @var Config_Interface
	 */
	private $config;

	/**
	 * Walker instance
	 *
	 * @var Walker_Nav_Menu
	 */
	private $walker;

	/**
	 * @var bool|callable   $fallback_cb If the menu doesn't exists, a callback function will fire.
	 * 						Default is 'wp_page_menu'. Set to false for no fallback.
	 */
	private $fallback_cb;

	/**
	 * Init the constructor
	 *
	 * @param Config_Interface $config
	 * @param Walker_Nav_Menu  $walker
	 * @param callable|bool    $fallback_cb If the menu doesn't exists, a callback function will fire.
	 * 										Default is 'wp_page_menu'. Set to false for no fallback.
	 */
	public function __construct( Config_Interface $config, Walker_Nav_Menu $walker, $fallback_cb = false ) {

		$this->config = $config;
		$this->walker = $walker;
		$this->fallback_cb = $fallback_cb;

		/**
		 * Count this instance
		 */
		self::$instance_count ++;

		$this->number = self::$instance_count;

		$this->navbar_id = apply_filters( 'italystrap_navbar_id', 'italystrap-menu-' . $this->number );
		$this->navbar_id = apply_filters( 'italystrap_navbar_id_' . $this->number, $this->navbar_id );

		$this->theme_mods = $this->config->all();
	}

	/**
	 * Get the wp_nav_menu with default parameters for Bootstrap CSS style
	 *
	 * @param  array $args The wp_nav_menu arguments.
	 *
	 * @return string      Return the wp_nav_menu HTML
	 */
	public function get_wp_nav_menu( array $args = [] ) {

		/**
		 * Arguments for wp_nav_menu()
		 * For filtering wp_nav_menu use the 'wp_nav_menu' hooks with 2 parameters
		 * add_filter( 'wp_nav_menu', 'your_functions', 10, 2 );
		 * For this situation the container attribute is set to false because
		 * we need the collapsable functionality of Bootstrap CSS.
		 *
		 * @link https://developer.wordpress.org/reference/functions/wp_nav_menu/
		 * @var array
		 */
		$defaults = array(
			'menu'				=> '',
			'container'			=> false, // WP Default div.
			'container_class'	=> false,
			'container_id'		=> false,
			'menu_class'		=> 'nav navbar-nav',
			'menu_id'			=> 'main-menu',
			'echo'				=> false,
			'fallback_cb'		=> $this->fallback_cb,
			'before'			=> '',
			'after'				=> '',
			'link_before'		=> '<span class="item-title" itemprop="name">',
			'link_after'		=> '</span>',
			'items_wrap'		=> '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'item_spacing'		=> 'preserve',
			'depth'				=> 10,
			'walker'			=> $this->walker,
			'theme_location'	=> 'main-menu',
			'search'			=> false,
		);

		$args = wp_parse_args( $args, $defaults );

		$args = apply_filters( 'italystrap_' . $args[ 'theme_location' ] . '_args', $args, $this->navbar_id );

		return wp_nav_menu( $args );
	}

	/**
	 * Get secondary wp-nav-menu
	 *
	 * @return string      Return the secondary wp_nav_menu HTML
	 */
	public function get_secondary_wp_nav_menu() {

		if ( ! has_nav_menu( 'secondary-menu' ) ) {
			return '';
		}

		$args = array(
			'menu_class'		=> 'nav navbar-nav navbar-right',
			'menu_id'			=> 'secondary-menu',
			'fallback_cb'		=> false,
			'theme_location'	=> 'secondary-menu',
		);

		return $this->get_wp_nav_menu( $args );
	}

	/**
	 * Get Brand
	 *
	 * @return string Return the HTML for brand name and/or image.
	 */
	public function get_brand() {

		/**
		 * The ID of the logo image for navbar
		 * By default in the customizer is set a url for the image instead of an integer
		 * When it is choices an image than it will set an integer for $this->theme_mods['navbar_logo']
		 *
		 * @var integer
		 */
		$attachment_id = (int)apply_filters( 'italystrap_navbar_logo_image_id', $this->config->get( 'navbar_logo_image' ) );

		$brand = '';

		if ( $attachment_id && 'display_image' === $this->theme_mods[ 'display_navbar_brand' ] ) {

			$attr = array(
				'class' => 'img-brand img-responsive center-block',
				'alt' => esc_attr( GET_BLOGINFO_NAME ) . ' &dash; ' . esc_attr( GET_BLOGINFO_DESCRIPTION ),
				'itemprop' => 'image',
			);

			/**
			 * Size default: navbar-brand-image
			 */
			$brand .= wp_get_attachment_image( $attachment_id, $this->theme_mods[ 'navbar_logo_image_size' ], false, $attr );

			$brand .= '<meta  itemprop="name" content="' . esc_attr( GET_BLOGINFO_NAME ) . '"/>';

		} elseif ( $attachment_id && 'display_all' === $this->theme_mods[ 'display_navbar_brand' ] ) {

			$attr = array(
				'class' => 'img-brand img-responsive center-block',
				'alt' => esc_attr( GET_BLOGINFO_NAME ) . ' - ' . esc_attr( GET_BLOGINFO_DESCRIPTION ),
				'itemprop' => 'image',
				'style' => 'display:inline;margin-right:15px;',
			);
			/**
			 * Size default: navbar-brand-image
			 */
			$brand .= wp_get_attachment_image( $attachment_id, $this->theme_mods[ 'navbar_logo_image_size' ], false, $attr );

			$brand .= '<span class="brand-name" itemprop="name">' . esc_attr( GET_BLOGINFO_NAME ) . '</span>';

		} else {

			$brand .= '<span class="brand-name" itemprop="name">' . esc_attr( GET_BLOGINFO_NAME ) . '</span><meta  itemprop="image" content="' . \italystrap_get_the_custom_image_url( 'logo', TEMPLATEURL . '/img/italystrap-logo.jpg' ) . '"/>';

		}

		return $brand;
	}

	/**
	 * Get the HTML for description
	 *
	 * @param  array $attr The navbar brand attributes.
	 *
	 * @return string       Return the HTML for description
	 */
	public function get_navbar_brand( array $attr = array() ) {

		if ( 'none' === $this->theme_mods[ 'display_navbar_brand' ] ) {
			return apply_filters( 'italystrap_navbar_brand_none', '', $this->navbar_id );
		}

		$default = array(
			'class' => 'navbar-brand',
			'href' => esc_url( $this->config->get( 'HOME_URL' ) ),
			'title' => sprintf(
				'%s  -  %s',
				$this->config->get( 'GET_BLOGINFO_NAME' ),
				$this->config->get( 'GET_BLOGINFO_DESCRIPTION' )
			),
			'rel' => 'home',
			'itemprop' => 'url',
		);

		return $this->create_element(
			'navbar_brand',
			'a',
			array_merge( $default, $attr ),
			$this->get_brand()
		);
	}

	/**
	 * Get the HTML for toggle button
	 *
	 * @return string Return the HTML for toggle button
	 */
	public function get_toggle_button() {

		$icon_bar = apply_filters(
			'italystrap_icon_bar',
			'<span class="icon-bar">&nbsp</span><span class="icon-bar">&nbsp</span><span class="icon-bar">&nbsp</span>'
		);

		$a = array(
			'class' => 'navbar-toggle',
			'data-toggle' => 'collapse',
			'data-target' => '#' . $this->navbar_id,
		);

//		$output = sprintf(
//			'<button%s><span class="sr-only">%s</span>%s</button>',
//			$this->get_attr( $a, 'toggle_button' ),
//			esc_attr__( 'Toggle navigation', 'italystrap' ),
//			$icon_bar
//		);
//
//		return apply_filters( 'italystrap_toggle_button', $output, $this->navbar_id );
		/**
		 * '<button%s><span class="sr-only">%s</span>%s</button>'
		 */
		return $this->create_element(
			'toggle_button',
			'button',
			$a,
			$this->create_element(
				'toggle_button_content',
				'span',
				['class' => 'sr-only screen-reader-text'],
				esc_attr__( 'Toggle navigation', 'italystrap' )
			) . $icon_bar
		);
	}

	/**
	 * Get the HTML for Navbar Header
	 *
	 * @return string Return the HTML for Navbar Header
	 */
	public function get_navbar_header() {

		$a = [
			'class' => 'navbar-header',
			'itemprop' => 'publisher',
			'itemscope' => true,
			'itemtype' => 'https://schema.org/Organization',
		];

		return $this->create_element(
			'navbar_header',
			'div',
			$a,
			$this->get_navbar_brand() . $this->get_toggle_button()
		);
	}

	/**
	 * Get the collapsable HTML menu
	 *
	 * @TODO $output .= get_search_form();
	 * http://bootsnipp.com/snippets/featured/expanding-search-button-in-css
	 *
	 * @return string Return the HTML
	 */
	public function get_collapsable_menu() {

		$a = array(
			'id' => $this->navbar_id,
			'class' => 'navbar-collapse collapse',
		);

		return $this->create_element(
			'collapsable_menu',
			'div',
			$a,
			$this->get_wp_nav_menu() . $this->get_secondary_wp_nav_menu()
		);
	}

	/**
	 * A container inside the navbar-default/revers
	 *
	 * @return string The html output.
	 */
	public function get_last_container() {
//		add_filter( 'italystrap_pre_last_container', '__return_true' );
		$a = [
			'id' => 'menus-container-' . $this->number,
			'class' => $this->theme_mods[ 'navbar' ][ 'menus_width' ],
		];

		return $this->create_element(
			'last_container',
			'div',
			$a,
			$this->get_navbar_header() . $this->get_collapsable_menu()
		);
	}

	/**
	 * The regulare navbar container,
	 * this manage the type of navabr available from Twitter Bootstrap
	 *
	 * @see http://getbootstrap.com/components/#navbar
	 *
	 * navbar-default
	 * navbar-inverse
	 *
	 * navbar navbar-default navbar-relative-top
	 *
	 * navbar navbar-default navbar-fixed-top // body { padding-top: 70px; }
	 * navbar navbar-default navbar-fixed-bottom // body { padding-bottom: 70px; }
	 *
	 * navbar navbar-default navbar-static-top
	 *
	 * @return string The navbar string.
	 */
	public function get_navbar_container() {

		$a = [
			'class' => sprintf(
				'navbar %s %s',
				$this->theme_mods[ 'navbar' ][ 'type' ],
				$this->theme_mods[ 'navbar' ][ 'position' ]
			),
			'itemscope' => true,
			'itemtype' => 'https://schema.org/SiteNavigationElement',
		];

		return $this->create_element(
			'navbar_container',
			'nav',
			$a,
			$this->get_last_container()
		);

	}

	/**
	 * Generate the nav tag container of entire navbar
	 *
	 * @see http://getbootstrap.com/components/#navbar
	 *
	 * This manage the full width or boxed width (.conainer or null)
	 *
	 * @return string Return the entire navbar.
	 */
	public function get_nav_container() {

//		if ( 'none' === $this->theme_mods[ 'navbar' ][ 'nav_width' ] ) {
//			return $this->get_navbar_container();
//		}

//		d( $this->config );

		$a = [
			'id'	=> 'main-navbar-container-' . $this->navbar_id,
			'class' => sprintf(
				'navbar-wrapper %s',
				$this->theme_mods[ 'navbar' ][ 'nav_width' ]
			),
		];

		return $this->create_element( 'nav_container', 'div', $a, $this->get_navbar_container() );
	}

	/**
	 * @param  $context
	 * @param  $tag
	 * @param  array $attr
	 * @param  $content
	 *
	 * @return string
	 */
	private function create_element( $context, $tag, array $attr, $content ) {

		if ( !is_string( $context ) ) {
			throw new \InvalidArgumentException( 'The $context variable must be a string', 0 );
		}

		if ( !is_string( $tag ) ) {
			throw new \InvalidArgumentException( 'The $tag variable must be a string', 0 );
		}

		if ( !is_string( $content ) ) {
			throw new \InvalidArgumentException( 'The $content variable must be a string', 0 );
		}

		$content = (string)apply_filters( 'italystrap_' . $context . '_child', $content, $this->navbar_id );

		if ( empty( $content ) ) {
			$content = '&nbsp;';
		}

		if ( (bool)apply_filters( 'italystrap_pre_' . $context, false ) ) {
			return $content;
		}

		$tag = apply_filters( 'italystrap_' . $context . '_tag', $tag, $this->navbar_id );

		$output = sprintf(
			'<%1$s%2$s>%3$s</%1$s>',
			esc_attr( $tag ),
			$this->get_attr( $attr, $context ),
			$content
		);

		return apply_filters( 'italystrap_' . $context, $output, $this->navbar_id );
	}

	/**
	 * Render the HTML tag attributes from an array
	 *
	 * @param  array $attr The HTML attributes with key value.
	 * @param  string $context
	 *
	 * @return string          Return a string with HTML attributes
	 */
	private function get_attr( array $attr = [], $context = '' ) {
		return HTML\get_attr( $context, $attr, false, $this->navbar_id );
	}

	/**
	 * @return string
	 */
	public function render() {
		return $this->get_nav_container();
	}

	/**
	 * Output the HTML
	 */
	public function output() {
		echo $this->render();
	}
}
