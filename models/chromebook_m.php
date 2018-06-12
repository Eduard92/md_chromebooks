<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * create sliders with swipe.js
 *
 * @author 		James Doyle (james2doyle)
 * @website		http://ohdoylerules.com/
 * @package 	PyroCMS
 * @subpackage 	Sliders
 * @copyright 	MIT
 */
class Chromebook_m extends MY_Model {


	public function __construct()
	{
		parent::__construct();
		$this->_table = 'default_chromebooks';
		
	}

	    function edit($input,$id)
    {
        $data = array(
        
            'email'            => $input['email'],

        );
        
        return$this->db->where('id',$id)
        		   ->set($data)
                   ->update($this->_table);
        
    }

		
 }
 ?>