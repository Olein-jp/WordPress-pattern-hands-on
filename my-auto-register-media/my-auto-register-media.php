<?php
/*
Plugin Name: My Auto Upload Plugin
Description: 有効化されたら指定のフォルダ内のすべての画像をメディアライブラリにアップロードするプラグイン
Version: 1.0
Author: あなたの名前
*/

// プラグインが有効化されたときに実行される関数
function map_upload_custom_images() {
	$images_dir = plugin_dir_path(__FILE__) . 'images/'; // プラグインフォルダ内の画像フォルダへのパス

	// フォルダ内のすべての画像ファイルを取得
	$image_files = glob($images_dir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

	// 画像ファイルが存在するかチェック
	if (empty($image_files)) {
		return;
	}

	// 画像をメディアライブラリにアップロード
	$upload_dir = wp_upload_dir();

	foreach ($image_files as $image_file) {
		if (file_exists($image_file) && is_readable($image_file)) {
			$image_data = file_get_contents($image_file);
			$filename = basename($image_file);
			$file = $upload_dir['path'] . '/' . wp_unique_filename($upload_dir['path'], $filename);

			// 画像をアップロードディレクトリに保存
			if (file_put_contents($file, $image_data) === false) {
				continue; // ファイル書き込みに失敗した場合、次のファイルへ
			}

			// ファイルの詳細を設定して添付ファイルとして登録
			$wp_filetype = wp_check_filetype($filename, null);
			if ($wp_filetype['type']) {
				$attachment = array(
					'post_mime_type' => $wp_filetype['type'],
					'post_title'     => sanitize_text_field(pathinfo($filename, PATHINFO_FILENAME)),
					'post_content'   => '',
					'post_status'    => 'inherit'
				);

				$attach_id = wp_insert_attachment($attachment, $file);
				if (!is_wp_error($attach_id)) {
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					$attach_data = wp_generate_attachment_metadata($attach_id, $file);
					wp_update_attachment_metadata($attach_id, $attach_data);
				}
			}
		}
	}
}

// プラグインが有効化されたときに関数をフック
register_activation_hook(__FILE__, 'map_upload_custom_images');
