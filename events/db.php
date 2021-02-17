<?php

$this->event->listen(['db', 'insert', 'prepare', 'posts'], function($event){
  

  $missionId=$event['data']['post_mission'];

   $query = $this->db->get_where('missions', array('mission_id' => $missionId));
   $model = ($query->num_rows() > 0) ? $query->row() : false;
   if(!empty($model) && $model->mission_ext_ordered_legacy_mode==1)
   {
      if(($day = $this->input->post('post_chronological_mission_post_day', true)) !== false)
    $event['data']['post_chronological_mission_post_day'] = $day;
  if(($time = $this->input->post('post_chronological_mission_post_time', true)) !== false)
    $event['data']['post_chronological_mission_post_time'] = $time;

   }else {
     if(($day = $this->input->post('nova_ext_ordered_post_day', true)) !== false)
    $event['data']['nova_ext_ordered_post_day'] = $day;
  if(($time = $this->input->post('nova_ext_ordered_post_time', true)) !== false)
    $event['data']['nova_ext_ordered_post_time'] = $time;
   }

  

if(($startDate = $this->input->post('nova_ext_ordered_post_stardate', true)) !== false)
    $event['data']['nova_ext_ordered_post_stardate'] = $startDate;
if(($date = $this->input->post('nova_ext_ordered_post_date', true)) !== false)
    $event['data']['nova_ext_ordered_post_date'] = $date;
});
$this->event->listen(['db', 'update', 'prepare', 'posts'], function($event){


  
     if(($day = $this->input->post('nova_ext_ordered_post_day', true)) !== false)
    $event['data']['nova_ext_ordered_post_day'] = $day;
    if(($time = $this->input->post('nova_ext_ordered_post_time', true)) !== false)
    $event['data']['nova_ext_ordered_post_time'] = $time;
   
     if(($day = $this->input->post('post_chronological_mission_post_day', true)) !== false)
    $event['data']['post_chronological_mission_post_day'] = $day;
    if(($time = $this->input->post('post_chronological_mission_post_time', true)) !== false)
    $event['data']['post_chronological_mission_post_time'] = $time;


if(($startDate = $this->input->post('nova_ext_ordered_post_stardate', true)) !== false)
    $event['data']['nova_ext_ordered_post_stardate'] = $startDate;
if(($date = $this->input->post('nova_ext_ordered_post_date', true)) !== false)
    $event['data']['nova_ext_ordered_post_date'] = $date;
});

$this->event->listen(['db', 'insert', 'prepare', 'missions'], function($event){

    $event['data']['mission_ext_ordered_is_new_record'] = 1;

    
  if(($config = $this->input->post('mission_ext_ordered_config_setting', true)) !== false)
    $event['data']['mission_ext_ordered_config_setting'] = $config;
    if(($postNumbering = $this->input->post('mission_ext_ordered_post_numbering', true)) !== false)
    $event['data']['mission_ext_ordered_post_numbering'] =  $postNumbering;

      if(($legacyMode = $this->input->post('mission_ext_ordered_legacy_mode', true)) !== false)
    $event['data']['mission_ext_ordered_legacy_mode'] = $legacyMode;

  if(($defaultMissionDate = $this->input->post('mission_ext_ordered_default_mission_date', true)) !== false)
    $event['data']['mission_ext_ordered_default_mission_date'] = $defaultMissionDate;

  if(($defaultStardate = $this->input->post('mission_ext_ordered_default_stardate', true)) !== false)
    $event['data']['mission_ext_ordered_default_stardate'] = $defaultStardate;

});
$this->event->listen(['db', 'update', 'prepare', 'missions'], function($event){
  if(($config = $this->input->post('mission_ext_ordered_config_setting', true)) !== false)
    $event['data']['mission_ext_ordered_config_setting'] = $config;



    if(($postNumbering = $this->input->post('mission_ext_ordered_post_numbering', true)) !== false)
    {
      $event['data']['mission_ext_ordered_post_numbering'] = $postNumbering;
    }else {
       $event['data']['mission_ext_ordered_post_numbering'] = 0;
    }


     if(($legacyMode = $this->input->post('mission_ext_ordered_legacy_mode', true)) !== false)
    {
      $event['data']['mission_ext_ordered_legacy_mode'] = $legacyMode;
    }else {
       $event['data']['mission_ext_ordered_legacy_mode'] = 0;
    }

    if(($defaultMissionDate = $this->input->post('mission_ext_ordered_default_mission_date', true)) !== false)
    $event['data']['mission_ext_ordered_default_mission_date'] = $defaultMissionDate;

  if(($defaultStardate = $this->input->post('mission_ext_ordered_default_stardate', true)) !== false)
    $event['data']['mission_ext_ordered_default_stardate'] = $defaultStardate;


   
});