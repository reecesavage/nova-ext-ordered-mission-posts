<?php 

$this->event->listen(['location', 'view', 'data', 'main', 'sim_missions_one'], function($event){
  

  $this->config->load('extensions'); 
  $extensionsConfig = $this->config->item('extensions');
       $editDayLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['label_view_prefix'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['label_view_prefix']
                        : 'Mission Day';
       $editDateLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['label_view_prefix'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['label_view_prefix']
                        : 'Date';
        $editStartDateLabel = isset($extensionsConfig['nova_ext_ordered_mission_posts']['label_view_prefix'])
                        ? $extensionsConfig['nova_ext_ordered_mission_posts']['label_view_prefix']
                        : 'Stardate';
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


   $query = $this->db->get_where('missions', array('mission_id' => $event['data']['mission']));
   $model = ($query->num_rows() > 0) ? $query->row() : false;
   if(!empty($model))
   {

      

      if($model->mission_ext_ordered_config_setting=='day_time'){

           $data['mission_day']='nova_ext_ordered_post_day';
            $viewPrefixLabel=$editDayLabel;
        
          }else if($model->mission_ext_ordered_config_setting=='date_time')
          {

            $data['mission_day']='nova_ext_ordered_post_date';

              $viewPrefixLabel=$editDateLabel;
              
          }else if($model->mission_ext_ordered_config_setting=='stardate')
          {
            $data['mission_day']='nova_ext_ordered_post_stardate';
            $viewPrefixLabel=$editStartDateLabel;
          }else {
           
           $data['mission_day']='';
            
          }





       $this->db->from('posts');
  $this->db->where('post_mission', $event['data']['mission']);
  $this->db->where('post_status', 'activated');
  $this->db->order_by('nova_ext_ordered_post_time', 'desc');
  $this->db->order_by($postOrderColumnFallback, 'desc');
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
               $timeline = $viewPrefixLabel.' '.$post->$column.' '.$viewConcatLabel.' '.$post->nova_ext_ordered_post_time.' '.$viewSuffixLabel;
           }else {
            if(empty($post->post_timeline)){

              $viewPrefixLabel=$editDayLabel;
            $timeline = $viewPrefixLabel.' '.$post->post_chronological_mission_post_day.' '.$viewConcatLabel.' '.$post->post_chronological_mission_post_time.' '.$viewSuffixLabel;
            }else{
            $timeline = $post->post_timeline;
            }
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