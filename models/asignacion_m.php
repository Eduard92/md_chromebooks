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
class Asignacion_m extends MY_Model {


	public function __construct()
	{
		parent::__construct();
		$this->_table = 'default_chromebook_asignacion';
		
	}
    function create($input,$id)
    {

        $data = array(
            'id_chromebook'      => $id,
            'asignado'           => date('Y-m-d H:i:s', now()),
            'id_email'              => $input['alumno'],
            'obs_asig'           => $input['observaciones']?$input['observaciones']:NULL,
            'responsable'        => $input['responsable']
            
        );
        
        
        return $this->insert($data);
        
    }
        function edit($input,$id)
    {

        $data = array(
                
            'removido'           => date('Y-m-d H:i:s', now()),
            'obs_remov'          => $input['observaciones']?$input['observaciones']:NULL,
                    
        );
        
        
 		return $this->db->where('id_chromebook',$id)
                ->set($data)
                ->update($this->_table);
        
    }


		
 }
 ?>