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
	<div class="wrapper-xs wrapper-pd">

        <h1 class="heading-underline"><?php the_title(); ?></h1>

        <div class ="row">
        <?php


        $value = get_field('whiterose');
        if ($value) {
            echo("<div class='col-sm-2'><a href=" . $value . "><img width='80em' src='" . plugins_url("leeds-wp-projects/resources/whiterose_pdf.svg") . "'/></a></div> ");
        }

        echo ("<div class='col-sm-10'>");

        $value = get_field( 'conf' );
            if( $value )
                echo( '<h3 style="text-align: left">'.$value.'</h3>' );

            $value = get_field( 'authors' );
            if( $value )
                echo( '<h3 style="text-align: left">'.$value.'</h3>' );

        echo ("</div></div><br/>");

        if ( has_post_thumbnail() ) {
            ?>
            <div style="display: flex; justify-content: center;">
            <img src="<?php the_post_thumbnail_url( 'large' ); ?>">
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
		<?php
	}
} ?>


        <?php

        $posts = get_field('author');

        echo("<div class='row'>");
//        load_template( apply_filters( 'tk_profiles_template', 'cards', 'header' ), false );

        if( $posts ):
                echo ("<h2>Authors from the VCG</h2>");
                foreach( $posts as $post):
                    setup_postdata($post);
                    load_template( apply_filters( 'tk_profiles_template', 'cards', 'row' ), false );
                endforeach;

            wp_reset_postdata();
        endif;

//        load_template( apply_filters( 'tk_profiles_template', 'cards', 'footer' ), false );
        echo("</div>");

        ?>

        <?php
        get_footer(); ?>
