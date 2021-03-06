<?php 

$this->event->listen(['location', 'view', 'data', 'admin', 'manage_posts_edit'], function($event){
  

$id = (is_numeric($this->uri->segment(4))) ? $this->uri->segment(4) : false;
  $post = $id ? $this->posts->get_post($id) : null;
    
    $postDay= $post ? $post->nova_ext_ordered_post_day : 1;
    $postTime=$post ? $post->nova_ext_ordered_post_time : '0000';
    $postDayName='nova_ext_ordered_post_day';
    $postTimeName='nova_ext_ordered_post_time';
  if(!empty($post))
  {
     $query = $this->db->get_where('missions', array('mission_id' => $post->post_mission));
   $model = ($query->num_rows() > 0) ? $query->row() : false;
   if(!empty($model) && $model->mission_ext_ordered_legacy_mode==1 && $model->mission_ext_ordered_config_setting=='day_time')
   {
        $postDay=$post->post_chronological_mission_post_day;
        $postTime=$post->post_chronological_mission_post_time;

        $postDayName='post_chronological_mission_post_day';
        $postTimeName='post_chronological_mission_post_time';
   }
  }
  
  
  $timepickerOptions = [
    'timeFormat' => 'HHmm',
    'defaultTime' =>  $postTime
  ];

  $this->config->load('extensions');
  $extensionsConfig = $this->config->item('extensions');


   $extConfigFilePath = dirname(__FILE__).'/../config.json';
         
        if ( file_exists( $extConfigFilePath ) ) { 
            $file = file_get_contents( $extConfigFilePath );
            $json = json_decode( $file, true );
    }
    
    
  if(!empty($extensionsConfig['nova_ext_ordered_mission_posts']['timepicker_options'])){
    foreach($extensionsConfig['nova_ext_ordered_mission_posts']['timepicker_options'] as $key => $value){
      $timepickerOptions[$key] = $value;
    }
  }
  
  $editDayLabel = isset($json['nova_ext_ordered_mission_posts']['label_edit_day'])
                        ? $json['nova_ext_ordered_mission_posts']['label_edit_day']['value']
                        : 'Mission Day';

  $editDateLabel = isset($json['nova_ext_ordered_mission_posts']['label_edit_date'])? $json['nova_ext_ordered_mission_posts']['label_edit_date']['value']
                        : 'Date';


  $editStartDateLabel = isset($json['nova_ext_ordered_mission_posts']['label_edit_startdate'])
                        ? $json['nova_ext_ordered_mission_posts']['label_edit_startdate']['value']
                        : 'Stardate';

  $editTimeLabel = isset($json['nova_ext_ordered_mission_posts']['label_edit_time'])
                        ? $json['nova_ext_ordered_mission_posts']['label_edit_time']['value']
                        : 'Time';



  
   $event['data']['label']['nova_ext_ordered_post_day'] = $editDayLabel;
      $event['data']['inputs']['nova_ext_ordered_post_day'] = array(
        'name' => $postDayName,
        'id' => 'nova_ext_ordered_post_day',
        'onkeypress' => 'return (function(evt)
        {
           var charCode = (evt.which) ? evt.which : event.keyCode
           if (charCode > 31 && (charCode < 48 || charCode > 57))
              return false;

           return true;
        })(event)',
        'value' => $postDay
      );
      
      $event['data']['label']['nova_ext_ordered_post_time'] = $editTimeLabel;
      $event['data']['inputs']['nova_ext_ordered_post_time'] = array(
        'name' => $postTimeName,
        'id' => 'nova_ext_ordered_post_time',
        'data-timepicker' => str_replace('"', '&quot;', json_encode($timepickerOptions)),
        'value' => $postTime
      );


       $event['data']['label']['nova_ext_ordered_post_date'] = $editDateLabel;
      $event['data']['inputs']['nova_ext_ordered_post_date'] = array(
        'name' => 'nova_ext_ordered_post_date',
        'id' => 'nova_ext_ordered_post_date',
         'class'=>'medium datepick',
        'data-value' => $post ? $post->nova_ext_ordered_post_date : ''
      );


        $event['data']['label']['nova_ext_ordered_post_stardate'] = $editStartDateLabel;
        $event['data']['inputs']['nova_ext_ordered_post_stardate'] = array(
        'name' => 'nova_ext_ordered_post_stardate',
        'id' => 'nova_ext_ordered_post_stardate',
        'onkeypress' => 'return (function(evt)
        {
           var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
        })(event)',
        'value' => $post ? $post->nova_ext_ordered_post_stardate : ''
      );
});

$this->event->listen(['location', 'view', 'output', 'admin', 'manage_posts_edit'], function($event){

 $event['output'] .= $this->extension['nova_ext_ordered_mission_posts']->inline_css('manage', 'admin', $event['data']);

  $event['output'] .= $this->extension['jquery']['generator']
                  ->select('[name="post_timeline"]')->closest('p')
                  ->before(
                    $this->extension['nova_ext_ordered_mission_posts']
                         ->view('form', $this->skin, 'admin', $event['data'])
                  );

});
