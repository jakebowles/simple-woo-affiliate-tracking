<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       jakebowles.com/simple-woo-affiliate-tracking
 *
 * @package    Simple_Woo_Affiliate_Tracking
 * @subpackage Simple_Woo_Affiliate_Tracking/includes
 */
/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Simple_Woo_Affiliate_Tracking
 * @subpackage Simple_Woo_Affiliate_Tracking/includes
 * @author     Jake Bowles jake@jakebowles.com
 */
class Simple_Woo_Affiliate_Tracking_Loader {

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @access   protected
	 * @var      array    $actions    The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @access   protected
	 * @var      array    $filters    The filters registered with WordPress to fire when the plugin loads.
	 */
	protected $filters;


	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 */
	public function __construct() {

		$this->actions = array();
		$this->filters = array();

	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @var      string               $hook             The name of the WordPress action that is being registered.
	 * @var      object               $component        A reference to the instance of the object on which the action is defined.
	 * @var      string               $callback         The name of the function definition on the $component.
	 * @var      int      Optional    $priority         The priority at which the function should be fired.
	 * @var      int      Optional    $accepted_args    The number of arguments that should be passed to the $callback.
	 */
	public function add_action( $hook, $component, $callback ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback );
	}


	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @var      string               $hook             The name of the WordPress filter that is being registered.
	 * @var      object               $component        A reference to the instance of the object on which the filter is defined.
	 * @var      string               $callback         The name of the function definition on the $component.
	 * @var      int      Optional    $priority         The priority at which the function should be fired.
	 * @var      int      Optional    $accepted_args    The number of arguments that should be passed to the $callback.
	 */
	public function add_filter( $hook, $component, $callback ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @access   private
	 * @var      array                $hooks            The collection of hooks that is being registered (that is, actions or filters).
	 * @var      string               $hook             The name of the WordPress filter that is being registered.
	 * @var      object               $component        A reference to the instance of the object on which the filter is defined.
	 * @var      string               $callback         The name of the function definition on the $component.
	 * @var      int      Optional    $priority         The priority at which the function should be fired.
	 * @var      int      Optional    $accepted_args    The number of arguments that should be passed to the $callback.
	 * @return   array                                  The collection of actions and filters registered with WordPress.
	 */
	public function add( $hooks, $hook, $component, $callback ) {

		$hooks[] = array(
			'hook'		=> $hook,
			'component' => $component,
			'callback'  => $callback
		);

		return $hooks;

	}

	/**
	 * Register the filters and actions with WordPress.
	 *
	 */
	public function run() {

		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ) );
		}

		foreach ($this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ) );
		}

	}

}