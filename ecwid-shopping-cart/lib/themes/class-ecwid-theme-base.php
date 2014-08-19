<?php

class Ecwid_Theme_Base {

	protected $adjust_pb_scroll = false;

	public function __construct()
	{
		if ( $this->adjust_pb_scroll ) {
			wp_enqueue_script(
				'ecwid-scroller',
				plugins_url( 'ecwid-shopping-cart/js/create_scroller.js' ),
				array( 'jquery' )
			);
		}
	}

	protected function need_advanced_layout()
	{
		return get_option('ecwid_advanced_theme_layout') == 'Y';
	}
}