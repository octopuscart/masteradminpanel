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

    //mailer using mailchimp
    public function getContactList() {

        $result = $this->mailchimp_library->get('lists');
        $this->db->order_by("display_index", "asc");
        $query = $this->db->get('mailchimp_list');
        $resultdata = $query->result_array();
        $contactarray2 = array();
        foreach ($resultdata as $key => $value) {
            $contactarray2[$value['m_id']] = $value;
        }
        $contactarray = [];



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
                        'total_members' => $member_count | 0,
                    );
                    $contactarray2[$id]['total_members'] = $member_count;
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

        foreach ($contactarray2 as $key => $value) {
            array_push($contactarray, $value);
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
    //end of mailchimp


    //start of sendgrid mailer
    public function getContactListTxn() {
        $this->db->order_by("display_index", "asc");
        $query = $this->db->get('mailer_list');
        $query = $this->db->query("select ml.*, (select count(mc.id) from mailer_contacts as mc where mc.mailer_list_id  = ml.id) as total_members from mailer_list as ml order by ml.display_index");
        $resultdata = $query->result_array();
        $contactarray = $resultdata;
        $data['contactdata'] = $contactarray;
        if (isset($_POST['addcontact'])) {
            $email_address = $this->input->post("email_address");
            $first_name = $this->input->post("first_name");
            $last_name = $this->input->post("last_name");
            $mailer_contacts = array(
                "email" => $email_address,
                "first_name" => $first_name,
                "last_name" => $last_name,
                "status" => '1',
                "mailer_list_id" => $list_id,
                "datetime" => ""
            );
            $this->db->insert('mailer_contacts', $mailer_contacts);
        }
        $this->load->view('Email/contactlisttxn', $data);
    }

    public function sendMailThirdParty($list_id, $lattertype) {

        $this->db->where('id', $list_id);
        $query = $this->db->get('mailer_list');
        $mailerobj = $query->row();
        $data['mailerobj'] = $mailerobj;

        $this->db->where('status', 1);
        $this->db->where('mailer_list_id', $list_id);
        $query = $this->db->get('mailer_contacts');
        $contactdata = $query->result_array();

        $this->load->library('parser');
        $this->load->library('email');

        //sendgrid setting
        $this->email->initialize(array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.sendgrid.net',
            'smtp_user' => 'octopuscartltd@gmail.com',
            'smtp_pass' => 'India$2018',
            'smtp_port' => 587,
            'crlf' => "\r\n",
            'newline' => "\r\n"
        ));


        $data['contactlist'] = $contactdata;
        $data['lattertype'] = $lattertype;
        $data['list_id'] = $list_id;
        $data['exportdata'] = 'yes';
        $date1 = date('Y-m-') . "01";
        $date2 = date('Y-m-d');
        $data['mailstatus'] = "";

        $daterange = $date1 . " to " . $date2;
        $data['daterange'] = $daterange;



        if (isset($_POST['sendmail'])) {
            $emailtemplate = $this->input->post("emailtemplate");
            $subject = $this->input->post("subject");

            foreach ($emaillist as $key => $value) {
                $emailaddr = $value['email'];
                $first_name = $value['first_name'];
                $ftemplate = $this->parser->parse_string($emailtemplate, $value);
                //echo $ftemplate;
                $this->email->from('info@cctailor.com', 'CC Tailor');
                $this->email->to($emailaddr);
                $this->email->subject($subject);
                $this->email->message($ftemplate);
                $this->email->send();
                //echo $this->email->print_debugger();
            }
            redirect("Messages/sendMailThirdParty/$list_id/$lattertype");
        }


        if (isset($_POST['addcontact'])) {
            $email_address = $this->input->post("email_address");
            $first_name = $this->input->post("first_name");
            $last_name = $this->input->post("last_name");
            $mailer_contacts = array(
                "email" => $email_address,
                "first_name" => $first_name,
                "last_name" => $last_name,
                "status" => '1',
                "mailer_list_id" => $list_id,
                "datetime" => ""
            );
            $this->db->insert('mailer_contacts', $mailer_contacts);

            redirect("Messages/sendMailThirdParty/$list_id/$lattertype");
        }




        $this->load->view('Email/sendtemplate', $data);
    }

    public function removeContactFromList($list_id, $contact_id, $lattertype) {

        $this->db->set('mailer_list_id', "");
        $this->db->where('id', $contact_id); //set column_name and value in which row need to update
        $this->db->update('mailer_contacts');
        redirect("Messages/sendMailThirdParty/$list_id/$lattertype");
    }

    public function sendSingleEmail() {
        $this->load->library('email');
        $receiver_email = "octopuscartltd@gmail.com";
        //sendgrid setting
        $this->email->initialize(array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.sendgrid.net',
            'smtp_user' => 'octopuscartltd@gmail.com',
            'smtp_pass' => 'India$2018',
            'smtp_port' => 587,
            'crlf' => "\r\n",
            'newline' => "\r\n"
        ));

//        gmail setting
//        $this->email->initialize(array(
//            'protocol' => 'smtp',
//            'smtp_host' => 'ssl://smtp.googlemail.com',
//            'smtp_user' => 'tailor123hk@gmail.com',
//            'smtp_pass' => 'dedkfkyvvjooevli',
//            'smtp_port' => 465,
//            'crlf' => "\r\n",
//            'newline' => "\r\n"
//        ));
//        $this->email->initialize(array(
//            'protocol' => 'smtp',
//            'smtp_host' => 'ssl://costcointernational.com',
//            'smtp_user' => 'manager@costcointernational.com',
//            'smtp_pass' => 'India$2018',
//            'smtp_port' => 465,
//            'crlf' => "\r\n",
//            'newline' => "\r\n"
//        ));


        $this->email->from('info@cctailor.com', 'CC Tailor');
        $this->email->to($receiver_email);
        $this->email->subject('Email from CC Tailor Hong Kong');
        $this->email->message('Hello this CC Tailor Newsletter Hong Kong');
        $this->email->send();

        echo $this->email->print_debugger();
    }

}

?>
