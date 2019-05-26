<?php
/**
 * Archive template for the Toolkit Profiles plugin
 *
 * @package TK_Profiles
 */

/* ACF Profile page settings */

// Custom title.
$profiles_page_title = (get_field('tk_profiles_page_settings_title', 'option') ?: 'Profiles');

// Intro (Lead text).
$intro = get_field('tk_profiles_page_settings_introduction', 'option');

// Content (after list).
$outro = get_field('tk_profiles_page_settings_content', 'option');

// Display logic.
$display = (get_field('tk_profile_display', 'option') ?: 'all');

?>
<?php get_header();
?>

<div class="tk-profiles-list-wrapper">
    <h1 class="heading-underline">Featured Projects</h1>
</div>

<?php

// Get layout.
$template = get_field('tk_profiles_page_settings_template', 'option');

// Collect query args.
$args = array(
    'post_type' => 'projects',
    'posts_per_page' => -1,
    'order' => 'ASC',
);

// New query.
$loop = new WP_Query($args);

if ($loop->have_posts()) {

// Page is not split by category.
    while ($loop->have_posts()) {

        $loop->the_post();
        $profile_link = apply_filters('tk_profile_url', '', get_the_id());
        $name = get_the_title();

        ?>

        <!--        <div class="card-flat card-stacked-xs skin-bd-b skin-box-module">-->

        <div class="row card-flat  skin-bd-b skin-box-module " style="min-height:11em;">
            <div>
                <div class="col-xs-4 col-sm-2">
                    <a href="<?php echo esc_url($profile_link); ?>">
                        <?php
                        if (has_post_thumbnail()) {
                            // Check if Thumbnail exists.
                            ?>
                            <div>
                                <img  style='  display: block; margin-left: auto;  margin-right: auto;' src='<?php the_post_thumbnail_url('sq512'); ?>'
                                     alt='"<?php echo esc_attr($name); ?>"'/>
                            </div>
                            <?php
                        } else {
                        ?>

                        <div style="max-height:10em">
                            <div class="rs-img"></div>
                            </div>


                            <?php
                            }
                            ?>
                    </a>
                </div>
                <div class="col-xs-8 col-sm-10">
                    <a href="<?php echo esc_url($profile_link); ?>">
                        <h2 style="font-family: freight-display-pro; margin-top:0.5em"><?php the_title(); ?></h2>

                        <?php
                        $value = get_field('all_authors');
                        if ($value)
                            echo('<h3 style="text-align: left">' . $value . '</h3>');
                        ?>
                    </a>
                </div>
                </a>
            </div>
        </div>

        <?php
    }
}


get_footer(); ?>
