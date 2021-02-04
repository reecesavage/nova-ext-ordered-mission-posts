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

  $defaultMissionDateLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['mission_ext_ordered_default_mission_date'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['mission_ext_ordered_default_mission_date']
                        : 'Default Mission Date';

  $defaultStardateLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['mission_ext_ordered_default_stardate'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['mission_ext_ordered_default_stardate']
                        : 'Default Stardate';

  $legacyModeLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['mission_ext_ordered_legacy_mode'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['mission_ext_ordered_legacy_mode']
                        : 'Day Time Legacy Mode';
  
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
      $event['data']['configId']['mission_ext_ordered_config_setting'] = 'id="mission_ext_ordered_config_setting"';


      $event['data']['label']['mission_ext_ordered_post_numbering'] = $editPostNumberLabel;
         $event['data']['inputs']['mission_ext_ordered_post_numbering'] = 'mission_ext_ordered_post_numbering';
         $event['data']['value']['mission_ext_ordered_post_numbering'] = '1';
       $event['data']['checked']['mission_ext_ordered_post_numbering'] = $post ? $post->mission_ext_ordered_post_numbering : '0';


        $event['data']['label']['mission_ext_ordered_default_mission_date'] = $defaultMissionDateLabel;
      $event['data']['inputs']['mission_ext_ordered_default_mission_date'] = array(
        'name' => 'mission_ext_ordered_default_mission_date',
        'id' => 'mission_ext_ordered_default_mission_date',
        'type'=>'date',
        
        'onkeypress' => 'return (function(evt)
        {  
            var charCode = (evt.which) ? evt.which : event.keyCode
          if((charCode>=35 && charCode<=40)||(charCode>=96 && charCode<=105))
        return true;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    if(charCode==8)
        return false;
             
        })(event)',
        'value' => $post ? $post->mission_ext_ordered_default_mission_date : '1'
      );


       $event['data']['label']['mission_ext_ordered_default_stardate'] = $defaultStardateLabel;
        $event['data']['inputs']['mission_ext_ordered_default_stardate'] = array(
        'name' => 'mission_ext_ordered_default_stardate',
        'id' => 'mission_ext_ordered_default_stardate',
        'onkeypress' => 'return (function(evt)
        {
           var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
        })(event)',
        'value' => $post ? $post->mission_ext_ordered_default_stardate : '1'
      );


         $event['data']['label']['mission_ext_ordered_legacy_mode'] = $legacyModeLabel;
         $event['data']['inputs']['mission_ext_ordered_legacy_mode'] = 'mission_ext_ordered_legacy_mode';
         $event['data']['value']['mission_ext_ordered_legacy_mode'] = '1';
       $event['data']['checked']['mission_ext_ordered_legacy_mode'] = $post ? $post->mission_ext_ordered_legacy_mode : '0';


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