
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin extends Admin_Controller {
  protected $section='chromebooks';
  public function __construct()
  {
    parent::__construct();
        
      //  $this->load->library('GService');
        $this->load->model(array('chromebook_m','asignacion_m','files/file_folders_m','emails/org_m'));
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
    function load($table='asignaciones',$page=1)
    {
        
         $result = array(
         
            'status' => false,
            'data'   => array()
         );
         
         $q            = $this->input->get('q');
         $base_where   = array();
         
         
         if($q)
         {
            $base_where['(id_chromebook LIKE \'%'.$q.'%\'  OR default_chromebook_asignacion.email LIKE \'%'.$q.'%\')'] = null;
         }
         
         $total_asignaciones = $this->asignacion_m->where($base_where)
                                ->join('emails','emails.email=chromebook_asignacion.email')
                                
                                ->count_by('removido IS NULL',null);
                                
         if($total_asignaciones)
         {
            $data = array(
                'pagination' => false,
                'rows'       => array()
            );
            
            $pagination = create_pagination('admin/chromebooks/'.$anio, $total_asignaciones,20,5);
         
           
           
            $asignaciones = $this->asignacion_m->where($base_where)
                                ->select('responsable,full_name,observaciones, org_path,chromebook_asignacion.id AS id,chromebook_asignacion.email,asignado,id_chromebook')
                                ->limit($pagination['limit'],$pagination['offset'])//->select('*,chromebook_asignacion.id AS id')
                                ->join('emails','emails.email=chromebook_asignacion.email')
                                ->where('removido IS NULL',null)->get_all();
                                
            $result['status']   = true;
            $data['rows']       = $asignaciones;
            $data['pagination'] = $pagination;
                                
            $result['data'] = $data;
         }
         
         return $this->template->build_json($result);
    }
    function index()
    {   
         $resume = array(
            'chromebooks' => array(),
            'asignaciones'    => array()
         
         );
         
         $orgs         = $this->org_m->get_all();
         $q            = $this->input->get('q');
         $base_where   = array();
         
         if($q)
         {
            $base_where['id_chromebook'] = $q;
         }
         
         $chromebooks  = $this->chromebook_m
                                ->where('id NOT IN(SELECT id_chromebook FROM default_chromebook_asignacion WHERE removido IS NULL)',null)
                                ->get_all();
        
         $total_asignaciones = $this->asignacion_m->where($base_where)
                                ->join('emails','emails.email=chromebook_asignacion.email')
                                
                                ->count_by('removido IS NULL',null);
                                
          
         $pagination = create_pagination('admin/chromebooks/', $total_asignaciones,20);
         
         
         
         $asignaciones = $this->asignacion_m->where($base_where)
                                ->select('full_name,observaciones, org_path,chromebook_asignacion.id AS id,chromebook_asignacion.email,asignado,id_chromebook')
                                ->limit($pagination['limit'],$pagination['offset'])//->select('*,chromebook_asignacion.id AS id')
                                ->join('emails','emails.email=chromebook_asignacion.email')
                                ->where('removido IS NULL',null)->get_all();
                                
                                
         if($this->input->is_ajax_request())
         {
               
            
                return $this->template->build_json($asignaciones);
           
         }
         
        
         foreach($chromebooks as $chromebook)
         {
             $resume['chromebooks'][] = $chromebook;
         }
         
         foreach($asignaciones as $chromebook)
         {
            $resume['asignaciones'][] = $chromebook;
         }
        /*$status = $this->input->get('tab')?$this->input->get('tab'):'libres';
        

      
        $q = $this->input->get('q');
        if (empty($f_keywords)==false) 
        {
             $base_where['CONCAT(default_chromebooks.serial) LIKE "%'.$f_keywords.'%" '] = NULL;
        }
  
         switch($status)
         {
            case 'libres':
               
                if($q)
                {
                    $base_where['(default_chromebooks.id  LIKE \'%'.$q.'%\')']=NULL;
                }
               
                $base_where['default_chromebooks.id NOT IN (
                    SELECT default_chromebook_asignacion.id_chromebook FROM `default_chromebook_asignacion` 
                     
                    
                    WHERE  default_chromebook_asignacion.removido is null)'] = NULL;

                                        

                $total_rows = $this->db->where($base_where)
                                ->count_all_results('chromebooks');

                $pagination = create_pagination('admin/chromebooks/index/', $total_rows,20);

                $chromebooks = $this->chromebook_m
                                ->where($base_where)
                                ->limit($pagination['limit'],$pagination['offset'])
                                ->get_all();

                

            break;
            case 'asignados':
             if($q)
             {
                    $base_where['(default_chromebook_asignacion.id_chromebook  LIKE \'%'.$q.'%\')']=NULL;
             }
             $base_where['default_chromebook_asignacion.removido is null'] = NULL;

             $total_rows = $this->db
                                ->where($base_where)
                                ->join('chromebooks','chromebook_asignacion.id_chromebook=chromebooks.id')
                                //->join('emails','chromebook_asignacion.email=emails.email')
                                ->count_all_results('chromebook_asignacion');

             $pagination = create_pagination('admin/chromebooks/index/', $total_rows,NULL);

             $chromebooks= $this->chromebook_asignacion_m->select('*,chromebook_asignacion.id AS id')
                                ->where($base_where)
                                ->join('chromebooks','chromebook_asignacion.id_chromebook=chromebooks.id')
                                //->join('emails','chromebook_asignacion.email=emails.email')
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
                   //->set('chromebooks',$chromebooks)
                   //->set('status',$status)
                   ->set('total_asignaciones',$total_asignaciones)
                   ->append_metadata('<script type="text/javascript"> var orgs='.json_encode($orgs).', resume='.json_encode($resume).';</script>')
                   ->set('resume',$resume)
                   ->append_js('module::chromebook.controller.js')
                   ->build('admin/index');
    }
    public function history($id=0)
    {
        $result = array(
            'status' => false,
            'data'   => array()
        );
        $historial = $this->asignacion_m->where('id_chromebook',$id)->get_all();
        
        if($historial)
        {
            $result['data']   = $historial;
            $result['status'] = true;
        }
        /*$asignacion = $this->chromebook_asignacion_m->get($id) ;
        if(!$asignacion)
        {
            
            $this->session->set_flashdata('error',lang('global:not_found_edit'));
            
            redirect('admin/chromebooks');
        }*/



           //$base_where['default_chromebook_asignacion.asignado is not null'] = NULL;

            /*$chromebook= $this->db->select('*')
                               ->where($base_where)
                               ->where('id_chromebook',$asignacion->id_chromebook)
                                ->join('chromebooks','chromebook_asignacion.id_chromebook=chromebooks.id')
                                ->join('emails','chromebook_asignacion.email=emails.email')
                                ->get('chromebook_asignacion')->result();
      $this->template->title($this->module_details['name'])
                ->set('chromebook',$chromebook)
                ->set('asignacion',$asignacion)
                ->build('admin/form_historia');*/ 
                
      return $this->template->build_json($result);
    }
    public function asignar($id=0)
    {
        $result = array(
            'status' => false,
            'message' => '',
            'data'    => false
        );
        
        $asignacion = $this->asignacion_m->get_by(array(
            'id_chromebook' => $id,
            'removido IS NULL'      =>NULL, 
        ));
        
        if($asignacion)
        {
            $result['message'] = lang('chromebook:pre_asignado'); 
        }
        
        else
        {
            $email = $this->input->post('email');
            $insert = array(
                'responsable' => $email['full_name'],
                'email'       => $email['email'],
                'id_chromebook' => $id,
                'asignado'      => date('Y-m-d H:i:s'),
                'observaciones' => $this->input->post('observaciones')
            );
            
            if($result_id = $this->asignacion_m->insert($insert))
            {
                $insert['id'] = $result_id ;
                $result['message'] = lang('chromebook:asignado'); 
                $result['data']   = $insert;
                $result['status']   = true;
            }
            else
            {
                $result['message'] = lang('chromebook:error'); 
            }
            
        }
        
        
        
        return $this->template->build_json($result);
    }
    public function remover($id=0)
    {
        $result = array(
            'status' => false,
            'message' => '',
            'data'    => false
        );
        $asignacion = $this->asignacion_m->where('removido IS NULL',null)->get_by('id_chromebook',$id) ;
        if(!$asignacion)
        {
            
            $result['message'] = lang('global:not_found_edit');
            
            
        }
        
        else
        {
            $this->asignacion_m->update($asignacion->id,array(
                'removido' => date('Y-m-d H:i:s'),
                'observaciones' => $this->input->post('observaciones')
            ));
            
             $result['message'] = lang('chromebook:removido');
            $result['status'] = true;
        }
        return $this->template->build_json($result);
        /*$base_where['chromebook_asignacion.asignado is not null AND default_chromebook_asignacion.removido is null'] = NULL;
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
                ->build('admin/form'); */
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
        
        /*$result = $this->db->select('*')
                        ->order_by('emails.full_name','ASC')
                        ->where('default_emails.email NOT IN (
                            SELECT default_chromebook_asignacion.email FROM `default_chromebook_asignacion` 
                            JOIN `default_chromebooks` ON    `default_chromebook_asignacion`.`id_chromebook` =    `default_chromebooks`.`id` 
                            JOIN `default_emails`      ON    `default_chromebook_asignacion`.`email`      =    `default_emails`.`email`
                            WHERE default_chromebook_asignacion.asignado is not null AND default_chromebook_asignacion.removido is null)',null)
                        ->where('default_emails.org_path',$org_path)
                        ->get('emails')
                        ->result();*/
                        
          $result = $this->db->where('org_path',$org_path)
                             ->order_by('email')
                            ->get('emails')
                           
                            ->result();
          return $this->template->build_json($result);      
        //if($result)echo json_encode($result);
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


    public function acuse($table='asignado',$id=0)
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


    public function report()
    {
        $estatus = $_GET["estatus"];
        $org =     $_GET["org"];

        print_r($estatus);
        print_r($org);
        if(is_numeric($estatus)&& $estatus==0)
        {
            
            $chromebooks  = $this->chromebook_m
                                ->where('id NOT IN(SELECT id_chromebook FROM default_chromebook_asignacion WHERE removido IS NULL)',null)
                                ->get_all();

                 $title = 'Relación de Chromebooks Disponibles';         
                 $table = '<tbody>';
                 $table_header = '<tr>';
               
                for ($i = 1; $i <= 9; $i++) 
                {
                 $table_header .='<th width="63"; align="center" style="border-bottom: #a6ce39 2px solid;padding: 3px; font-size: 10px;">Serial</th>';
                }
                 $table_header .= '</tr>';
                $c=0;
                foreach ($chromebooks as $chromebook)
                {        
                     if($c == 0)
                    {
                         $table .= '<tr>';                                
                    }    
                                
                    $table .='<td  width="63"; align="left" style="padding: 3px;vertical-align: middle;font-size: 10px; border-bottom: #7A7A7A 1px solid;">'.$chromebook->id.'</td>';
                    $c++;

                    if($c == 9)
                    {
                         $table .= '</tr>'; 
                         $c = 0;
                    }

                }
                 
                if($c == 0){
                    $table .='</tbody>';
                }
                else{
                    $table .='</tr></tbody>';
                }                 
          
        }
        elseif(is_numeric($estatus)&& $estatus==1)
        {   
            $base_where   = array();
            
         

            $chromebooks = $this->asignacion_m->where($base_where)
                                ->select('responsable,full_name,observaciones, org_path,chromebook_asignacion.id AS id,chromebook_asignacion.email,asignado,id_chromebook')
                                
                                ->join('emails','emails.email=chromebook_asignacion.email')
                                ->where('removido IS NULL',null)->where('org_path',$org)->get_all();

                    if(empty($chromebooks)==false){

                     $title = 'Relación de Chromebooks Asignadas a '.$org;

                     $table_header = '<tr>';

                     $table_header .='<th width="63"; align="center" style="border-bottom: #a6ce39 2px solid;padding: 3px; font-size: 10px;">Serial</th>';
                     $table_header .='<th width="200"; align="center" style="border-bottom: #a6ce39 2px solid;padding: 3px; font-size: 10px;">Nombre</th>';
                     $table_header .='<th width="170"; align="center" style="border-bottom: #a6ce39 2px solid;padding: 3px; font-size: 10px;">Org</th>';
                     $table_header .='<th width="200"; align="center" style="border-bottom: #a6ce39 2px solid;padding: 3px; font-size: 10px;">Email</th>';

                     $table_header .= '</tr>';

                    $table = '<tbody>';
                  
                    foreach ($chromebooks as $chromebook)
                    {        

                        $table .= '<tr>';  
                                    
                        $table .='<td  width="63"; align="left" style="padding: 3px;vertical-align: middle;font-size: 10px; border-bottom: #7A7A7A 1px solid;">'.$chromebook->id_chromebook.'</td>';
                        $table .='<td  width="200"; align="left" style="padding: 3px;vertical-align: middle;font-size: 10px; border-bottom: #7A7A7A 1px solid;">'.$chromebook->full_name.'</td>';
                        $table .='<td  width="170"; align="center" style="padding: 3px;vertical-align: middle;font-size: 10px;border-bottom: #7A7A7A 1px solid;"> '.$chromebook->org_path.'</td>';
                        $table .='<td  width="200"; align="center" style="padding: 3px;vertical-align: middle;font-size: 10px; border-bottom: #7A7A7A 1px solid;">'.$chromebook->email.'</td>';

                        $table .= '</tr>'; 
                    }

                    $table .= '</tbody>';
                }
                else{
                    $table .= '<tr>';  
                                    
                        $table .='<td  width="650"; align="center" style="padding: 3px;vertical-align: middle;font-size: 14px;"> '.$org.' NO Cuenta con Chromebooks Asignadas</td>';
                        $table .= '</tr>'; 
                }


        }
        else
        {
              $this->session->set_flashdata('error',lang('chromebook:error_report'));
            
              redirect('admin/chromebooks');
        }
        

        ini_set('max_execution_time', 300);

        $this->load->library(array('pdf'));
        
        $html2pdf = new HTML2PDF('P', 'A4', 'es');
        

        ob_clean();
       

        $output = ''; 

        $doc = 'reporte_chrome';


        $output=$this->template->set_layout(false)
          //                   ->title('Reporte ')
                               ->enable_parser(true)
            ->build('templates/'.$doc,
              array('table'=>$table,
                    'table_header'=>$table_header,
                    'title'=>$title),true);
           
        $html2pdf->writeHTML($output);
        $html2pdf->Output($doc.'_'.now().'.pdf');
     
    }
      
    
    
 }