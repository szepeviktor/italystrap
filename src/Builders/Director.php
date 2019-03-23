<?php
/**
 * Created by PhpStorm.
 * User: fisso
 * Date: 18/03/2019
 * Time: 12:39
 */

namespace ItalyStrap\Builders;

use ItalyStrap\Event\Subscriber_Interface;
use function \ItalyStrap\Config\get_config_file_content;
use function \ItalyStrap\Factory\get_event_manager;

class Director implements Subscriber_Interface {

	/**
	 * Returns an array of events (hooks) that this subscriber wants to register with
	 * the Events Manager API.
	 *
	 * The array key is the name of the hook. The value can be:
	 *
	 *  * The method name
	 *  * An array with the method name and priority
	 *  * An array with the method name, priority and number of accepted arguments
	 *
	 * For instance:
	 *
	 * array(
	 *     // 'event_name'             => 'method_name',
	 *     'italystrap_before_header'  => 'render',
	 * )
	 * array(
	 *     // 'event_name'                     => 'method_name',
	 *     'italystrap_before_entry_content'   => array(
	 *         'function_to_add'   => 'render',
	 *         'priority'          => 30, // Default 10
	 *         'accepted_args'     => 1 // Default 1
	 *     ),
	 * );
	 *
	 * @return array
	 */
	public static function get_subscribed_events() {
		return [
			'wp'	=> 'create_page', // @TODO is it a good hook? Or I have to create one just before the 'italystrap'
		];
	}

	/**
	 * @var Builder_Interface
	 */
	private $builder;

	/**
	 * Director constructor.
	 *
	 * @param Builder_Interface $builder
	 */
	public function __construct( Builder_Interface $builder ) {
		$this->builder = $builder;
	}

	/**
	 * Create the page
	 */
	public function create_page() {

		/**
		 * Injector setter is run on dependencies.php
		 */
		$this->builder->set_structure( get_config_file_content( 'structure' ) )->build();
	}

	/**
	 * Remove from hooks
	 */
	public function destroy() {
		get_event_manager()->remove_subscriber( $this );
	}
}