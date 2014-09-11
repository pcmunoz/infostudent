<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Acadhead_model extends CI_Model {

    function __construct()
    {
        parent::__construct();

    }

    function get_all_news()
    {
        $this->datatables
            ->select('news_id,title,image,type,date_posted,date_updated')
            ->from('news')
            ->add_column('input', '<a class="btn btn-info" href="input_news/$1">Edit</a>', 'news_id');
        echo $this->datatables->generate();
    }

    function get_all_curriculum()
    {
        $this->datatables
            ->select('curi_id,name,description,years,sy')
            ->from('curriculum')
            ->add_column('subject', '<button type="button" id="subject_list" name="$1" class="btn btn-info">Subject List</button>', 'curi_id')
            ->add_column('upload', '<button type="button" id="upload_list" name="$1" class="btn btn-info">Upload Curriculum</button>', 'curi_id');
        echo $this->datatables->generate();
    }

    function get_all_course()
    {
        $this->datatables
            ->select('*')
            ->from('course');
        echo $this->datatables->generate();
    }

    function data_all_subjects()
    {
        $this->db->select('*');
        $query = $this->db->get('course');
        return $query->result();
     }

    function get_news_content($id)
    {
        $query = $this->db->get_where("news",array("news_id"=>$id));
        return $query->row_array();
    }

    function get_news_title()
    {
        $query = $this->db->get_where("news",array("title"=>$this->input->post('title')));
        return $query->row_array();
    }

    function get_all_subjects()
    {
        $query = $this->db->get('course');
        $value = array();
        foreach($query->result_array() as $val)
        {
            $value[$val['course_id']] = $val['course_id'];
        }
        return $value;
    }

    function get_all_subject_list()
    {
        $query = $this->db->get('course');
        return $query->result_array();
    }

    function entry_insert_news()
    {
        $time = time();
        $datenow = date('Y-m-d h:i A',$time);
        $data = array(
            'title' => $this->input->post('title'), 
            'content' => $this->input->post('content'), 
            'type' => $this->input->post('type'), 
            'image' => $this->input->post('image'),
            'date_posted' => $datenow
            );
        $this->db->insert('news',$data);

        $this->load->library('email');
        $query = $this->db->get_where('users','user_id LIKE '."'5%'");

        $config['protocol'] = "smtp";
        $config['smtp_host'] = "ssl://smtp.gmail.com";
        $config['smtp_port'] = "465";
        $config['smtp_user'] = "handsomepcm@gmail.com"; 
        $config['smtp_pass'] = "master-4577";
        $config['charset'] = "utf-8";
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        
        $this->email->initialize($config);
        
        $emails="";
        
        foreach ($query->result() as $row)
        {
            
            $emails=$emails.','.$row->email_address;
        }

        $this->email->from('informatics-sis',$this->input->post('type'));  
        $this->email->to($emails);  
        $this->email->subject($this->input->post('title'));  
        $this->email->message($this->input->post('content'));  
        $this->email->send();
        if (!$result)
        {
            echo $this->email->print_debugger();
        }  
    }

    function entry_update_news()
    {
        $time = time();
        $datenow = date('Y-m-d h:i A',$time);

        $data = array(
            'title' => $this->input->post('title'), 
            'content' => $this->input->post('content'), 
            'type' => $this->input->post('type'), 
            'date_updated' => $datenow,
            'image'=> $this->input->post('image')
            );

        $this->db->where('news_id',$this->input->post('news_id'));
        $this->db->update('news',$data);
    }

    function edit_curriculum_name()
    {
        $data = array(
            'name' => $this->input->post('value')
            );
        $this->db->where('curi_id',$this->input->post('id'));
        $this->db->update('curriculum',$data);
    }

    function edit_curriculum_description()
    {
        $data = array(
            'description' => $this->input->post('value')
            );
        $this->db->where('curi_id',$this->input->post('id'));
        $this->db->update('curriculum',$data);
    }

    function edit_curriculum_years()
    {
        $data = array(
            'years' => $this->input->post('value')
            );
        $this->db->where('curi_id',$this->input->post('id'));
        $this->db->update('curriculum',$data);
    }

    function edit_curriculum_sy()
    {
        $data = array(
            'sy' => $this->input->post('value')
            );
        $this->db->where('curi_id',$this->input->post('id'));
        $this->db->update('curriculum',$data);
    }

    function add_curriculum(){
        $data = array(
        'name' => $this->input->post('curi_name'), 
        'description' => $this->input->post('description'), 
        'years' => $this->input->post('years'),
        'sy' =>  $this->input->post('school_year')
        );
        $this->db->insert('curriculum',$data);
    }

    function edit_course_name()
    {
        $data = array(
            'course_name' => $this->input->post('value')
            );
        $this->db->where('course_id',$this->input->post('id'));
        $this->db->update('course',$data);
    }

    function edit_course_prereq()
    {
        $data = array(
            'course_prereq' => $this->input->post('value')
            );
        $this->db->where('course_id',$this->input->post('id'));
        $this->db->update('course',$data);
    }

    function edit_course_coreq()
    {
        $data = array(
            'course_coreq' => $this->input->post('value')
            );
        $this->db->where('course_id',$this->input->post('id'));
        $this->db->update('course',$data);
    }

    function edit_course_unit_lec()
    {
        $data = array(
            'course_unit_lec' => $this->input->post('value')
            );
        $this->db->where('course_id',$this->input->post('id'));
        $this->db->update('course',$data);
    }

    function edit_course_unit_lab()
    {
        $data = array(
            'course_unit_lab' => $this->input->post('value')
            );
        $this->db->where('course_id',$this->input->post('id'));
        $this->db->update('course',$data);
    }

    function add_course(){
        $data = array(
        'course_id' => $this->input->post('course_id'), 
        'course_name' => $this->input->post('course_name'), 
        'course_prereq' => $this->input->post('course_prereq'),
        'course_coreq' =>  $this->input->post('course_coreq'),
        'course_unit_lab' =>  $this->input->post('course_unit_lab'),
        'course_unit_lec' =>  $this->input->post('course_unit_lec')
        );
        $this->db->insert('course',$data);
    }

    function data_all_course($id)
    {
        $query = $this->db->query("SELECT sub.*,combi.term,combi.year
            FROM course AS sub INNER JOIN curriculum_courses AS combi ON sub.course_id=combi.course_id
            WHERE combi.curi_id=$id");
        //$query = $this->db->get_where('curriculum_courses',array('curi_id'=>$id));
        //AND combi.sy LIKE '$sy';
        return $query -> result();
    }

    function curriculum_name($id)
    {
        $query = $this->db->get_where('curriculum',array('curi_id'=>$id));
        return $query -> result_array();
    }
    function add_to_course()
    {
        $data = array(
            'curi_id' => $this->input->post('id'),
            'course_id' => $this->input->post('add_subject_id'),  
            'year' => $this->input->post('add_year'), 
            'term' => $this->input->post('add_term')
        );
        $this->db->insert('curriculum_courses',$data);

    }
    function delete_from_course()
    {
        $data = array(
            'curi_id' => $this->input->post('id'),
            'course_id' => $this->input->post('del_subject_id')
        );
        $this->db->delete('curriculum_courses', $data); 

    }

    function save_csv($dir,$id){
        $row = 1;
        if (($handle = fopen($dir, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                if($row>1){
                    for ($c=0; $c < $num; $c++) {
                        $storage[$c]=$data[$c];
                    
                    }

                    $query="INSERT IGNORE INTO 
                            curriculum_courses (curi_id,course_id,year,term) 
                            VALUES ($id,'$storage[0]','$storage[1]','$storage[2]')";
                    $this->db->query($query);  
                    
                }
                $row++;
            }
        }
    }

}

/* End of file acadhead_model.php */
/* Location: ./application/models/acadhead_model.php */