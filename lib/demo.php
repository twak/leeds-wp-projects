<?php

function twak_demo_info( $data ) {
    $posts = get_posts( array(
        'post_type'  => 'any',
        'orderby'   => 'rand',
        'posts_per_page' => 6,
        'meta_query' => array(
            array(
                'key' => '_thumbnail_id',
                'compare' => 'EXISTS'
            ),
        )
    ) );

    if ( empty( $posts ) ) {
        return "null";
    }

    $out = [];

    foreach ($posts as $post) {

        $link = get_permalink($post);
        if (get_post_type($post) == "tk_profiles")
            $link =  apply_filters( 'tk_profile_url', '', $post->ID );


        array_push($out, array (
            "name" => acf_get_post_title($post),
            "img" => wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'sq512', false ),
            "link" =>  $link ) );
    }

    return $out;
}

add_action( 'rest_api_init', 'twak_register_routes' );

# responds to https://localhost/vcg/wp-json/myplugin/v1/demo
function twak_register_routes() {
    register_rest_route(
        'myplugin/v1',
        '/demo',
        array(
            'methods' => 'GET',
            'callback' => 'twak_demo_info',
        )
    );
}