<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    if ($session = $this->session->userdata('login_session') == '') {
    //take them back to signin
        redirect(base_url() . 'login');
    }
    $this->load->view('human_resource/navigation');
    $empId = $user['user_id'];
    $userId = $user['emp_id'];
    $userType = $user['user_type'];
    $userName = $user['user_name'];

?>
<link href="<?php echo base_url() . "assets/"; ?>global/css/mystyle1.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />


<div class="page-content-wrapper">
    <div class="page-content" style="min-height: 660px;">
        <div class="row">
            <div class="col-md-12">
                <div class="row wrapper-shadow">
                    <div class="portlet light portlet-fit portlet-form ">
                        <div class="portlet-title">
                            <div class="caption font-red-sunglo">
                                <i class="icon-settings font-red-sunglo"></i>
                                <span class="caption-subject bold uppercase">Ticket Generation</span>
                            </div>
                            <div class="actions">
                                <div class="btn-group right">
                                    <butto id="ticket_type_id"  data-toggle="modal" data-ticket_type_id="0" data-target="#TicketType" class="btn blue-hoki btn-outline sbold uppercase popovers" data-container="body" data-trigger="hover" data-placement="left" data-content="Generate ticket">Ticket Type</button>
                                </div>

                                <div class="btn-group right">
                                    <butto id="sample_1_new"  data-toggle="modal" data-ticket_type_id="0" data-target="#TicketGeneration" class="btn blue-hoki btn-outline sbold uppercase popovers" data-container="body" data-trigger="hover" data-placement="left" data-content="Generate ticket">Create Ticket</button>
                                </div>

                                <div class="btn-group right">
                                    <a href="#" title="" target="_blank" onclick="goToTicketEngine()" class="btn blue-hoki btn-outline sbold uppercase popovers">
                                        <div><i class="fa fa-link"></i></div>
                                    </a> 
                                </div>
                                <div class="btn-group">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="portlet-title">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-hover  dtr-inline dt-responsive dataTable no-footer dtr-inline collapsed" role="grid" style="width:100%;" id="ticket_generated_table">
                                        <thead>
                                            <tr>
                                                <th >Ticket Id</th>
                                                <th >Sender Name</th>
                                                <th >Subject</th>
                                                <th >Date</th>
                                                <th >Status</th>
                                                <th >Handled By</th>
                                                <th >Priority</th>
                                                <th >Department</th>
                                                <th >Chat</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->load->view('human_resource/footer'); ?>
    </div>
</div>

<!-- Generate New Ticket Type Model -->
<div class="modal fade" id="TicketType" tabindex="-1">
    <div class="modal-dialog modal-half">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="text-decoration:bold;">Ticket Type</h4>
            </div>
            <div class="modal-body">
                <form id="ticket_type" enctype="multipart/form-data">
					<input type="hidden" id="email_id" name="email_id" value="<?= $empId ?>">
                    <div class="form-group"> <!-- Config Email -->
                        <label>Parent Ticket Type</label>
                        <div class="input-group">
                            <span class="input-group-addon"> <i class="fa fa-envelope"></i> </span>
                            <select name="requester_email_id" id="parentTicketType" class="form-control select2">
                                <option selected disabled value="">Select Employee Email</option>
                                <?php 
                                    $types = ['Technical Support', 'IT Infrastructure', 'Development Related', 'HR Related', 'Admin / Office', 'Finance Related', 'Customer Support'];
                                ?>
                                <?php foreach($types as $type) { ?>
                                    <option value="<?= $type ?>"><?= $type ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Sub Ticket Type</label>
                        <div class="input-group">
                            <span class="input-group-addon"> <i class="fa fa-envelope"></i> </span>
                            <select name="bcc_email_id" id="subTicketType" class="form-control select2">
                                <?php 
                                    $subTypes = ['Software Issue', 'Hardware Issue', 'System Crash', 'Application Error', 'Network Issue', 'Login Issue', 'Printer Issue', 'Email Issue', 'Server Down', 'Database Issue', 'Backup Failure', 'VPN Access', 'Firewall Issue', 'Domain Issue', 'Bug Fix', 'New Feature Request', 'Enhancement', 'API Issue', 'Performance Optimization', 'UI Fix', 'Leave Request', 'Salary Issue', 'Attendance Correction', 'ID Card Request', 'Document Verification', 'Asset Request (Laptop, Mouse etc.)', 'Maintenance Request', 'Cleaning Request', 'Access Card Issue', 'Meeting Room Booking', 'Reimbursement', 'Invoice Issue', 'Payment Delay', 'Vendor Payment', 'Complaint', 'Query', 'Refund Request', 'Product Issue', 'Service Issue', 'Other'];
                                ?>
                                <option selected disabled value="">Select BCC Email</option>
                                <?php foreach($subTypes as $user) { ?>
                                    <option value="<?= $user ?>"><?= $user ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="message" id="type_description" rows="3" class="form-control" placeholder="Enter description"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="submitTicketForm(event)"> Save </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Generate New Ticket Model -->
<div class="modal fade" id="TicketGeneration" tabindex="-1">
    <div class="modal-dialog modal-half">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="text-decoration:bold;">Ticket Generation</h4>
            </div>
            <div class="modal-body">
                <form id="ticket_generation" enctype="multipart/form-data">
					<input type="hidden" id="email_id" name="email_id" value="<?= $empId ?>">
					<input type="hidden" id="ticket_id" name="ticket_id">
					<input type="hidden" id="message_id" name="message_id">
					<input type="hidden" id="referance_id" name="referance_id">
					<input type="hidden" id="status_id" name="status_id" value="0">
                    <?php if($userType == 5) { ?>
                        <div class="form-group"> <!-- Config Email -->
                            <label>Config Email</label>
                            <div class="input-group">
                                <span class="input-group-addon"> <i class="fa fa-envelope"></i> </span>
                                <select name="requester_email_id" id="configEmail" class="form-control select2">
                                    <option selected disabled value="">Select Employee Email</option>
                                    <?php foreach($active_users as $user) { ?>
                                        <option value="<?= $user->user_id ?>"><?= $user->email ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if($userType == 5) { ?>
                        <div class="form-group"> <!-- Config Email -->
                            <label>BCC Email</label>
                            <div class="input-group">
                                <span class="input-group-addon"> <i class="fa fa-envelope"></i> </span>
                                <select name="bcc_email_id" id="bccEmail" class="form-control select2">
                                    <option selected disabled value="">Select BCC Email</option>
                                    <?php foreach($active_users as $user) { ?>
                                        <option value="<?= $user->user_id ?>"><?= $user->email ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    <?php } ?>
                    
                    <div class="form-group"> <!-- Priorty Type -->
                        <label>Priorty Type</label>
                        <div class="input-group">
                            <span class="input-group-addon"> <i class="fa fa-list"></i> </span>
                            <select name="priority" id="priority_status" class="form-control select2" >
                                <option selected disabled></option>
                                <option value="1">Low</option>
                                <option value="2">Medium</option>
                                <option value="3">High</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group"> <!-- Ticket Type -->
                        <label>Ticket Type</label>
                        <div class="input-group">
                            <span class="input-group-addon"> <i class="fa fa-list"></i> </span>
                            <select name="type_name" id="type_name" class="form-control select2" >
                            </select>
                        </div>
                    </div>

                    <div class="form-group"> <!-- Config Email -->
                        <label>Select Departments</label>
                        <div class="input-group">
                            <span class="input-group-addon"> <i class="fa fa-building"></i> </span>
                            <select name="ticket_department_id" id="ticket_department_id" class="form-control select2" >
                                <?php if(!empty($departments)) { ?>
                                    <option selected disabled value="">Select Department</option>
                                    <?php foreach($departments as $department) { ?>
                                        <option value="<?= $department->id ?>"><?= $department->department ?></option>
                                    <?php } ?>
                                <?php } else { ?>
                                    <option selected disabled value="">No Department Available</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group"> <!-- Subject -->
                        <label>Enter Subject</label>
                        <div class="input-group">
                            <span class="input-group-addon"> <i class="fa fa-file-text"></i> </span>
                            <input type="text" name="new_sub" id="new_sub" class="form-control" >
                        </div>
                    </div>

                    <div class="form-group"> <!-- Work Description -->
                        <label>Work Description</label>
                        <textarea name="message" id="message" rows="3" class="form-control" placeholder="Enter work"></textarea>
                    </div>

                    <div class="form-group"> <!-- Attachment -->
                        <label>Attach File</label>
                        <input type="file" name="userfile[]" multiple class="form-control">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="submitTicketForm(event)"> Save </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reply Message with fetch messages from -->
<div class="modal fade bd-example-modal-lg" id="replayMessageModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-l">
		<div class="modal-content">
			<div class="modal-header mt-4">
				<button type="button" class="close" data-dismiss="modal">&times;</button> Description
                <div class="col-md-12" style="margin-top: 2rem;">
                    <div class="col-md-4">
                        <select name="ticketPriority" id="ticketPriority" onchange="setTicketPriority()"  class="form-control select2">
                            <option selected disabled>Priority</option>
                            <option value="1">Low</option>
                            <option value="2">Medium</option>
                            <option value="3">High</option>
                            <option value="4">Critical</option>
                        </select>
                    </div>
                    <div class="col-md-4"></div>
                    <div id="" class="col-md-4">
                        <select name="" id="" class="form-control select2" onchange="changeTicketStatus(this.value)">
                            <option selected disabled>Select Action</option>
                            <option value="accept" class="btn btn-success"> Accept </option>
                            <option value="reject" class="btn btn-danger"> Reject </option>
                            <option value="reopen" class="btn btn-info"> Reopen </option>
                            <option value="close_ticket" class="confirmLink btn btn-danger">  Close Ticket </option>
                        </select>
                    </div>
                </div>
			</div>
			<?php if ($userType == 8) { ?>
				<div class="" id="userDropdown" class="mx-auto w-100">
					<select class="form-control userDropdown1" name="userDropdown1" onchange="send_mail_to_user(1)">
					</select>
				</div>
			<?php } ?>
			<div class="modal-body">
				<form id="ticket_typ_form" name="ticket_typ_form" method="post">
					<input type="hidden" id="subject" name="subject">
					<input type="hidden" id="email_id" name="email_id">
					<input type="hidden" id="requester_name" name="requester_name">
					<input type="hidden" id="ticket_id" name="ticket_id">
					<input type="hidden" id="message_id" name="message_id">
					<input type="hidden" id="referance_id" name="referance_id">
					<input type="hidden" id="reply_id" name="reply_id">
					<input type="hidden" id="status_id" name="status_id" value="0">
					<input type="hidden" id="forw_email" name="forw_email" value="0">
					<input type="hidden" id="forw_emailId" name="forw_emailId" value="0">
					<input type="hidden" id="newMsg" name="newMsg">
					<input type="hidden" id="original_sub" name="original_sub">
					<input type="hidden" id="user_list" name="user_list">
                    <div id="attachment_list"></div>
					<div class="" id="userDropdown_new" class="mx-auto w-100">
						<select name="user_name" id="user_name_id" class="form-control userDropdown1 select2">
                            <option selected disabled value="">Select User</option>
                            <?php foreach($active_users as $user) { ?>
                                <option value="<?= $user->user_id ?>"><?= $user->user_name ?></option>
                            <?php } ?>
						</select>
					</div>
                    <br>
					<div class="chat-wrapper" id="message_list" style="overflow: auto; height: 300px;"> </div>
                    <br>
                    <div class="chat-input-area">
                        <textarea id="reply_message" class="form-control border-0 shadow-none" placeholder="Type your message..." rows="1" style=""></textarea>
                        <label for="reply_file" class="btn btn-light mb-0 ml-2">📎</label>
                        <input type="file" name="attachment[]" id="reply_file" hidden>
                        <button type="button" onclick="sendReply()" class="btn btn-primary ml-2"> ➤ </button>
                    </div>
				</form>
			</div>
		</div>
	</div>
</div>



<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery-notific8/jquery.notific8.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>js/comman.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        loadTickets();
        function getAllDepartment() {
			$.ajax({
				url: '<?= base_url("ticket/get_fetch_department") ?>',
				type: 'post',
				dataType: 'Json',
				processing: false,
				contentType: false,
				success: function (resp) {
					if (resp.status = 200) {
						$("#ticket_department_id").html('');
						$("#ticket_department_id").html(resp.data);
					} else {
						$("#ticket_department_id").html('');
						$("#ticket_department_id").html(resp.data);
					}
				}
			});
		}

        function loadTickets() {
            $('#ticket_generated_table').DataTable({
                "processing": true,
                "serverSide": false,
                "ajax": {
                    "url": "<?= base_url('ticket/list') ?>",
                    "type": "POST",
                    "dataSrc": "tickets"
                },
                "columns": [
                    { "data": "ticket_id" },
                    { "data": "requester_name" },
                    { "data": "original_sub" },
                    { "data":
                        function (data, type, row) {
                            var date = new Date(data.date);
                            var day = String(date.getDate()).padStart(2, '0');
                            var month = date.toLocaleString('default', { month: 'long' });
                            var year = date.getFullYear();
                            return day + ' ' + month + ' ' + year;
                        }
                    },
                    { "data": 
                        function (data, type, row) {
                            if(data.status == 0) {
                                return '<span class="mb-2 mr-2 badge badge-pill badge-info">Pending</span>';
                            } else if(data.status == 3) {
                                return '<span class="mb-2 mr-2 badge badge-pill badge-danger">Rejected</span>';
                            } else if(data.status == 2) {
                                return '<span class="mb-2 mr-2 badge badge-pill badge-secondary">Closed</span>';
                            } else if(data.status == 1) {
                                return '<span class="mb-2 mr-2 badge badge-pill badge-success">Accepted</span>';
                            } else {
                                return '<span class="mb-2 mr-2 badge badge-pill badge-default">Unknown</span>';
                            }
                        }
                     },
                    { "data": 
                        function (data, type, row) {
                            if(data.priority == 6) {
                                return '<span class="mb-2 mr-2 badge badge-pill badge-success">Low</span>';
                            } else {
                                return '<span class="mb-2 mr-2 badge badge-pill badge-default"></span>';
                            }
                        }
                    },
                    { "data": 
                        function (data, type, row) {
                            if(data.priority == 1) {
                                return '<span class="mb-2 mr-2 badge badge-pill badge-success">Low</span>';
                            } else if(data.priority == 2) {
                                return '<span class="mb-2 mr-2 badge badge-pill badge-warning">Medium</span>';
                            } else if(data.priority == 3) {
                                return '<span class="mb-2 mr-2 badge badge-pill badge-danger">High</span>';
                            } else {
                                return '<span class="mb-2 mr-2 badge badge-pill badge-default">Unknown</span>';
                            }
                        }
                    },
                    { "data": "department" },
                    {
                        "render": function (data, type, row) {
                            return `<button class="btn btn-primary btn-sm viewBtn"
                                        onclick='replayMessageModal(${JSON.stringify(row)})'>
                                        <i class="fa fa-eye"></i>
                                    </button>`;
                        }
                    }
                ],
                "order": [[1, "asc"]],
                "pageLength": 10,
            });
        }

        $('#configEmail').select2({
            dropdownParent: $('#TicketGeneration'),
            placeholder: "Select Configuration Email",
            allowClear: true,
            width: '100%'
        });

        $('#bccEmail').select2({
            dropdownParent: $('#TicketGeneration'),
            placeholder: "Select BCC Email",
            allowClear: true,
            width: '100%'
        });

        $('#priority_status').select2({
            dropdownParent: $('#TicketGeneration'),
            placeholder: "Select Priority Level",
            allowClear: true,
            width: '100%'
        });

        $('#type_name').select2({
            dropdownParent: $('#TicketGeneration'),
            placeholder: "Select Ticket Type",
            allowClear: true,
            width: '100%'
        });

        $('#ticket_department_id').select2({
            dropdownParent: $('#TicketGeneration'),
            placeholder: "Select Requester Department",
            allowClear: true,
            width: '100%'
        });

        $('#user_name_id').select2({
            dropdownParent: $('#replayMessageModal'),
            placeholder: "Select Requester Department",
            allowClear: true,
            width: '100%'
        });
        
        $('#parentTicketType').select2({
            dropdownParent: $('#TicketType'),
            placeholder: "Select parent ticket type",
            allowClear: true,
            width: '100%'
        });

        $('#subTicketType').select2({
            dropdownParent: $('#TicketType'),
            placeholder: "Select sub ticket type",
            allowClear: true,
            width: '100%'
        });

    });

    document.getElementById('#reply_file').addEventListener('change', function(e) {
        let file = e.target.files[0]
        if(file && file.type.startWith('image/')) {
            let render = new FileReader();
            alert('image');
            reader.onload = function(e) {
                alert("onload");
            }
        }
    })


    function submitTicketForm(e) {
        e.preventDefault();
        let formData = new FormData(document.getElementById('ticket_generation'));
        let data = [];
        formData.forEach((value, key) => {
            data.push({ name: key, value: value });
        });
        console.log(data);
        
       
    }

    function setTicketPriority() {
        swalMessage("Success", "Hello priority", true);
    }


    function changeTicketStatus(id) {

    }

    // $("#ticket_generation").on("submit", function(event) {
    //     event.preventDefault();
    //         let fields = [
    //         { id: '#configEmail', message: 'Please select employee email' },
    //         { id: '#type_name', message: 'Please select ticket type' },
    //         { id: '#ticket_department_id', message: 'Please select department' },
    //         { id: '#new_sub', message: 'Please enter subject' },
    //         { id: '#message', message: 'Please enter work description' }
    //     ];

    //     for (let field of fields) {
    //         let value = $(field.id).val();
    //         if (!value || value.trim() === '') {
    //             swalMessage('Error', field.message, 'error');
    //             $(field.id).focus();
    //             return;
    //         }
    //     }

    //     let files = $('input[type="file"]')[0].files;
    //     if (files.length === 0) {
    //         swalMessage('Error', 'Please attach at least one file', 'error');
    //         return;
    //     }
    //     alert("All validations passed. Form can be submitted.");
    // });


    function replayMessageModal(row) {
        console.log(row);
        $('#subject').val(row.subject);
        $('#email_id').val(row.requester_email_id);
        $('#requester_name').val(row.requester_name);
        $('#ticket_id').val(row.ticket_id);
        $('#message_id').val(row.message_id);
        $('#reference_id').val(row.reference_id);
        $('#reply_id').val(row.reply_id);
        $('#status_id').val(row.status_id);
        $('#forw_email').val(row.forw_email);
        $('#forw_emailId').val(row.forw_emailId);
        $("input[name='userfile[]']")[0].files = row.files;
        $('select#user_name_id').val(row.user_name);
        $('#newMsg').val(row.new_msg);
        $('#original_sub').val(row.original_sub);
        $('#user_list').val(row.user_list);
        $('#message').val(row.message);
        $('#attachment').val(row.attachment);
        if(row.files) {
            $('#attachment_list').html(`<a href="${row.files}" target="_blank">Download Attachment</a>`);
        }
        loadConversation(row.ticket_id);
        $('#replayMessageModal').modal('show');
    }

    function loadConversation(ticketId) {
        $.ajax({
            url: '<?= base_url("ticket/fetch_email_from_webmail") ?>',
            type: 'post',
            data: { 
                ticket_id: ticketId 
            },
            dataType: 'json',
            success: function (resp) {
                if(resp.status == 200) {
                    let html = '';
                    resp.messages.forEach(msg => {
                        let isSupport = msg.sender_id == 'support';
                        let align = isSupport ? 'right' : 'left';
                        let bgColor = isSupport ? '#d1e7dd' : '#f8d7da';
                        let borderRadius = isSupport ? '20px 20px 0px 20px' : '20px 20px 20px 0px';  // left bubble
                        html += `
                                <div style="text-align:${align}; margin-bottom:15px;">
                                    <div style="display:inline-block; max-width:70%; padding:12px 16px; border-radius:${borderRadius}; background:${bgColor}; word-wrap:break-word; ">
                                        <p style="margin:0; font-weight:bold; color:#0d6efd;">
                                            ${msg.from}
                                        </p>   
                                        <div>
                                            <p style="margin:5px 0;">${msg.message}</p>
                                            <small style="color:#6c757d;">${msg.timestamp}</small>
                                        </div>
                                    </div>
                                </div>
                        `;
                    });

                    $('#message_list').html(html);
                    scrollToBottom();
                } else {
                    $('#message_list').html('<p>No messages found.</p>');  
                }
            }
        });
    }

    function scrollToBottom() {
        let div = document.getElementById("message_list");
        div.scrollTop = div.scrollHeight;
    }

    function sendReply(){
        let message = $('#reply_message').val();
        let ticket_id = $('#ticket_id').val();
        let file = $('#reply_file')[0].files[0];
        let formData = new FormData();
        formData.append('ticket_id', ticket_id);
        formData.append('message', message);
        formData.append('file', file);
        $.ajax({
            url: "<?= base_url('ticket/sendReply') ?>",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(res){
                $('#reply_message').val('');
                loadConversation(ticket_id);
            }
        });
    }

</script>
