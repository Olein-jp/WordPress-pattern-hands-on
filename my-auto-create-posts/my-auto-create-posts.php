<?php
/*
Plugin Name: My Auto Create Posts
Description: 有効化されたら自動的に任意の投稿を作成するプラグイン
Version: 1.0
Author: あなたの名前
*/

// プラグインが有効化されたときに実行される関数
function map_create_custom_post() {
	// 投稿がすでに存在しないかチェック
	$post_title = '自動生成された投稿';
	$existing_post = get_page_by_title($post_title, OBJECT, 'post');

	if ($existing_post === null) {
		// 新しい投稿のデータ
		$new_post = array(
			'post_title'    => $post_title,
			'post_content'  => 'これはプラグインによって自動的に作成された投稿です。',
			'post_status'   => 'publish',
			'post_author'   => 1,  // 投稿の作者ID（通常は1）
			'post_type'     => 'post'
		);

		// 投稿を挿入
		wp_insert_post($new_post);
	}
}

// プラグインが有効化されたときに関数をフック
register_activation_hook(__FILE__, 'map_create_custom_post');
