<?php 


$this->event->listen(['location', 'view', 'data', 'admin', 'manage_missions'], function($event){
       


  if(isset($event['data']['missions']['current']))
     {
        foreach($event['data']['missions']['current'] as $key =>$value)
        {
          $event['data']['missions']['current'][$key]['desc'] = $value['desc'] ."<td class='col_100 align_right'><button><a href='#' myaction='count' myid='$key' rel='count' class='image'><?=$countId;?>Word Count</button></td></a>";
        }
     }

     if(isset($event['data']['missions']['completed']))
     {
        foreach($event['data']['missions']['completed'] as $key =>$value)
        {
          $event['data']['missions']['completed'][$key]['desc'] = $value['desc'] ."<td class='col_100 align_right'><button><a href='#' myaction='count' myid='$key' rel='facebox' class='image'><?=$countId;?>Word Count</button></td></a>";
        }
     }


     if(isset($event['data']['missions']['upcoming']))
     {
        foreach($event['data']['missions']['upcoming'] as $key =>$value)
        {
          $event['data']['missions']['upcoming'][$key]['desc'] = $value['desc'] ."<td class='col_100 align_right'><button><a href='#' myaction='count' myid='$key' rel='facebox' class='image'><?=$countId;?>Word Count</button></td></a>";
        }
     }

    
     
    
});
