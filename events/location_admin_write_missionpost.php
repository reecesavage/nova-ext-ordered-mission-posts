<?php
 

$this->event->listen(['location', 'view', 'data', 'admin', 'write_missionpost'], function($event){



  $id = (is_numeric($this->uri->segment(3))) ? $this->uri->segment(3) : false;
  $post = $id ? $this->posts->get_post($id) : null;
  


  $timepickerOptions = [
    'timeFormat' => 'HHmm',
    'defaultTime' =>  $post ? $post->nova_ext_ordered_post_time : '0000'
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

  $viewPrefixLabel = isset($json['nova_ext_ordered_mission_posts']['label_view_prefix'])
                        ? $json['nova_ext_ordered_mission_posts']['label_view_prefix']['value']
                        : 'Mission Day';

  $viewConcatLabel = isset($json['nova_ext_ordered_mission_posts']['label_view_concat'])
                        ? $json['nova_ext_ordered_mission_posts']['label_view_concat']['value']
                        : 'at';

  $viewSuffixLabel = isset($json['nova_ext_ordered_mission_posts']['label_view_suffix'])
                        ? $json['nova_ext_ordered_mission_posts']['label_view_suffix']['value']
                        : '';
  
  switch($this->uri->segment(4)){
   


  case 'view':

   if(!empty($post->post_mission))
   {
   $query = $this->db->get_where('missions', array('mission_id' => $post->post_mission));


   $model = ($query->num_rows() > 0) ? $query->row() : false;
   if(!empty($model))
   {
      if(!empty($model))
      {
          if($model->mission_ext_ordered_config_setting=='day_time')
          {  
            if($model->mission_ext_ordered_legacy_mode==1){
                 $column='post_chronological_mission_post_day';
                 $columnTime='post_chronological_mission_post_time';

            }else {
              $column='nova_ext_ordered_post_day';
               $columnTime='nova_ext_ordered_post_time';
            } 
           
           $viewPrefixLabel=$editDayLabel;
            $flag=true;
          
          }
          else if($model->mission_ext_ordered_config_setting=='date_time')
          {
              $column='nova_ext_ordered_post_date';
               $columnTime='nova_ext_ordered_post_time';
              $viewPrefixLabel=$editDateLabel;
               $flag=true;
            } 
            else if($model->mission_ext_ordered_config_setting=='stardate')
            {
             $column='nova_ext_ordered_post_stardate';
              $columnTime='nova_ext_ordered_post_time';
             $viewPrefixLabel=$editStartDateLabel;
             $flag=true;
            }else {
              $flag=false;
            }
              if($flag==true)
              {
              $event['data']['inputs']['timeline']['value'] = $viewPrefixLabel.' '.$post->$column.' '.$viewConcatLabel.' '.$post->$columnTime.' '.$viewSuffixLabel;
             }

             
            
      }

   }
   }  
    


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
  }
  
});
$this->event->listen(['location', 'view', 'output', 'admin', 'write_missionpost'], function($event){
  switch($this->uri->segment(4)){
    case 'view':
      break;
    default:
     
    $this->config->load('extensions');
              
             $event['output'] .= $this->extension['nova_ext_ordered_mission_posts']->inline_css('manage', 'admin', $event['data']);
                $event['output'] .= $this->extension['jquery']['generator']
                      ->select('#timeline')->closest('p')
                      ->before(
                        $this->extension['nova_ext_ordered_mission_posts']
                             ->view('form', $this->skin, 'admin', $event['data'])
                      );
      
 }
                  
});
