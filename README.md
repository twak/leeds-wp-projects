# leeds-wp-projects

wordpress plugin for projects on [leeds cms](https://vcg.leeds.ac.uk/projects/) and tom's [homepage](https://twak.org).

use the shortcode `[demo height="600px" px_size=64]` to insert a demo-cube.

or php code 

``
					<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
			 			<?php
				 	demo_auto_grid(0, 64, "'.autogrid2'", 200, -1, -1 )
				?>
					<div><div style='width:100%; height:1200px ; overflow:hidden' class='autogrid2'> </div></div>
``
				


