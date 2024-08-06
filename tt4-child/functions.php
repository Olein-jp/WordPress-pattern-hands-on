<?php
/**
 * Add theme support
 *
 * @return void
 */
function tt4_child_theme_support() {
	add_theme_support( 'starter-content', tt4_starter_content() );
}
add_action( 'after_setup_theme', 'tt4_child_theme_support' );

/**
 * Get starter content
 * @return array[]
 */
function tt4_starter_content() {
	$starter_content = array(
		'posts' => array(
			'post1' => array(
				'post_type'    => 'post',
				'post_title'   => '投稿1です',
				'post_content' => join(
					'',
					array(
						'<!-- wp:paragraph -->',
						'<p>スターターコンテンツです。投稿サンプルです。</p>',
						'<!-- /wp:paragraph -->',
					)
				),
			),
		),
	);

	return $starter_content;
}

/**
 * Register meta
 *
 * @return void
 */
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
add_action( 'init', 'tt4_child_register_meta' );