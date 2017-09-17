<?php
/**
 * Plugin Name: kintone form Multiple APP
 * Plugin URI:  
 * Description: This plugin is an addon for "kintone form".
 * Version:	 1.0.0
 * Author:	  Takashi Hosoya
 * Author URI:  http://ht79.info/
 * License:	 GPLv2 
 * Text Domain: kintone-form-multiple-app
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2017 Takashi Hosoya ( http://ht79.info/ )
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

define( 'KINTONE_FORM_MULTIPLE_APP_URL',  plugins_url( '', __FILE__ ) );
define( 'KINTONE_FORM_MULTIPLE_APP_PATH', dirname( __FILE__ ) );


$KintoneFormMultipleApp = new KintoneFormMultipleApp();
$KintoneFormMultipleApp->register();

require_once( KINTONE_FORM_MULTIPLE_APP_PATH . '/inc/BFIGitHubPluginUploader.php' );


class KintoneFormMultipleApp {

	private $version = '';
	private $langs   = '';
	private $nonce   = 'kintone_form_multiple_app_';
		
	function __construct()
	{
		$data = get_file_data(
			__FILE__,
			array( 'ver' => 'Version', 'langs' => 'Domain Path' )
		);
		$this->version = $data['ver'];
		$this->langs   = $data['langs'];
		
	}

	public function register()
	{
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 1 );
	}

	public function plugins_loaded()
	{
		load_plugin_textdomain(
			'kintone-form-multiple-app',
			false,
			dirname( plugin_basename( __FILE__ ) ).$this->langs
		);

		if ( is_admin() ) {
		    new BFIGitHubPluginUpdater( __FILE__, 'tkc49', "form-data-to-kintone-multiple-app" );
		}		


		add_action( 'kintone_form_setting_panel_after', array( $this, 'kintone_form_setting_panel_after' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'kintone_form_do_admin_enqueue_scripts' ) );

		
	}

	public function kintone_form_setting_panel_after(){

		$html = '';
		$html .= '<table class="template row" style="margin-bottom: 30px; border-top: 6px solid #ccc; width: 100%;">';
		$html .= '	<tr>';
		$html .= '		<td valign="top" style="padding: 10px 0px;">';
		$html .= '			APP ID:<input type="text" id="kintone-form-appid" name="kintone_setting_data[app_datas][{{row-count-placeholder}}][appid]" class="small-text" size="70" value="" />';
		$html .= '			Api Token:<input type="text" id="kintone-form-token" name="kintone_setting_data[app_datas][{{row-count-placeholder}}][token]" class="regular-text" size="70" value="" />';
		$html .= '		</td>';
		$html .= '		<td width="10%"><span class="remove button">Remove</span></td>';
		$html .= '	</tr>';
		$html .= '</table>';		

		echo $html;

	}

	public function kintone_form_do_admin_enqueue_scripts(){

		wp_enqueue_script(
			'repeatable-fields',
			plugins_url( 'asset/js/repeatable-fields/repeatable-fields.js', __FILE__ ),
			array( 'jquery' ),
			filemtime( dirname( __FILE__ ) . '/asset/js/repeatable-fields/repeatable-fields.js' ),
			true
		);

		wp_enqueue_script(
			'kintone-form',
			plugins_url( 'asset/js/scripts.js', __FILE__ ),
			array( 'jquery' ),
			filemtime( dirname( __FILE__ ) . '/asset/js/scripts.js' ),
			true
		);

	}
}