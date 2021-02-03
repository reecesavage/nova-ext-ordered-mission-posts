<?php 

$this->event->listen(['parser', 'parse_string', 'output', 'write', 'missionpost'], function($event){
     $this->config->load('extensions');
            $extensionsConfig = $this->config->item('extensions');
            
           $editDayLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['label_edit_day'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['label_edit_day']
                        : 'Mission Day';
            $editDateLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['label_edit_date'])? $extensionsConfig['nova_ext_ordered_mission_posts']['label_edit_date']
                        : 'Date';
           $editStartDateLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['label_edit_startdate'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['label_edit_startdate']
                        : 'Stardate';

            $viewConcatLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['label_view_concat'])
                                  ? $extensionsConfig['nova_ext_ordered_mission_posts']['label_view_concat']
                                  : 'at';

            $viewSuffixLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['label_view_suffix'])
                                  ? $extensionsConfig['nova_ext_ordered_mission_posts']['label_view_suffix']
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