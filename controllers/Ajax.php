<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once MODPATH.'core/libraries/Nova_controller_main.php';

class __extensions__nova_ext_ordered_mission_posts__Ajax extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

        // load the resources
       
        $this->load->library('session');
       

        
        // check to see if they are logged in
        Auth::is_logged_in();
        
        // set and load the language file needed
        $this->lang->load('app', $this->session->userdata('language'));
        
        // set the template file
        Template::$file = '_base/template_ajax';
        
        // set the module
        Template::$data['module'] = 'core';
        
        // set the default regions
        $this->_regions['content'] = false;
        $this->_regions['controls'] = false;
	}
  
  public function mission()
  {   



	// load the resources
	  $data['status']='NOK';
    $id= $this->input->get('mission', TRUE);
   if(!empty($id))
   {
    $query = $this->db->get_where('missions', array('mission_id' => $id));
    $post = ($query->num_rows() > 0) ? $query->row() : false;
    $data['status']='OK';
    $data['post']=$post;
   }
   echo json_encode($data);exit;
   
  }



   public function count_word()
    {
       
            
            
            $head = sprintf(
                lang('fbx_head'),
                'Word Count',
                ''
            );
            
            // data being sent to the facebox
            $data['header'] = $head;
             


            
            $data['id'] = $this->uri->segment(5, 0, true);
           
         $count=0;
          
             $query = $this->db->select('post_content');
            $query = $this->db->get_where('posts', array('post_mission' => $data['id'], 'post_status'=>'activated'));
            $models = ($query->num_rows() > 0) ? $query->result() : false;
            if(!empty($models))
            {  
                foreach ($models as $model)
                {
                    $count+= str_word_count($model->post_content);
                }
            }
        
            
            $data['text'] = sprintf(
                "Total Word Count is : $count",
                '',                 
                ''
            );
            
           
            
    
            
           
            

            $this->_regions['content'] = Location::ajax('/../extensions/nova_ext_ordered_mission_posts/views/admin/pages/_count', null, null, $data);
            
            
            Template::assign($this->_regions);
            
            Template::render();
        
    }
}
