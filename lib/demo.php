<?php

function twak_demo_info( $data ) {
    $posts = get_posts( array(
        'post_type'  => 'any',
        'orderby'   => 'rand',
        'posts_per_page' => 10,
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
        'myplugin/v1',
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

        demo_auto_grid(1, 256, "window", $speed, $page );
    ?>
        <body>


        <div style="    display: flex;     flex-direction: row;     flex-wrap: nowrap;     justify-content: center;     align-content: space-around;     align-items: stretch;; overflow:auto; background-color:#111111;">
        <div class="auto-grid" style="flex: 1 0 66%; align-self: auto; overflow:hidden;">
        </div>
        <div style="flex: 3 0 34%; align-self: auto;">
            <iframe style="    position: relative;    height: 100%;     width: 100%; " frameBorder="0"src="<?php echo(get_site_url()); ?>" name="iframe_a"></iframe>
        </div>
        </div>
        </body>
        </html>


        <?php

        exit();
    }
}

function demo_auto_grid($add /* 0 if shortcode, 1 if fullscreen */, $px_size, $element, $speed, $page) {
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
                        window.coords.push(id);
                    }
                    tr.appendTo(table);
                }

                shuffleArray(window.coords);
                window.coords.pop();
                table.appendTo ( $(".auto-grid")[0] );
                window.current_coord = 0;

            }

            $( <?php echo ($element); ?> ).resize(function() {
                 build();
            });

            $( document ).ready(function() {
                window.data_cache=[];
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

                window.idle_limit_sec = 60; // if the mouse isn't moved for this long, update webpage
                window.update_limit_ms = <?php echo($page); ?>; // update the shared this often, upate webpage

                $(".the_link").attr('alt', "VCG" );
                $(".the_link").attr('title', "VCG" );

                window.setInterval(fetchData, <?php echo($speed* 10); ?> )
                window.setInterval(displayData, <?php echo($speed); ?>);
            });

            function timerIncrement() {
                window.idleTime ++;
            }

            function fetchData () {

                if (window.data_cache.length < 2000)
                $.ajax({
                    dataType: "json",
                    url: <?php get_site_url() ?>"/wp-json/myplugin/v1/demo",
                    // data: data,
                    success: function(data) {
                        window.data_cache=window.data_cache.concat(data);
                        // console.log(data);
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

                        // $(item).empty();
                        var d = window.data_cache[Math.floor(Math.random()*window.data_cache.length)];
                        // $(item).attr("href", d['link']);
                        // $(item).find("img").attr('src', d['img']);

                        var img =$(item).find("img");

                        $(img).fadeOut().queue(function() {
                            var img = $(this);
                            $(img).attr('src', d['img']);
                            $(item).find("a").attr("href", d['link']).attr('alt', d['name'] );
                            $(item).find("a").attr('title', d['name'] );


                        <?php if ($add == 1) { ?>

                            // console.log(window.last_update);
                            // console.log(window.idleTime);

                            if (window.last_update == null )
                            {
                                window.last_update = new Date().getTime();
                            }
                            else if ( new Date().getTime() - window.last_update > window.update_limit_ms /*ms*/ && window.idleTime > window.idle_limit_sec /* seconds */) {


                                // $('iframe').fadeOut().queue(function() {
                                //     $('iframe').attr('src', d['link']).queue(function() {
                                //         $('iframe').fadeIn();
                                //     });
                                // } );

                                $("iframe").attr('src', d['link']);
                                window.last_update = new Date().getTime();
                            }

                            <?php
                            }
                            ?>



                            // $('#prev').attr('src', d['link'])

                            $(img).fadeIn();
                            $(img).dequeue();
                        });

                        //
                        // $(img).fadeTo(250, function() {
                        //     $(img).attr("src",  d['img'] );
                        // });
                        $(item).find("img").removeClass("jadu-cms");
                        // $(item) .append($ ( "<a href='" +d['link']+ "'><img src='"+d['img']+"'/></a>") );
                    }
                    // $(item).append(("foo"));//.attr("href", window.data[window.index]['url']) );
                    // window.index = (window.index + 1) % window.data.length;
                    window.current_coord = (window.current_coord + 1) % window.coords.length;

            //


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

    demo_auto_grid(0, $px_size, "'.auto-grid'", 600, -1);

    ?>

    <div style="border-width: 10; border-color: black">
    <div style='width:100%; height:<?php echo($height) ?> ; overflow:hidden' class='auto-grid'> </div>
    </div>
<?php


    return ob_get_clean();
}
