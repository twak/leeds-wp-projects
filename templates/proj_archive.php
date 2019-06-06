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
    <h1 class="heading-underline">Research Projects</h1>
</div>

<?php

// Get layout.
$template = get_field('tk_profiles_page_settings_template', 'option');

// Collect query args.
$args = array(
    'post_type' => 'projects',
    'posts_per_page' => -1,
    'order' => 'DESC',
);

// New query.
$loop = new WP_Query($args);

if ($loop->have_posts()) {

// Page is not split by category.
    while ($loop->have_posts()) {

        $loop->the_post();

        twak_inner_project();

    }
}


get_footer(); ?>
