<?php

add_shortcode( 'gal', 'gal_shortcode' );

function get_all_image_sizes() {
    $image_sizes = array();

    global $_wp_additional_image_sizes;
    $default_image_sizes = array( 'thumbnail', 'medium', 'large' );

    foreach ( $default_image_sizes as $size ) {
        $image_sizes[$size]['width']	= intval( get_option( "{$size}_size_w") );
        $image_sizes[$size]['height'] = intval( get_option( "{$size}_size_h") );
        $image_sizes[$size]['crop']	= get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
    }

    if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) )
        $image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );

    return $image_sizes;
}

function gal_shortcode( $attr ) {
    $post = get_post();

    static $instance = 0;
    $instance++;

    if ( ! empty( $attr['ids'] ) ) {
        // 'ids' is explicitly ordered, unless you specify otherwise.
        if ( empty( $attr['orderby'] ) ) {
            $attr['orderby'] = 'post__in';
        }
        $attr['include'] = $attr['ids'];
    }

    $output = apply_filters( 'post_gallery', '', $attr, $instance );
    if ( $output != '' ) {
        return $output;
    }

    $html5 = current_theme_supports( 'html5', 'gallery' );
    $atts  = shortcode_atts(
        array(
            'order'      => 'ASC',
            'orderby'    => 'menu_order ID',
            'id'         => $post ? $post->ID : 0,
            'itemtag'    => $html5 ? 'figure' : 'dl',
            'icontag'    => $html5 ? 'div' : 'dt',
            'captiontag' => $html5 ? 'figcaption' : 'dd',
            'columns'    => 3,
            'size'       => 'thumbnail',
            'include'    => '',
            'exclude'    => '',
            'link'       => '',
        ),
        $attr,
        'gallery'
    );

    $id = intval( $atts['id'] );

    if ( ! empty( $atts['include'] ) ) {
        $_attachments = get_posts(
            array(
                'include'        => $atts['include'],
                'post_status'    => 'inherit',
                'post_type'      => 'attachment',
                'post_mime_type' => 'image',
                'order'          => $atts['order'],
                'orderby'        => $atts['orderby'],
            )
        );

        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[ $val->ID ] = $_attachments[ $key ];
        }
    } elseif ( ! empty( $atts['exclude'] ) ) {
        $attachments = get_children(
            array(
                'post_parent'    => $id,
                'exclude'        => $atts['exclude'],
                'post_status'    => 'inherit',
                'post_type'      => 'attachment',
                'post_mime_type' => 'image',
                'order'          => $atts['order'],
                'orderby'        => $atts['orderby'],
            )
        );
    } else {
        $attachments = get_children(
            array(
                'post_parent'    => $id,
                'post_status'    => 'inherit',
                'post_type'      => 'attachment',
                'post_mime_type' => 'image',
                'order'          => $atts['order'],
                'orderby'        => $atts['orderby'],
            )
        );
    }

    if ( empty( $attachments ) ) {
        return '';
    }

    if ( is_feed() ) {
        $output = "\n";
        foreach ( $attachments as $att_id => $attachment ) {
            $output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
        }
        return $output;
    }

    $thumb_size = 'medium';

    if (is_array($attr) and array_key_exists( 'size', $attr ) )
        $thumb_size = $attr['size'];

    $im_height =  get_all_image_sizes()[$thumb_size]['width']; //?? no defined heights

//    print_r(get_all_image_sizes());

    $json ="";
    if (is_array($attr))
    foreach ($attr as $key => $val){
        $json .= $key.": '".$val."',";
    }

    $output .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css\"/><link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css\"/>";

    $output .="<style>.slick-arrow:before {color:#51738c; } button.slick-arrow {height:100%;} .slick-twak{margin: 0 1em 0 1em ;} @media (max-width: 479px) { button.slick-arrow {display: none; margin: 0px; }}
}</style>";

    $output .= "<script type=\"text/javascript\" src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js\"></script>";
    $output .=  "<script type=\"text/javascript\" src=\"https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js\"></script>";

    $output .= "<div class='slick-twak'>";

    foreach ( $attachments as $id => $attachment ) {


        $src = wp_get_attachment_image_src( $id, "full", true  )[0];
//        $output .= "<div class='slick-twak-inner'><a href='".$src."'>";
        $output .= "<div class='slick-twak-inner'><a href='".$src."'>";
        $output.=  "<img style='height:". $im_height ."px;' src='".$src."'/>";
//        $output.=  "<img style='height:". $im_height ."px;' src='".wp_get_attachment_image_src( $id, $thumb_size, false )[0] ."'/>";
        $output .= "</a></div>";
    }

    $output .= "</div>";

    $output .= "<script type=\"text/javascript\">    $(document).ready(function(){       $('.slick-twak').slick({
        variableWidth: true,
        dots         : true,
        infinite     : true,
        autoplay     : true,".$json."

  });     });   </script>";

    return $output;
}

?>
