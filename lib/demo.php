<?php

function twak_demo_info( $data ) {

    $offset = 0;

    if (array_key_exists('s', $_GET))
        $offset = (int) $_GET["s" ];


    $posts = get_posts( array(
        'post_type'  => 'any',
//        'orderby'   => 'rand'
        'offset'     => $offset,
        'orderby'          => 'date',
        'posts_per_page' => 10,
        'meta_query' => array(
            array(
                'key' => '_thumbnail_id',
                'compare' => 'EXISTS'
            ),
        )
    ) );

//    if ( empty( $posts ) ) {
//        return "null";
//    }

    $out = [];

    foreach ($posts as $post) {

        $link = get_permalink($post);
        if (get_post_type($post) == "tk_profiles")
            $link =  apply_filters( 'tk_profile_url', '', $post->ID );


        array_push($out, array (
            "name" => acf_get_post_title($post),
            "img" => wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'sq512' )[0],
            "link" =>  $link ) );
    }

    return $out;
}

add_action( 'rest_api_init', 'twak_register_routes' );

# responds to https://localhost/vcg/wp-json/myplugin/v1/demo
# https://css-tricks.com/a-responsive-grid-layout-with-no-media-queries/

function twak_register_routes() {
    register_rest_route(
        'leeds-wp-projects/v1',
        '/demo',
        array(
            'methods' => 'GET',
            'callback' => 'twak_demo_info',
        )
    );
}

add_action('parse_request', 'demo');

function demo($params) {



    if( strtok ( $_SERVER["REQUEST_URI"], '?' ) == '/demo')
    {

        ?>
        <!DOCTYPE html>

        <html>
        <head>
            <title>VCG demo</title>
        </head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!--        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>-->
<!--        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">-->

        <style>

            body {
                margin:0;
                overflow:hidden;
            }

        </style>



        <?php

        $speed = 300;
        if (array_key_exists('speed', $_GET))
            $speed = (int) $_GET["speed" ];

        $page = 4000;
        if (array_key_exists('page', $_GET))
            $page = (int) $_GET["page" ];

        $px = 256;
        if (array_key_exists('px', $_GET))
            $px = (int) $_GET["px"];

        $idle = 60;
        if (array_key_exists('idle', $_GET))
            $idle = (int) $_GET["idle" ];

        demo_auto_grid(1, $px, "window", $speed, $page, $idle );
    ?>
        <body>


        <div style="    display: flex;     flex-direction: row;     flex-wrap: nowrap;     justify-content: center;     align-content: space-around;     align-items: stretch; overflow:hidden;">
        <div class="auto-grid" style="flex: 1 0 66%; align-self: auto; overflow:hidden;">
        </div>

        <?php if ($idle > 0) {  ?>

        <div style="flex: 3 0 34%; align-self: auto;">
            <iframe scrolling="no" style="    position: relative;    height: 100%;     width: 100%; " frameBorder="0"src="" name="iframe_a"></iframe>
        </div>
            <?php }  ?>
        </div>
        </body>
        </html>


        <?php

        exit();
    }
}

function demo_auto_grid($add /* 0 if shortcode, 1 if fullscreen */, $px_size, $element, $speed, $page, $idle_time_sec) {
    ?>

        <script>

            function build() {
                console.log($(".auto-grid")[0]);
                $(".auto-grid").empty();

                var rows =  Math.floor( $( <?php echo ($element); ?> ).height() / <?php echo($px_size)?> ) +  <?php echo ($add); ?>; //here's your number of rows and columns
                var cols =  Math.floor( $( <?php echo ($element); ?> ).width() / <?php echo($px_size)?> )+  <?php echo ($add); ?>;
                var table = $('<table cellspacing="0" cellpadding="0"><tbody>');
                window.coords = [];
                for(var r = 0; r < rows; r++)
                {
                    var tr = $("<tr style='height:<?php echo($px_size)?>px'>");
                    for (var c = 0; c < cols; c++) {
                        var id = 'x_' + c + '_y_' + r ;
                        $('<td class="cell" id="' + id + '" ><a class="the_link" target="<?php echo ($add == 1 ? "iframe_a" : "_self"); ?>" href="/"><img src="<?php get_site_url() ?>/wp-content/plugins/leeds-wp-projects/resources/logo_blue.svg"/></a></td>').appendTo(tr);
                        if (r != 1 || c != 1)
                            window.coords.push(id);
                    }
                    tr.appendTo(table);
                }

                shuffleArray(window.coords);
                table.appendTo ( $(".auto-grid")[0] );
                window.current_coord = 0;

            }



            $( document ).ready(function() {

                window.data_cache=[];
                window.data_offset = 0;
                window.data_complete = false;
                window.current_datum = 0;

                fetchData();
                build();

                window.idleTime = 0;
                setInterval(timerIncrement, 1000); // 1 sec
                $(this).mousemove(function (e) {
                    window.idleTime = 0;
                });
                $(this).keypress(function (e) {
                    window.idleTime = 0;
                });

                // window.idle_limit_sec = 60; // if the mouse isn't moved for this long, update webpage
                window.update_limit_ms = <?php echo($page); ?>; // update the shared this often, upate webpage

                $(".the_link").attr('alt', "VCG" );
                $(".the_link").attr('title', "VCG" );

                window.setInterval(fetchData, <?php echo($speed* 10); ?> )
                window.setInterval(displayData, <?php echo($speed); ?>);

                $(<?php echo ($element); ?>).resize(function() {
                    build();
                });


            });

            function timerIncrement() {
                window.idleTime ++;
            }

            function fetchData () {

                if (!window.data_complete)
                $.ajax({
                    dataType: "json",
                    url: <?php get_site_url() ?>"/wp-json/leeds-wp-projects/v1/demo?s="+window.data_offset,
                    // data: data,
                    success: function(data) {

                        if (data.length === 0) {
                            window.data_complete = true;
                            return;
                        }

                        shuffleArray(data);

                        window.data_cache=window.data_cache.concat(data);
                        window.data_offset += 10;
                        console.log(data);
                    }
                });
            }

            function displayData() {


                    if (window.data_cache == undefined || window.data_cache.length == 0) {
                        console.log("waiting for first data");
                        return;
                    }

                    // items[Math.floor(Math.random()*items.length)]
                    var item = $("#"+window.coords[window.current_coord]);

                    if (!item.is(":hover") ) {

                        var d = window.data_cache[window.current_datum];

                        var img =$(item).find("img");

                        $(img).fadeOut().queue(function() {
                            var img = $(this);
                            $(img).attr('src', d['img']);
                            $(item).find("a").attr("href", d['link']).attr('alt', d['name'] );
                            $(item).find("a").attr('title', d['name'] );

                        <?php if ($add == 1) { ?>

                            if (window.last_update == null )
                            {
                                window.last_update = new Date().getTime();
                            }
                            else if ( new Date().getTime() - window.last_update > window.update_limit_ms /*ms*/ && window.idleTime > <?php echo( $idle_time_sec ); ?> /* seconds */) {

                                window.last_update = new Date().getTime();

                                $('iframe').fadeOut(300,function(){
                                    $('iframe').attr('src', d['link'] ).on ("load", function(){
                                        $(this).fadeIn(300);
                                    });
                                });

                                // $("iframe").fadeOut(1000,function() {
                                //
                                //     $("iframe").attr('src', "");
                                //
                                //
                                //
                                //     $("iframe").attr('src', d['link']);
                                //
                                //     $("iframe").fadeIn(0);
                                //     // $("iframe").dequeue();
                                // } );


                            }

                            <?php
                            }
                            ?>

                            $(img).fadeIn();
                            $(img).dequeue();
                        });

                        $(item).find("img").removeClass("jadu-cms");
                        // $(item) .append($ ( "<a href='" +d['link']+ "'><img src='"+d['img']+"'/></a>") );
                    }


                window.current_coord = (window.current_coord + 1) % window.coords.length;


                window.current_datum++;
                if (window.current_datum >= window.data_cache.length) {
                    // shuffleArray(window.data_cache);
                    window.current_datum = window.current_datum % window.data_cache.length;
                }
            }

            function shuffleArray(array) {
                for (var i = array.length - 1; i > 0; i--) {
                    var j = Math.floor(Math.random() * (i + 1));
                    var temp = array[i];
                    array[i] = array[j];
                    array[j] = temp;
                }
            }


        </script>

        <style>

            table, th, td {
                border: 0;
                margin: 0 auto; /* or margin: 0 auto 0 auto */

            }

            iframe {
                overflow-y:hidden;
            }

            img, .cms a>img, .jadu-cms a>img {
                display: block;
                max-width: <?php echo($px_size)?>px;
                max-height: <?php echo($px_size)?>px;
                margin: auto;
                width: auto;
                height: auto;
                padding: 0px;
                border: 0px;
            }

            .cms a>img:hover, .jadu-cms a>img:hover {
                border: none;
            }

            img:hover {
                filter: brightness(120%);
            }

            .cell {
                background-color: white; /*  #51738c;*/
                min-width: <?php echo($px_size)?>px;
                min-height: <?php echo($px_size)?>px;
                border: 0px;
            }


        </style>
        <?php
}

add_shortcode( 'demo', 'demo_iframe' );

function demo_iframe($atts) {

    ob_start();

    if ( $atts != null ) {
        $height = $atts['height'];
        $px_size = $atts['px_size'];
    }

    if (!isset($height))
        $height = "512px";

    if (!isset($px_size))
        $px_size = 128;

    ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <?php

    demo_auto_grid(0, $px_size, "'.auto-grid'", 600, -1, -1);

    ?>

    <div>
    <div style='width:100%; height:<?php echo($height) ?> ; overflow:hidden' class='auto-grid'> </div>
    </div>
<?php


    return ob_get_clean();
}
