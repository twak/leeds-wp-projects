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

if ( get_field( 'tk_profiles_page_settings_show_breadcrumb', 'option' ) ) {
	// Custom title.
	$profiles_page_title = ( get_field( 'tk_profiles_page_settings_title', 'option' ) ? : 'Profiles' );
	$profiles_page_url   = get_post_type_archive_link( 'tk_profiles' );
	?>
	<div class="tk-profiles-breadcrumb-wrapper">
		<ul class="tk-profiles-breadcrumb">
			<li><a href="<?php echo esc_url( site_url() ); ?>">Home</a></li>
			<li><a href="<?php echo esc_url( $profiles_page_url ); ?>"><?php echo esc_html( $profiles_page_title ); ?></a></li>
			<li><?php the_title(); ?></li>
		</ul>
	</div>
	<?php
}
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		?>
<div class="main wrapper-lg" style="margin-top:1em">
	<div class="wrapper-xs-pd" style="margin-right: 1em; margin-left:1em">

        <h1 class="heading-underline"><?php the_title(); ?></h1>

        <div class ="row">
        <?php


        $value = get_field('whiterose');
        if ($value) {
            echo("<div class='col-sm-2'><a href=" . $value . "><img width='80em' style='  display: block; margin-left: auto;  margin-right: auto;' src='" . plugins_url("leeds-wp-projects/resources/whiterose_pdf.svg") . "'/></a></div> ");
        }

        echo ("<div class='col-sm-10'>");

        $value = get_field( 'conf' );
            if( $value )
                echo( '<h3 style="text-align: left">'.$value.'</h3>' );

            $value = get_field( 'all_authors' );
            if( $value )
                echo( '<h3 style="text-align: left">'.$value.'</h3>' );

        echo ("</div></div><br/>");


        if ( has_post_thumbnail() && tk_display_featured_image() ) {
            ?>
            <div style="justify-content: center; ">
            <img style="width:100%; margin-bottom: 1em" src="<?php the_post_thumbnail_url( 'full' ); ?>">

            </div>
            <?php
        }?>


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


        if( $posts && get_field('show_authors')):
            echo("<div class='row jadu-cms'>");
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
            echo("</div>");
        endif;

//        load_template( apply_filters( 'tk_profiles_template', 'cards', 'footer' ), false );


        $posts = get_field('partners');


        if( $posts && get_field('show_partners')):
//            echo("<div class='row jadu-cms'>");
            ?>
            <!--    <div class="wrapper-lg" style="margin-top:1em">-->
            <h2 style='margin-right: 0.4em; margin-left:0.4em'>Partners</h2>
            <div style='margin:4em; margin-top:128px; display: flex; flex-direction: row; flex-wrap: wrap; justify-content: center; align-content: flex-start; align-items: flex-start;'>

            <?php


            foreach( $posts as $post):
                setup_postdata($post);
                twak_inner_partner();

            endforeach;
            ?> </div><?php


            wp_reset_postdata();
        endif;

        //        load_template( apply_filters( 'tk_profiles_template', 'cards', 'footer' ), false );
        echo("</div>");


        ?>

        <?php
        get_footer(); ?>
