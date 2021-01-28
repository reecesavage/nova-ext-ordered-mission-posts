<?php
 

$this->event->listen(['location', 'view', 'data', 'admin', 'write_missionpost'], function($event){

  $id = (is_numeric($this->uri->segment(3))) ? $this->uri->segment(3) : false;
  $post = $id ? $this->posts->get_post($id) : null;
  
  $timepickerOptions = [
    'timeFormat' => 'HHmm',
    'defaultTime' =>  $post ? $post->post_chronological_mission_post_time : '0000'
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

  $viewPrefixLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['label_view_prefix'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['label_view_prefix']
                        : 'Mission Day';

  $viewConcatLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['label_view_concat'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['label_view_concat']
                        : 'at';

  $viewSuffixLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['label_view_suffix'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['label_view_suffix']
                        : '';
  
  switch($this->uri->segment(4)){
    case 'view':
      $event['data']['inputs']['timeline']['value'] = $viewPrefixLabel.' '.$post->post_chronological_mission_post_day.' '.$viewConcatLabel.' '.$post->post_chronological_mission_post_time.' '.$viewSuffixLabel;
      break;
    default:
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
        'value' => $post ? $post->post_chronological_mission_post_day : '1'
      );
      
      $event['data']['label']['nova_ext_ordered_post_time'] = $editTimeLabel;
      $event['data']['inputs']['nova_ext_ordered_post_time'] = array(
        'name' => 'nova_ext_ordered_post_time',
        'id' => 'nova_ext_ordered_post_time',
        'data-timepicker' => str_replace('"', '&quot;', json_encode($timepickerOptions)),
        'value' => $post ? $post->post_chronological_mission_post_time : '0000'
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
        'value' => $post ? $post->post_chronological_mission_post_day : '1'
      );


        $event['data']['label']['nova_ext_ordered_post_start_date'] = $editStartDateLabel;
      $event['data']['inputs']['nova_ext_ordered_post_start_date'] = array(
        'name' => 'nova_ext_ordered_post_start_date',
        'id' => 'nova_ext_ordered_post_start_date',
         
        'onkeypress' => 'return (function(evt)
        {
           var charCode = (evt.which) ? evt.which : event.keyCode
           if (charCode > 31 && (charCode < 48 || charCode > 57))
             // return false;

           return true;
        })(event)',
        'value' => $post ? $post->post_chronological_mission_post_day : '1'
      );
  }
  
});

$this->event->listen(['location', 'view', 'output', 'admin', 'write_missionpost'], function($event){
  switch($this->uri->segment(4)){
    case 'view':
      break;
    default:
     
    $this->config->load('extensions');
    $extensionsConfig = $this->config->item('extensions');
    $dateFormatFile=  isset($extensionsConfig['nova_ext_ordered_mission_posts']['format'])?$extensionsConfig['nova_ext_ordered_mission_posts']['format']:'day_time';
                $event['output'] .= $this->extension['jquery']['generator']
                      ->select('#timeline')->closest('p')
                      ->after(
                        $this->extension['nova_ext_ordered_mission_posts']
                             ->view($dateFormatFile, $this->skin, 'admin', $event['data'])
                      );
      
     

      $event['output'] .= $this->extension['jquery']['generator']
                               ->select('#timeline')->closest('p')->remove();
 }
                  
});
