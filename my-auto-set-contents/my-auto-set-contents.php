<?php
/*
Plugin Name: My Auto Set Contents
Description: Automatically creates a post and uploads all images in a specified folder to the media library upon activation.
Version: 1.0
Author: Koji Kuno
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Function to create a post and upload images to the media library upon plugin activation.
 *
 * @return void
 */
function map_create_post_and_upload_images() {
	// Image upload part
	$images_dir = plugin_dir_path( __FILE__ ) . 'images/'; // Path to the images folder within the plugin directory

	// Retrieve all image files in the folder
	$image_files = glob( $images_dir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE );

	// Check if image files exist
	if ( ! empty( $image_files ) ) {
		// Upload images to the media library
		$upload_dir = wp_upload_dir();

		foreach ( $image_files as $image_file ) {
			if ( file_exists( $image_file ) && is_readable( $image_file ) ) {
				$image_data = file_get_contents( $image_file );
				$filename   = basename( $image_file );
				$file       = $upload_dir['path'] . '/' . wp_unique_filename( $upload_dir['path'], $filename );

				// Save the image to the upload directory
				if ( file_put_contents( $file, $image_data ) !== false ) {
					// Set up the file details and register as an attachment
					$wp_filetype = wp_check_filetype( $filename, null );
					if ( $wp_filetype['type'] ) {
						$attachment = array(
							'post_mime_type' => $wp_filetype['type'],
							'post_title'     => sanitize_text_field( pathinfo( $filename, PATHINFO_FILENAME ) ),
							'post_content'   => '',
							'post_status'    => 'inherit',
						);

						$attach_id = wp_insert_attachment( $attachment, $file );
						if ( ! is_wp_error( $attach_id ) ) {
							require_once ABSPATH . 'wp-admin/includes/image.php';
							$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
							wp_update_attachment_metadata( $attach_id, $attach_data );
						}
					}
				}
			}
		}
	}

	// Post creation part
	// Check if the post already exists
	$post_title    = 'Automatically Created Post';
	$existing_post = get_page_by_title( $post_title, OBJECT, 'post' );

	if ( null === $existing_post ) {
		// Data for the new post
		$new_post = array(
			'post_title'   => $post_title,
			'post_content' => 'This post was automatically created by the plugin.',
			'post_status'  => 'publish',
			'post_author'  => 1, // Author ID of the post (usually 1)
			'post_type'    => 'post',
		);

		// Insert the post
		wp_insert_post( $new_post );
	}
}

// Hook the function to run on plugin activation
register_activation_hook( __FILE__, 'map_create_post_and_upload_images' );
