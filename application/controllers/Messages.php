<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Messages extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->model('User_model');
        $this->load->model('Order_model');
        $this->load->library('session');

        $apikey = MAILCHIMP_APIKEY;
        $apiendpoint = MAILCHIMP_APIENDPOINT;

        $params = array('api_key' => $apikey, 'api_endpoint' => $apiendpoint);

        $this->load->library('mailchimp_library', $params);


        $session_user = $this->session->userdata('logged_in');
        if ($session_user) {
            $this->user_id = $session_user['login_id'];
        } else {
            $this->user_id = 0;
        }
        $this->user_id = $this->session->userdata('logged_in')['login_id'];
        $this->user_type = $this->session->logged_in['user_type'];
    }

    public function index() {
        redirect('/');
    }

//order list accroding to user type
    public function getContactList() {

        $result = $this->mailchimp_library->get('lists');
        $this->db->order_by("display_index", "asc");
        $query = $this->db->get('mailchimp_list');
        $resultdata = $query->result_array();
        $contactarray = $resultdata;



        foreach ($result as $key => $value) {

            foreach ($value as $key1 => $value1) {

                $name = $value1['name'];
                $id = $value1['id'];

                $member_count = $value1['stats']['member_count'];
                $date_created = $value1['date_created'];
                $this->db->where('m_id', $id);
                $query = $this->db->get('mailchimp_list');
                $resultdata = $query->result_array();

                if ($name) {
                    $mlistarray = array(
                        'm_id' => $id,
                        'name' => $name,
                        'datetime' => $date_created,
                        'member_count' => $member_count | 0,
                    );
                    $this->db->set('member_count', $member_count);
                    $this->db->set('name', $name);
                    $this->db->where('m_id', $id); //set column_name and value in which row need to update
                    $this->db->update('mailchimp_list');
                    
                }
                if (count($resultdata)) {
                    $this->db->set('member_count', $member_count);
                    $this->db->where('m_id', $id); //set column_name and value in which row need to update
                    $this->db->update('mailchimp_list');
                } else {
                    if ($id) {

                        $this->db->insert('mailchimp_list', $mlistarray);
                    }
                }
            }
        }
        $data['contactdata'] = $contactarray;

        if (isset($_POST['addcontact'])) {
            $email_address = $this->input->post("email_address");
            $listid = $this->input->post("listid");

            $result = $this->mailchimp_library->post("lists/$listid/members", [
                'email_address' => $email_address,
                'status' => 'subscribed',
            ]);
            if ($result) {
                redirect("Messages/getContactList");
            }
        }

        $this->load->view('Email/contactlist', $data);
    }

    public function addContact() {
        $list_id = '29106f6490';

        $result = $this->mailchimp_library->post("lists/$list_id/members", [
            'email_address' => 'pankaj21pathak@gmail.com',
            'status' => 'subscribed',
        ]);

        print_r($result);
    }

    public function removeContact() {
        
    }

    public function createTemplate($list_id, $lattertype) {


        $memvers = $this->mailchimp_library->get("lists/$list_id/members");
        $data['contactdata'] = $memvers;
        $data['listid'] = $list_id;
        $this->db->where('m_id', $list_id);
        $query = $this->db->get('mailchimp_list');
        $resultdata = $query->row();
        $data['contactlist'] = $resultdata;
        $data['lattertype'] = $lattertype;
        $data['exportdata'] = 'yes';
        $date1 = date('Y-m-') . "01";
        $date2 = date('Y-m-d');
        $data['mailstatus'] = "";
        if (isset($_GET['daterange'])) {
            $daterange = $this->input->get('daterange');
            $datelist = explode(" to ", $daterange);
            $date1 = $datelist[0];
            $date2 = $datelist[1];
        }
        $daterange = $date1 . " to " . $date2;
        $data['daterange'] = $daterange;
        $data['users_all'] = $this->User_model->user_reports("User");
        if (isset($_POST['sendmail'])) {
            $emailtemplate = $this->input->post("emailtemplate");
            $subject = $this->input->post("subject");



            $result = $this->mailchimp_library->post("campaigns", [
                'recipients' => array("list_id" => $list_id),
                "type" => "regular",
                "settings" => array("subject_line" => $subject,
                    "reply_to" => "tailor123hk@gmail.com",
                    "from_name" => "Cotcokart.com")
            ]);

            $comp_id = $result['id'];

            $result = $this->mailchimp_library->PUT("campaigns/$comp_id/content", [
                "html" => $emailtemplate
            ]);

            $result = $this->mailchimp_library->POST("campaigns/$comp_id/actions/send");
            if ($result == 1) {
                $data['mailstatus'] = "Email Sent to " . $resultdata->name . " list";
            }
        }

        $this->load->view('Email/create_template', $data);
    }

    public function sendBulkMail() {
        $campainid = "97d721a084";
        $result = $this->mailchimp_library->POST("campaigns/$campainid/actions/send");
        print_r($result);
    }

}

?>
