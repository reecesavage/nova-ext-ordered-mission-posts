<?php 

$this->require_extension('jquery');
$this->require_extension('timepicker');

$this->event->listen(['template', 'render', 'data'], function($event){
  $event['data']['javascript'] 
        .= $this->extension['nova_ext_ordered_mission_posts']->inline_js('custom', 'admin');
});
require_once dirname(__FILE__).'/events/location_admin_write_missionpost.php';
require_once dirname(__FILE__).'/events/db.php';
require_once dirname(__FILE__).'/events/location_admin_manage_posts_edit.php';
require_once dirname(__FILE__).'/events/location_main_sim_missions_one.php';
