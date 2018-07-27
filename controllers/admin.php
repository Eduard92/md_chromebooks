
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
    }
        
   function index()
    {   
        $this->load->library('centros/centro');

         $orgs_path = array();
        
         $resume = array(
            'chromebooks' => array(),         
         );
         
         $orgs         = $this->org_m->get_all();
         $q            = $this->input->get('q');
         $base_where   = array();
         
         if($q)
         {
            $base_where['id_chromebook'] = $q;
         } 

         if(!group_has_role('chromebooks','admin_chrome'))
        {
            $orgs_perm = Centro::GetPermissions('orgs');
          
            $orgs_path = $this->org_m->where_in('id',$orgs_perm)->dropdown('id','org_path');
            
        }

        if(count($orgs_path)>0)
         {
             $chromebooks = $this->chromebook_m->where_in('org_path',$orgs_path)
                            ->order_by('org_path')
                            ->get_all();
         }
          else{
         
         $chromebooks  = $this->chromebook_m
                                ->order_by('org_path')
                                ->get_all();
        }
        

        $this->template->title($this->module_details['name'])
                   ->append_metadata('<script type="text/javascript"> var orgs='.json_encode($orgs).', resume='.json_encode($chromebooks).';</script>')
                   ->set('chromebooks',$chromebooks)
                   ->append_js('module::chromebook.controller.js')
                   ->build('admin/chromebooks/index');
    }

    public function newChromebook()
    {
        
         $result = array(
         
            'status' => false,
            'message'=>'',
            'data'   => array()
         );

            $chromebook = $this->chromebook_m->get($this->input->post('serie')) ;

            if($chromebook)
            {

                 $result['message'] =  lang('chromebook:exist');
            }
            else
            {   
                $chromebook_ = $this->chromebook_m->create($this->input->post());

                $result['status'] = true;
                $result['data'] =  $chromebook_ ;
                $result['message'] = lang('chromebook:new_success');
            }
              

        return $this->template->build_json($result);
    }

    public function asignarOrg()
    {
        
         $result = array(
            'status' => false,
            'message'=>'',
            'data'   => array()
         );

                
            $chromebook = $this->chromebook_m->get($this->input->post('serie')) ;

            if($chromebook)
            {
                $data = array(
                'org_path'  =>  $this->input->post('org_path'));

                    if($this->chromebook_m->update($this->input->post('serie'),$data))
                    {            
                        $result['message'] = lang('chromebook:new_asigned');
                        $result['status'] = true;
                    }
                    else
                    {
                        $result['message'] = lang('chromebook:error');
                    }                
            }
            else
            {   
                $result['message'] = 'No existe el registro';
            }
              
               

        return $this->template->build_json($result);
    }

    
    public function removerOrg()
    {
        
         $result = array(
            'status' => false,
            'message'=>'',
            'data'   => array()
         );


            $chromebook = $this->chromebook_m->get($this->input->post('serie')) ;

            if($chromebook)
            {
                      $data = array(
                      'org_path'  =>  null);

                    if($this->chromebook_m->update($this->input->post('serie'),$data))
                    {            
                                $result['message'] = lang('chromebook:removed');
                                $result['status'] = true;
                    }
                    else
                    {
                        $result['message'] = lang('chromebook:error');
                    }
                 
            }
            else
            {   
                $result['message'] = 'No existe el registro';
            }
              
               

        return $this->template->build_json($result);
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
        $base_where =  array();

        if(is_numeric($estatus)&& $estatus==0)
        {
            if(empty($org) == false)
            {
              $base_where['org_path'] = $org;
              
              $plantel = explode("/",$org);  

              $plantel  =  str_replace('/','',$plantel[count($plantel)-1]); 

              $title = 'Relación de Chromebooks Disponibles '.$plantel; 
 
            }
            
            $chromebooks  = $this->chromebook_m->where($base_where)
                                ->where('id NOT IN(SELECT id_chromebook FROM default_chromebook_asignacion WHERE removido IS NULL)',null)
                                ->get_all();

            if(empty($chromebooks) == true)
            {
                  $title = $plantel.' No Cuenta con Chromebooks Disponibles'; 
            }
            else
            {

               //  $title = 'Relación de Chromebooks Disponibles';         
                 $table = '<tbody>';
                 $table_header = '<tr>';
                
                $count = count($chromebooks)<9?count($chromebooks):9;
                
                for ($i = 1; $i <= $count; $i++) 
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
          
        }
        elseif(is_numeric($estatus)&& $estatus==1)
        {   
            $base_where   = array();
            
         

            $chromebooks = $this->asignacion_m->where($base_where)
                                ->select('responsable,full_name,observaciones, org_path,chromebook_asignacion.id AS id,chromebook_asignacion.email,asignado,id_chromebook')
                                
                                ->join('emails','emails.email=chromebook_asignacion.email')
                                ->where('removido IS NULL',null)->where('org_path',$org)->get_all();
                    $count = 0;

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
                        $count++;

                        $table .= '<tr>';  
                                    
                        $table .='<td  width="63"; align="left" style="padding: 3px;vertical-align: middle;font-size: 10px; border-bottom: #7A7A7A 1px solid;">'.$chromebook->id_chromebook.'</td>';
                        $table .='<td  width="200"; align="left" style="padding: 3px;vertical-align: middle;font-size: 10px; border-bottom: #7A7A7A 1px solid;">'.$chromebook->full_name.'</td>';
                        $table .='<td  width="170"; align="center" style="padding: 3px;vertical-align: middle;font-size: 10px;border-bottom: #7A7A7A 1px solid;"> '.$chromebook->org_path.'</td>';
                        $table .='<td  width="200"; align="center" style="padding: 3px;vertical-align: middle;font-size: 10px; border-bottom: #7A7A7A 1px solid;">'.$chromebook->email.'</td>';

                        $table .= '</tr>'; 
                    }

                    $table .= '</tbody>';

                    $total= 'Total Asignadas: '.$count;

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
                    'title'=>$title,
                    'total'=>$total,),true);
           
        $html2pdf->writeHTML($output);
        $html2pdf->Output($doc.'_'.now().'.pdf');
     
    }
      
    
    
 }