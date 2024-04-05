<?php

function twak_demo_info( $data ) {

    $offset = 0;

    if (array_key_exists('s', $_GET))
        $offset = (int) $_GET["s" ];


    $posts = get_posts( array(
        'post_type'  => 'any',
        'offset'     => $offset,
        'orderby'          => 'date',
        'posts_per_page' => 100,
        'meta_query' => array(
            array(
                'key' => '_thumbnail_id',
                'compare' => 'EXISTS'
            ),
        )
    ) );

    $out = [];
    $domain = get_site_url();

    foreach ($posts as $post) {

        $link = get_permalink($post);
        if (get_post_type($post) == "tk_profiles")
            $link =  apply_filters( 'tk_profile_url', '', $post->ID );


        array_push($out, array (
            "name" => get_the_title($post),
            "img" => str_replace($domain, "", wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'sq512' )[0] ),
            "link" => str_replace($domain, "", $link) ) );
            //"link" => str_replace($link, "127", "128") ) );
    }

    $myfile = fopen(get_home_path()."static/demo_cache_".$offset, "w"); // for static site caching
    fwrite($myfile, json_encode($out));
    fclose($myfile);


    return $out;
}

add_action( 'rest_api_init', 'twak_register_routes' );

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
            <title>twak.org demo</title>
        </head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <style>

            body {
                margin:0;
                overflow:hidden;
                height: 100vh;
            }

        </style>



        <?php

        $speed = 300; // how often to update the left hand side little picture links
        if (array_key_exists('speed', $_GET))
            $speed = (int) $_GET["speed" ];

        $page = 4000; // how often to update the right hand side webpage
        if (array_key_exists('page', $_GET))
            $page = (int) $_GET["page" ];

        $px = 64; // size of little square pictures.
        if (array_key_exists('px', $_GET))
            $px = (int) $_GET["px"];

        $idle = 60;
        if (array_key_exists('idle', $_GET))
            $idle = (int) $_GET["idle" ];

         demo_auto_grid(False, 1, $px, "'.autogrid3'", $speed, $page, $idle );
    ?>
        <body>


        <div style="height: 100vh;  display: flex;     flex-direction: row;     flex-wrap: nowrap;     justify-content: center;     align-content: space-around;     align-items: stretch; overflow:hidden;">
        <div class="autogrid3" style="flex: 1 0 66%; align-self: auto; overflow:hidden;">
        </div>

        <?php if ($idle > 0) {  ?>

        <div style="flex: 3 0 34%; align-self: auto;">
            <iframe scrolling="no" style="    position: relative;    height: 100%;     width: 100%; " frameBorder="0"src="/" name="iframe_a"></iframe>
        </div>
            <?php }  ?>
        </div>
        </body>
        </html>


        <?php

        exit();
    }
}

function demo_auto_grid($homepage, $add, $px_size, $element, $speed, $page, $idle_time_sec) {
    $ele = str_replace (".", "", $element); // js function name postfix
    $ele = str_replace ("'", "", $ele);
    ?>

        <script>

            function build<?php echo ($ele); ?>() {
                console.log($(<?php echo ($element); ?>)[0]);
                $(<?php echo ($element); ?>).empty();

                var rows =  Math.floor( $( <?php echo ($element); ?> ).height() / <?php echo($px_size)?> ) +  <?php echo ($add); ?>; //here's your number of rows and columns
                var cols =  Math.floor( $( <?php echo ($element); ?> ).width() / <?php echo($px_size)?> )+  <?php echo ($add); ?>;
                var table = $('<table cellspacing="0" cellpadding="0"><tbody>');
                var homepage = <?php echo (var_export($homepage,true) ); ?>;
                window.coords<?php echo ($ele); ?> = [];
                for(var r = 0; r < rows; r++)
                {
                    var tr = $("<tr style='height:<?php echo($px_size)?>px'>");
                    for (var c = 0; c < cols; c++) {
                        var id = 'x_' + c + '_y_' + r + "_<?php echo ($ele); ?>";

                        if (homepage && r == 0 && c == 0) {
                            $('<td rowspan="2" colspan="3" class="" id="' + id + '" ><a class="the_link" target="<?php echo ($add == 1 ? "iframe_a" : "_self"); ?>" href="/"><img class="demo-logo-big<?php echo ($ele); ?>" src="<?php get_site_url() ?>/wp-content/uploads/full_logo.svg"/></a></td>').appendTo(tr);
                        }
                        else if (homepage && r <2 && c <3) {
                            // nothing - row+colspan.
                        }
                        else {
                            klazz = "demo-img<?php echo ($ele); ?>";
                            cell = "cell-big<?php echo ($ele); ?>";
//                             if (!homepage)
                           src = "<?php get_site_url() ?>/wp-content/uploads/logo_blue.svg";
//                             else
//                             src = "<?php get_site_url() ?>/wp-content/uploads/px.png";
                            $('<td class="'+cell+'" id="' + id + '" ><a class="the_link" target="<?php echo ($add == 1 ? "iframe_a" : "_self"); ?>" href="/"><img class="'+klazz +'" src="'+src+'"/></a></td>').appendTo(tr);
                            window.coords<?php echo ($ele); ?>.push(id);
                        }
                    }
                    tr.appendTo(table);
                }

                shuffleArray(window.coords<?php echo ($ele);?>);
                table.appendTo ( $(<?php echo ($element); ?>)[0] );
                window.current_coord<?php echo ($ele); ?> = 0;

            }

            $( document ).ready(function() {

                window.data_cache<?php echo ($ele); ?>=[];
                window.data_offset<?php echo ($ele); ?> = 0;
                window.data_complete<?php echo ($ele); ?> = false;
                window.current_datum<?php echo ($ele); ?> = 0;

                fetchData<?php echo ($ele); ?>();
                build<?php echo ($ele); ?>();

                window.idleTime<?php echo ($ele); ?> = 0;
                setInterval(timerIncrement<?php echo ($ele); ?>, 1000); // 1 sec
                $(this).mousemove(function (e) {
                    window.idleTime<?php echo ($ele); ?> = 0;
                });
                $(this).keypress(function (e) {
                    window.idleTime<?php echo ($ele); ?> = 0;
                });

                // window.idle_limit_sec = 60; // if the mouse isn't moved for this long, update webpage
                window.update_limit_ms<?php echo ($ele); ?> = <?php echo($page); ?>; // update the shared this often, upate webpage

                $(".the_link").attr('alt', "twak's logo" );
                $(".the_link").attr('title', "twak" );

                window.setInterval(fetchData<?php echo ($ele); ?>, <?php echo($speed* 8); ?> )
                window.setInterval(displayData<?php echo ($ele); ?>, <?php echo($speed); ?>);

                $(<?php echo ($element); ?>).resize(function() {
                    build<?php echo ($ele); ?>();
                });


            });

            function timerIncrement<?php echo ($ele); ?>() {
                window.idleTime<?php echo ($ele); ?> ++;
            }

            function fetchData<?php echo ($ele); ?> () {

                if (!window.data_complete<?php echo ($ele); ?>)
                $.ajax({
                    dataType: "json",
                    url: <?php get_site_url() ?>"/static/demo_cache_"+window.data_offset<?php echo ($ele); ?>, // this line to read from the pre-cached results (for static deploy)
                    // url: <?php get_site_url() ?>"/wp-json/leeds-wp-projects/v1/demo?s="+window.data_offset<?php echo ($ele); ?>, // this line to use live rest api (and write cache)
                    // data: data,
                    success: function(data) {

                        if (data.length === 0) {
                            window.data_complete<?php echo ($ele); ?> = true;
                            return;
                        }

                        shuffleArray(data);

                        window.data_cache<?php echo ($ele); ?>=window.data_cache<?php echo ($ele); ?>.concat(data);
                        window.data_offset<?php echo ($ele); ?> += 100;
                        console.log(data);
                    }
                });
            }

            function displayData<?php echo ($ele); ?>() {

                    if (window.data_cache<?php echo ($ele); ?> == undefined || window.data_cache<?php echo ($ele); ?>.length == 0) {
                        console.log("waiting for first data");
                        return;
                    }

                    // items[Math.floor(Math.random()*items.length)]
                    var item = $("#"+window.coords<?php echo ($ele); ?>[window.current_coord<?php echo ($ele); ?>]);

                    if (!item.is(":hover") ) {

                        var d = window.data_cache<?php echo ($ele); ?>[window.current_datum<?php echo ($ele); ?>];

                        var img =$(item).find("img");

                        $(img).fadeOut().queue(function() {
                            var img = $(this);
                            $(img).attr('src', d['img']);
                            $(item).find("a").attr("href", d['link']).attr('alt', d['name'] );
                            $(item).find("a").attr('title', d['name'] );

                        <?php if ($add == 1) { ?>

                            if (window.last_update<?php echo ($ele); ?> == null )
                            {
                                window.last_update<?php echo ($ele); ?> = new Date().getTime();
                            }
                            else if ( new Date().getTime() - window.last_update<?php echo ($ele); ?> > window.update_limit_ms<?php echo ($ele); ?> /*ms*/ && window.idleTime<?php echo ($ele); ?> > <?php echo( $idle_time_sec ); ?> /* seconds */) {

                                window.last_update<?php echo ($ele); ?> = new Date().getTime();

                                $('iframe').fadeOut(300,function(){
                                    $('iframe').attr('src', d['link'] ).on ("load", function(){
                                        $(this).fadeIn(300);
                                    });
                                });
                            }

                            <?php
                            }
                            ?>

                            $(img).fadeIn();
                            $(img).dequeue();
                        });

                        // $(item).find("img").removeClass("jadu-cms");
                        // $(item) .append($ ( "<a href='" +d['link']+ "'><img src='"+d['img']+"'/></a>") );
                    }


                window.current_coord<?php echo ($ele); ?> = (window.current_coord<?php echo ($ele); ?> + 1) % window.coords<?php echo ($ele); ?>.length;


                window.current_datum<?php echo ($ele); ?>++;
                if (window.current_datum<?php echo ($ele); ?> >= window.data_cache<?php echo ($ele); ?>.length) {
                    // shuffleArray(window.data_cache<?php echo ($ele); ?>);
                    window.current_datum<?php echo ($ele); ?> = window.current_datum<?php echo ($ele); ?> % window.data_cache<?php echo ($ele); ?>.length;
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

            .demo-img<?php echo ($ele); ?> {
	            display: block;
                max-width: <?php echo($px_size)?>px;
                max-height: <?php echo($px_size)?>px;
                margin: 0px;
                width: <?php echo($px_size)?>px;
                //height: <?php echo($px_size)?>px;
                padding: 0px;
                border: 0px;
            }

            .demo-logo-big<?php echo ($ele); ?> {
	            display: inline-block;
	            position: relative;
	            top: 0px; // <?php echo(-$px_size/2) ?>px
                max-width: <?php echo($px_size)*3 ?>px;
                max-height: <?php echo($px_size)*2 ?>px;
                height: 100%;
                padding: 0px;
                border: 0px;
            }

            .the_link>img:hover {
                filter: brightness(120%);
            }

            .cell<?php echo ($ele); ?> {
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

    demo_auto_grid(False, 0, $px_size, "'.autogrid3'", 600, -1, -1);

    ?>

    <div>
    <div style='width:100%; height:<?php echo($height) ?> ; overflow:hidden' class='autogrid3'> </div>
    </div>
<?php


    return ob_get_clean();
}
