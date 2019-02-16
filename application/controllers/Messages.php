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
                    "reply_to" => email_bcc,
                    "from_name" => email_sender_name)
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
        $query = $this->db->query("select ml.*, (select count(mc.id) from mailer_contacts2 as mc where mc.mailer_list_id  = ml.id) as total_members from mailer_list as ml order by ml.display_index");
        $resultdata = $query->result_array();
        $contactarray = $resultdata;
        $data['contactdata'] = $contactarray;
        if (isset($_POST['addcontact'])) {
            $email_address = $this->input->post("email_address");
            $first_name = $this->input->post("first_name");
            $last_name = $this->input->post("last_name");
            $listid = $this->input->post("listid");
            $mailer_contacts = array(
                "email" => $email_address,
                "first_name" => $first_name,
                "last_name" => $last_name,
                "status" => '1',
                "mailer_list_id" => $listid,
                "datetime" => ""
            );
            $this->db->insert('mailer_contacts', $mailer_contacts);
            redirect("Messages/getContactListTxn");
        }
        $this->load->view('Email/contactlisttxn', $data);
    }

    public function sendMailThirdParty($list_id, $lattertype) {

        $this->db->where('id', $list_id);
        $query = $this->db->get('mailer_list');
        $mailerobj = $query->row();

        $this->db->select('email');
        $query = $this->db->get('mailer_contacts2_check');
        $mmailer_contacts2_check = $query->result();

        $emailcheck = array();

        foreach ($mmailer_contacts2_check as $key => $value) {
            array_push($emailcheck, $value->email);
        }



        $data['mailerobj'] = $mailerobj;

        $ignore = $emailcheck;
//        $ignore = [];

        $this->db->where('status', 1);
        $this->db->where('mailer_list_id', $list_id);

        if (count($ignore)) {
            $this->db->where_not_in('mailer_contacts2.email', $ignore);
        }
        $this->db->group_by('email');

        $query = $this->db->get('mailer_contacts2');

        $contactdata = $query->result_array();

//        $contactdata = array_chunk($contactdata, 10)[0];


        $this->load->library('parser');
        $this->load->library('email');



        $this->db->where('default', '1');
        $query = $this->db->get('configuration_email');
        $mailerconf = $query->row();


        // sendgrid setting
        $this->email->initialize(array(
            'protocol' => 'smtp',
            'smtp_host' => $mailerconf->smtp_server,
            'smtp_user' => $mailerconf->username,
            'smtp_pass' => $mailerconf->password,
            'smtp_port' => $mailerconf->smtp_port,
            'crlf' => "\r\n",
            'newline' => "\r\n"
        ));

//        //Amazon
//        $this->email->initialize(array(
//            'protocol' => 'smtp',
//            'smtp_host' => "ssl://email-smtp.us-east-1.amazonaws.com",
//            'smtp_user' => "AKIAJFKUSW3BKPPJ2TNA",
//            'smtp_pass' => "AuNsBWfoNKvcadKTz1kOWZi8x11sMqj2L8teknNaN2MJ",
//            'smtp_port' => "465",
//            'crlf' => "\r\n",
//            'newline' => "\r\n"
//        ));






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
            foreach ($contactdata as $key => $value) {
                $emailaddr = $value['email'];
                $first_name = $value['full_name'];
                $ftemplate = $this->parser->parse_string($emailtemplate, $value);
                //echo $ftemplate;
                $this->email->from(email_bcc, email_sender_name);
                $this->email->to($emailaddr);
                $this->email->subject($subject);
                $this->email->message($ftemplate);
                $mstatus = 1;
                $checksend = $this->email->send();

                if ($checksend) {
                    $mstatus = "Send";
                } else {
                    $mstatus = $this->email->print_debugger();
                }


                $mailer_contacts2_check = array(
                    "email" => $emailaddr,
                    "status" => $mstatus,
                    "mailer_contact_id" => $value['id'],
                    "datetime" => date('Y-m-d H:M:S')
                );
                $this->db->insert('mailer_contacts2_check', $mailer_contacts2_check);
            }
            //redirect("Messages/sendMailThirdParty/$list_id/$lattertype");
        }


        if (isset($_POST['addcontact'])) {
            $email_address = $this->input->post("email_address");
            $first_name = $this->input->post("first_name");
            $last_name = $this->input->post("last_name");
            $mailer_contacts = array(
                "email" => $email_address,
                "first_name" => $first_name,
                "full_name" => $first_name . " " . $last_name,
                "last_name" => $last_name,
                "status" => '1',
                "mailer_list_id" => $list_id,
                "datetime" => ""
            );
            $this->db->insert('mailer_contacts2', $mailer_contacts);

            redirect("Messages/sendMailThirdParty/$list_id/$lattertype");
        }

        $this->load->view('Email/sendtemplate', $data);
    }

    public function sendMailThirdPartyApi($list_id, $lattertype) {

        $this->db->where('id', $list_id);
        $query = $this->db->get('mailer_list');
        $mailerobj = $query->row();

        $this->db->select('email');
        $query = $this->db->get('mailer_contacts2_check');
        $mmailer_contacts2_check = $query->result();

        $emailcheck = array();

        foreach ($mmailer_contacts2_check as $key => $value) {
            array_push($emailcheck, $value->email);
        }



        $data['mailerobj'] = $mailerobj;

        $ignore = $emailcheck;
        $this->db->where('status', 1);
        $this->db->where('mailer_list_id', $list_id);

        if (count($ignore)) {
            $this->db->where_not_in('mailer_contacts2.email', $ignore);
        }
        $this->db->group_by('email');

        $query = $this->db->get('mailer_contacts2');

        $contactdata = $query->result_array();

        $contactdata = array_chunk($contactdata, 10)[0];


        $this->load->library('parser');
        $this->load->library('email');



        $this->db->where('default', '1');
        $query = $this->db->get('configuration_email');
        $mailerconf = $query->row();


        //sendgrid setting
        $this->email->initialize(array(
            'protocol' => 'smtp',
            'smtp_host' => $mailerconf->smtp_server,
            'smtp_user' => $mailerconf->username,
            'smtp_pass' => $mailerconf->password,
            'smtp_port' => $mailerconf->smtp_port,
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



        if (isset($_GET['sendmail'])) {
            $emailtemplate = $this->load->view('mailtemplate/template3', $order_details, true);
            $subject = email_sender_name . " Wishing You Merry Christmas and Happy New Year!";
            foreach ($contactdata as $key => $value) {
                $emailaddr = $value['email'];
                $first_name = $value['full_name'];
                $ftemplate = $this->parser->parse_string($emailtemplate, $value);
                //echo $ftemplate;
                $this->email->from(email_bcc, email_sender_name);
                $this->email->to($emailaddr);
                $this->email->subject($subject);
                $this->email->message($ftemplate);
                $mstatus = 1;
                // print_r($ftemplate);
                $checksend = $this->email->send();
                if ($checksend) {
                    $mstatus = "Send";
                } else {
                    $mstatus = $this->email->print_debugger();
                }


                $mailer_contacts2_check = array(
                    "email" => $emailaddr,
                    "status" => $mstatus,
                    "mailer_contact_id" => $value['id'],
                    "datetime" => date('Y-m-d H:M:S')
                );
                $this->db->insert('mailer_contacts2_check', $mailer_contacts2_check);
            }
            redirect("Messages/sendMailThirdPartyApi/$list_id/$lattertype");
        }




        $this->load->view('Email/sendtemplate', $data);
    }

    public function templateTest() {
        $this->load->view('mailtemplate/template2');
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
        $this->db->where('id', 1);
        $query = $this->db->get('configuration_email');
        $mailerconf = $query->row();

        //sendgrid setting
        $this->email->initialize(array(
            'protocol' => 'smtp',
            'smtp_host' => $mailerconf->smtp_server,
            'smtp_user' => $mailerconf->username,
            'smtp_pass' => $mailerconf->password,
            'smtp_port' => $mailerconf->smtp_port,
            'crlf' => "\r\n",
            'newline' => "\r\n"
        ));




        $this->email->from(email_bcc, email_sender_name);
        $this->email->to($receiver_email);
        $this->email->subject('Email from CC Tailor Hong Kong');
        $this->email->message('Hello this CC Tailor Newsletter Hong Kong');
        $this->email->send();

        $this->email->print_debugger();
    }

    public function sendMailChimpSingleMail($param) {

        $apikey = MAILCHIMP_APIKEY;
        $apiendpoint = MAILCHIMP_APIENDPOINT;


        $to_emails = array('you@example.com', 'your_mom@example.com');
        $to_names = array('You', 'Your Mom');

        $message = array(
            'html' => 'Yo, this is the <b>html</b> portion',
            'text' => 'Yo, this is the *text* portion',
            'subject' => 'This is the subject',
            'from_name' => 'Me!',
            'from_email' => 'verifed@example.com',
            'to_email' => $to_emails,
            'to_name' => $to_names
        );

        $tags = array('WelcomeEmail');

        $params = array(
            'apikey' => $apikey,
            'message' => $message,
            'track_opens' => true,
            'track_clicks' => false,
            'tags' => $tags
        );

        $url = "$apiendpoint/SendEmail";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        curl_close($ch);

        $data = json_decode($result);
        echo "Status = " . $data->status . "\n";
    }

    public function sendSingleEmailSES() {
        $this->load->library('email');
        echo $receiver_email ="tailor123hk@gmail.com";
        //sendgrid setting
       

        //sendgrid setting
        $this->email->initialize(array(
            'protocol' => 'smtp',
            'smtp_host' => "email-smtp.us-east-1.amazonaws.com",
            'smtp_user' => "AKIAJ7ZMGRUKW7V2XT6Q",
            'smtp_pass' => "BIbmB62Cc5ZHxYS6yv7/Bw3YSfA1rx0rAT1FxRn7Jt+A",
            'smtp_port' => 587,
            'crlf' => "\r\n",
            'newline' => "\r\n",
            "smtp_crypto"=>"tls",
        ));




        $this->email->from(email_bcc, email_sender_name);
        $this->email->to($receiver_email);
        $this->email->subject('Email from SES');
        $this->email->message('Hello this Mail from SES');
        echo $this->email->send();
        print "hello";
        print_r($this->email->print_debugger());
    }

}

?>
