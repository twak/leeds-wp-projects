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

<div style="margin-top:1em" class="tk-profiles-list-wrapper">
    <h1 class="heading-underline">Partners</h1>
</div>

<?php

// Get layout.
$template = get_field('tk_profiles_page_settings_template', 'option');

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


get_footer(); ?>
