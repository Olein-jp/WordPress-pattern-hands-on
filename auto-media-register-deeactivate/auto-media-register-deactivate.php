<?php
/*
Plugin Name: Auto Media Register & Deactivate
Description: Automatically registers all files in wp-content/uploads to the Media Library upon activation and then deactivates itself.
Version: 1.0
Author: Your Name
*/

// プラグインが有効化されたときに実行されるフック
register_activation_hook(__FILE__, 'register_uploads_media');

function register_uploads_media() {
	$uploads_dir = ABSPATH . 'wp-content/uploads';

	if (!is_dir($uploads_dir)) {
		return;
	}

	$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($uploads_dir));
	$all_files_processed = true;

	foreach ($files as $file) {
		if ($file->isFile()) {
			// ファイルのパスを取得
			$file_path = $file->getRealPath();

			// ファイルのタイプを取得
			$filetype = wp_check_filetype($file_path, null);

			// サポートされていないファイルタイプはスキップ
			if (!$filetype['type']) {
				continue;
			}

			// 投稿データの準備
			$attachment = array(
				'guid'           => $file_path,
				'post_mime_type' => $filetype['type'],
				'post_title'     => sanitize_file_name(basename($file_path)),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);

			// 添付ファイルをデータベースに挿入
			$attach_id = wp_insert_attachment($attachment, $file_path);

			// 挿入が成功しなかった場合は次のファイルへ
			if (is_wp_error($attach_id)) {
				$all_files_processed = false;
				continue;
			}

			// 添付ファイルのメタデータを生成してデータベースに保存
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			$attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
			$update_result = wp_update_attachment_metadata($attach_id, $attach_data);

			// メタデータの更新が成功しなかった場合は次のファイルへ
			if (is_wp_error($update_result)) {
				$all_files_processed = false;
				continue;
			}
		}
	}

	// 全てのファイルの処理が完了したらプラグインを無効化
	if ($all_files_processed) {
		deactivate_plugins(plugin_basename(__FILE__));
	}
}
?>
