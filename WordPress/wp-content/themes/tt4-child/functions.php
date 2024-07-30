<?php
add_action( 'init', 'tt4_child_register_meta' );
function tt4_child_register_meta() {
	register_meta(
		'post',
		'cf-image-title',
		array(
			'show_in_rest'      => true,
			'single'            => true,
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => 'No title',
		)
	);

	register_meta(
		'post',
		'cf-image-url',
		array(
			'show_in_rest'      => true,
			'single'            => true,
			'type'              => 'string',
			'sanitize_callback' => 'esc_url_raw',
			'default'           => 'https://placehold.jp/1000x1000.png',
		)
	);

	register_meta(
		'post',
		'cf-image-description',
		array(
			'show_in_rest'      => true,
			'single'            => true,
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => 'No description',
		)
	);
}
