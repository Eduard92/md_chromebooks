<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Groups module
 *
 * @author PyroCMS Dev Team
 * @package PyroCMS\Core\Modules\Groups
 */
 class Module_Chromebooks extends Module
{

	public $version = '1.0';

	public function info()
	{
		$info= array(
			'name' => array(
				'en' => 'Chromebooks',
				
				'es' => 'Chromebooks',
				
			),
			'description' => array(
				'en' => '.',
				
				'es' => 'Administración de Chromebooks',
				
			),
			'frontend' => false,
			'backend' => true,
			'menu' => 'admin',
            'roles' => array(
				'create', 'admin_chrome','admin_asignaciones'),
            'sections'=>array(
                'chromebooks'=>array(
                    'name'=>'chromebook:title',
                    'uri' => 'admin/chromebooks',
        			'shortcuts' => array(
        				/*array(
        					'name' => 'email:upload',
        					'uri' =>  'admin/emails/upload',
        					'class' => 'btn btn-default',
                            'open-modal' => 'true',
                            'modal-title' => 'Subir archivo scv'
        				),
                        array(
        					'name' => 'email:download',
        					'uri' => 'admin/emails/download',
        					'class' => 'btn btn-default',
                            'open-modal' => 'true',
                            'modal-title' => 'Descargar datos de Google'
        				),*/
        			)
                ),
              'Asignaciones'=>array(
                    'name'=>'chromebook:asinaciones_title',
                    'uri' => 'admin/chromebooks/asignaciones',
        			
                )
           )
		);
        
        
        
        return $info;
	}

	public function install()
	{/*
	    $this->dbforge->drop_table('emails');
        $this->dbforge->drop_table('chromebooks');
		$tables = array(
		    'emails'=>array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true,),
				'email' => array('type' => 'VARCHAR', 'constraint' => 100,),
                'org_path' => array('type' => 'VARCHAR', 'constraint' => 200,'null'=>true),
                'group' => array('type' => 'VARCHAR', 'constraint' => 200,'null'=>true),
                'table' => array('type' => 'VARCHAR', 'constraint' => 200,'null'=>true),
                'table_id' => array('type' => 'VARCHAR', 'constraint' => 200,'null'=>true),
                
                'given_name' => array('type' => 'VARCHAR', 'constraint' => 250,'null'=>true),
                'family_name' => array('type' => 'VARCHAR', 'constraint' => 250,'null'=>true),
                'full_name' => array('type' => 'VARCHAR', 'constraint' => 250,'null'=>true),
				'created_on' => array('type' => 'INT', 'constraint' => 11, 'null' => true,),
                'updated_on' => array('type' => 'INT', 'constraint' => 11, 'null' => true,),
              
                
                'data' => array('type' => 'TEXT','null'=>true),
				//'active' => array('type' => 'ENUM',  'constraint' => array('aviso'=>'aviso','memorandun'=>'memorandun'),),
				'active' => array('type' => 'INT', 'constraint' => 11, 'null' => true,'default'=>'0'),
                'syncronize' => array('type' => 'INT', 'constraint' => 11, 'null' => true,'default'=>'0'),
            ),
            'chromebooks'=>array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true,),
				'email' => array('type' => 'VARCHAR', 'constraint' => 100,),
                'serial' => array('type' => 'VARCHAR', 'constraint' => 200,'null'=>true),
                'created_on' => array('type' => 'INT', 'constraint' => 11, 'null' => true,),
            )
			
		);

        if ( ! $this->install_tables($tables))
		{
			return false;
		}*/
        return true;
        
		

		
	}

	public function uninstall()
	{
	  
       // $this->dbforge->drop_table('emails');
		return true;
	}

	public function upgrade($old_version)
	{
		return true;
	}

}
?>