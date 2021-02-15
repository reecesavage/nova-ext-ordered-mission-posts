<?php 

$this->require_extension('jquery');
$this->require_extension('timepicker');


$this->event->listen(['template', 'render', 'data'], function($event){

	$modFolder=  base_url() . MODFOLDER;
    $event['data']['javascript'] 
        .= '<link rel="stylesheet" href="'.$modFolder.'/assets/js/css/jquery.ui.datepicker.css">' 
        . '<script type="text/javascript" src="'.$modFolder.'/assets/js/jquery.ui.datepicker.min.js"></script>'
        .$this->extension['nova_ext_ordered_mission_posts']->inline_js('custom', 'admin');
});
require_once dirname(__FILE__).'/events/location_admin_write_missionpost.php';
require_once dirname(__FILE__).'/events/db.php';
require_once dirname(__FILE__).'/events/location_admin_manage_posts_edit.php';
require_once dirname(__FILE__).'/events/location_main_sim_missions_one.php';
require_once dirname(__FILE__).'/events/location_admin_add_mission.php';
require_once dirname(__FILE__).'/events/parser_parse_string_nova_missionpost.php';


require_once dirname(__FILE__) . '/controllers/Installer.php';
$manager = ( new \nova_ext_ordered_mission_posts\Installer() )->install();


