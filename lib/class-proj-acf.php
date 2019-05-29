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
                        'instructions' => 'shown at top of page. not indexed.',
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
                        'instructions' => 'white rose pdf url to show at top of page. optional.',
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
                    array(
                        'key' => 'field_5ce9e3cd73279',
                        'label' => 'show authors',
                        'name' => 'show_authors',
                        'type' => 'true_false',
                        'instructions' => 'show list of vcg authors associated with this project at the bottom of this project',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'message' => '',
                        'default_value' => 1,
                        'ui' => 0,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
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
                        'min' => '1',
                        'max' => '',
                        'return_format' => 'object',
                    ),
                    array(
                        'key' => 'field_5ce9e3iu5479',
                        'label' => 'show partners',
                        'name' => 'show_partners',
                        'type' => 'true_false',
                        'instructions' => 'show below list of partners at the bottom of this project?',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'message' => '',
                        'default_value' => 1,
                        'ui' => 0,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),
                    array (
                        'key' => 'field_34534hh444',
                        'label' => 'partners',
                        'name' => 'partners',
                        'type' => 'relationship',
                        'instructions' => 'Which partners to associate with this project?  (click \'select post\' type then \'person\')',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'post_type' => array (
                            0 => 'partners',
                        ),
                        'elements' => '',
                        'min' => '',
                        'max' => '',
                        'return_format' => 'object',
                    ),
                    array(
                        'key' => 'field_5ce9e37673278',
                        'label' => 'show bibtex papers',
                        'name' => 'show_papers',
                        'type' => 'true_false',
                        'instructions' => 'show related papers at bottom of project?',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'message' => '',
                        'default_value' => 1,
                        'ui' => 0,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),
                    array(
                        'key' => 'field_5ce9b0485d974',
                        'label' => 'bibtex papers',
                        'name' => 'bibtex_papers',
                        'type' => 'repeater',
                        'instructions' => 'list of papers associated with this project.  (click \'add\' then copy bibtex id from <a href="https://vcg.leeds.ac.uk/research/publications/">publications page</a>)',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'collapsed' => 'field_5ce9b0605d975',
                        'min' => 0,
                        'max' => 0,
                        'layout' => 'table',
                        'button_label' => 'add',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_5ce9b0605d975',
                                'label' => 'bibtex id',
                                'name' => 'bibtex_id',
                                'type' => 'text',
                                'instructions' => 'for each paper, enter bibtex id from publication page.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => 'wrro144010',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                            ),
                        ),
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
