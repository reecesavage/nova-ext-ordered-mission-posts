<?php

$this->event->listen(['db', 'insert', 'prepare', 'posts'], function($event){

  if(($day = $this->input->post('nova_ext_ordered_post_day', true)) !== false)
    $event['data']['nova_ext_ordered_post_day'] = $day;
  if(($time = $this->input->post('nova_ext_ordered_post_time', true)) !== false)
    $event['data']['nova_ext_ordered_post_time'] = $time;
if(($startDate = $this->input->post('nova_ext_ordered_post_start_date', true)) !== false)
    $event['data']['nova_ext_ordered_post_start_date'] = $startDate;
if(($date = $this->input->post('nova_ext_ordered_post_date', true)) !== false)
    $event['data']['nova_ext_ordered_post_date'] = $date;
  if(($config = $this->input->post('nova_ext_ordered_config_setting', true)) !== false)
    $event['data']['nova_ext_ordered_config_setting'] = $config;
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
  if(($config = $this->input->post('nova_ext_ordered_config_setting', true)) !== false)
    $event['data']['nova_ext_ordered_config_setting'] = $config;
});