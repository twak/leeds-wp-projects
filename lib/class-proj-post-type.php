<?php
if (!class_exists('Proj_Post_Type')) {


    class Proj_Post_Type
    {

        public function __construct()
        {
            add_action( 'acf/init', array( $this, 'create_post_type' ), 9 );

            add_filter( 'single_template', array( $this, 'single_template' ) );
            add_filter( 'archive_template', array( $this, 'archive_template' ) );

            if (!has_image_size( 'sq512') )
                add_image_size( 'sq512', 512, 512, true );
        }

        public function archive_template( $archive_template ) {
            global $wp_query;
            if ( is_post_type_archive ( 'projects' ) ) {
                return dirname( __DIR__ ) . '/templates/archive.php';
            }
            return $archive_template;
        }

        public function single_template( $single_template ) {
            global $post;
            if ( 'projects' === $post->post_type ) {
                return dirname( __DIR__ ) . '/templates/single.php';
            }

            return $single_template;
        }


        public function create_post_type()
        {

// Set UI labels for Custom Post Type
            $labels = array(
                'name' => __('Projects'),
                'singular_name' => __('Projects'),
                'menu_name' => __('Projects'),
                'parent_item_colon' => __('Parent Project'),
                'all_items' => __('All Projects'),
                'view_item' => __('View Project'),
                'add_new_item' => __('Add New Project'),
                'add_new' => __('Add New'),
                'edit_item' => __('Edit Project'),
                'update_item' => __('Update Project'),
                'search_items' => __('Search Project'),
                'not_found' => __('Not Found'),
                'not_found_in_trash' => __('Not found in Trash'),
            );

// Set other options for Custom Post Type

            $args = array(
                'label' => __('projects'),
                'description' => __('research projects'),
                'labels' => $labels,
                // Features this CPT supports in Post Editor
                'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields',),
                // You can associate this CPT with a taxonomy or custom taxonomy.
                'taxonomies' => array('genres'),
                'hierarchical' => false,
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'show_in_nav_menus' => true,
                'show_in_admin_bar' => true,
                'menu_position' => 5,
                'can_export' => true,
                'has_archive' => true,
                'exclude_from_search' => false,
                'publicly_queryable' => true,
                'capability_type' => 'page',
                'menu_icon'    => 'dashicons-pressthis',
            );

            // Registering your Custom Post Type
            register_post_type('projects', $args);

        }
    }

    new Proj_Post_Type();
}