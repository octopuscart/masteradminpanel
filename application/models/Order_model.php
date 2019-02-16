<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model {

    function __construct() {
// Call the Model constructor
        parent::__construct();
        $this->load->database();
    }

//get order details  
    public function getOrderDetails($key_id, $is_key = 0) {
        $order_data = array();
        if ($is_key === 'key') {
            $this->db->where('order_key', $key_id);
        } else {
            $this->db->where('id', $key_id);
        }
        $query = $this->db->get('user_order');
        $order_details = $query->row();
        if ($order_details) {

            $order_data['order_data'] = $order_details;
            $this->db->where('order_id', $order_details->id);
            $query = $this->db->get('cart');
            $cart_items = $query->result();

            $this->db->order_by('display_index', 'asc');
            $this->db->where('order_id', $order_details->id);
            $query = $this->db->get('custom_measurement');
            $custom_measurement = $query->result_array();

            $order_data['measurements_items'] = $custom_measurement;

            foreach ($cart_items as $key => $value) {
                $cart_id = $value->id;

                $this->db->where('cart_id', $cart_id);
                $query = $this->db->get('cart_customization');
                $cartcustom = $query->result_array();

                $customdata = array();
                foreach ($cartcustom as $key1 => $value1) {
                    $customdata[$value1['style_key']] = $value1['style_value'];
                }
                $value->custom_dict = $customdata;
                $value->product_status = array();
            }
            $order_data['cart_data'] = $cart_items;
        }
        return $order_data;
    }

    public function getOrderDetailsV2($key_id, $is_key = 0) {
        $order_data = array();
        if ($is_key === 'key') {
            $this->db->where('order_key', $key_id);
        } else {
            $this->db->where('id', $key_id);
        }
        $query = $this->db->get('user_order');
        $order_details = $query->row();
        $payment_details = array("payment_mode" => "", "txn_id" => "", "payment_date" => "");

        if ($order_details) {

            $this->db->order_by('id', 'desc');
            $this->db->where('order_id', $order_details->id);
            $query = $this->db->get('user_order_status');
            $userorderstatus = $query->result();
            $order_data['order_status'] = $userorderstatus;

            if ($order_details->payment_mode == 'PayPal') {
                $this->db->where('order_id', $order_details->id);
                $query = $this->db->get('paypal_status');
                $paypal_details = $query->result();

                if ($paypal_details) {
                    $paypal_details = end($paypal_details);
                    $payment_details['payment_mode'] = "PayPal";
                    $payment_details['txn_id'] = $paypal_details->txn_no;
                    $payment_details['payment_date'] = $paypal_details->timestemp;
                }
            }

            $order_id = $order_details->id;
            $order_data['order_data'] = $order_details;
            $this->db->where('order_id', $order_details->id);
            $query = $this->db->get('cart');
            $cart_items = $query->result();

            $this->db->order_by('display_index', 'asc');
            $this->db->where('order_id', $order_details->id);
            $query = $this->db->get('custom_measurement');
            $custom_measurement = $query->result_array();

            $order_data['measurements_items'] = $custom_measurement;

            foreach ($cart_items as $key => $value) {
                $cart_id = $value->id;

                $this->db->where('cart_id', $cart_id);
                $query = $this->db->get('cart_customization');
                $cartcustom = $query->result_array();

                $customdata = array();
                foreach ($cartcustom as $key1 => $value1) {
                    $customdata[$value1['style_key']] = $value1['style_value'];
                }
                $value->custom_dict = $customdata;

//                $this->db->where('order_id', $order_id);
//                $this->db->where('vendor_id', $vendor_id);
//                $query = $this->db->get('vendor_order_status');
//                $orderstatus = $query->result();
                $value->product_status = array();
            }
            $order_data['payment_details'] = $payment_details;
            $order_data['cart_data'] = $cart_items;
//            $order_data['amount_in_word'] = $this->convert_num_word($order_data['order_data']->total_price);
        }
        return $order_data;
    }

    public function getVendorsOrder($key_id) {
        $order_data = array();
        $this->db->where('order_key', $key_id);
        $query = $this->db->get('user_order');
        $order_details = $query->row();
        $venderarray = array();
        if ($order_details) {
            $order_id = $order_details->id;
            $order_data['order_data'] = $order_details;
            $this->db->where('order_id', $order_id);
            $query = $this->db->get('vendor_order');
            $vendor_orders = $query->result();
            $order_data['vendor'] = array();
            foreach ($vendor_orders as $key => $value) {
                $vid = $value->vendor_id;
                $order_data['vendor'][$vid] = array();
                $order_data['vendor'][$vid]['vendor'] = $value;

                $this->db->order_by('id', 'desc');
                $this->db->where('vendor_order_id', $value->id);
                $query = $this->db->get('vendor_order_status');
                $status = $query->row();

                $order_data['vendor'][$vid]['status'] = $status ? $status->status : $value->status;
                $order_data['vendor'][$vid]['remark'] = $status ? $status->remark : $value->status;

                $this->db->where('order_id', $order_id);
                $this->db->where('vendor_id', $vid);
                $query = $this->db->get('cart');
                $order_data['vendor'][$vid]['cart_items'] = $query->result();
            }
        }

        return $order_data;
    }

    function order_mail($order_id, $subject = "") {
        setlocale(LC_MONETARY, 'en_US');
        $order_details = $this->getOrderDetailsV2($order_id, 'key');
        $emailsender = email_sender;
        $sendername = email_sender_name;
        $email_bcc = email_bcc;

        if ($order_details) {
            $currentstatus = $order_details['order_status'][0];
            $order_no = $order_details['order_data']->order_no;
            $this->email->from(email_bcc, $sendername);
            $this->email->to($order_details['order_data']->email);
            $this->email->bcc(email_bcc);
            $subject = sitename." - " . $currentstatus->remark;
            $this->email->subject($subject);
            
            $checkcode = report_mode;
            if ($checkcode == 0) {
                echo $this->load->view('Email/order_mail', $order_details, true);
            } else {
                $this->email->message($this->load->view('Email/order_mail', $order_details, true));
                $this->email->print_debugger();
                $result = $this->email->send();
            }
        }
    }

    function order_pdf($order_id, $subject = "") {  
        setlocale(LC_MONETARY, 'en_US');
        $order_details = $this->getOrderDetails($order_id, 0);
        if ($order_details) {
            $order_no = $order_details['order_data']->order_no;
            $html = $this->load->view('Email/order_pdf', $order_details, true);
            $html_header = $this->load->view('Email/order_mail_header', $order_details, true);
            $html_footer = $this->load->view('Email/order_mail_footer', $order_details, true);
            $pdfFilePath = $order_no . ".pdf";
            $checkcode = report_mode;
            if ($checkcode == 0) {
                echo $html;
            } else {
                $this->load->library('m_pdf');
                $this->m_pdf->pdf->SetHTMLHeader($html_header);
                $this->m_pdf->pdf->SetHTMLFooter($html_footer);

                $this->m_pdf->pdf->WriteHTML($html);
                $this->m_pdf->pdf->Output($pdfFilePath, "D");
            }
        }
    }

    function order_pdf_worker($order_id, $subject = "") {
        setlocale(LC_MONETARY, 'en_US');
        $order_details = $this->getOrderDetails($order_id, 0);
        if ($order_details) {
            $order_no = $order_details['order_data']->order_no;
            $html = $this->load->view('Email/order_pdf_worker', $order_details, true);
            $html_header = $this->load->view('Email/order_mail_header', $order_details, true);
            $html_footer = $this->load->view('Email/order_mail_footer', $order_details, true);
            $pdfFilePath = $order_no . "_worker_report.pdf";
            $checkcode = report_mode;
            if ($checkcode == 0) {
                echo $html;
            } else {
                $this->load->library('m_pdf');
                $this->m_pdf->pdf->SetHTMLHeader($html_header);
                $this->m_pdf->pdf->SetHTMLFooter($html_footer);

                $this->m_pdf->pdf->WriteHTML($html);
                $this->m_pdf->pdf->Output($pdfFilePath, "D");
            }
        }
    }

}

?>
