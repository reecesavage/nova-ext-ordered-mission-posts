<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once MODPATH.'core/libraries/Nova_controller_main.php';

class __extensions__nova_ext_ordered_mission_posts__Manage extends Nova_controller_main
{
	public function __construct()
	{
		parent::__construct();
       

      // $this->_regions['nav_sub'] = Menu::build('adminsub', 'manageext');
		$this->_regions['nav_sub'] = Menu::build('sub', 'sim');

		
	}
  
  public function config()
  {
	
	$this->_regions['javascript'] .= $this->extension['nova_ext_ordered_mission_posts']->inline_css('manage', 'admin', $data);
        $extConfigFilePath = dirname(__FILE__).'/../config.json';
         
        if ( !file_exists( $extConfigFilePath ) ) {	
			return [];
		}
        $file = file_get_contents( $extConfigFilePath );
		$data['jsons'] = json_decode( $file, true );
        $data['title']='Configuration';

        if(isset($_POST['submit']) && $_POST['submit']=='Submit')
        {
          $data['jsons']['nova_ext_ordered_mission_posts']['mission_ext_ordered_config_setting']=$_POST['mission_ext_ordered_config_setting'];
          $data['jsons']['nova_ext_ordered_mission_posts']['mission_ext_ordered_post_numbering']=$_POST['mission_ext_ordered_post_numbering'];
          $data['jsons']['nova_ext_ordered_mission_posts']['mission_ext_ordered_default_mission_date']=$_POST['mission_ext_ordered_default_mission_date'];
          $data['jsons']['nova_ext_ordered_mission_posts']['mission_ext_ordered_default_stardate']=$_POST['mission_ext_ordered_default_stardate'];
         $data['jsons']['nova_ext_ordered_mission_posts']['mission_ext_ordered_legacy_mode']=$_POST['mission_ext_ordered_legacy_mode'];
          $data['jsons']['nova_ext_ordered_mission_posts']['label_edit_day']=$_POST['label_edit_day'];
          $data['jsons']['nova_ext_ordered_mission_posts']['label_edit_date']=$_POST['label_edit_date'];
          $data['jsons']['nova_ext_ordered_mission_posts']['label_edit_startdate']=$_POST['label_edit_startdate'];
          $data['jsons']['nova_ext_ordered_mission_posts']['label_edit_time']=$_POST['label_edit_time'];
          $data['jsons']['nova_ext_ordered_mission_posts']['label_view_concat']=$_POST['label_view_concat'];
          $data['jsons']['nova_ext_ordered_mission_posts']['label_view_suffix']=$_POST['label_view_suffix'];
         $jsonEncode = json_encode( $data['jsons'],JSON_PRETTY_PRINT);



          file_put_contents($extConfigFilePath, $jsonEncode);
        }
     
		$this->_regions['title'] .= 'Configuration';
		$this->_regions['content'] = $this->extension['nova_ext_ordered_mission_posts']
		                                ->view('config', $this->skin, 'admin', $data);
		                           
		Template::assign($this->_regions);
		Template::render();
  }
}
