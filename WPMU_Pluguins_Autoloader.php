<?php
/*
 Plugin Name: WPMU Plugins Autoload
 Description: This is a plugin which autoload files in subfolders of WordPress Must Use Plulgins
 Version: 1.0
 Author: Leandro de Amorim
 Author URI: https://www.linkedin.com/in/leandrodeamorim
 License: GNU GPL 2
 */

class WPMU_Plugins_Autoload
{
	private static $non_dirs = array( '.', '..', '.DS_Store' );
	
	public static function autoload($name)
	{
		if ( ! defined( 'WPMU_PLUGIN_DIR' ) ) {
			return;
		}
		
		$files = self::parse_files( WPMU_PLUGIN_DIR );
		self::include_plugin( self::get_folders($files) );
	}
	
	private static function parse_files( $file_path ) 
	{
		$files = scandir( $file_path );
		
		foreach ( $files as $key => $file ) {
			$files[$key] = "{$file_path}/{$file}";

			if (in_array($file, self::$non_dirs)) {
				unset( $files[$key] );
			}
		}
		
		return $files;
	}
	
	private function get_folders( $files )
	{
		foreach ( $files as $key => $file ) {
			if ( !is_dir( $file ) ) {
				unset($files[$key]);
			}
		}
		
		return $files;
	}
	
	private function get_files( $files )
	{
		foreach ( $files as $key => $file ) {
			if ( is_dir( $file ) ) {
				unset($files[$key]);
			}
		}
		
		return $files;
	}
	
	private static function include_plugin( $subfolders ) 
	{
		foreach ( $subfolders as $file ) {
			$files = self::get_files( self::parse_files( $file ) );
			
			foreach ( $files as $plugin ) {
				include_once $plugin;
			}
		}
	}
}

spl_autoload_register( array( 'WPMU_Plugins_Autoload', 'autoload' ) );