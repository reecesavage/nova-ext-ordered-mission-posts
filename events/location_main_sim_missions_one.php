<?php 

$this->event->listen(['location', 'view', 'data', 'main', 'sim_missions_one'], function($event){
  
  $this->config->load('extensions');
  $extensionsConfig = $this->config->item('extensions');
    $dateFormatFile=  isset($extensionsConfig['nova_ext_ordered_mission_posts']['format'])?$extensionsConfig['nova_ext_ordered_mission_posts']['format']:'day_time';
    if($dateFormatFile=='day_time'){
       $viewPrefixLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['label_view_prefix'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['label_view_prefix']
                        : 'Mission Day';
       $column='nova_ext_ordered_post_day';
     }elseif ($dateFormatFile=='date_time'){
       $viewPrefixLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['label_view_prefix'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['label_view_prefix']
                        : 'Mission Date';
                         $column='nova_ext_ordered_post_date';
     }elseif($dateFormatFile=='startdate_time'){
        $viewPrefixLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['label_view_prefix'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['label_view_prefix']
                        : 'Mission Start Date';
                         $column='nova_ext_ordered_post_start_date';
     }
  $viewConcatLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['label_view_concat'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['label_view_concat']
                        : 'at';

  $viewSuffixLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['label_view_suffix'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['label_view_suffix']
                        : '';

  $postOrderColumnFallback = isset($extensionsConfig['nova_ext_ordered_mission_posts']['post_order_column_fallback'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['post_order_column_fallback']
                        : 'post_date';
  
  $event['data']['posts'] = [];

  $this->db->from('posts');
  $this->db->where('post_mission', $event['data']['mission']);
  $this->db->where('post_status', 'activated');
    $this->db->order_by($column, 'desc');
  $this->db->order_by('nova_ext_ordered_post_time', 'desc');
  $this->db->order_by($postOrderColumnFallback, 'desc');
  $this->db->limit(25, 0);
  $posts = $this->db->get();

  if ($posts->num_rows() > 0)
  {
    foreach ($posts->result() as $post)
    {     
        if(empty($post->post_timeline)){
            $timeline = $viewPrefixLabel.' '.$post->$column.' '.$viewConcatLabel.' '.$post->nova_ext_ordered_post_time.' '.$viewSuffixLabel;
        }else{
            $timeline = $post->post_timeline;
        }
        $event['data']['posts'][] = [
            'id' => $post->post_id,
            'title' => $post->post_title,
            'authors' => $this->char->get_authors($post->post_authors, true, true),
            'timeline' => $timeline,
            'location' => $post->post_location,
        ];
    }
  }
  
});



