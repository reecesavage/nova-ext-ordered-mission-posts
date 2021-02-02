<?php

$this->event->listen(['db', 'insert', 'prepare', 'posts'], function($event){

  if(($day = $this->input->post('nova_ext_ordered_post_day', true)) !== false)
    $event['data']['nova_ext_ordered_post_day'] = $day;
  if(($time = $this->input->post('nova_ext_ordered_post_time', true)) !== false)
    $event['data']['nova_ext_ordered_post_time'] = $time;
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
if(($startDate = $this->input->post('nova_ext_ordered_post_stardate', true)) !== false)
    $event['data']['nova_ext_ordered_post_stardate'] = $startDate;
if(($date = $this->input->post('nova_ext_ordered_post_date', true)) !== false)
    $event['data']['nova_ext_ordered_post_date'] = $date;
});



$this->event->listen(['db', 'insert', 'prepare', 'missions'], function($event){


  if(($config = $this->input->post('mission_ext_ordered_config_setting', true)) !== false)
    $event['data']['mission_ext_ordered_config_setting'] = $config;

    if(($postNumbering = $this->input->post('mission_ext_ordered_post_numbering', true)) !== false)
    $event['data']['mission_ext_ordered_post_numbering'] = $postNumbering;

});
$this->event->listen(['db', 'update', 'prepare', 'missions'], function($event){


  if(($config = $this->input->post('mission_ext_ordered_config_setting', true)) !== false)
    $event['data']['mission_ext_ordered_config_setting'] = $config;

    if(($postNumbering = $this->input->post('mission_ext_ordered_post_numbering', true)) !== false)
    $event['data']['mission_ext_ordered_post_numbering'] = $postNumbering;
});