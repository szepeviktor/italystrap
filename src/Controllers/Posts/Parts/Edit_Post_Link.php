<?php
/**
 * Edit_Post_Link Controller API
 *
 * This class renders the Edit_Post_Link output on the registered position.
 *
 * @link www.italystrap.com
 * @since 4.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Controllers\Posts\Parts;

use ItalyStrap\Controllers\Controller;
use ItalyStrap\Event\Subscriber_Interface;

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

/**
 * Class description
 */
class Edit_Post_Link extends Controller implements Subscriber_Interface  {

	/**
	 * Returns an array of hooks that this subscriber wants to register with
	 * the WordPress plugin API.
	 *
	 * @hoocked 'italystrap_after_entry_content' - 20
	 *
	 * @return array
	 */
	public static function get_subscribed_events() {

		return array(
			// 'hook_name'							=> 'method_name',
			'italystrap_after_entry_content'	=> array(
				'function_to_add'	=> 'render',
				// 'priority'			=> 20,
				'priority'			=> 999,
			),
			// 'woocommerce_after_single_product'	=> array(
			// 	'function_to_add'	=> 'render',
			// 	'priority'			=> 20,
			// ),
			// 'the_content'	=> array(
			// 	'function_to_add'	=> 'append_to_content',
			// 	'priority'			=> 99999999,
			// ),
		);
	}

	/**
	 * Render the output of the controller.
	 */
	public function render() {

		if ( ! current_theme_supports( 'italystrap_edit_delete_post_link' ) ) {
			return;
		}

		echo $this->append_to_content();
		return;

		/**
		 * Arguments for edit_post_link()
		 *
		 * @var array
		 */
		$args = array(
			/* translators: %s: Name of current post */
			'link_text'	=> __( 'Edit<span class="screen-reader-text"> "%s"</span>', 'italystrap' ),
			'before'	=> '<p>',
			'after'		=> '</p>',
			'class'		=> 'btn btn-sm btn-primary', // 4.4.0
		);

		$args = apply_filters( 'italystrap_edit_post_link_args', $args );

		edit_post_link(
			sprintf(
				$args['link_text'],
				get_the_title()
			),
			$args['before'],
			$args['after'],
			null,
			$args['class']
		);

		// printf(
		// 	'<a class="%s" href="%s">%s</a>',
		// 	'btn btn-sm btn-danger',
		// 	get_delete_post_link(),
		// 	'Delete'
		// );
	}

	/**
	 * Add edit and delete post link to the content.
	 *
	 * @param  string $content The post content.
	 *
	 * @return string          The post content with the links.
	 */
	public function append_to_content( $content = '' ) {

		if ( is_archive() ) {
			return $content;
		}

		$edit_post = get_edit_post_link();

		if ( ! $edit_post ) {
			return $content;
		}

		$delete_post = get_delete_post_link();

		$content .= sprintf(
			'<p><small><a class="" href="%s">%s</a> - <a class="" href="%s">%s</a></small></p>',
			esc_url( $edit_post ),
			__( 'Edit This', 'italystrap' ),
			esc_url( $delete_post ),
			__( 'Delete post', 'italystrap' )
		);
	
		return $content;
	}
}
