
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin extends Admin_Controller {
  protected $section='chromebooks';
  public function __construct()
  {
    parent::__construct();
        
      //  $this->load->library('GService');
        $this->load->model(array('chromebook_m','chromebook_asignacion_m','files/file_folders_m'));
        $this->lang->load('chromebook');
        $this->load->library(array('files/files'));

      //  $this->config->load('files/files');
      //  $this->_path = FCPATH.rtrim($this->config->item('files:path'), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
         $this->validation_rules = array(
             array(
                'field' => 'full_name',
                'label' => 'Nombre',
                'rules' => 'trim'
            ),
             array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim'
            ),
             array(
                'field' => 'org_path',
                'label' => 'Org',
                'rules' => 'trim'
            ),
             array(
                'field' => 'serial',
                'label' => 'Serial',
                'rules' => 'trim'
            ),
             array(
                'field' => 'cargo',
                'label' => 'Cargo',
                'rules' => 'trim'
            ),
             array(
                'field' => 'observaciones',
                'label' => 'Observaciones',
                'rules' => 'trim'
            ),
            array(
                'field' => 'responsable',
                'label' => 'Responsable',
                'rules' => 'trim'
            ),
        );
    }
        function index()
    {   
        if($this->input->get('f_status'))
        {
        $status   = $this->input->get('f_status');
        }else
        {

        $status = $this->input->get('tab')?$this->input->get('tab'):'libres';
        }

      
        $f_keywords = $this->input->get('f_keywords');
        if (empty($f_keywords)==false) 
        {
             $base_where['CONCAT(default_chromebooks.serial) LIKE "%'.$f_keywords.'%" '] = NULL;
        }
  
         switch($status)
         {
            case 'libres':

            $base_where['default_chromebooks.id NOT IN (
                    SELECT default_chromebook_asignacion.id_chromebook FROM `default_chromebook_asignacion` 
                    JOIN `default_chromebooks` ON    `default_chromebook_asignacion`.`id_chromebook` =    `default_chromebooks`.`id` 
                    JOIN `default_emails`      ON    `default_chromebook_asignacion`.`email`      =    `default_emails`.`email`
                    WHERE default_chromebook_asignacion.asignado is not null AND default_chromebook_asignacion.removido is null)'] = NULL;

                                        

           $total_rows = $this->db->where($base_where)
                                ->get('chromebooks')->num_rows();

             $pagination = create_pagination('admin/chromebooks/index/', $total_rows,20);

                  $chromebooks = $this->chromebook_m
                                ->where($base_where)
                                ->limit($pagination['limit'],$pagination['offset'])
                                ->get_all();



            break;
            case 'asignados':
             $base_where['chromebook_asignacion.asignado is not null AND default_chromebook_asignacion.removido is null'] = NULL;

             $total_rows = $this->db
                                ->where($base_where)
                                ->join('chromebooks','chromebook_asignacion.id_chromebook=chromebooks.id')
                                ->join('emails','chromebook_asignacion.email=emails.email')
                                ->get('chromebook_asignacion')->num_rows();

             $pagination = create_pagination('admin/chromebooks/index/', $total_rows,NULL);

             $chromebooks= $this->chromebook_asignacion_m
                                ->where($base_where)
                                ->join('chromebooks','chromebook_asignacion.id_chromebook=chromebooks.id')
                                ->join('emails','chromebook_asignacion.email=emails.email')
                                ->limit($pagination['limit'],$pagination['offset'])
                                ->get_all();

            break;           
         }       
        
        // $total_rows = $this->chromebook_m->count_by($base_where);

       //  $pagination = create_pagination('admin/chromebooks/index/', $total_rows,NULL);
                   
        /* $chromebooks= $this->chromebook_m
            ->where($base_where)
            ->or_where($base_or_where)
            ->limit($pagination['limit'],$pagination['offset'])
            ->get_all();*/
        $this->template->title($this->module_details['name'])
                   ->set('chromebooks',$chromebooks)
                   ->set('status',$status)
                   ->set('pagination',$pagination)
                   ->set('f_keywords',$f_keywords)
                   ->append_js('module::chromebook.controller.js')
                   ->build('admin/index');
        }
    public function history($id=0)
    {
        $chromebook = $this->chromebook_asignacion_m->get($id) ;
        if(!$chromebook)
        {
            
            $this->session->set_flashdata('error',lang('global:not_found_edit'));
            
            redirect('admin/chromebooks');
        }



           $base_where['default_chromebook_asignacion.asignado is not null'] = NULL;

            $chromebook= $this->db->select('*')
                               ->where($base_where)
                               ->where('id_chromebook',$id)
                                ->join('chromebooks','chromebook_asignacion.id_chromebook=chromebooks.id')
                                ->join('emails','chromebook_asignacion.email=emails.email')
                                ->get('chromebook_asignacion')->result();
      $this->template->title($this->module_details['name'])
                ->set('chromebook',$chromebook)
                ->build('admin/form_historia'); 
    }
    public function asignar($id=0)
    {
      $chromebook = $this->chromebook_m->get($id) ;
        if(!$chromebook)
        {
            
            $this->session->set_flashdata('error',lang('global:not_found_edit'));
            
            redirect('admin/chromebooks');
        }
        $base_where['chromebook_asignacion.asignado is not null AND default_chromebook_asignacion.removido is null'] = NULL;

        
$asignado= $this->db->select('*')
                                ->where($base_where)
                                ->where('id_chromebook',$id)
                                ->join('chromebooks','chromebook_asignacion.id_chromebook=chromebooks.id')
                                ->join('emails','chromebook_asignacion.email=emails.email')
                                ->get('chromebook_asignacion')->row();
       if(empty($asignado->id)== false)
        {
                $this->session->set_flashdata('error',sprintf(lang('chromebook:pre_asignado')));
                    redirect('admin/chromebooks/');
        }
        $chromebook = new stdClass();
        $this->form_validation->set_rules($this->validation_rules);
        
        if ($this->form_validation->run())
        {
             unset($_POST['btnAction']);
               
            if($this->chromebook_asignacion_m->create($this->input->post(),$id))
            {

               $this->session->set_flashdata('success',sprintf(lang('chromebook:asignado')));
                                 redirect('admin/chromebooks/');
                
            }
            else
            {
                $this->session->set_flashdata('error',lang('global:save_error'));
                
            }
            redirect('admin/chromebooks');
        }

        $orgs = $this->db->select('count(id),org_path')
                        ->where('default_emails.email NOT IN (
                            SELECT default_chromebook_asignacion.email FROM `default_chromebook_asignacion` 
                            JOIN `default_chromebooks` ON    `default_chromebook_asignacion`.`id_chromebook` =    `default_chromebooks`.`id` 
                            JOIN `default_emails`      ON    `default_chromebook_asignacion`.`email`      =    `default_emails`.`email`
                            WHERE default_chromebook_asignacion.asignado is not null AND default_chromebook_asignacion.removido is null)',null)
                        ->group_by('org_path')
                        ->get('emails')
                        ->result();
                
        foreach ($this->validation_rules as $key => $field)
        {
                $chromebook->$field['field'] = set_value($field['field']);
        }
        
        $chromebook->serial = $this->db->select('serial')->where('default_chromebooks.id',$id)
                            ->get('chromebooks')->result_array();
        $chromebook->serial = $chromebook->serial[0]['serial'];  
       
         $this->template->title($this->module_details['name'])
                ->set('orgs',array_for_select($orgs,'org_path','org_path'))
                ->append_js('module::chromebook.controller.js')
                ->append_metadata('<script type="text/javascript">var emails ;</script>')
          //      ->append_metadata('<script type="text/javascript"> emails=\''.$configuracion->autocomplete_display.'\', text_empty=\''.lang('registros:empty_'.($configuracion->forced==1?'forced':'free')).'\', data ='.json_encode($data_autocomplete).'; </script>')

                ->set('chromebook',$chromebook)
                ->build('admin/form'); 
    }
    public function remover($id=0)
    {
      $chromebook = $this->chromebook_asignacion_m->get($id) ;
        if(!$chromebook)
        {
            
            $this->session->set_flashdata('error',lang('global:not_found_edit'));
            
            redirect('admin/chromebooks');
        }
        
        $base_where['chromebook_asignacion.asignado is not null AND default_chromebook_asignacion.removido is null'] = NULL;
        $chromebook= $this->db->select('*')
                                ->where($base_where)
                                ->where('id_chromebook',$id)
                                ->join('chromebooks','chromebook_asignacion.id_chromebook=chromebooks.id')
                                ->join('emails','chromebook_asignacion.email=emails.email')
                                ->get('chromebook_asignacion')->row() ;

        $this->form_validation->set_rules($this->validation_rules);
        
        if ($this->form_validation->run())
        {
             unset($_POST['btnAction']);
           
            if($this->chromebook_asignacion_m->edit($this->input->post(),$id))
            {
                               
                     $this->session->set_flashdata('info',sprintf(lang('chromebook:removido')));
                                 redirect('admin/chromebooks/?tab=asignados');
                                                    
            }
            else
            {
             $this->session->set_flashdata('error',lang('global:save_error'));
            redirect('admin/chromebooks/remover/'.$id);
          }
        }

         $this->template->title($this->module_details['name'])
                ->set('chromebook',$chromebook)
                ->build('admin/form'); 
    }
        public function details($id)
    {

        $orgs = $this->db->select('count(id), org_path')
        ->where('chromebook is null',null)->or_where('chromebook',0)->group_by('org_path')->get('emails')->result();
        $chromebook=$this->db->select('*, default_emails.id As id_email')->where('default_chromebooks.id',$id)
                            ->join('chromebooks','emails.email = chromebooks.email')
                            ->join('default_chromebook_historial','emails.email = chromebook_historial.email_log')
                            ->get('emails')->row() or redirect('admin/chromebooks');


         $this->template->title($this->module_details['name'])
                ->set('chromebook',$chromebook)
                ->set('orgs',array_for_select($orgs,'org_path','org_path'))
                ->build('admin/form'); 
    }
        function get_emails()
    {
        
        $org_path = $this->input->post('org_path');
        
        $result = $this->db->select('*')
                        ->order_by('emails.full_name','ASC')
                        ->where('default_emails.email NOT IN (
                            SELECT default_chromebook_asignacion.email FROM `default_chromebook_asignacion` 
                            JOIN `default_chromebooks` ON    `default_chromebook_asignacion`.`id_chromebook` =    `default_chromebooks`.`id` 
                            JOIN `default_emails`      ON    `default_chromebook_asignacion`.`email`      =    `default_emails`.`email`
                            WHERE default_chromebook_asignacion.asignado is not null AND default_chromebook_asignacion.removido is null)',null)
                        ->where('default_emails.org_path',$org_path)
                        ->get('emails')
                        ->result();
                
        if($result)echo json_encode($result);
    }
    /*
    public function inicializar_asignacion()
    {
        $email_= $this->db->select('*, chromebooks.id AS ID_CHROME,emails.id AS ID_EMAIL')
                          ->join('chromebooks','emails.email=chromebooks.email')
                          ->get('emails')->result();
                print_r($email_);
        foreach ($email_ as $y) {
             $data = array(
            'chromebook' => 1,
            //'id_email'           => $y->ID_EMAIL,
                );
            $this->db->where('id',$y->ID_EMAIL);
           $this->db->update('default_emails',$data);
        }
    }
*/
    public function inicializar()
    {
        $base_where['email IS NOT NULL'] = null;
        $chromebooks= $this->chromebook_m
            ->where($base_where)
            ->get_all();

       foreach ($chromebooks as $chromebook) 
        {
           $data = array(
            'id_chromebook' => $chromebook->id,
            'email'         => $chromebook->email,
            'asignado'          => date('Y-m-d H:i:s', now()),
           );
           $this->db->insert('default_chromebook_asignacion',$data);
        }
    }


    public function acuse($id=0)
    {
      $chromebook = $this->chromebook_asignacion_m->get($id) ;
        if(!$chromebook)
        {
            
            $this->session->set_flashdata('error',lang('global:not_found_edit'));
            
            redirect('admin/chromebooks');
        }
        
        $base_where['chromebook_asignacion.asignado is not null AND default_chromebook_asignacion.removido is null'] = NULL;
        $chromebook= $this->db->select('*')
                                ->where($base_where)
                                ->where('id_chromebook',$id)
                                ->join('chromebooks','chromebook_asignacion.id_chromebook=chromebooks.id')
                                ->join('emails','chromebook_asignacion.email=emails.email')
                                ->get('chromebook_asignacion')->row() ;

        /*$alumno = $this->select('*')
                       ->where('idalum',$chromebook->id)*/

        ini_set('max_execution_time', 300);

        $this->load->library(array('pdf'));
        
        $html2pdf = new HTML2PDF('P', 'A4', 'es');
        

        ob_clean();
       
        $output = ''; 

        $doc = 'comodato_alumno';


        $output=$this->template->set_layout(false)
          //                   ->title('Reporte ')
                               ->enable_parser(true)
            ->build('templates/'.$doc,
              array('serial'=>$chromebook->serial,
                    'responsable'=>$chromebook->responsable,
                    'plantel'=>substr($chromebook->org_path,9),
                    'email'=>$chromebook->email,
                    'alumno'=>$chromebook->full_name),true);
           
        $html2pdf->writeHTML($output);
        $html2pdf->Output($doc.'_'.now().'.pdf','D');
     
    }

      
    
    
 }