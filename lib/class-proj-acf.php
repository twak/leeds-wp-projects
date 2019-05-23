<?php
/**
 * Advanced Custom Fields setup for Toolkit Profiles plugin
 *
 * @package TK_Profiles
 */

if ( ! class_exists( 'Proj_Profiles_ACF' ) ) {
	/**
	 * Class to create custom fields for the Profile post type using the
	 * Advanced Custom Fields plugin
	 */
	class Proj_Profiles_ACF {
		/**
		 * Constructor - registers all hooks with the WordPress API.
		 */
		public function __construct() {
			/**
			 * Sets up custom fields in ACF
			 */
			add_action( 'acf/init', array( $this, 'setup_acf' ), 9 );
		}

		/**
		 * Adds ACF custom fields and options page
		 */
		public function setup_acf() {

            acf_add_local_field_group(array (
                'key' => 'group_5cdeb70cc0b51',
                'title' => 'Publication',
                'fields' => array (
                    array (
                        'key' => 'field_5cdeb74322e53',
                        'label' => 'Published at',
                        'name' => 'conf',
                        'type' => 'text',
                        'instructions' => 'Conference or journal to place under headline',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => 'siggbellybuttonfluff',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array (
                        'key' => 'field_5cdeb78b22e54',
                        'label' => 'Authors',
                        'name' => 'all_authors',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => 'me, myself, and I',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array (
                        'key' => 'field_5ce3fbc848b02',
                        'label' => 'white rose',
                        'name' => 'whiterose',
                        'type' => 'url',
                        'instructions' => 'white rose pdf url',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => 'http://eprints.whiterose.ac.uk/138256/1/KellyGuerreroEtAl_FrankenGAN_SigAsia2018.pdf',
                    ),
                    array (
                        'key' => 'field_5ce43b662fb4f',
                        'label' => 'authors',
                        'name' => 'authors',
                        'type' => 'relationship',
                        'instructions' => 'Which profiles should be shown below this project page? (click \'select post\' type then \'profile\')',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'post_type' => array (
                            0 => 'tk_profiles',
                        ),
                        'taxonomy' => array (
                        ),
                        'filters' => array (
                            0 => 'search',
                            1 => 'post_type',
                            2 => 'taxonomy',
                        ),
                        'elements' => '',
                        'min' => '',
                        'max' => '',
                        'return_format' => 'object',
                    ),
                ),
                'location' => array (
                    array (
                        array (
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'projects',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'acf_after_title',
                'style' => 'default',
                'label_placement' => 'left',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            ));
		}
	}
	new Proj_Profiles_ACF();
}
