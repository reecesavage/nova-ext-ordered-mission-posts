<?php 

$this->event->listen(['location', 'view', 'data', 'main', 'sim_missions_one'], function($event){
  
  $this->config->load('extensions'); 
  $extensionsConfig = $this->config->item('extensions');

       $extConfigFilePath = dirname(__FILE__).'/../config.json';
         
        if ( file_exists( $extConfigFilePath ) ) { 
            $file = file_get_contents( $extConfigFilePath );
            $json = json_decode( $file, true );
    }
    
       $editDayLabel = isset($json['nova_ext_ordered_mission_posts']['label_view_prefix'])
                        ? $json['nova_ext_ordered_mission_posts']['label_view_prefix']
                        : 'Mission Day';
       $editDateLabel = isset($json['nova_ext_ordered_mission_posts']['label_view_prefix'])
                        ? $json['nova_ext_ordered_mission_posts']['label_view_prefix']
                        : 'Date';
        $editStartDateLabel = isset($json['nova_ext_ordered_mission_posts']['label_view_prefix'])
                        ? $json['nova_ext_ordered_mission_posts']['label_view_prefix']
                        : 'Stardate';
  $viewConcatLabel = isset($json['nova_ext_ordered_mission_posts']['label_view_concat'])
                        ? $json['nova_ext_ordered_mission_posts']['label_view_concat']
                        : 'at';
  $viewSuffixLabel = isset($json['nova_ext_ordered_mission_posts']['label_view_suffix'])
                        ? $json['nova_ext_ordered_mission_posts']['label_view_suffix']
                        : '';
  $postOrderColumnFallback = isset($json['nova_ext_ordered_mission_posts']['post_order_column_fallback'])
                        ? $json['nova_ext_ordered_mission_posts']['post_order_column_fallback']
                        : 'post_date';
  
  $event['data']['posts'] = [];


   $query = $this->db->get_where('missions', array('mission_id' => $event['data']['mission']));
   $model = ($query->num_rows() > 0) ? $query->row() : false;
   if(!empty($model))
   {

      

      if($model->mission_ext_ordered_config_setting=='day_time'){
              

              if($model->mission_ext_ordered_legacy_mode==1){
                 $data['mission_day']='post_chronological_mission_post_day';
                 $data['mission_time']='post_chronological_mission_post_time';
              }else {
                $data['mission_day']='nova_ext_ordered_post_day';
                $data['mission_time']='nova_ext_ordered_post_time';
              }
           
           $viewPrefixLabel=$editDayLabel;
        
          }else if($model->mission_ext_ordered_config_setting=='date_time')
          {
            $data['mission_day']='nova_ext_ordered_post_date';
            $data['mission_time']='nova_ext_ordered_post_time';
            $viewPrefixLabel=$editDateLabel;
              
          }else if($model->mission_ext_ordered_config_setting=='stardate')
          {
            $data['mission_day']='nova_ext_ordered_post_stardate';
            $data['mission_time']='nova_ext_ordered_post_time';
            $viewPrefixLabel=$editStartDateLabel;
          }else {
           
           $data['mission_day']='';
            
          }

$this->db->from('posts');
  $this->db->where('post_mission', $event['data']['mission']);
  $this->db->where('post_status', 'activated');
  if($model->mission_ext_ordered_post_numbering==1)
  {
       $this->db->order_by($postOrderColumnFallback, 'asc');
  }else if(!empty($data['mission_day'])) {

    $column= $data['mission_day'];
     $timeColumn= $data['mission_time'];

     
     $this->db->order_by('cast('.$column.' as UNSIGNED)', 'desc');
     $this->db->order_by($timeColumn, 'desc');
     
      
  }
 
  $this->db->limit(25, 0);
  $posts = $this->db->get();

  if ($posts->num_rows() > 0)
  {
    foreach ($posts->result() as $key=> $post)
    {     
        $i=$key+1;
          if($model->mission_ext_ordered_post_numbering==1)
          {
             $title="Post $i - $post->post_title";
           }else {
            $title=$post->post_title;
           }
          
           if(!empty($data['mission_day']))
           {  $column= $data['mission_day'];
               $timeColumn= $data['mission_time'];
               $timeline = $viewPrefixLabel.' '.$post->$column.' '.$viewConcatLabel.' '.$post->$timeColumn.' '.$viewSuffixLabel;
           }else {
            $timeline = $post->post_timeline;
           }
        $event['data']['posts'][] = [
            'id' => $post->post_id,
            'title' => $title,
            'authors' => $this->char->get_authors($post->post_authors, true, true),
            'timeline' => $timeline,
            'location' => $post->post_location,
        ];
    }
  }
   }
});