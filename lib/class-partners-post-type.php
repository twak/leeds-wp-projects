<?php
if (!class_exists('Partner_Post_Type')) {


    class Partner_Post_Type
    {

        public function __construct()
        {
            add_action( 'acf/init', array( $this, 'create_post_type' ), 9 );
            add_filter( 'single_template', array( $this, 'single_template' ) );
            add_filter( 'archive_template', array( $this, 'archive_template' ) );
        }

        public function archive_template( $archive_template ) {
            global $wp_query;
            if ( is_post_type_archive ( 'partners' ) ) {
                return dirname( __DIR__ ) . '/templates/partner_archive.php';
            }
            return $archive_template;
        }

        public function single_template( $single_template ) {
            global $post;
            if ( 'partners' === $post->post_type ) {
                return dirname( __DIR__ ) . '/templates/partner_single.php';
            }

            return $single_template;
        }


        public function create_post_type()
        {

// Set UI labels for Custom Post Type
            $labels = array(
                'name' => __('Parter'),
                'singular_name' => __('Partners'),
                'menu_name' => __('Partners'),
                'parent_item_colon' => __('Parent Partner'),
                'all_items' => __('All Partners'),
                'view_item' => __('View Partners'),
                'add_new_item' => __('Add New Partner'),
                'add_new' => __('Add New'),
                'edit_item' => __('Edit Partner'),
                'update_item' => __('Update Partner'),
                'search_items' => __('Search Partner'),
                'not_found' => __('Not Found'),
                'not_found_in_trash' => __('Not found in Trash'),
            );

// Set other options for Custom Post Type

            $args = array(
                'label' => __('partners'),
                'description' => __('research partners'),
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
                'menu_icon'    => 'dashicons-admin-site-alt3',
            );

            // Registering your Custom Post Type
            register_post_type('partners', $args);

        }
    }

    new Partner_Post_Type();
}

function twak_inner_partner()
{
    $profile_link = apply_filters('tk_profile_url', '', get_the_id());
    $name = get_the_title();

    ?>

    <div style="margin:2em; width:180px; text-align: center;">
        <a href="<?php echo esc_url($profile_link); ?>">
            <?php
            if (has_post_thumbnail()) {
                // Check if Thumbnail exists.
                ?>
                <img style="border:none; position: relative; top: 50%; transform: translateY(-50%);"
                     src='<?php the_post_thumbnail_url('sq512'); ?>'
                     alt='"<?php echo esc_attr($name); ?>"'/>
                <?php
            } else {
                ?>

                <div style="text-align: center;  transform: translateY(-50%);">
                    <h3 style="top:50%; transform: translateY(-50%);"><?php echo($name) ?> </h3>
                </div>


                <?php
            }
            ?>
        </a>
    </div>
    <?php
}

function shortcode_all_partners() {
// Collect query args.
    $args = array(
        'post_type' => 'partners',
        'posts_per_page' => -1,
        'order' => 'ASC',
    );

// New query.
    $loop = new WP_Query($args);

    if ($loop->have_posts()) {

        echo ("<div style='margin:4em; margin-top:90px; display: flex; flex-direction: row; flex-wrap: wrap; justify-content: center; align-content: flex-start; align-items: flex-start;'>");

// Page is not split by category.
        while ($loop->have_posts()) {

            $loop->the_post();
            twak_inner_partner();
        }

        echo("</div>");
    }

}

add_shortcode("all_partners", "shortcode_all_partners" );

?>