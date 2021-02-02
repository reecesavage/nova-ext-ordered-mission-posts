<?php
 
$this->event->listen(['location', 'view', 'data', 'admin', 'manage_missions_action'], function($event){


  
   $id = isset($event['data']['id'])?$event['data']['id']:'';
   if(!empty($id))
   {
    $query = $this->db->get_where('missions', array('mission_id' => $id));
    $post = ($query->num_rows() > 0) ? $query->row() : false;
   }
  $this->config->load('extensions');
  $extensionsConfig = $this->config->item('extensions');


  $editConfigLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['mission_ext_ordered_config_setting'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['mission_ext_ordered_config_setting']
                        : 'Configuration';

  $editPostNumberLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['mission_ext_ordered_post_numbering'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['mission_ext_ordered_post_numbering']
                        : 'Post Numbering';
  
  switch($this->uri->segment(4)){
     


    default:
      
         $event['data']['label']['mission_ext_ordered_config_setting'] = $editConfigLabel;
         $event['data']['inputs']['mission_ext_ordered_config_setting'] = 'mission_ext_ordered_config_setting';
        $event['data']['option']['mission_ext_ordered_config_setting'] = array(
        'default'         => 'Nova Default',
        'day_time'           => 'Day Time',
        'date_time'         => 'Date Time',
        'stardate'        => 'Stardate',
);
         $event['data']['value']['mission_ext_ordered_config_setting'] = $post ? $post->mission_ext_ordered_config_setting : 'default';
      $event['data']['configId']['mission_ext_ordered_config_setting'] = 'id="mission_ext_ordered_config_setting" style="width: 281px;height: 31px;"';


      $event['data']['label']['mission_ext_ordered_post_numbering'] = $editPostNumberLabel;
         $event['data']['inputs']['mission_ext_ordered_post_numbering'] = 'mission_ext_ordered_post_numbering';
         $event['data']['value']['mission_ext_ordered_post_numbering'] = '1';
      $event['data']['checked']['mission_ext_ordered_post_numbering'] = $post ? $post->mission_ext_ordered_post_numbering : '0';


  }
  
});

$this->event->listen(['location', 'view', 'output', 'admin', 'manage_missions_action'], function($event){
  switch($this->uri->segment(4)){
    case 'view':
      break;
    default:   
    $this->config->load('extensions');
                $event['output'] .= $this->extension['jquery']['generator']
                      ->select('[name="mission_status"]')->closest('p')
                      ->after(
                        $this->extension['nova_ext_ordered_mission_posts']
                             ->view('mission-form', $this->skin, 'admin', $event['data'])
                      );
      
 }
});