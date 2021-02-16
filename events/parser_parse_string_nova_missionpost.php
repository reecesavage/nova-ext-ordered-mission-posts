<?php 

$this->event->listen(['parser', 'parse_string', 'output', 'write', 'missionpost'], function($event){
     $this->config->load('extensions');
            $extensionsConfig = $this->config->item('extensions');

             $extConfigFilePath = dirname(__FILE__).'/../config.json';
         
        if ( file_exists( $extConfigFilePath ) ) { 
            $file = file_get_contents( $extConfigFilePath );
            $json = json_decode( $file, true );
    }
            
           $editDayLabel = isset($json['nova_ext_ordered_mission_posts']['label_edit_day'])
                        ? $json['nova_ext_ordered_mission_posts']['label_edit_day']['value']
                        : 'Mission Day';
            $editDateLabel = isset($json['nova_ext_ordered_mission_posts']['label_edit_date'])? $json['nova_ext_ordered_mission_posts']['label_edit_date']['value']
                        : 'Date';
           $editStartDateLabel = isset($json['nova_ext_ordered_mission_posts']['label_edit_startdate'])
                        ? $json['nova_ext_ordered_mission_posts']['label_edit_startdate']['value']
                        : 'Stardate';

            $viewConcatLabel = isset($json['nova_ext_ordered_mission_posts']['label_view_concat'])
                                  ? $json['nova_ext_ordered_mission_posts']['label_view_concat']['value']
                                  : 'at';

            $viewSuffixLabel = isset($json['nova_ext_ordered_mission_posts']['label_view_suffix'])
                                  ? $json['nova_ext_ordered_mission_posts']['label_view_suffix']['value']
                                  : '';


     $id=$this->input->post('mission');
   if(!empty($id))
   {
   $query = $this->db->get_where('missions', array('mission_id' => $id));


   $model = ($query->num_rows() > 0) ? $query->row() : false;
   if(!empty($model))
   {
      if(!empty($model))
      {
          if($model->mission_ext_ordered_config_setting=='day_time')
          {
           $column='nova_ext_ordered_post_day';
           $viewPrefixLabel=$editDayLabel;
            $flag=true;
          
          }
          else if($model->mission_ext_ordered_config_setting=='date_time')
          {
              $column='nova_ext_ordered_post_date';
              $viewPrefixLabel=$editDateLabel;
               $flag=true;
            } 
            else if($model->mission_ext_ordered_config_setting=='stardate')
            {
             $column='nova_ext_ordered_post_stardate';
             $viewPrefixLabel=$editStartDateLabel;
             $flag=true;
            }else {
              $flag=false;
            }
              if($flag==true)
              {
               $timelineValue = $viewPrefixLabel.' '.$this->input->post($column).' '.$viewConcatLabel.' '.$this->input->post('nova_ext_ordered_post_time').' '.$viewSuffixLabel;
                $event['output'] = preg_replace(
                '/'.preg_quote(lang('email_content_post_timeline')).'.*\<br \/\>/', 
                lang('email_content_post_timeline').' '.$timelineValue.'<br />', 
                $event['output'], 
                1
            );
              }

             
            
      }

   }
   }  




});