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

            <h1 class="heading-underline"><?php the_title(); ?></h1>


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
        if( have_rows('bibtex_papers') && get_field('show_papers') ) {
?>
        <div class='row jadu-cms'>

<!--        <div class="main wrapper-lg" style="margin-top:1em">-->
            <div class="wrapper-xs-pd" style="margin-right: 1.4em; margin-left:1.4em">
            <h2>Papers</h2>
            <?php

            $cite="";

            // loop through the rows of data
            while (have_rows('bibtex_papers')) {
                the_row();
                // display a sub field value
                $cite .= get_sub_field('bibtex_id');
                $cite .= ",";
            }
            echo (papercite_cb("[bibtex key=" . $cite . "sort=year order=desc]") );
            ?>
            </div></div> <?php
        }

        $posts = get_field('authors');
        echo("<div class='row jadu-cms'>");

        if( $posts && get_field('show_authors')):
        ?>
<!--    <div class="wrapper-lg" style="margin-top:1em">-->
        <div class="wrapper-xs-pd" style="margin-right: 1em; margin-left:1em">
            <?php
                echo ("<h2 style='margin-right: 0.4em; margin-left:0.4em'>Authors from VCG</h2>");

                foreach( $posts as $post):
                    setup_postdata($post);
                    load_template( apply_filters( 'tk_profiles_template', 'cards', 'row' ), false );
                endforeach;
                ?> </div><?php


            wp_reset_postdata();
        endif;

//        load_template( apply_filters( 'tk_profiles_template', 'cards', 'footer' ), false );
        echo("</div>");

        ?>

        <?php
        get_footer(); ?>
