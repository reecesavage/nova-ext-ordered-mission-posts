<?php 

$this->event->listen(['location', 'view', 'data', 'admin', 'manage_posts_edit'], function($event){
  


$id = (is_numeric($this->uri->segment(4))) ? $this->uri->segment(4) : false;
  $post = $id ? $this->posts->get_post($id) : null;

  
  
  $timepickerOptions = [
    'timeFormat' => 'HHmm',
    'defaultTime' =>  $post ? $post->nova_ext_ordered_post_time : '0000'
  ];

  $this->config->load('extensions');
  $extensionsConfig = $this->config->item('extensions');
    

    
  if(!empty($extensionsConfig['nova_ext_ordered_mission_posts']['timepicker_options'])){
    foreach($extensionsConfig['nova_ext_ordered_mission_posts']['timepicker_options'] as $key => $value){
      $timepickerOptions[$key] = $value;
    }
  }
  
  $editDayLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['label_edit_day'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['label_edit_day']
                        : 'Mission Day';

  $editDateLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['label_edit_date'])? $extensionsConfig['nova_ext_ordered_mission_posts']['label_edit_date']
                        : 'Mission Date';


  $editStartDateLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['label_edit_startdate'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['label_edit_startdate']
                        : 'Mission Start Date';

  $editTimeLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['label_edit_time'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['label_edit_time']
                        : 'Time';
  
   $event['data']['label']['nova_ext_ordered_post_day'] = $editDayLabel;
      $event['data']['inputs']['nova_ext_ordered_post_day'] = array(
        'name' => 'nova_ext_ordered_post_day',
        'id' => 'nova_ext_ordered_post_day',
        'onkeypress' => 'return (function(evt)
        {
           var charCode = (evt.which) ? evt.which : event.keyCode
           if (charCode > 31 && (charCode < 48 || charCode > 57))
              return false;

           return true;
        })(event)',
        'value' => $post ? $post->nova_ext_ordered_post_day : '1'
      );
      
      $event['data']['label']['nova_ext_ordered_post_time'] = $editTimeLabel;
      $event['data']['inputs']['nova_ext_ordered_post_time'] = array(
        'name' => 'nova_ext_ordered_post_time',
        'id' => 'nova_ext_ordered_post_time',
        'data-timepicker' => str_replace('"', '&quot;', json_encode($timepickerOptions)),
        'value' => $post ? $post->nova_ext_ordered_post_time : '0000'
      );


       $event['data']['label']['nova_ext_ordered_post_date'] = $editDateLabel;
      $event['data']['inputs']['nova_ext_ordered_post_date'] = array(
        'name' => 'nova_ext_ordered_post_date',
        'id' => 'nova_ext_ordered_post_date',
        'type'=>'date',
        'style'=>'width: 281px;height: 31px;',
        'onkeypress' => 'return (function(evt)
        {
              return false;
        })(event)',
        'value' => $post ? $post->nova_ext_ordered_post_date : '1'
      );


        $event['data']['label']['nova_ext_ordered_post_start_date'] = $editStartDateLabel;
      $event['data']['inputs']['nova_ext_ordered_post_start_date'] = array(
        'name' => 'nova_ext_ordered_post_start_date',
        'id' => 'nova_ext_ordered_post_start_date',
        'onkeypress' => 'return (function(evt)
        {
           var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
        })(event)',
        'value' => $post ? $post->nova_ext_ordered_post_start_date : '1'
      );
  
});

$this->event->listen(['location', 'view', 'output', 'admin', 'manage_posts_edit'], function($event){

 $this->config->load('extensions');
    $extensionsConfig = $this->config->item('extensions');

 $dateFormatFile=  isset($extensionsConfig['nova_ext_ordered_mission_posts']['format'])?$extensionsConfig['nova_ext_ordered_mission_posts']['format']:'day_time';

  $event['output'] .= $this->extension['jquery']['generator']
                  ->select('[name="post_timeline"]')->closest('p')
                  ->after(
                    $this->extension['nova_ext_ordered_mission_posts']
                         ->view($dateFormatFile, $this->skin, 'admin', $event['data'])
                  );

  $event['output'] .= $this->extension['jquery']['generator']
                           ->select('[name="post_timeline"]')->closest('p')->remove();
                  
});
