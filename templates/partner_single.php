<?php
/**
 * Single Profile template for the Toolkit Profiles plugin
 *
 * @package TK_Profiles
 */

/* redirect this page if set to use an external profile */
$profile_id = get_queried_object_id();

// Redirection.
$external_url = apply_filters( 'tk_profile_url', '', $profile_id );
$permalink = get_permalink( $profile_id );
if ( $external_url !== $permalink ) {
	wp_safe_redirect( $external_url );
	exit;
}

get_header();

?>

<!--<h2> --><?php //the_title() ?><!-- </h2>-->

<?php


if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		?>
<div class="main wrapper-lg" style="margin-top:1em">
	<div class="wrapper-xs-pd" style="margin-right: 1em; margin-left:1em">

        <?php

        if ( has_post_thumbnail() && tk_display_featured_image() ) {
            ?>
            <div style="justify-content: center; ">
            <img style="margin-bottom: 1em;  display: block;  margin-left: auto; margin-right: auto;" src="<?php the_post_thumbnail_url( 'large' ); ?>">

            </div>
            <?php
        }?>

<!--            <h1 class="heading-underline">--><?php //the_title(); ?><!--</h1>-->


            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="jadu-cms">
				<?php the_content(); ?>
				<?php do_action( 'tk_profiles_after_content', $profile_id, get_the_title() ); ?>
			</div>
		</div>
	</div>
    </div>
		<?php
	}
} ?>


<?php
$args = array(
    'post_type' => 'projects',
    'meta_query' => array(
        array(
            'key' => 'partners', // name of custom field
            'value' => '"' . get_the_ID() . '"', // matches exaclty "123", not just 123. This prevents a match for "1234"
            'compare' => 'LIKE'
        )
    )
);


$loop = new WP_Query($args);

if ($loop->have_posts()) {

    ?>

    <div class="main wrapper-lg">
        <div class="wrapper-xs-pd" style="margin-right: 1em; margin-left:1em">

            <h2>Projects:</h2>

            <?php

            // Page is not split by category.
            while ($loop->have_posts()) {

                $loop->the_post();
                twak_inner_project();

            } ?>
        </div>
    </div>

    <?php
}
        get_footer(); ?>
