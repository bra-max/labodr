<?php

class OsCSVHelper {
	public static function array_to_csv( $data ) {
		$output = fopen( "php://output", "wb" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fopen
		foreach ( $data as $row ) {
			fputcsv( $output, $row );
		}
		fclose( $output ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
	}


	public static function get_import_dir( bool $create = true ): string {
		$wp_upload_dir = wp_upload_dir( null, $create );
		if ( $wp_upload_dir['error'] ) {
			throw new \Exception( esc_html( $wp_upload_dir['error'] ) );
		}

		$upload_dir = trailingslashit( $wp_upload_dir['basedir'] ) . 'latepoint';
		if ( $create ) {
			if ( ! file_exists( $upload_dir ) ) {
				wp_mkdir_p( $upload_dir );
			}
		}
		return $upload_dir;
	}

	public static function upload_csv_file($files,  $file_name ) {
		if(empty($files[$file_name])){
			throw new \Exception('File not selected');
		}


		$file = $files[$file_name];

		$upload_dir = OsCsvHelper::get_import_dir();
		$tmp_name = uniqid('latepoint_customers_csv_') . '.csv';
		$filepath = $upload_dir . '/' . $tmp_name;

		if (!move_uploaded_file($file['tmp_name'][0], $filepath)) {
			throw new \Exception('Error uploading file');
		}
		set_transient('csv_import_file_' . OsWpUserHelper::get_current_user_id(), $filepath, 3600);
		return $filepath;
	}


	public static function is_valid_csv( $file_path ): bool {
		$valid_filetypes = [
			'csv' => 'text/csv',
			'txt' => 'text/plain',
		];

		$filetype = wp_check_filetype( $file_path, $valid_filetypes );

		if ( in_array( $filetype['type'], $valid_filetypes, true ) ) {
			return true;
		}

		return false;
	}

	public static function get_csv_data( $file_path, $limit = false ) {
		if (!file_exists($file_path)) {
			throw new \Exception('File does not exist');
		}

		if (!OsCSVHelper::is_valid_csv($file_path)) {
			throw new \Exception('Invalid file format');
		}

		$data = [];
		$i = 0;
		if (($handle = fopen($file_path, 'r')) !== false) {
			while (($row = fgetcsv($handle)) !== false) {
				$data[] = $row;
				$i++;
				if ($limit && $i >= $limit) {
					break;
				}
			}
			fclose($handle);
		} else {
			throw new \Exception('Error reading file');
		}
		return $data;
	}

}