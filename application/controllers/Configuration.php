<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 */
defined('BASEPATH') OR exit('No direct script access allowed');
ob_start();

class Configuration extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->model('User_model');
        $this->load->library('session');
        $this->user_id = $this->session->userdata('logged_in')['login_id'];
        $this->user_type = $this->session->logged_in['user_type'];
    }

    public function index() {
        $this->all_query();
    }

    //Add product function
    function add_sliders($slider_id = 0) {
        $query = $this->db->get('sliders');
        $data['sliders'] = $query->result();

        $this->db->where('id', $slider_id);
        $query = $this->db->get('sliders');
        $sliderobj = $query->row();

        $operation = "add";



        $sliderdata = array(
            'id' => '',
            'title' => '',
            'title_color' => '',
            'line1' => '',
            'line1_color' => '',
            'line2' => '',
            'line2_color' => '',
            'file_name' => '',
            'link' => '',
            'link_text' => '',
            'position' => '',
        );

        $data['sliderdata'] = $sliderobj;
        if ($slider_id) {
            $operation = "edit";
            $data['sliderdata'] = $sliderobj;

            $sliderdata = array(
                'id' => $sliderobj->id,
                'title' => $sliderobj->title,
                'title_color' => $sliderobj->title_color,
                'line1' => $sliderobj->line1,
                'line1_color' => $sliderobj->line1_color,
                'line2' => $sliderobj->line2,
                'line2_color' => $sliderobj->line2_color,
                'file_name' => $sliderobj->file_name,
                'link' => $sliderobj->link,
                'link_text' => $sliderobj->link_text,
                'position' => $sliderobj->position,
            );

            $data['sliderdata'] = $sliderdata;


            if (isset($_POST['submit'])) {
                if (!empty($_FILES['picture']['name'])) {
                    $config['upload_path'] = 'assets_main/sliderimages';
                    $config['allowed_types'] = '*';
                    $temp1 = rand(100, 1000000);
                    $ext1 = explode('.', $_FILES['picture']['name']);
                    $ext = strtolower(end($ext1));
                    $file_newname = $temp1 . "1." . $ext;
                    $config['file_name'] = $file_newname;
                    //Load upload library and initialize configuration
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('picture')) {
                        $uploadData = $this->upload->data();
                        $picture = $uploadData['file_name'];
                    } else {
                        $picture = '';
                    }
                    $this->db->set('file_name', $file_newname);
                } else {
                    $picture = '';
                }
                $user_id = $this->session->userdata('logged_in')['login_id'];

                $this->db->set('title', $this->input->post('title'));

                $this->db->set('line1', $this->input->post('line1'));
                $this->db->set('title_color', $this->input->post('title_color'));
                $this->db->set('line1_color', $this->input->post('line1_color'));
                $this->db->set('line2_color', $this->input->post('line2_color'));
                $this->db->set('line2', $this->input->post('line2'));
                $this->db->set('link', $this->input->post('link'));
                $this->db->set('link_text', $this->input->post('link_text'));
                $this->db->set('position', $this->input->post('position'));



                $this->db->where('id', $this->input->post('slider_id')); //set column_name and value in which row need to update
                $this->db->update('sliders');

                redirect('Configuration/add_sliders');
            }
        } else {
            if (isset($_POST['submit'])) {
                if (!empty($_FILES['picture']['name'])) {
                    $config['upload_path'] = 'assets_main/sliderimages';
                    $config['allowed_types'] = '*';
                    $temp1 = rand(100, 1000000);
                    $ext1 = explode('.', $_FILES['picture']['name']);
                    $ext = strtolower(end($ext1));
                    $file_newname = $temp1 . "1." . $ext;
                    $config['file_name'] = $file_newname;
                    //Load upload library and initialize configuration
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('picture')) {
                        $uploadData = $this->upload->data();
                        $picture = $uploadData['file_name'];
                    } else {
                        $picture = '';
                    }
                } else {
                    $picture = '';
                }
                $user_id = $this->session->userdata('logged_in')['login_id'];
                $post_data = array(
                    'title' => $this->input->post('title'),
                    'title_color' => $this->input->post('title_color'),
                    'line1_color' => $this->input->post('line1_color'),
                    'line2_color' => $this->input->post('line2_color'),
                    'line1' => $this->input->post('line1'),
                    'line2' => $this->input->post('line2'),
                    'link' => $this->input->post('link'),
                    'link_text' => $this->input->post('link_text'),
                    'file_name' => $file_newname);
                $this->db->insert('sliders', $post_data);
                $last_id = $this->db->insert_id();
                redirect('Configuration/add_sliders');
            }
        }
        $data['operation'] = $operation;
        $this->load->view('Configuration/add_sliders', $data);
    }

    //set detault barcode
    function setBarcodeDefalt($barcode_id) {
        //set all to new 
        $this->db->set('active', 'no');
        $this->db->update('payment_barcode');
        //set news
        $this->db->set('active', 'yes');
        $this->db->where('id', $barcode_id); //set column_name and value in which row need to update
        $this->db->update('payment_barcode');
        redirect('Configuration/add_barcode');
    }

    //delete barcode data
    function delete_barcode($barcode_id) {
        $this->db->where('id', $barcode_id); //set column_name and value in which row need to update
        $this->db->delete('payment_barcode');
        redirect('Configuration/add_barcode');
    }

    //Add product function
    function add_barcode($slider_id = 0) {
        $query = $this->db->get('payment_barcode');
        $data['sliders'] = $query->result();

        $this->db->where('id', $slider_id);
        $query = $this->db->get('payment_barcode');
        $sliderobj = $query->row();

        $operation = "add";

        $sliderdata = array(
            'id' => '',
            'mobile_no' => '',
            'file_name' => '',
            'active' => '',
        );

        $data['sliderdata'] = $sliderobj;
        if ($slider_id) {
            $operation = "edit";
            $data['sliderdata'] = $sliderobj;

            $sliderdata = array(
                'id' => $sliderobj->id,
                'mobile_no' => $sliderobj->mobile_no,
                'file_name' => $sliderobj->file_name,
                'active' => $sliderobj->active,
            );

            $data['sliderdata'] = $sliderdata;


            if (isset($_POST['submit'])) {
                if (!empty($_FILES['picture']['name'])) {
                    $config['upload_path'] = 'assets_main/barcodes';
                    $config['allowed_types'] = '*';
                    $temp1 = rand(100, 1000000);
                    $ext1 = explode('.', $_FILES['picture']['name']);
                    $ext = strtolower(end($ext1));
                    $file_newname = $temp1 . "1." . $ext;
                    $config['file_name'] = $file_newname;
                    //Load upload library and initialize configuration
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('picture')) {
                        $uploadData = $this->upload->data();
                        $picture = $uploadData['file_name'];
                    } else {
                        $picture = '';
                    }
                } else {
                    $picture = '';
                }
                $user_id = $this->session->userdata('logged_in')['login_id'];

                $this->db->set('mobile_no', $this->input->post('mobile_no'));
                $this->db->set('active', $sliderobj->active);
                $this->db->set('file_name', $file_newname);
                $this->db->where('id', $this->input->post('slider_id')); //set column_name and value in which row need to update
                $this->db->update('payment_barcode');

                redirect('Configuration/add_barcode');
            }
        } else {
            if (isset($_POST['submit'])) {
                if (!empty($_FILES['picture']['name'])) {
                    $config['upload_path'] = 'assets_main/barcodes';
                    $config['allowed_types'] = '*';
                    $temp1 = rand(100, 1000000);
                    $ext1 = explode('.', $_FILES['picture']['name']);
                    $ext = strtolower(end($ext1));
                    $file_newname = $temp1 . "1." . $ext;
                    $config['file_name'] = $file_newname;
                    //Load upload library and initialize configuration
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('picture')) {
                        $uploadData = $this->upload->data();
                        $picture = $uploadData['file_name'];
                    } else {
                        $picture = '';
                    }
                } else {
                    $picture = '';
                }
                $user_id = $this->session->userdata('logged_in')['login_id'];
                $post_data = array(
                    'mobile_no' => $this->input->post('mobile_no'),
                    'file_name' => $file_newname);
                $this->db->insert('payment_barcode', $post_data);
                $last_id = $this->db->insert_id();
                redirect('Configuration/add_barcode');
            }
        }
        $data['operation'] = $operation;
        $this->load->view('Configuration/add_barcode', $data);
    }

    //Add shipping configuration
    function shipping_configuration($shipping_conf_id = 0) {

        $query = $this->db->get('payment_barcode');
        $data['sliders'] = $query->result();

        $query = $this->db->get('payment_barcode');
        $data['sliders'] = $query->result();

        $this->db->where('id', $slider_id);
        $query = $this->db->get('payment_barcode');
        $sliderobj = $query->row();

        $operation = "add";

        $sliderdata = array(
            'id' => '',
            'mobile_no' => '',
            'file_name' => '',
            'active' => '',
        );

        $data['sliderdata'] = $sliderobj;
        if ($slider_id) {
            $operation = "edit";
            $data['sliderdata'] = $sliderobj;

            $sliderdata = array(
                'id' => $sliderobj->id,
                'mobile_no' => $sliderobj->mobile_no,
                'file_name' => $sliderobj->file_name,
                'active' => $sliderobj->active,
            );

            $data['sliderdata'] = $sliderdata;


            if (isset($_POST['submit'])) {
                if (!empty($_FILES['picture']['name'])) {
                    $config['upload_path'] = 'assets_main/barcodes';
                    $config['allowed_types'] = '*';
                    $temp1 = rand(100, 1000000);
                    $ext1 = explode('.', $_FILES['picture']['name']);
                    $ext = strtolower(end($ext1));
                    $file_newname = $temp1 . "1." . $ext;
                    $config['file_name'] = $file_newname;
                    //Load upload library and initialize configuration
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('picture')) {
                        $uploadData = $this->upload->data();
                        $picture = $uploadData['file_name'];
                    } else {
                        $picture = '';
                    }
                } else {
                    $picture = '';
                }
                $user_id = $this->session->userdata('logged_in')['login_id'];

                $this->db->set('mobile_no', $this->input->post('mobile_no'));
                $this->db->set('active', $sliderobj->active);
                $this->db->set('file_name', $file_newname);
                $this->db->where('id', $this->input->post('slider_id')); //set column_name and value in which row need to update
                $this->db->update('payment_barcode');

                redirect('Configuration/add_barcode');
            }
        } else {
            if (isset($_POST['submit'])) {
                if (!empty($_FILES['picture']['name'])) {
                    $config['upload_path'] = 'assets_main/barcodes';
                    $config['allowed_types'] = '*';
                    $temp1 = rand(100, 1000000);
                    $ext1 = explode('.', $_FILES['picture']['name']);
                    $ext = strtolower(end($ext1));
                    $file_newname = $temp1 . "1." . $ext;
                    $config['file_name'] = $file_newname;
                    //Load upload library and initialize configuration
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('picture')) {
                        $uploadData = $this->upload->data();
                        $picture = $uploadData['file_name'];
                    } else {
                        $picture = '';
                    }
                } else {
                    $picture = '';
                }
                $user_id = $this->session->userdata('logged_in')['login_id'];
                $post_data = array(
                    'mobile_no' => $this->input->post('mobile_no'),
                    'file_name' => $file_newname);
                $this->db->insert('payment_barcode', $post_data);
                $last_id = $this->db->insert_id();
                redirect('Configuration/add_barcode');
            }
        }
        $data['operation'] = $operation;
        $this->load->view('Configuration/add_barcode', $data);
    }

    public function migration() {
        if ($this->db->table_exists('mailchimp_list')) {
            // table exists
        } else {
            $this->db->query('CREATE TABLE IF NOT EXISTS `mailchimp_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `m_id` varchar(100) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `datetime` varchar(100) DEFAULT NULL,
  `member_count` varchar(50) NOT NULL,
  `display_index` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;');
        }


        if ($this->db->table_exists('configuration_email')) {
            // table exists
        } else {
            $this->db->query('CREATE TABLE IF NOT EXISTS `configuration_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_type` varchar(200) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `smtp_server` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `smtp_port` varchar(50) NOT NULL,
  `api_key` varchar(512) NOT NULL,
  `api_endpoint` varchar(512) NOT NULL,
  `default` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;');
        }


        if ($this->db->table_exists('mailer_contacts2_check')) {
            // table exists
        } else {
            $this->db->query('CREATE TABLE IF NOT EXISTS `mailer_contacts2_check` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(200) NOT NULL,
  `mailer_contact_id` varchar(50) NOT NULL,
  `status` varchar(500) NOT NULL,
  `datetime` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1242 ;');
        }


        if ($this->db->table_exists('mailer_contacts')) {
            // table exists
        } else {
            $this->db->query('CREATE TABLE IF NOT EXISTS `mailer_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(200) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `mailer_list_id` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `datetime` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;');
        }

        if ($this->db->table_exists('mailer_list')) {
            // table exists
        } else {
            $this->db->query('CREATE TABLE IF NOT EXISTS `mailer_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `m_id` varchar(100) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `datetime` varchar(100) DEFAULT NULL,
  `member_count` varchar(50) NOT NULL,
  `display_index` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;');
        }





        if ($this->db->table_exists('mailer_contacts2')) {
            // table exists
        } else {
            $this->db->query('CREATE TABLE IF NOT EXISTS `mailer_contacts2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(200) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `full_name` varchar(200) NOT NULL,
  `mailer_list_id` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `datetime` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;');
        }

        
        if ($this->db->table_exists('appointment_list')) {
            // table exists
        } else {
            $this->db->query('CREATE TABLE IF NOT EXISTS `appointment_list` (
  `id` int(122) NOT NULL AUTO_INCREMENT,
  `select_date` varchar(122) NOT NULL,
  `select_time` varchar(122) NOT NULL,
  `first_name` varchar(122) NOT NULL,
  `no_of_person` varchar(30) NOT NULL,
  `last_name` varchar(122) NOT NULL,
  `email` varchar(122) NOT NULL,
  `contact_no` varchar(122) NOT NULL,
  `hotel` varchar(200) NOT NULL,
  `address` varchar(300) NOT NULL,
  `city_state` varchar(200) NOT NULL,
  `country` varchar(200) NOT NULL,
  `referral` varchar(122) NOT NULL,
  `datetime` varchar(122) NOT NULL,
  `appointment_type` varchar(122) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=60 ;');
        }
        
        
        if ($this->db->field_exists('hotel', 'appointment_list')) {
            // table exists
        } else {
            $this->db->query('ALTER TABLE `appointment_list` ADD `hotel` VARCHAR(200) NOT NULL AFTER `contact_no`, ADD `address` VARCHAR(300) NOT NULL AFTER `hotel`, ADD `city_state` VARCHAR(200) NOT NULL AFTER `address`, ADD `country` VARCHAR(200) NOT NULL AFTER `city_state`;');
        }

        
        

        if ($this->db->table_exists('appointment_entry')) {
            // table exists
        } else {
            $this->db->query('CREATE TABLE IF NOT EXISTS `appointment_entry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` varchar(10) NOT NULL,
  `country` varchar(100) NOT NULL,
  `city_state` varchar(100) NOT NULL,
  `appointment_type` varchar(50) NOT NULL,
  `hotel` varchar(200) NOT NULL,
  `address` varchar(250) NOT NULL,
  `days` varchar(200) NOT NULL,
  `start_date` varchar(100) NOT NULL,
  `end_date` varchar(100) NOT NULL,
  `contact_no` varchar(50) NOT NULL,
  `date` varchar(100) NOT NULL,
  `from_time` varchar(100) NOT NULL,
  `to_time` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;');
        }
        
        if ($this->db->field_exists('display_index', 'category')) {
            // table exists
        } else {
            $this->db->query('ALTER TABLE `category` ADD `display_index` INT NOT NULL AFTER `parent_id`;');
        }
        
        if ($this->db->field_exists('display_index', 'products ')) {
            // table exists
        } else {
            $this->db->query('ALTER TABLE `products` ADD `display_index` INT NOT NULL AFTER `folder`;');
        }
        
        
        if ($this->db->table_exists('configuration_cartcheckout')) {
            // table exists
        } else {
            $this->db->query('CREATE TABLE `configuration_cartcheckout` (
  `id` int(11) NOT NULL,
  `product_path_pre` varchar(200) NOT NULL,
  `product_path_post` varchar(200) NOT NULL,
  `payment_paypal` varchar(10) NOT NULL,
  `payment_bank` varchar(10) NOT NULL,
  `payment_cheque` varchar(10) NOT NULL,
  `payment_cod` varchar(10) NOT NULL,
  `default_payment_mode` varchar(50) NOT NULL,
  `gift_coupon` varchar(10) NOT NULL,
  `order_prefix` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
        }
        
        
    }

}
