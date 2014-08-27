<?php
/**
 * Plugin Name.
 *
 * @package   cn_wordpress_Admin
 * @author    Jesse Greathouse <jesse.greathouse@gmail.com>
 * @license   MIT
 * @link      http://cookingnutritious.com
 * @copyright 2014 Jesse Greathouse @cookingnutritious.com
 */
 
use cookingnutritious\CookingNutritiousClient\CookingNutritiousClient;
use cookingnutritious\CookingNutritiousClient\CookingNutritiousTools;

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-cn_wordpress.php`
 *
 * @TODO: Rename this class to a proper name for your plugin.
 *
 * @package cn_wordpress_Admin
 * @author  Jesse Greathouse <jesse.greathouse@gmail.com>
 */
class cn_wordpress_Admin {

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
		 * - Rename "cn_wordpress" to the name of your initial plugin class
		 *
		 */
		$plugin = cn_wordpress::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . $this->plugin_slug . '.php' );
        //Load Includes
        include( plugin_dir_path( __FILE__ ) . 'includes/index.php');
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		/*
		 * Define custom functionality.
		 *
		 * Read more about actions and filters:
		 * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_action ( 'edit_form_advanced', array( $this, 'advanced_add_api_input'));
        add_action ( 'save_post', array( $this, 'save_post_api'));
		#add_filter( '@TODO', array( $this, 'filter_method_name' ) );

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
	 * - Rename "cn_wordpress" to the name your plugin
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), cn_wordpress::VERSION );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @TODO:
	 *
	 * - Rename "cn_wordpress" to the name your plugin
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), cn_wordpress::VERSION );
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
			__( 'Cooking Nutritious Options', $this->plugin_slug ),
			__( 'CN Wordpress', $this->plugin_slug ),
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
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        
        $api_token = (isset($_POST['api_token']) && $_POST[ 'do_cn_options' ] == 'Y') ? $_POST['api_token'] : get_option( 'cn_api_token' );
		update_option('cn_api_token', $api_token );
        
        $use_parent_category = (isset($_POST['use_parent_category']) && $_POST[ 'do_cn_options' ] == 'Y') ? $_POST['use_parent_category'] : get_option( 'use_parent_category' );
        update_option('use_parent_category', $use_parent_category );
        
        $parent_category = (isset($_POST['parent_category']) && $_POST[ 'do_cn_options' ] == 'Y') ? $_POST['parent_category'] : get_option( 'parent_category' );
        update_option('parent_category', $parent_category );
        
        $category = (isset($_POST['category']) && $_POST[ 'do_cn_options' ] == 'Y') ? $_POST['category'] : get_option( 'category' );
        update_option('category', $category );
        
        $tags = (isset($_POST['tags']) && $_POST[ 'do_cn_options' ] == 'Y') ? $_POST['tags'] : get_option( 'tags' );
        update_option('tags', $tags );
        
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
    public static function advanced_add_api_input() {
        global $wpdb;
        $table_name = $wpdb->prefix . "cn_wordpress"; 
        $api_url = "";

        $post_id = isset($GLOBALS['post_ID']) ? (int) $GLOBALS['post_ID'] : 0;
        $row = $wpdb->get_row("SELECT * FROM $table_name WHERE post = $post_id");
        if (NULL !== $row) {
            $api_url = $row->url;
        }
        echo '<style>',"\n";
        echo '.input {display: block;}', "\n";
        echo '.input span {
              position: absolute;
              z-index: 1;
              cursor: text;
              pointer-events: none;
              /* Input padding + input border */
              /* Firefox does not respond well to different line heights. Use padding instead. */
              line-height: 1.4em;
              font-family: sans-serif;
              /* This gives a little gap between the cursor and the label */
              margin-left: 2px;
              font-size: 1.7em;
              padding: 11px 10px;
              vertical-align: middle;
              color: #bbb;
            }', "\n";
        echo '.input input, .input textarea, .input select {
              background-color: #fff;
              z-index: 0;
              padding: 3px 8px;
              margin: 1px 0;
              height: 1.7em;
              font: inherit;
              font-family: sans-serif;
              font-size: 1.7em;
              line-height: 100%;
              width: 100%;
              outline: 0;
              border-radius: 3px;
              border-width: 1px;
              border-style: solid;
            }', "\n";
        echo '.input select {
              padding: 5px;
              height: 31px;
            }', "\n";
        echo '</style>',"\n";
        echo '<div id="cn_api">',"\n";
        echo '<label class="input" for="api_url"><span>API Recipe URL</span><input type="text" name="api_url" value="'. $api_url .'"  /></label>',"\n";
        echo '</div>',"\n";
        echo '<script>',"\n";
        echo 'var placement = document.getElementById("titlewrap");',"\n";
        echo 'var includediv = document.getElementById("cn_api");',"\n";
        echo 'placement.parentNode.appendChild(includediv);',"\n";
        echo "(function($) {
                  function measureWidth(deflt) {
                    var dummy = $('<label></label>').text(deflt).css('visibility','hidden').appendTo(document.body);
                    var result = dummy.width();
                    dummy.remove();
                    return result;
                  }

                  function toggleLabel() {
                    var input = $(this);
                    var deflt = input.attr('title');
                    var span = input.prev('span');
                    setTimeout(function() {
                      if (!input.val() || (input.val() == deflt)) {
                        span.css('visibility', '');
                        if (deflt) {
                          span.css('margin-left', measureWidth(deflt) + 2 + 'px');
                        }
                      } else {
                        span.css('visibility', 'hidden');
                      }
                    }, 0);
                  };

                  $(document).on('cut', 'input, textarea', toggleLabel);
                  $(document).on('keydown', 'input, textarea', toggleLabel);
                  $(document).on('paste', 'input, textarea', toggleLabel);
                  $(document).on('change', 'select', toggleLabel);

                  $(document).on('focusin', 'input, textarea', function() {
                      $(this).prev('span').css('color', '#ccc');
                  });
                  $(document).on('focusout', 'input, textarea', function() {
                      $(this).prev('span').css('color', '#999');
                  });

                  function init() {
                    $('input, textarea, select').each(toggleLabel);
                  };

                  // Set things up as soon as the DOM is ready.
                  $(init);

                  // Do it again to detect Chrome autofill.
                  $(window).load(function() {
                    setTimeout(init, 0);
                  });

                })(jQuery);", "\n";
        echo '</script>',"\n";
    }
    
    public static function save_post_api($post_id) {
        global $wpdb;
        if (isset($_POST['api_url'])) {
            $table_name = $wpdb->prefix . "cn_wordpress"; 
            $api_url = $_POST['api_url'];
            $row = $wpdb->get_row("SELECT * FROM $table_name WHERE post = $post_id");
            if (NULL !== $row) {
                $wpdb->update($table_name, 
                           array('url' => $api_url), 
                           array('post' => $post_id));
            } else {
                $wpdb->insert($table_name, 
                           array('url' => $api_url, 
                                 'post' => $post_id));
            }
            
            $parent_category = 0;
            if ((true == get_option('use_parent_category')) && ("" != get_option('parent_category'))) {
                $parent_category = get_option('parent_category');
                $category = self::create_category_if_not_exists($parent_category);
                //var_dump($parent_category); die();
                wp_set_post_categories( $post_id, $category->term_id);
            }
            
            //call the api and add appropriate category and tags.
            $client = new CookingNutritiousClient(get_option('cn_api_token'));
            $cn = $client->requestGet($api_url);
            if ($cn->getCode() == 200) {
                $response = $cn->getResponse();
                //if the options elect to set the category automatically
                if ((true == get_option('category')) && ($response->meal_category != "")) { 
                    $category = self::create_category_if_not_exists($response->meal_category, $parent_category);
                    wp_set_post_categories( $post_id, $category->term_id, true);
                }
                
                //if the options elect to set the tags automatically
                if ((true == get_option('tags')) && (count($response->tags) > 0)) {
                    $tags = array();
                    foreach($response->tags as $tag) {
                        //$tags[] = create_tag_if_not_exists($tag);
                        $tags[] = $tag;
                        
                    }
                    wp_set_post_tags($post_id, implode(",", $tags), true);
                }
            }
        }
    }
    
    public static function create_category_if_not_exists($name, $parent_category = 0) {
        if (!($category = get_term_by('name', $name, 'category'))) {
            $category = wp_create_category($name, $parent_category);
        }
        
        return $category; 
    }
    
    public static function create_tag_if_not_exists($name) {
        if (!($tag = get_term_by('name', $name, 'post_tag'))) {
            $tag = wp_create_tag($name);
        }
        
        return $tag; 
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

	}

}
