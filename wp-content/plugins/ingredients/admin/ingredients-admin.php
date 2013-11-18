<?php
/**
 * Plugin Name.
 *
 * @package   Ingredients_Admin
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-plugin-name.php`
 *
 * @TODO: Rename this class to a proper name for your plugin.
 *
 * @package Ingredients_Admin
 * @author  Your Name <email@example.com>
 */
class Ingredients_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		/*
		 * Call $plugin_slug from public plugin class.
		 *
		 * @TODO:
		 *
		 * - Rename "Plugin_Name" to the name of your initial plugin class
		 *
		 */
		$plugin = Ingredients::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		/*
		 * Define custom functionality.
		 *
		 * Read more about actions and filters:
		 * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		//add_action( '@TODO', array( $this, 'action_method_name' ) );
		//add_filter( '@TODO', array( $this, 'filter_method_name' ) );

		add_action( 'add_meta_boxes', array( $this, 'add_ingredients_metaboxes' ) );
		add_action( 'save_post', array( $this, 'save_ingredients' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @TODO:
	 *
	 * - Rename "Plugin_Name" to the name your plugin
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		//$screen = get_current_screen();
		//if ( $this->plugin_screen_hook_suffix == $screen->id ) {
		//	wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), Ingredients::VERSION );
		//}

		$screen = get_current_screen();
		//var_dump($screen);
		if ($screen->post_type === 'recipe' && $screen->base === 'post') {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), Ingredients::VERSION );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @TODO:
	 *
	 * - Rename "Ingredients" to the name your plugin
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		//$screen = get_current_screen();
		//if ( $this->plugin_screen_hook_suffix == $screen->id ) {
		//	wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), Ingredients::VERSION );
		//}

		$screen = get_current_screen();
		//var_dump($screen);
		if ($screen->post_type === 'recipe' && $screen->base === 'post') {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), Ingredients::VERSION );
		}

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 * @TODO:
		 *
		 * - Change 'Page Title' to the title of your plugin admin page
		 * - Change 'Menu Text' to the text for menu item for the plugin settings page
		 * - Change 'manage_options' to the capability you see fit
		 *   For reference: http://codex.wordpress.org/Roles_and_Capabilities
		 */
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Page Title', $this->plugin_slug ),
			__( 'Menu Text', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}

	/**
	 * NOTE:     Actions are points in the execution of a page or process
	 *           lifecycle that WordPress fires.
	 *
	 *           Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *           Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// @TODO: Define your action hook callback here
	}

	/**
	 * NOTE:     Filters are points of execution in which WordPress modifies data
	 *           before saving it or sending it to the browser.
	 *
	 *           Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *           Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// @TODO: Define your filter hook callback here
	}

	function add_ingredients_metaboxes() {
    	add_meta_box(
    		'ingredients',									//The ID attribute of the 'Edit' screen
    		__('Ingredients', 'ingredients'),				//The localized version of the title of the meta box
    		array( $this, 'ingredients_metabox_display'),	//A reference to the function for rendering the meta box
    		'recipe',												//Where to display the meta box in the dashboard
    		'normal',											//The priority of the meta box for where it should display
    		'high'												//Where the box should be displayed.
    	);
	}

	function ingredients_metabox_display( $post ){
		wp_nonce_field(plugin_basename(__FILE__),'ingredients_nonce');

		$data = get_post_meta($post->ID, 'ingredients_data', true);

		//var_dump($urls);
		$html = '<div id="ingredients_metabox">';
		$html .= '<div data-name="ingredients">';
		$html .= '<p class="description">Paste youtube video url or select an image.</p>';
		$html .= '<ul class="ingredients_rows">';
		$index = 0;
		for(;$index < count($data) && !empty($data); $index++){
			$type  = isset($data[$index]['type']) ? $data[$index]['type'] : '';
			$url   = isset($data[$index]['url']) ? $data[$index]['url'] : '';
			$cover = isset($data[$index]['cover']) ? $data[$index]['cover'] : '';
			$title = isset($data[$index]['title']) ? $data[$index]['title'] : '';
			$html .= '<li class="ingredients_row"><span class="ingredients_move"></span>';

			//type
			$html .= '<select class="ingredients_type" name="ingredients_type[]">';
			foreach ($this->mediaTypes as $value => $name) {
				$html .= '<option';
				$selected = false;
				if ($type === $value){
					$html .= ' selected="selected"';
					$selected = true;
				}
				$html .= ' value="'.$value.'">'.$name.'</option>';
			}
			$html .= '</select>';

			//url
			$html .= '<div>';
			$html .= '<input class="ingredients_url" name="ingredients_url[]" type="text" ';
			if ($type === 'photo' || !$selected){
				$html .= 'readonly="readonly" onfocus="this.blur();" ';
			}
			$html .= 'value="'.$url.'">';
			$html .= '<a href="javascript:;" class="ingredients_url_button button"';
			if ($type !== 'photo' && $selected){
				$html .= 'style="display: none;" ';
			}
			$html .= '>'. __('Select Image', 'comments-add') . '</a>';
			$html .= '</div>';

			//cover image
			$html .= '<div>';
			$html .= '<input class="ingredients_cover" name="ingredients_cover[]" type="text" ';
			$html .= 'readonly="readonly" onfocus="this.blur();" placeholder="Cover Url" ';
			if ($type === 'photo'){
				$html .= 'style="display: none;" ';
			}
			$html .= 'value="'.$cover.'">';
			$html .= '<a href="javascript:;" class="ingredients_cover_button button"';
			if ($type === 'photo'){
				$html .= 'style="display: none;" ';
			}
			$html .= '>'. __('Select Cover', 'comments-add') . '</a>';
			$html .= '</div>';

			//title
			$html .= '<input class="ingredients_title" name="ingredients_title[]" type="text" placeholder="Media Title" ';
			$html .= 'value="'.$title.'">';

			$html .= '<a class="ingredients_close">close</a>';
			$html .= '</li>';
		}
		$html .= '</ul>';
		$html .= '<a href="javascript:;" class="add-new-ingredients-item button">'. __('Add item', 'comments-add') . '</a>';
		$html .= '</div>';

		echo $html;
	}

	function save_ingredients( $post_id ) {
    	if( isset($_POST['ingredients_nonce']) && isset($_POST['post_type'])) {
    		
    		//Don't save if the user hasn't submitted the changes
    		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    			return;
    		}

    		// Verify that the input is coming from the proper form
    		if (!wp_verify_nonce($_POST['media_carousel_nonce'], plugin_basename(__FILE__))) {
    			return;
    		}

    		//Make sure the user has permission to post.
    		if ('post' == $_POST['post_type']){
    			if (!current_user_can('edit_post', $post_id)){
    				return;
    			}
    		}

    		//Get data
    		if (isset($_POST['ingredients_type'])){
	    		$dd = array(
		    		'types' => $_POST['ingredients_type'],
		    		'urls' => $_POST['ingredients_url'],
		    		'titles' => $_POST['ingredients_title'],
		    		'covers' => $_POST['ingredients_cover']
	    		);
	    		$cleanData = array();

		    	for($index = 0; $index < count($dd['types']); $index++){
	    			$type = esc_attr($dd['types'][$index]);
	    			$url = esc_url($dd['urls'][$index]);
	    			$title = esc_attr($dd['titles'][$index]);
	    			$cover = esc_url($dd['covers'][$index]);
	    			$cleanData[] = array(
						'type' => $type,
		    			'url' => $url,
		    			'title' => $title,
		    			'cover' => $cover
	    			);
		    	}
		    } else {
		    	$cleanData = false;
		    }

    		if (empty($cleanData)){
	    		delete_post_meta($post_id, 'ingredients_data', '');
	    	} else {
	    		update_post_meta($post_id, 'ingredients_data', $cleanData);
	    	}
    	}
	}
}
