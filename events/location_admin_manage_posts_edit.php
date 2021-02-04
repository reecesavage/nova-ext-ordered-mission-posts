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
    
  if(!empty($extensionsConfig['nova_ext_ordered_mission_posts']['timepicker_options'])){
    foreach($extensionsConfig['nova_ext_ordered_mission_posts']['timepicker_options'] as $key => $value){
      $timepickerOptions[$key] = $value;
    }
  }
  
  $editDayLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['label_edit_day'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['label_edit_day']
                        : 'Mission Day';

  $editDateLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['label_edit_date'])? $extensionsConfig['nova_ext_ordered_mission_posts']['label_edit_date']
                        : 'Date';


  $editStartDateLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['label_edit_startdate'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['label_edit_startdate']
                        : 'Stardate';

  $editTimeLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['label_edit_time'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['label_edit_time']
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
        'value' => $post ? $post->nova_ext_ordered_post_date : '1'
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



  $event['output'] .= $this->extension['jquery']['generator']
                  ->select('[name="post_timeline"]')->closest('p')
                  ->before(
                    $this->extension['nova_ext_ordered_mission_posts']
                         ->view('form', $this->skin, 'admin', $event['data'])
                  );

});
