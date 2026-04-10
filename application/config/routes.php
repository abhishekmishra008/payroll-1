<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//super admin
$route["user_dashboard"] = "DashboardController/userDashboard";
$route["export_users_excel"] = "DashboardController/export_users_excel";
$route['default_controller'] = 'Login';
$route['reset_password'] = 'Login/reset_password_fun';
$route['branch_firm_form'] = 'Firm_form/index_branch_firm';
$route['add_firm'] = 'Firm_form/insert_client_firm';
$route['show_firm'] = 'Firm_form/view_firm_data';
$route['hq_show_firm'] = 'Hq_firm_form/view_firm_data';
$route['hq_show_firm_hr'] = 'Hq_firm_form/view_firm_data_admin';
$route['hq_add_firm'] = 'Hq_firm_form/insert_client_firm';
$route['edit_firm'] = 'Firm_form/edit_firm';
$route['show_due_date'] = 'Due_date/view_duedate_data';
$route['show_client_due_date'] = 'Due_date/view_client_duedate_data';
$route['show_client_due_date_task'] = 'Due_date/view_client_duedate_task_data';
$route['create_client_due_date_task'] = 'Due_date/add_client_due_date_task';
$route['create_due_date_task'] = 'Due_date/add_due_date_task';
$route['show_due_date_task'] = 'Due_date/view_duedate_task_data';
$route['add_due_date_task'] = 'Due_date/add_due_date_task';
$route['hq_duedate_task_list'] = 'Hq_firm_form/duedate_task_list';
$route['view_course/(:any)'] = 'Recommendation_Course/fetch_course_data/$1';
$route['admin_university'] = 'Add_university/view_university';
$route['admin_view_university'] = 'Add_university/index'; //admin
$route['admin_addcourse'] = 'Add_course/index';
$route['admin_editcourse/(:any)'] = 'Add_course/edit_course/$1';
$route['admin_certification'] = 'Certification/index';
$route['View_Certification'] = 'View_admincertification/index';
$route['Create_Your_Own_ourse'] = 'Create_owncourse/index';
$route['dashboard'] = 'Dashboard/admin_dashboard';
$route['sa_edit_firm_data/(:any)'] = 'Hq_firm_form/get_firm_data_edit/$1';
//Recommendation
$route['Recommendation_registration'] = 'Recommendation_registration/index';
$route['Recommendation_Course'] = 'Recommendation_Course/index';
//$route['profile'] = 'profile/index';
$route['view_recommendations'] = 'Certification/view_recommendation';

//hq admin
$route['hq_add_task'] = 'Task/hq_index';
$route['hq_view_task'] = 'Task/hq_view_task';
$route['hq_add_sub_task'] = 'Sub_task/hq_index';
$route['hq_view_sub_task'] = 'Sub_task/hq_view_sub_task';
$route['show_due_date_detail'] = 'Due_date/view_duedate_detail';
$route['hq_add_customer'] = 'Hq_customer';
$route['hq_show_customer'] = 'Hq_customer/view_customer_data';
$route['hq_KnowledgeStore'] = 'Knowledge_Store/hq_knowledge_store';
$route['hq_template_store'] = 'Template_Store/hq_template_store';
$route['view_hq_edit_customer/(:any)'] = 'Hq_customer/edit_customer_details_view/$1';
$route['view_firm_customer_detail/(:any)'] = 'Hq_customer/view_firm_customer_detail/$1';
$route['hq_calendar'] = 'Calendar/hq_calendar';
$route['hq_calender_holiday'] = 'Calendar_holiday/hq_calender_holiday';
$route['hqallot_duedate_task'] = 'Due_date/hqallot_duedate_task';
$route['hr_show_employee'] = 'Employee/view_employee_hr';
$route['Employee_hq'] = 'Employee/employee_hq';
$route['hr_edit_emp_data/(:any)/(:any)'] = 'Employee/fetch_emp_data_hr/$1/$2';
$route['HqDashboard'] = 'Dashboard/index_hq/';
$route['hq_branch_all_task_graph'] = 'Dashboard/hq_branch_all_task_graph/';
$route['hq_branch_emp_all_task_graph'] = 'Dashboard/hq_branch_emp_all_task_graph/';
$route['License_request'] = 'License_request/index';
$route['show_template_category'] = 'Template_cats/template_category_hq';
//$route['hq_show_template_category_III'] = 'Template_category_hq/show_template_categoryIII';
//$route['hq_show_template_category_II'] = 'Template_category_hq/show_template_categoryII';
//$route['hq_show_template_category_I'] = 'Template_category_hq/show_template_categoryI';
$route['graphs'] = 'Graphs_hq/index';
$route['Ticket_Configuration'] = 'Ticket_Configuration/index';
$route['Ticket_Generation'] = 'Ticket_Generation/index';
$route['Ticket_report'] = 'Ticket_report_controller/index';
$route['hq_manage_template'] = 'Template_store/manage_template';
$route['show_knowledge_category'] = 'Knowledge_cat/knowledge_category_hq';
//$route['hq_show_knowledge_category_III'] = 'Knowledge_category_hq/show_knowledge_categoryIII';
//$route['hq_show_knowledge_category_II'] = 'Knowledge_category_hq/show_knowledge_categoryII';
//$route['hq_show_knowledge_category_I'] = 'Knowledge_category_hq/show_knowledge_categoryI';
$route['hq_manage_knowledge'] = 'Knowledge_Store/hq_manage_knowledge';
$route['hq_show_service'] = 'Services/hq_show_service_type';
$route['hq_show_enquiry'] = 'Services/serv_cat';
$route['hq_add_enquiry'] = 'Enquiry/hq_add_enquiry';
$route['hq_view_enquiry'] = 'Enquiry/hq_showEnquiryDetails';
$route['hqDesignation'] = 'Designation/show_designation_hq';
$route['hq_leavemanagement'] = 'Leave_management/leave_approve_hq';
$route['hq_viewcourse'] = 'Purchase_course/index'; //hq
$route['View_purchase_course/(:any)'] = 'View_purchase_course/index/$1'; //hq
$route['hq_edit_firm_data/(:any)'] = 'Hq_firm_form/fetch_firm_data/$1';
$route['hq_edit_firm_data_admin/(:any)'] = 'Hq_firm_form/fetch_firm_data_admin/$1';
$route['hq_view_hr'] = 'Human_resource/view_hr_function';
$route['create_hr'] = 'Human_resource/create_hr_page';
$route['hq_survey'] = 'Createnewtemplate/hq_survey';
$route['hq_survey_form'] = 'Createnewtemplate/survey_form';
$route['setting'] = 'Setting/index';
$route['setting/(:any)'] = 'Setting/index/$1';
$route['selection_approch'] = 'Setting/personnel_assignment_selection_approach';
$route['task_graphs_hq'] = 'graphs/task_allotment_graph_hq';
$route['leave_graphs_hq'] = 'graphs/leave_graph_hq';
$route['due_date_task_graphs_hq'] = 'graphs/due_date_task_graphs_hq';
$route['Configure'] = 'Service_Offering_config/index';
$route['warnings_hq'] = "Warning/hq_admin_dashboard";
$route['send_warning_hq'] = "Send_Warning/hq_admin_dashboard_send_warning";
$route['show_due_date_detailreceived_warning'] = "send_warning/received_dashboard";

//HR
$route['hr_dashboard'] = 'Human_resource/index';
$route['employee_survey'] = 'Human_resource/employee_survey';
$route['employee_view'] = 'Human_resource/view_employee_survey';
$route['hr_designation'] = 'Human_resource/hr_designation';
$route['hr_view_emp'] = 'Human_resource/hr_view_employee';
$route['hr_working_paper'] = 'Human_resource/hr_working_paper';
$route['add_employee_hr'] = 'Employee/add_employee_hr';
$route['Leave_request_hr'] = 'Human_resource/Leave_request_hr';
$route['Leave_approve_hr'] = 'Human_resource/Leave_approve_hr';

$route['hr_edit/(:any)'] = 'Human_resource/hr_edit_fun/$1';
$route['excel_upload'] = 'Excel_Upload_hr/index';
$route['hr_checklist_master'] = 'Hr_checklist_Controller';
$route['hr_checklist_master/make_list'] = 'Hr_checklist_Controller/create_checklist';
$route['hr_checklist_master/list'] = 'Hr_checklist_Controller/get_all_checklist';
$route['hr_checklist_master/remove'] = "Hr_checklist_Controller/checklist_delete_by";
$route['hr_checklist_master/checklist_record'] = "Hr_checklist_Controller/get_checklist_by_id";
$route['hr_checklist_master/checklist_option_record'] = "Hr_checklist_Controller/get_checklist_option_by_id";
$route['hr_checklist_master/make_list_option'] = 'Hr_checklist_Controller/create_checklist_option';
$route['hr_checklist_master/checklist_option_list'] = "Hr_checklist_Controller/get_all_checklist_options_by_list_ref";
$route['hr_checklist_master/hr_checklist_option'] = "Hr_checklist_Controller/get_checklist_options";
$route['hr_checklist_master/hr_user_option'] = "Hr_checklist_Controller/get_firm_user";
$route['hr_checklist_master/assign_checklist'] = "Hr_checklist_Controller/assign_checklist_to_user";
$route['warnings_hr'] = "Warning/index";
$route['send_warning'] = "Send_Warning/index";
$route['received_warning'] = "Send_Warning/received_dashboard";
$route["add_loan_info"] = "Employee/add_loan_info";

//$route['send_warning'] = "Send_Warning/index";
// check list completed or not
$route["is_complete_checklist"] = "Hr_checklist_Controller/user_checklist_status";
$route["upload_new_document"] = "Hr_checklist_Controller/upload_document";
$route["hr_view_checklist_document"] = "Hr_checklist_Controller/cheklistModal";
$route["uploded_document_list"] = "Hr_checklist_Controller/get_all_new_uploaded_document";
$route["uploded_document_all_list"] = "Hr_checklist_Controller/get_all_uploaded_document";
//client admin
$route['client_add_task'] = 'Task';
$route['client_view_task'] = 'Task/view_task';
$route['client_add_sub_task'] = 'Sub_task';
$route['client_view_sub_task'] = 'Sub_task/view_sub_task';
$route['customer_task_allotment'] = 'Task_allotment';
$route['customer_show_task_allotment'] = 'Task_allotment/view_task_detail';
$route['client_show_employee'] = 'Employee/view_employee';
$route['client_custom_task_sub_allotment'] = 'Custom_sub_task_allotment';
$route['client_sticky'] = 'Sticky_note';
$route['reminder'] = 'Clientadmin/reminder';
$route['client_view_custom_task_sub_allotment'] = 'Custom_sub_task_allotment/Client_view_custom_sub_task';
$route['view_edit_customer/(:any)'] = 'Customer/edit_customer_details_view/$1';
$route['client_due_date'] = 'Due_date/client_duedate_data';
$route['show_client_due_date_detail'] = 'Due_date/view_client_duedate_detail';
$route['add_customer'] = 'Customer';
$route['show_customer'] = 'Customer/view_customer_data';
$route['ca_Knowledge_Store'] = 'Knowledge_Store/ca_knowledge_store';
$route['ca_template_store'] = 'Template_Store/ca_template_store';
$route['ca_dashboard'] = 'Dashboard/index_ca';
$route['ca_branch_all_graph'] = 'Dashboard/ca_branch_all_graph';
$route['ca_all_graph'] = 'Dashboard/ca_all_graph';
$route['ca_edit_emp_data/(:any)'] = 'Employee/fetch_emp_data/$1';
$route['ca_selfemp_view_task'] = 'View_employee_task/index';
$route['ca_selfemp_view_due_date_task'] = 'View_employee_task/due_date_task';
$route['caDesignation'] = 'Designation/index';
$route['Leave_request_ca'] = 'Leave_management/leave_request_ca';
$route['Leave_approve_ca'] = 'Leave_management/leave_approve_ca';
$route['ca_view_enquiry'] = 'Enquiry/ca_showEnquiryDetails';
$route['ca_show_service'] = 'Services/ca_show_service_type';
$route['ca_add_enquiry'] = 'Enquiry/ca_add_enquiry';
$route['ca_view_enquiry_allotment'] = 'Enquiry/ca_view_enquiry_allotment';
$route['ca_view_enquiry_customer'] = 'Enquiry/ca_showEnquiryCustomer';
$route['show_ca_knowledge_category'] = 'Knowledge_cat/knowledge_category_ca';
$route['show_ca_template_category'] = 'Template_cats/template_category_ca';
$route['ca_Service_Offering_config'] = 'Service_Offering_config/ca_configure';
//$route['ca_show_template_category_III'] = 'Template_category/show_template_categoryIII';
//$route['ca_show_template_category_II'] = 'Template_category/show_template_categoryII';
//$route['ca_show_template_category_I'] = 'Template_category/show_template_categoryI';
$route['ca_manage_template'] = 'Template_category/manage_template';
$route['due_date_graphs'] = 'graphs/due_date_graph_ca';
$route['task_graphs'] = 'graphs/task_allotment_graph';
$route['leave_graphs'] = 'graphs/leave_graph';
$route['enquiry_report'] = 'Graphs_ca/leave_graph';
$route['view_edit_customer_files/(:any)'] = 'Gst/edit_customer_file_details_view/$1';
$route['config_report'] = 'Graphs_ca/leave_graph';
$route['enquiry_report'] = 'Graphs_ca/leave_graph';
$route['enquiry_report'] = 'Graphs_ca/leave_graph';
$route['emp_view_enquiry_allotment'] = 'Enquiry/employee_view_enquiry_new'; //done by pooja

$route['warnings_ca'] = 'Warning/client_admin_dashboard';
$route['send_warning_ca'] = 'Send_Warning/send_dashboard_ca';
$route['received_warning_ca'] = 'Send_Warning/received_dashboard_ca';

//$route['ca_show_knowledge_category_III'] = 'Knowledge_category/show_knowledge_categoryIII';
//$route['ca_show_knowledge_category_II'] = 'knowledge_category/show_knowledge_categoryII';
//$route['ca_show_knowledge_category_I'] = 'knowledge_category/show_knowledge_categoryI';
$route['ca_manage_knowledge'] = 'knowledge_Store/manage_knowledge';
$route['ca_show_service'] = 'Services/ca_show_service_type';
$route['ca_selfemp_view_task'] = 'view_employee_task/index';
$route['ca_course'] = 'View_Ca_Course/index';
$route['Employee_ca'] = 'Employee/index';
$routw['ca_viewcourse'] = 'View_CaCourse/index';
$route['ca_calender'] = 'Calendar/ca_calender';
$route['ca_hr_policy'] = 'Human_resource/ca_hr_policy';
$route['c_cal'] = 'users/user';
$route['ca_calendar_holiday'] = 'Calendar_holiday/ca_calendar_holiday';
$route['ca_view_purchase_course/(:any)'] = 'View_purchase_course/ca_viewcoursedetails/$1'; //hq
$route['ca_approve_denied_report'] = 'Task_allotment/ca_approve_denied_report'; //done by pooja 1-10-2019
$route['hq_approve_denied_report'] = 'Task_allotment/hq_approve_denied_report'; //done by pooja 1-10-2019
$route['ca_digital_communication'] = 'Bulk_email/ca_digital_communication'; //done by pooja 3-10-2019
//Employee
$route['duedate'] = 'Due_date/view_client_duedate_detail_employee';
$route['employee_dashboard'] = 'Employee_login/employee_dashboard';
$route['employee_task'] = 'Employee_login/Employee_task';

$route['view_subtask'] = 'Employee_login/view_subtask';
$route['view_stickynote'] = 'Employee_login/view_stickynote';
$route['show_duedate'] = 'Employee_login/show_duedate';
$route['show_public_duedate'] = 'Employee_login/show_public_duedate';
$route['create_stickynote'] = 'Employee_login/create_stickynote';
$route['edit_task_assign/(:any)'] = 'Employee_login/edit_task_assign/$1';
$route['due_date_checklist'] = 'Due_date/Due_date_checklist';
$route['Leave_request'] = 'Leave_management/leave_request'; //employee admin
$route['Leave_approve'] = 'Leave_management/leave_approve'; //employee admin
$route['Leave_info'] = 'Leave_management/leave_policy';

$route['working_papers'] = 'Leave_management/hr_working_papers'; //employee admin working_papers
$route['accept_enquiry'] = 'Enquiry/accept_enquiry';
$route['fill_data'] = 'Enquiry/fill_data_emp';
$route['enquiry_transaction'] = 'Enquiry/ca_view_enquiry_transaction';
$route['ca_assign_enq_task'] = 'Enquiry/ca_create_enq_assig';
$route['designation_employee'] = 'Designation/designation_emp';
$route['employee_edit_emp_data/(:any)'] = 'Employee/employee_fetch_emp_data/$1';
$route['view_employee'] = 'Employee/emp_view_employee';
$route['Employee'] = 'Employee/create_employee';
$route['emp_calendar_holiday'] = 'Calendar_holiday/emp_calendar_holiday';
$route['emp_task'] = 'Task_allotment/employee_task';
// $route['edit_enquiry_cust/(:any)/(:any)/(:any)'] = 'Customer/edit_enquiry_cust_fun/$1/$2/$3';

$route['accept_enquiry'] = 'Enquiry/accept_enquiry';
$route['fill_data'] = 'Enquiry/fill_data_emp';
$route['complete_close_enquiry/(:any)/(:any)'] = 'Enquiry/close_complete_enq/$1/$2';
$route['edit_enquiry_cust/(:any)/(:any)'] = 'Customer/edit_enquiry_cust_fun/$1/$2';

$route['add_enquiry'] = 'Enquiry/add_enquiry';
$route['view_enquiry'] = 'Enquiry/showEnquiryDetails';

//employee prachi
$route['Leave_request'] = 'Leave_management/leave_request'; //employee admin
$route['Leave_approve'] = 'Leave_management/leave_approve'; //employee admin
$route['Leave_info'] = 'Leave_management/leave_policy';
$route['accept_enquiry'] = 'Enquiry/accept_enquiry';
$route['fill_data'] = 'Enquiry/fill_data_emp';
$route['emp_KnowledgeStore'] = 'Knowledge_Store/emp_knowledge_store'; //employee admin
$route['emp_template_store'] = 'Template_Store/emp_template_store'; //employee admin
$route['emp_manage_template'] = 'Template_store/emp_manage_template';
$route['show_emp_knowledge_category'] = 'Knowledge_cat/knowledge_category_emp';
//$route['emp_show_knowledge_category_III'] = 'Knowledge_category_emp/show_knowledge_categoryIII';
//$route['emp_show_knowledge_category_II'] = 'knowledge_category_emp/show_knowledge_categoryII';
//$route['emp_show_knowledge_category_I'] = 'knowledge_category_emp/show_knowledge_categoryI';
$route['emp_manage_knowledge'] = 'knowledge_category_emp/manage_knowledge';
$route['emp_show_service'] = 'Services/emp_show_service_type';
$route['course'] = 'Employee_Course/index'; //employee
$route['warnings_emp'] = "Warning/employee_dashboard";
$route['received_warning_emp'] = "Send_Warning/received_dashboard_employee";
$route['send_warning_emp'] = "Send_Warning/employee_dashboard_send_warning";
//template
$route['question_rating'] = 'createnewtemplate/question_rating_function';
$route['type_subtype'] = 'createnewtemplate/type_subtype_function';
$route['view_template'] = 'createnewtemplate/view_template_function';
$route['Create_Report_Design'] = 'createnewtemplate/Create_Report_Design';
$route['Edit_Report_Design'] = 'createnewtemplate/Edit_Report_Design';
$route['Enterdata'] = 'createnewtemplate/Enterdata';
$route['PrintReport'] = 'createnewtemplate/PrintReport';
$route['DesignPackage'] = 'DesignPackage/index';
$route['Enterdate'] = 'createnewtemplate/EnterDate';
//$route['Dashboard']='createnewtemplate/Dashboard';
//$route['Hq_Dashboard']='hqadmin/hq';
//hq_admin same as gold beries navigation bar
$route['createnew_template'] = 'hqadmin/createnew_template';
$route['Edit_template'] = 'hqadmin/Edit_template';
$route['createreportDesign'] = 'hqadmin/create_report_Design';
$route['EditReportDesign'] = 'hqadmin/Edit_Report_Design';
$route['Ennterdate'] = 'hqadmin/Ennterdate';
$route['PRINTREPORT'] = 'hqadmin/printreport';

//client_admin as per gold beries navigation
$route['List_Your_Client_Firm'] = 'hqadmin/yourclient';
$route['list_of_template'] = 'clientadmin/listof';
$route['upgrade'] = 'clientadmin/upgrade';
$route['explore_new_template'] = 'clientadmin/explore';
$route['Send_Reminder'] = 'clientadmin/reminder';
$route['report'] = 'Clientadmin/clientwiese';
$route['employeewise'] = 'Clientadmin/employeewise';
$route['duedatewise'] = 'Clientadmin/duedatewise';
$route['helpclient'] = 'Clientadmin/helpclient';
$route['future'] = 'Clientadmin/future';
$route['allot_duedate_task'] = 'Due_date/allot_duedate_task';


//Employee as per gold beries navigation

$route['Employee_help'] = 'Employee_user/help_employee';
$route['view_stickynote'] = 'Employee_user/view_sticky';
$route['Send_Reminder'] = 'Employee_user/Send_Reminder';
$route['Employee_Report'] = 'Employee_user/report';
$route['Create_Sticky_Note'] = 'Employee_user/Create_Sticky_Note';
$route['Employee_Reply_Sticky_Note'] = 'Employee_user/Reply_Sticky_Note';
$route['Employee_Task_Enquiry'] = 'Employee_user/Task_Enquiry';
$route['Employee_Due_Date_Enquiry'] = 'Employee_user/Due_Date_Enquiry';
$route['Employee_Data_Submission'] = 'Employee_user/Data_Submission';
$route['Employee_Survey_Emp'] = 'Employee_login/survey';
$route['Employee_Reports'] = 'Graphs_emp/index';
// cronJob Controller
$route['billing_cycle_cron'] = 'Cron_data/billing_cycle_cron';
$route['leave_update_cron'] = 'Cron_data/addEmployeeMonthlyLeave';


$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Excel testingexcel"] = 'ExcelController/employee_excel';
$route["employee_excel_formate"] = 'ExcelController/employee_excel';
$route["upload_employee"] = 'ExcelController/upload_employee';
$route["create_type_subtype_read"] = 'ExcelController/create_type_subtype_read';
$route["Typesubtype_excel_formate"] = 'ExcelController/create_type_subtypeexcel';
$route["scale_values_read"] = 'ExcelController/scale_values_read';
$route["question_excel"] = 'ExcelController/question_excel';
$route["question_read"] = 'ExcelController/question_read';
$route["create_scale_rating_excel"] = 'ExcelController/create_scale_rating_excel';

$route["test_excel"] = 'ExcelController';

$route["service_offering_excel_formate"] = 'ExcelController/create_service_offering_excel';
$route["upload_service_offering"] = 'ExcelController/upload_service_offering';
$route["task_sub_task_excel_formate"] = 'ExcelController/create_task_sub_checklist_excel';
$route["upload_task_sub_task"] = "ExcelController/upload_task_sub_task_checklist";

// personnal assignment
$route["personnal_assignment"] = "PersonnelAssigmentController/load_personnal_assignment";
$route["getJunior"] = "PersonnelAssigmentController/getJuniorOptions";
$route["getSenor"] = "PersonnelAssigmentController/getJuniorOptions";
$route["getAssignment"] = "PersonnelAssigmentController/get_personnelAssigment_details";
$route["createAssignment"] = "PersonnelAssigmentController/assignmentCreate";
$route["getResponseOfAssignment"] = "PersonnelAssigmentController/getResponseOfAssignment";

$route["addWorkDetails"] = "PersonnelAssigmentController/work_assignment";
$route["addWorkRejectionDetails"] = "PersonnelAssigmentController/reject_work";
$route["addAssignRejectionDetails"] = "PersonnelAssigmentController/reject_assignment";

$route["accept_assignment"] = "PersonnelAssigmentController/accept_assignment";
$route["accept_assignment_work"] = "PersonnelAssigmentController/accept_work";
$route["accept_assignment_work_and_assignment"] = "PersonnelAssigmentController/accept_assignment_and_work";
$route["unread_assigenement_notification"] = "PersonnelAssigmentController/unread_assigenement";
$route["getAssignmentById"] = "PersonnelAssigmentController/getAssignmentById";
$route["markasread"] = "PersonnelAssigmentController/markAsRead";
$route["getholidays"] = "PersonnelAssigmentController/getWeekend_Holidays";
//customer
$route["Customer_verification"] = "Customer_controller/index";

$route["Customer_details"] = "Customer_controller/customer_panel";

// template Project
$route["template_project"] = 'TemplateProjectController';
$route["template_project_create"] = 'TemplateProjectController/template_project';
$route["template_project_list"] = 'TemplateProjectController/getProjectList';
$route["getTemplateList"] = 'TemplateProjectController/getTemplatesOfFirm';
$route["getFirms"] = "TemplateProjectController/getFirms";
$route["getTaskByFirm"] = "TemplateProjectController/getTask";
$route["getCustomer"] = "TemplateProjectController/getCustomer";
$route["createTask"] = "TemplateProjectController/createTask";
$route["getTemplateTask"] = "TemplateProjectController/getTemplateTask";
$route['view_template_task_project'] = 'TemplateProjectController/view_template_task';
$route['approve_deny_report_template'] = 'TemplateProjectController/report_template';
$route["getEmployeeOptions"] = "TemplateProjectController/getEmployeeOptions";
$route["getSubtask"] = "TemplateProjectController/getSubtaskBox";
$route["getSubtaskTable"] = "TemplateProjectController/getSubtaskTask";
$route["deleteTemplateTask"] = "TemplateProjectController/deleteTask";
$route["createProject"] = "TemplateProjectController/createProject";
$route["getUserTasks"] = "TemplateProjectController/getUserTasks";
$route["getConventerTasks"] = "TemplateProjectController/getConventerTasks";
$route["getApprovalTasks"] = "TemplateProjectController/getApprovalTask";
$route["getSignTasks"] = "TemplateProjectController/getSignTask";
$route["getSignTasksTransaction"] = "TemplateProjectController/getSignProjectTransaction";
$route["getApprovalTasksTransaction"] = "TemplateProjectController/getApprovalProjectTransaction";
$route["demo_database"] = "Nas/delete_auth";


//HR new navigation
$route["calendar"] = "DashboardController";
$route["branch_sal_info"] = "SalaryInfoController";
$route["serviceRequest"] = "ServiceRequestController";
$route["MonthlyReport"] = "ServiceRequestController/MonthlyReport";

//bhava view employee in HR
$route["add_performance_allowance"] = "Employee/add_performance_allowance";
$route["add_loan"] = "Employee/add_loan";
$route["add_salary"] = "Employee/add_salary";
$route["add_due"] = "Employee/add_due";
$route["profile"] = "UserProfile";
$route['form16'] = "Form16";
$route['form16new'] = "Form16/form16new";
$route['form_16_creation'] = "Form16/form_16_creation";
$route['form_16_submission'] = "Form16/form_16_submission";

//nrendra routes
$route['RegisterApp'] = 'RegisterController/CheckUserExistance';
$route['CheckOtp'] = 'RegisterController/CheckOptValid';
$route['Login'] = 'RegisterController/Login';
$route['getUser'] = 'RegisterController/getUserDetails';

$route["otp/(:any)/(:any)"] = "Login/otp/$1/$2";

$route['get_attendance'] = 'Employee/GetAttendanceInfo';
$route['runpayroll'] = 'Runpayroll';
$route['form16ip'] = 'Form16/form16input';
$route['htmltopdf/(:any)/(:any)/(:any)'] = 'Runpayroll/downlodpdf/$1/$2/$3';
$route['login_api/(:any)'] = 'Login/login_api/$1';
$route['login_from_rmt/(:any)'] = 'Login/login_from_rmt/$1';
$route['otp_new'] = 'Login/otp_page';
$route['get_latestOtp'] = 'employee/get_latestOtp';
$route['getOTP'] = 'Employee/getOTP';
$route['run_past_salary/(:any)'] = 'SalaryInfoController/run_past_salary/$1';


$route['getEmployeeList'] = 'Human_resource/getEmployeeList';
$route['getEmployeeFirms'] = 'Employee/getEmployeeFirms';
$route['employeeAttendanceCron'] = 'Leave_management/employeeAttendanceCron';
//----schedule task  || TIME Sheet----------
$route['scheduleTask'] = 'TimesheetController/schedule_task_new';


//----------Assets Management ----------------//
$route['Assets/add_assets_details'] = 'Assets/add_assets_details';
$route['Assets/update_assets_details'] = 'Assets/update_assets_details';
$route['assetsDetails'] = 'Assets/assetsDetails';
$route['changeAssetStatus'] = 'Assets/changeAssetStatus';
$route['deleteAssets'] = 'Assets/deleteAssets';
$route['getAssetData'] = 'Assets/getAssetData';
$route['create_asset_type'] = 'Assets/createAssetType';
$route['create_sub_asset_type'] = 'Assets/createSubAssetType';
$route['get_asset_type_record'] = 'Assets/getAssetTypeRecord';
$route['get_sub_asset_type_record'] = 'Assets/getSubAssetTypeRecord';

$route['get_update_asset_type_record'] = 'Assets/getUpdateAssetTypeRecord';
$route['get_update_sub_asset_type_record'] = 'Assets/getUpdateSubAssetTypeRecord';
$route['show_asset_data'] = 'Assets/showAssetData';


//----------Word Report --------------
$route['word_report']="WordReportController/word_report";
$route['getReportTableList']="WordReportController/getReportTableList";
$route['addNewBMR']='WordReportController/addNewBMR';
$route['getBMRList']='WordReportController/getBMRList';
$route['report_list/(:any)/(:any)']='WordReportController/index/$1/$2';
$route['getPagesList']='WordReportController/getWordReportMakerData';
$route['saveHtmlTemplate']='WordReportController/saveHtmlTemplate';
$route['getPageDataToEditor']='WordReportController/getPageDataToEditor';
$route['all_bmr_report_view/(:any)/(:any)']='WordReportController/all_bmr_report_view/$1/$2';
$route['getAllBMRReport']='WordReportController/getAllBMRReport';
$route['fetch_templatesReportData']='WordReportController/fetch_templatesReportData';
$route['getColumnNames']='WordReportController/getColumnNames';
$route['uploadInvoiceForm']='WordReportController/uploadInvoiceForm';
$route['getInvoiceTemplates']='WordReportController/getInvoiceTemplates';
$route['getInvoiceToCustomer']='WordReportController/getInvoiceToCustomer';
$route['getInvoiceCreationTable']='WordReportController/getInvoiceCreationTable';
$route['saveReportPageData']='WordReportController/saveReportPageData';
$route['bmrReport/(:any)/(:any)/(:any)/(:any)/(:any)']='WordReportController/bmrReport/$1/$2/$3/$4/$5';
$route['getCompanylist']='WordReportController/getCompanylist';
$route['saveTallyCompany']='WordReportController/saveTallyCompany';
$route['getTallyCompanyData']='WordReportController/getTallyCompanyData';
$route['DeleteTallyCompanyData']='WordReportController/DeleteTallyCompanyData';
$route['getCompanyListOption']='WordReportController/getCompanyListOption';
$route['saveTallyBranch']='WordReportController/saveTallyBranch';
$route['getTallyBranchData']='WordReportController/getTallyBranchData';
$route['DeleteTallyBranchData']='WordReportController/DeleteTallyBranchData';
$route['getBranchListOption']='WordReportController/getBranchListOption';
$route['getBranchlist']='WordReportController/getBranchlist';
$route['changeStatus']='WordReportController/changeStatus';

//-----------show salary slip report--
$route['salary_report/(:any)/(:any)/(:any)'] = 'WordReportController/salary_report/$1/$2/$3';
$route['fetch_salaryReportData'] = 'WordReportController/fetch_salaryReportData';
//$route['salary_report/(:any)/(:any)/(:any)'] = 'Runpayroll/downlodpdf/$1/$2/$3';


$route['setLeaveConfigCron']='Employee/setLeaveConfigCron';

$route['task_project_report']='TimesheetController/task_project_report';
$route['myTaskListOption']='TimesheetController/myTaskListOption';
$route['getProjectsByTask']='TimesheetController/getProjectsByTask';



$route['get_actual_punch_in_time'] = 'DashboardController/getActualPunchTime';
$route['email_shoot_if_user_not_login'] = 'DashboardController/emailShootIfUserNotLoginAfterNoon';

// $route['show_user_pdf/(:any)'] = 'UserProfile/showUserPdf/$1';

$route["greeting_activity"] = "Human_resource/getGrettingWhenEmployeeBirthDayOrOtherActivity";
$route["addNotification"] = "Human_resource/addNotification";
$route["special_days"] = "Human_resource/specialDays";
$route["get_notification"] = "Human_resource/getNotification";
$route["create_notification"] = "Human_resource/addNotification";
$route["employee_monthly_leave_carry_forword"] = "Cron_data/addEmployeeMonthlyLeaveCarryForword";
$route["show_user_pdf"] = "UserProfile/showUserPdf";

$route["upload_company_policy_pdf"] = "UserProfile/uploadUserPdf";
$route["generate_employee_details"] = "Employee/downloadEmployeeDetails";

$route['hr_show_holiday'] = 'Human_resource/Show_holiday_details';
$route['hr_holiday'] = 'Human_resource/hr_holiday';
$route["get_holiday"] = "Human_resource/getHoliday";
$route["holiday_event_data"] = "Human_resource/editHolidayEventData";
$route["delete_holiday_event_record"] = "Human_resource/deleteRecord";
$route["ticket_login_by_payroll"] = "Login/ticketLoginByPayroll";
$route["logs"] = "Api/get_log";
$route["getSSMInfo"] = "Api/getSSMInfo1";


$route["ticket_login_by_payroll"] = "Login/ticketLoginByPayroll";
$route["ticket"] = "TicketController/index";
$route["ticket/list"] = "TicketController/getListMessages";
$route["ticket/fetch_email_from_webmail"] = "TicketController/fetchEmailFromWebmail";
$route["ticket/get_conversation"] = "TicketController/getConversation";


