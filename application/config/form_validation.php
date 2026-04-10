<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    'sign-in' => array(
        array(
            'field' => 'user_id',
            'label' => 'Email Id',
            'rules' => 'required|min_length[3]|max_length[50]|xss_clean|trim|htmlspecialchars|encode_php_tags',
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required|min_length[3]|max_length[50]|xss_clean|htmlspecialchars|encode_php_tags',
        )
    ),
    'client_firm' => array(
        array(
            'field' => 'firm_name',
            'label' => 'Firm Name',
            'rules' => 'required|min_length[2]|max_length[50]|xss_clean|trim|htmlspecialchars|encode_php_tags',
        ),
        array(
            'field' => 'firm_activity_status',
            'label' => 'Status',
            'rules' => 'required|xss_clean|trim|htmlspecialchars|encode_php_tags',
        ),
        array(
            'field' => 'firm_address',
            'label' => 'Firm Address',
            'rules' => 'required|min_length[3]|max_length[200]|xss_clean|trim|encode_php_tags',
        ),
        array(
            'field' => 'firm_email_id',
            'label' => 'Firm Email Id',
            'rules' => 'required|min_length[2]|max_length[50]|xss_clean|trim|htmlspecialchars|encode_php_tags',
        ),
        array(
            'field' => 'firm_type',
            'label' => 'Firm Type',
            'rules' => 'required|xss_clean|trim|htmlspecialchars|encode_php_tags',
        ),
        array(
            'field' => 'boss_name',
            'label' => 'Boss Name',
            'rules' => 'required|min_length[3]|max_length[50]|xss_clean|trim|htmlspecialchars|encode_php_tags',
        ),
        array(
            'field' => 'boss_mobile_no',
            'label' => 'Boss Mobile No',
            'rules' => 'required|regex_match[/^[0-9]{10}$/]',
        ),
        array(
            'field' => 'boss_email_id',
            'label' => 'Boss Email Id',
            'rules' => 'required|min_length[2]|max_length[50]|xss_clean|trim|htmlspecialchars|encode_php_tags',
        ),
        array(
            'field' => 'firm_no_of_employee',
            'label' => 'No of Employee in Firm',
            'rules' => 'required|min_length[1]|max_length[50]|xss_clean|trim|numeric|xss_clean',
        ),
        array(
            'field' => 'firm_no_of_customers',
            'label' => 'No of Customer in Firm',
            'rules' => 'required|min_length[1]|max_length[50]|xss_clean|trim|numeric|xss_clean',
        )
    ),
    'due_date' => array(
        array(
            'field' => 'due_date_name',
            'label' => 'Due Date Name',
            'rules' => 'required|xss_clean|trim|htmlspecialchars|encode_php_tags',
        ),
      
    ),
    'due_date_task' => array(
        array(
            'field' => 'due_date_task_name',
            'label' => 'due_date_task_name',
            'rules' => 'required|xss_clean|trim|htmlspecialchars|encode_php_tags',
        ),
    ),
    'hq_form_add_task' => array(
        array(
            'field' => 'task_name',
            'label' => 'task_name',
            'rules' => 'required|xss_clean|trim|htmlspecialchars|encode_php_tags',
        ),
    ),
    'form_add_task' => array(
        array(
            'field' => 'sub_task_name',
            'label' => 'sub_task_name',
            'rules' => 'required|xss_clean|trim|htmlspecialchars|encode_php_tags',
        ),
    ),
    'frm_add_customer' => array(
        array(
            'field' => 'customer_name',
            'label' => 'customer_name',
            'rules' => 'required|xss_clean|trim|htmlspecialchars|encode_php_tags',
        ),
        array(
            'field' => 'customer_address',
            'label' => 'customer_address',
            'rules' => 'required|xss_clean|trim|htmlspecialchars|encode_php_tags',
        ),
        array(
            'field' => 'customer_city',
            'label' => 'customer_city',
            'rules' => 'required|xss_clean|trim|htmlspecialchars|encode_php_tags',
        ),
        array(
            'field' => 'customer_state',
            'label' => 'customer_state',
            'rules' => 'required|xss_clean|trim|htmlspecialchars|encode_php_tags',
        ),
        array(
            'field' => 'customer_country',
            'label' => 'customer_country',
            'rules' => 'required|xss_clean|trim|htmlspecialchars|encode_php_tags',
        ),
        array(
            'field' => 'customer_contact_number',
            'label' => 'customer_contact_number',
            'rules' => 'required|xss_clean|trim|htmlspecialchars|encode_php_tags',
        ),
        array(
            'field' => 'customer_email_id',
            'label' => 'customer_email_id',
            'rules' => 'required|xss_clean|trim|htmlspecialchars|encode_php_tags',
        ),
       
    ),
    'addemp' => array(
        array(
            'field' => 'user_name',
            'label' => 'user_name',
            'rules' => 'required|xss_clean|trim|htmlspecialchars|encode_php_tags',
        ),
        array(
            'field' => 'mobile_no',
            'label' => 'mobile_no',
            'rules' => 'required|xss_clean|trim|htmlspecialchars|encode_php_tags',
        ),
        array(
            'field' => 'email',
            'label' => 'email',
            'rules' => 'required|xss_clean|trim|htmlspecialchars|encode_php_tags',
        ),
    ),
    'frm_custom_sub_task_allt' => array(
        array(
            'field' => 'sub_task_name',
            'label' => 'sub_task_name',
            'rules' => 'required|xss_clean|trim|htmlspecialchars|encode_php_tags',
        ),
    ),
);

