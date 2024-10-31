<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 *
 * @package    PCTA_Post_Count_Tracker
 * @subpackage PCTA_Post_Count_Tracker/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.1.0
 * @package    PCTA_Post_Count_Tracker
 * @subpackage PCTA_Post_Count_Tracker/includes
 * @author     Amir <amirnafees88@gmail.com>
 */
class PCTA_Post_Count_Tracker {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.1.0
	 * @access   protected
	 * @var      PCTA_Post_Count_Tracker_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.1.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.1.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.1.0
	 */
	public function __construct() {
		if ( defined( 'PCTA_POST_COUNT_TRACKER_VERSION' ) ) {
			$this->version = PCTA_POST_COUNT_TRACKER_VERSION;
		} else {
			$this->version = '1.1.0';
		}
		$this->plugin_name = 'post-count-tracker';

		add_action( 'wp_head', array( $this, 'pct_track_post_views' ) );
		add_filter( 'the_content', array( $this, 'pct_show_post_views' ) );
        add_filter( 'manage_posts_columns', array( $this, 'pct_add_views_column' ) );
        add_action( 'manage_posts_custom_column', array( $this, 'pct_display_views_column' ), 10, 2 );
		add_action( 'admin_menu', array( $this, 'pct_add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'pct_settings_init' ) );
		add_shortcode( 'pct_post_count', array( $this, 'pct_post_views_shortcode' ) );

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - PCTA_Post_Count_Tracker_Loader. Orchestrates the hooks of the plugin.
	 * - PCTA_Post_Count_Tracker_i18n. Defines internationalization functionality.
	 * - PCTA_Post_Count_Tracker_Admin. Defines all hooks for the admin area.
	 * - PCTA_Post_Count_Tracker_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.1.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-post-count-tracker-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-post-count-tracker-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-post-count-tracker-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-post-count-tracker-public.php';

		$this->loader = new PCTA_Post_Count_Tracker_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the PCTA_Post_Count_Tracker_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.1.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new PCTA_Post_Count_Tracker_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.1.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new PCTA_Post_Count_Tracker_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.1.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new PCTA_Post_Count_Tracker_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.1.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.1.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.1.0
	 * @return    PCTA_Post_Count_Tracker_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.1.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Implemenet the logic to add post counts as metadata.
	 *
	 * @since     1.1.0
	 */
	public function pct_track_post_views( $post_id = null ) {
        if ( ! is_single() ) return;

        if ( empty( $post_id ) ) {
            global $post;
            $post_id = $post->ID;
        }

        $count_key = 'pct_post_views_count';
        $count = get_post_meta( $post_id, $count_key, true );

        if ( $count == '' ) {
            $count = 0;
            delete_post_meta( $post_id, $count_key );
            add_post_meta( $post_id, $count_key, '0' );
        } else {
            $count++;
            update_post_meta( $post_id, $count_key, absint( $count ) );
        }
    }

	/**
	 * Get post counts from metadata.
	 *
	 * @since     1.1.0
	 * @return    string    Count number with text.
	 */
    public function pct_get_post_views( $post_id ) {
        $count_key = 'pct_post_views_count';
        $count = get_post_meta( $post_id, $count_key, true );
		$custom_text = get_option( 'pct_views_text' );
		$image_url = get_option( 'pct_views_image' );

		if ( empty( $custom_text ) ) {
			$custom_text = 'Total Count';
		}

		if ( empty( $image_url ) ) {
			$image_url = PCTA_POST_COUNT_TRACKER_PLUGIN_URL . 'public/images/ptc-view-icon.png';
		}

        if ( $count == '' ) {
            delete_post_meta( $post_id, $count_key );
            add_post_meta( $post_id, $count_key, '0' );
			$count = 0;
        }

		// Return the HTML with the image and count
		$output = '<div class="pct-post-views">';
		if ( ! empty( $image_url ) ) {
			$output .= '<img src="' . esc_url( $image_url ) . '" alt="Post Views"/>';
		}
		$output .= '<span>' . $count . ' ' . esc_html( $custom_text ) . '</span>';
		$output .= '</div>';
		return $output;
    }

	/**
	 * Add post count at the end of post content.
	 *
	 * @since     1.1.0
	 * @return    string    Post count HTML.
	 */
	public function pct_show_post_views( $content ) {
		$shortcode_siwtch = get_option( 'pct_shortcode_option' );
        if ( is_single() && '' === $shortcode_siwtch ) {
            global $post;
            $post_id = $post->ID;
            $views_html = $this->pct_get_post_views( $post_id );
            $content .= $views_html;
        }
        return $content;
    }

	/**
     * Shortcode callback to display post views.
     *
     * @param array $atts Shortcode attributes.
     * @return string HTML output for the shortcode.
     */
    public function pct_post_views_shortcode( $atts ) {
        // Extract shortcode attributes and set default values
        $atts = shortcode_atts( array(
            'id' => get_the_ID(), // Default to current post ID if not specified
        ), $atts, 'post_views' );

        // Use pct_get_post_views() to generate the output.
        return $this->pct_get_post_views( $atts['id'] );
    }

	/**
	 * Add post count column on posts in admin screen.
	 *
	 * @since     1.1.0
	 * @return    string    Posts page column name.
	 */
    public function pct_add_views_column( $columns ) {
        $columns['post_views'] = 'Views';
        return $columns;
    }

	/**
	 * Display post count in admin screen.
	 *
	 * @since     1.1.0
	 */
    public function pct_display_views_column( $column_name, $post_id ) {
        if ( $column_name === 'post_views' ) {
			$count = get_post_meta( $post_id, 'pct_post_views_count', true );
            echo esc_html( $count );
        }
    }

	/**
	 * Add a menu item to the WordPress admin.
	 *
	 * @since    1.1.0
	 */
	public function pct_add_admin_menu() {
		add_menu_page(
			'Post Count Tracker Settings',
			'Post Count Tracker',
			'manage_options',
			'pct_settings',
			array( $this, 'pct_settings_page' )
		);
	}

	/**
	 * Display the settings page content.
	 *
	 * @since    1.1.0
	 */
	public function pct_settings_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Post Count Tracker Settings', 'post-count-tracker' ); ?></h1>
			<form method="post" action="options.php" enctype="multipart/form-data">
				<?php 
				settings_fields( 'pct_settings_group' );
				do_settings_sections( 'pct_settings' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register plugin settings.
	 *
	 * @since    1.1.0
	 */
	public function pct_settings_init() {
		// Register settings
		register_setting( 'pct_settings_group', 'pct_shortcode_option' );
		register_setting( 'pct_settings_group', 'pct_views_text' );
		register_setting( 'pct_settings_group', 'pct_views_image' );


		// Add settings section
		add_settings_section(
			'pct_settings_section',
			__( 'General Settings', 'post-count-tracker' ),
			null,
			'pct_settings'
		);

		// Add checkbox field
		add_settings_field(
			'pct_shortcode_option',
			__( 'Enable Shortcode', 'post-count-tracker' ),
			array( $this, 'pct_shortcode_option_render' ),
			'pct_settings',
			'pct_settings_section'
		);

		// Add text input field
		add_settings_field(
			'pct_views_text',
			__( 'Views Text', 'post-count-tracker' ),
			array( $this, 'pct_views_text_render' ),
			'pct_settings',
			'pct_settings_section'
		);

		add_settings_field(
			'pct_views_image', // Option ID
			__( 'Post Views Image', 'post-count-tracker' ), // Label
			array( $this, 'pct_view_image_render' ), // Callback function to display the field
			'pct_settings', // Page where the field appears
			'pct_settings_section' // Section to which this field belongs
		);
	}

	/**
	 * Render the checkbox field.
	 *
	 * @since    1.1.0
	 */
	public function pct_shortcode_option_render() {
		$value = get_option( 'pct_shortcode_option', false );
		?>
		<input type="checkbox" name="pct_shortcode_option" value="1" <?php checked( 1, $value, true ); ?> />
		<label for="pct_shortcode_option"><?php esc_html_e( 'Use shortcode instead of auto inset at the end of post content (e.g. to use in Elementor).', 'post-count-tracker' ); ?></label>
		<h2><?php esc_html_e( 'How to use Shortcode', 'post-count-tracker' ); ?></h2>
        <p><?php esc_html_e( 'Copy [pct_post_count] and place in elementor shortcode widget or wordpress editor.', 'post-count-tracker' ); ?></p>
		<p><?php esc_html_e( 'Copy do_shortcode("[pct_post_count]"); and place in your PHP template.', 'post-count-tracker' ); ?></p>
		<?php
	}

	/**
	 * Render the text input field.
	 *
	 * @since    1.1.0
	 */
	public function pct_views_text_render() {
		$value = get_option( 'pct_views_text' );
		if ( empty( $value ) ) {
			$value = 'Total Count';
		}
		
		?>
		<input type="text" name="pct_views_text" value="<?php echo esc_attr( $value ); ?>" />
		<label for="pct_views_text"><?php esc_html_e( 'Enter a custom text to display with the post views.', 'post-count-tracker' ); ?></label>
		<?php
	}

	public function pct_view_image_render() {
		$image_url = get_option( 'pct_views_image' );

		?>
		<input type="text" id="pct_views_image" name="pct_views_image" value="<?php echo esc_attr( $image_url ); ?>" />
		<button type="button" class="button" id="pct_views_image_button">Upload Image</button>
		<br>
		<img id="pct_views_image_preview" src="<?php echo esc_url( $image_url ); ?>" style="max-width: 150px; margin-top: 10px;">
		<?php
	}

}
