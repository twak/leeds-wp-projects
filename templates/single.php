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
}

if ( get_field( 'tk_profiles_single_settings_related', 'option' ) ) {
	// Related profiles.
	$current_page_id_array = array( get_the_ID() );
	$profiles_image_flag   = 0;
	$terms                 = get_object_term_cache(  get_the_ID(), 'tk_profile_category' );
	if ( false === $terms ) {
		$terms = wp_get_object_terms(  get_the_ID(), 'tk_profile_category' );
	}
	if ( ! is_wp_error( $terms ) ) {
		$term_ids = wp_list_pluck( $terms, 'term_id' );
		$args     = array(
			'post_type'      => 'tk_profiles',
			'posts_per_page' => 3,
			'post__not_in'   => $current_page_id_array,
			'tax_query'      => array(
				array(
					'taxonomy' => 'tk_profile_category',
					'terms'    => $term_ids,
				),
			),
		);
		$query    = new WP_Query( $args );
		if ( $query->have_posts() ) {
			?>

</div><!-- ./wrapper-lg -->
	<div class="skin-bg-module island-lg">
		<div class="wrapper-sm wrapper-pd">
			<div class="divider-header">
				<h4 class="divider-header-heading divider-header-heading-underline">Related Profiles</h4>
			</div>
			<div class="row equalize">
			<?php
			while ( $query->have_posts() ) {
				$query->the_post();
				if ( has_post_thumbnail() ) {
					$profiles_image_flag = 1;
				}
			}
			$query->rewind_posts();
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_id = get_the_ID();
				$profile_link = apply_filters( 'tk_profile_url', '', $post_id );
				?>
				<div class="col-sm-4">
					<div class="card-flat card-stacked-sm skin-bg-white skin-bd-b equalize-inner">
				<?php
				if ( $profiles_image_flag ) {
					?>
					<div class="card-img">
						<div class="rs-img" <?php if ( has_post_thumbnail() ) : ?> style="background-image: url('<?php the_post_thumbnail_url(); ?>')" <?php endif; ?>>
							<a href="<?php echo esc_url( $profile_link ); ?>">
								<img src="<?php the_post_thumbnail_url( 'medium' ); ?>" alt="<?php the_title_attribute(); ?>">
							</a>
						</div>
					</div>
					<?php
				}
				?>
						<div class="card-content">
							<h3 class="heading-link text-center">
								<a href="<?php echo esc_url( $profile_link ); ?>"><?php the_title(); ?></a>
							</h3>
				<?php
				if ( get_field( 'tk_profiles_job_title' ) ) {
					?>
						<p class="heading-related text-center"><?php the_field( 'tk_profiles_job_title' ); ?></p>
					<?php
				}
				?>
						</div>
					</div>
				</div>
				<?php
			}
			wp_reset_postdata();
			?>
			</div>
		</div>
	</div>
	<div class="wrapper-lg">
			<?php
		}
	}
}
get_footer();
