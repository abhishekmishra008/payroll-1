<?php
    $this->load->view('human_resource/navigation');
    defined('BASEPATH') OR exit('No direct script access allowed');
    if ($session = $this->session->userdata('login_session') == '') {
        redirect(base_url() . 'login');
    }
    $username = $user['user_name'];
    $user_id = ($user['user_id']);
    $emp_id = ($user['emp_id']);
    $user_type = ($user['user_type']);
?>
<link href="<?php echo base_url() . "assets/"; ?>global/css/mystyle1.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css" rel="stylesheet">
<style>
    #holidayTableId tbody tr td {
        background-color: inherit !important;
    }

    .well-lg.table_well_padding {
        padding: 40px 10px !important;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<style>
    span.error {
        color: red;
    }
    .tabbable-custom>.nav-tabs>li.active>a {
        font-weight: bold;
    }

</style>

<input type="hidden" id="user_type" value="<?php echo $user_type; ?>">
<div class="page-content-wrapper">
    <div class="page-content">
	<div class="page-bar">
            <div class="page-toolbar">
                <ul class="page-breadcrumb">
                    <li class="<?= ($this->uri->segment(1) === 'calendar') ? 'active' : '' ?>">
                        <a href="<?= base_url() ?>calendar">Home</a>
                        <i class="fa fa-arrow-right" style="font-size: 10px;margin: 0 5px;position: relative;top: -1px; opacity: .4;"></i>
                    </li>
                    <li>
                        <a href="#"><?php echo $prev_title; ?></a>
                        <i class="fa fa-circle" style="font-size: 5px; margin: 0 5px; position: relative;top: -3px; opacity: .4;"></i>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-md-12 ">
            <div class="row wrapper-shadow">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption font-red-sunglo">
                                    <i class="icon-settings font-red-sunglo"></i>
                                    <span class="caption-subject bold uppercase">Holidays / Events</span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row well-lg table_well_padding" id="table_well">
                                    <div class="tabbable-custom ">
                                        <ul class="nav nav-tabs ">
                                            <li class="active">
                                                <a class="section-tab" href="#holidayTab" data-toggle="tab" data-tab="holiday-section" id="holiday_tab_id"> Holidays</a>
                                            </li>
                                            <li>
                                                <a class="section-tab" href="#eventTab" data-toggle="tab" data-tab="event-section" id="event_tab_id">Events </a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="holidayTab">
                                                <div class="button-group" style="margin-bottom: 20px; float: right;">
                                                    <?php if ($user_type == 1 || $user_type == 3 || $user_type == 5) { ?>
                                                        <button class="btn btn-primary" data-toggle="modal" data-target="#openAddHolidayModal" data-type="singleHolidayCreate"> <i class="fa fa-plus"></i> Single Create Holiday</button>
                                                        <button class="btn btn-primary download-sample" data-id="holidayId" data-url="<?= base_url('Human_resource/sampleExcelDownload') ?>" onclick="downloadSample(this)"> Sample Holiday </button>
                                                        <button class="btn btn-primary upload-model" data-type="holiday" data-upload-url="<?= base_url('Human_resource/uploadHolidayEventExcel') ?>" onclick="showImportModal(this)">Upload Holiday</button>
                                                        <button class="btn btn-primary" data-type="holidayBulkUpload" onclick="deleteData(this)">Undo Holiday</button>
                                                    <?php } else {
                                                        ?>
                                                    <?php }
                                                    ?>
                                                </div>
                                                <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="holidayTableId" role="grid" aria-describedby="sample_1_info" style="text-align:center;">
                                                    <thead>
                                                        <tr role="row">
                                                            <th style="width: 140%;">Holiday</th>
                                                            <th style="width: 20%;">Start Date</th>
                                                            <th style="width: 20%;">End Date</th>
                                                            <th style="width: 20%;">Holiday Day</th>
                                                            <th style="width: 20%;">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane" id="eventTab">
                                                <div class="actions">
                                                    <?php if($user_type==5) { ?>
                                                        <div class="button-group" style="margin-bottom: 20px; float: right;">
                                                            <button class="btn btn-primary" data-toggle="modal" data-target="#eventModalId" data-type="signleEventCreate"> <i class="fa fa-plus"></i> Single Event</button>
                                                            <button class="btn btn-primary" data-id="eventId" data-url="<?= base_url('Human_resource/sampleEventExcelDownload') ?>" onclick="downloadSample(this)">Sample Event</button>
                                                            <button class="btn btn-primary upload-model" data-type="event" data-upload-url="<?= base_url('Human_resource/uploadHolidayEventExcel') ?>" onclick="showImportModal(this)">Upload Event</button>
                                                            <button class="btn btn-primary" data-type="eventBulkUpload" onclick="deleteData(this)">Undo Event</button>
                                                        </div>
                                                        <br><br>
                                                    <?php } else { ?>
                                                    <?php } ?>
                                                </div>
                                                <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="eventTableId" role="grid" aria-describedby="sample_1_info" style="text-align:center;">
                                                    <thead>
                                                        <tr role="row">
                                                            <th style="width: 180%;">Event Name</th>
                                                            <th style="width: 20%;">Event Type</th>
                                                            <th style="width: 20%;">Start Date</th>
                                                            <th style="width: 20%;">Show Until</th>
                                                            <th style="width: 20%;">Event During</th>
                                                            <th style="width: 20%;">Message</th>
                                                            <th style="width: 20%;">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php $this->load->view('human_resource/footer'); ?>
        <script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    </div>
</div>


<!-- Start Holiday -->
    <div class="modal fade" id="openAddHolidayModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="openAddHolidayModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div style="display: flex;justify-content: space-between;">
                        <h5 class="modal-title" id=""><b>Add Holiday</b></h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" style="border: 0;">&times;</button>
                    </div>
                </div>
                <div class="">
                    <div class="col-md-12" style="margin-top: 2rem;">
                        <input type="hidden" id="holiday_section" value="holiday">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="message">Firm Location</label>
                                <select id="holiday_firm" name="firm_id" class="form-control select2" >
                                    <?php if(!empty($firms)) { ?>
                                        <?php foreach($firms as $firm) { ?>
                                            <option value="<?= $firm->firm_id ?>">
                                                <?= $firm->firm_id ?>
                                            </option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="message">Holiday Date</label>
                                <input name="holiday_start_date" id="holiday_date" type="date" class="form-control" rows="4" placeholder="Enter holiday date">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="holiday_color_code">Holiday Color</label>
                                <select id="holiday_color_code" name="holiday_color_code" class="form-control select2">
                                    <option value="#1ABC9C" style="background-color:#1ABC9C; color:#ffffff;">Turquoise</option>
                                    <option value="#F39C12" style="background-color:#F39C12; color:#000000;">Orange</option>
                                    <option value="#FF5733" style="background-color:#FF5733; color:#ffffff;">Red Orange</option>
                                    <option value="#8E44AD" style="background-color:#8E44AD; color:#ffffff;">Purple</option>
                                    <option value="#E74C3C" style="background-color:#E74C3C; color:#ffffff;">Red</option>
                                    <option value="#27AE60" style="background-color:#27AE60; color:#ffffff;">Green</option>
                                    <option value="#16A085" style="background-color:#16A085; color:#ffffff;">Dark Turquoise</option>
                                    <option value="#34495E" style="background-color:#34495E; color:#ffffff;">Dark Blue Gray</option>
                                    <option value="#2ECC71" style="background-color:#2ECC71; color:#000000;">Emerald Green</option>
                                    <option value="#229954" style="background-color:#229954; color:#ffffff;">Forest Green</option>
                                    <option value="#7D3C98" style="background-color:#7D3C98; color:#ffffff;">Deep Purple</option>
                                    <option value="#33FF57" style="background-color:#33FF57; color:#000000;">Light Green</option>
                                    <option value="#1F618D" style="background-color:#1F618D; color:#ffffff;">Royal Blue</option>
                                    <option value="#D68910" style="background-color:#D68910; color:#000000;">Golden Brown</option>
                                    <option value="#566573" style="background-color:#566573; color:#ffffff;">Slate Gray</option>
                                    <option value="#CB4335" style="background-color:#CB4335; color:#ffffff;">Dark Red</option>
                                    <option value="#AF7AC5" style="background-color:#AF7AC5; color:#000000;">Lavender</option>
                                    <option value="#F4D03F" style="background-color:#F4D03F; color:#000000;">Yellow</option>
                                    <option value="#3357FF" style="background-color:#3357FF; color:#ffffff;">Bright Blue</option>
                                    <option value="#EC7063" style="background-color:#EC7063; color:#000000;">Light Red</option>
                                    <option value="#DC7633" style="background-color:#DC7633; color:#000000;">Dark Orange</option>
                                    <option value="#48C9B0" style="background-color:#48C9B0; color:#000000;">Aqua</option>
                                    <option value="#C0392B" style="background-color:#C0392B; color:#ffffff;">Crimson</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="holiday_color_code">Holiday Name</label>
                                <select id="holiday_name" name="holiday_name" class="form-control select2">
                                    <option value="New Year Day">New Year Day</option>
                                    <option value="Makar Sankranti / Pongal">Makar Sankranti / Pongal</option>
                                    <option value="Republic Day">Republic Day</option>
                                    <option value="Maha Shivratri">Maha Shivratri</option>
                                    <option value="Holi">Holi</option>
                                    <option value="Id-ul-Fitr*">Id-ul-Fitr*</option>
                                    <option value="Mahavir Jayanti">Mahavir Jayanti</option>
                                    <option value="Good Friday">Good Friday</option>
                                    <option value="Buddha Purnima">Buddha Purnima</option>
                                    <option value="Id-ul-Zuha (Bakrid)*">Id-ul-Zuha (Bakrid)*</option>
                                    <option value="Muharram*">Muharram*</option>
                                    <option value="Independence Day">Independence Day</option>
                                    <option value="Id-e-Milad*">Id-e-Milad*</option>
                                    <option value="Ganesh Chaturthi">Ganesh Chaturthi</option>
                                    <option value="Gandhi Jayanti">Gandhi Jayanti</option>
                                    <option value="Dussehra (Vijayadashami)">Dussehra (Vijayadashami)</option>
                                    <option value="Naraka Chaturdasi">Naraka Chaturdasi</option>
                                    <option value="Diwali">Diwali</option>
                                    <option value="Govardhan Puja">Govardhan Puja</option>
                                    <option value="Bhai Dooj">Bhai Dooj</option>
                                    <option value="Chhath Puja">Chhath Puja</option>
                                    <option value="Guru Nanak Jayanti">Guru Nanak Jayanti</option>
                                    <option value="Christmas">Christmas</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="holiday_day">Holiday Days</label>
                                <select id="holiday_day" name="holiday_day" class="form-control select2">
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Saturday">Saturday</option>
                                    <option value="Sunday">Sunday</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="message">Active Location</label>
                                <select id="holiday_active_location" name="is_active" class="form-control select2">
                                    <option value="1">Show</option>
                                    <option value="0">Hide</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="holiday_image">Holiday Description</label>
                                <textarea name="description" id="holiday_description" type="text" class="form-control" rows="4" placeholder="Enter holiday image"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer mt-5">
                    <button class="btn btn-primary" data-type="singleHolidayCreate" onclick="submitEventHoldiayData(event)">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="openEditHolidayModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="openEditHolidayModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div style="display: flex;justify-content: space-between;">
                        <h5 class="modal-title" id=""><b>Edit Holiday</b></h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" style="border: 0;">&times;</button>
                    </div>
                </div>
                <div class="">
                    <div class="col-md-12" style="margin-top: 2rem;">
                        <input type="hidden" id="edit_holiday_section" value="holiday">
                        <input type="hidden" name="id" id="edit_holiday_id">
                        <input type="hidden" name="boss_id" id="edit_boss_id">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="message">Firm Location</label>
                                <select id="edit_holiday_firm" name="firm_id" class="form-control select2" >
                                    <?php if(!empty($firms)) { ?>
                                        <?php foreach($firms as $firm) { ?>
                                            <option value="<?= $firm->firm_id ?>">
                                                <?= $firm->firm_id ?>
                                            </option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="message">Holiday Date</label>
                                <input name="holiday_start_date" id="edit_holiday_date" type="date" class="form-control" rows="4" placeholder="Enter holiday date">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="holiday_color_code">Holiday Color</label>
                                <select id="edit_holiday_color_code" name="holiday_color_code" class="form-control select2">
                                    <option value="#1ABC9C" style="background-color:#1ABC9C; color:#ffffff;">Turquoise</option>
                                    <option value="#F39C12" style="background-color:#F39C12; color:#000000;">Orange</option>
                                    <option value="#FF5733" style="background-color:#FF5733; color:#ffffff;">Red Orange</option>
                                    <option value="#8E44AD" style="background-color:#8E44AD; color:#ffffff;">Purple</option>
                                    <option value="#E74C3C" style="background-color:#E74C3C; color:#ffffff;">Red</option>
                                    <option value="#27AE60" style="background-color:#27AE60; color:#ffffff;">Green</option>
                                    <option value="#16A085" style="background-color:#16A085; color:#ffffff;">Dark Turquoise</option>
                                    <option value="#34495E" style="background-color:#34495E; color:#ffffff;">Dark Blue Gray</option>
                                    <option value="#2ECC71" style="background-color:#2ECC71; color:#000000;">Emerald Green</option>
                                    <option value="#229954" style="background-color:#229954; color:#ffffff;">Forest Green</option>
                                    <option value="#7D3C98" style="background-color:#7D3C98; color:#ffffff;">Deep Purple</option>
                                    <option value="#33FF57" style="background-color:#33FF57; color:#000000;">Light Green</option>
                                    <option value="#1F618D" style="background-color:#1F618D; color:#ffffff;">Royal Blue</option>
                                    <option value="#D68910" style="background-color:#D68910; color:#000000;">Golden Brown</option>
                                    <option value="#566573" style="background-color:#566573; color:#ffffff;">Slate Gray</option>
                                    <option value="#CB4335" style="background-color:#CB4335; color:#ffffff;">Dark Red</option>
                                    <option value="#AF7AC5" style="background-color:#AF7AC5; color:#000000;">Lavender</option>
                                    <option value="#F4D03F" style="background-color:#F4D03F; color:#000000;">Yellow</option>
                                    <option value="#3357FF" style="background-color:#3357FF; color:#ffffff;">Bright Blue</option>
                                    <option value="#EC7063" style="background-color:#EC7063; color:#000000;">Light Red</option>
                                    <option value="#DC7633" style="background-color:#DC7633; color:#000000;">Dark Orange</option>
                                    <option value="#48C9B0" style="background-color:#48C9B0; color:#000000;">Aqua</option>
                                    <option value="#C0392B" style="background-color:#C0392B; color:#ffffff;">Crimson</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="holiday_color_code">Holiday Name</label>
                                <select id="edit_holiday_name" name="holiday_name" class="form-control select2">
                                    <option value="New Year Day">New Year Day</option>
                                    <option value="Makar Sankranti / Pongal">Makar Sankranti / Pongal</option>
                                    <option value="Republic Day">Republic Day</option>
                                    <option value="Maha Shivratri">Maha Shivratri</option>
                                    <option value="Holi">Holi</option>
                                    <option value="Id-ul-Fitr*">Id-ul-Fitr*</option>
                                    <option value="Mahavir Jayanti">Mahavir Jayanti</option>
                                    <option value="Good Friday">Good Friday</option>
                                    <option value="Buddha Purnima">Buddha Purnima</option>
                                    <option value="Id-ul-Zuha (Bakrid)*">Id-ul-Zuha (Bakrid)*</option>
                                    <option value="Muharram*">Muharram*</option>
                                    <option value="Independence Day">Independence Day</option>
                                    <option value="Id-e-Milad*">Id-e-Milad*</option>
                                    <option value="Ganesh Chaturthi">Ganesh Chaturthi</option>
                                    <option value="Gandhi Jayanti">Gandhi Jayanti</option>
                                    <option value="Dussehra (Vijayadashami)">Dussehra (Vijayadashami)</option>
                                    <option value="Naraka Chaturdasi">Naraka Chaturdasi</option>
                                    <option value="Diwali">Diwali</option>
                                    <option value="Govardhan Puja">Govardhan Puja</option>
                                    <option value="Bhai Dooj">Bhai Dooj</option>
                                    <option value="Chhath Puja">Chhath Puja</option>
                                    <option value="Guru Nanak Jayanti">Guru Nanak Jayanti</option>
                                    <option value="Christmas">Christmas</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="holiday_day">Holiday Days</label>
                                <select id="edit_holiday_day" name="holiday_day" class="form-control select2">
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Saturday">Saturday</option>
                                    <option value="Sunday">Sunday</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="message">Active Location</label>
                                <select id="edit_holiday_active_location" name="is_active" class="form-control select2">
                                    <option value="1">Show</option>
                                    <option value="0">Hide</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="holiday_image">Holiday Description</label>
                                <textarea name="description" id="edit_holiday_description" type="text" class="form-control" rows="4" placeholder="Enter holiday image"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer mt-5">
                    <button class="btn btn-primary" data-type="singleHolidayUpdate" onclick="submitEventHoldiayData(event)">Submit</button>
                </div>
            </div>
        </div>
    </div>
<!-- End Holiday -->



<!-- Start Event Modal -->
    <div class="modal fade" id="eventModalId" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="eventModalIdLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div style="display: flex;justify-content: space-between;">
                        <h5 class="modal-title" id=""><b>Create Event</b></h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" style="border: 0;">&times;</button>
                    </div>
                </div>
                <div class="">
                    <div class="col-md-12" style="margin-top: 2rem;">
                        <div class="col-md-6">
                        <div class="form-group">
                                <label for="event_type">Firm Location</label>
                                <select id="event_firm_id" name="firm_id" class="form-control">
                                    <option value="" selected disabled>Select Firm Location</option>
                                    <option value="Firm_1012">Firm_1012</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Event Name</label>
                                <input name="title" id="eventName" type="text" class="form-control" rows="4" placeholder="Enter event name">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="event_type">Event Type</label>
                                <select id="event_type" name="event_type" class="form-control">
                                    <option value="" selected disabled>Select Event Type</option>
                                    <option value="Company Annual Meet">Company Annual Meet</option>
                                    <option value="Holiday Celebration">Holiday Celebration</option>
                                    <option value="Employee Appreciation Day">Employee Appreciation Day</option>
                                    <option value="Work Anniversary Celebration">Work Anniversary Celebration</option>
                                    <option value="Welcome New Joiners">Welcome New Joiners</option>
                                    <option value="Birthday Bash">Birthday Bash</option>
                                    <option value="Other Special Event">Other Special Event</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="message"></label>
                                <select id="event_hour" name="event_hour" class="form-control">
                                    <option value="" selected disabled>Select Event Hours</option>
                                    <option value="Full Day">Full Day</option>
                                    <option value="Half Day - Morning">Half Day - Morning</option>
                                    <option value="Half Day - Afternoon">Half Day - Afternoon</option>
                                    <option value="2 Hours">2 Hours</option>
                                    <option value="3 Hours">3 Hours</option>
                                    <option value="4 Hours">4 Hours</option>
                                    <option value="5 Hours">5 Hours</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="event_date">Start Date</label>
                                <input name="start_date" id="start_date" type="text" onfocus="flatPicker(this)" class="form-control" rows="4" placeholder="Enter event start date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="message">Show Until Date</label>
                                <input name="show_from" id="end_date" type="text" onfocus="flatPicker(this)" class="form-control" rows="4" placeholder="Enter event show from">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <label for="message">Message</label>
                            <textarea name="event_note" id="event_note" class="form-control" rows="4" placeholder="Enter message"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer mt-5">
                    <button class="btn btn-primary" data-type="signleEventCreate" onclick="submitEventHoldiayData(event)">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editEventModalId" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="editEventModalIdLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div style="display: flex;justify-content: space-between;">
                        <h5 class="modal-title" id=""><b>Edit Event</b></h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" style="border: 0;">&times;</button>
                    </div>
                </div>
                <div class="">
                    <div class="col-md-12" style="margin-top: 2rem;">
                        <input type="hidden" name="id" id="edit_event_id">
                        <input type="hidden" id="edit_section" value="event">
                        <input type="hidden" name="firm_id" id="edit_firm_id">
                        <input type="hidden" name="user_id" id="edit_event_user_id">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Event Name</label>
                                <input name="title" id="editEventName" type="text" class="form-control" rows="4" placeholder="Enter notification title">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="event_type">Event Type</label>
                                <select id="edit_event_type" name="event_type" class="form-control">
                                    <option value="" selected disabled>Select Event Type</option>
                                    <option value="Company Annual Meet">Company Annual Meet</option>
                                    <option value="Holiday Celebration">Holiday Celebration</option>
                                    <option value="Employee Appreciation Day">Employee Appreciation Day</option>
                                    <option value="Work Anniversary Celebration">Work Anniversary Celebration</option>
                                    <option value="Welcome New Joiners">Welcome New Joiners</option>
                                    <option value="Birthday Bash">Birthday Bash</option>
                                    <option value="Other Special Event">Other Special Event</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="event_date">Start Date</label>
                                <input name="start_date" id="edit_start_date" type="text" class="form-control" rows="4" placeholder="Enter notification title">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="message">Show Until</label>
                                <input name="show_from" id="edit_end_date" type="text" class="form-control" rows="4" placeholder="Enter notification show from">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="message"></label>
                                <select id="edit_event_hour" name="event_hour" class="form-control">
                                    <option value="" selected disabled>Select Event Hours</option>
                                    <option value="Full Day">Full Day</option>
                                    <option value="Half Day - Morning">Half Day - Morning</option>
                                    <option value="Half Day - Afternoon">Half Day - Afternoon</option>
                                    <option value="2 Hours">2 Hours</option>
                                    <option value="3 Hours">3 Hours</option>
                                    <option value="4 Hours">4 Hours</option>
                                    <option value="5 Hours">5 Hours</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <label for="message">Message</label>
                            <textarea name="event_note" id="edit_event_note" class="form-control" rows="4" placeholder="Enter message"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer mt-5">
                    <button class="btn btn-primary" data-type="signleEventUpdate" onclick="submitEventHoldiayData(event)">Submit</button>
                </div>
            </div>
        </div>
    </div>
<!-- End Events Modal -->


<!-- Start Excel Upload Model -->
    <div class="modal fade" id="excel_import_modal" role="dialog" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="excel_import_modal_lebel" aria-hidden="true">
        <div class="modal-dialog modal-half">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" style="font-weight: 600;">Upload Excel</h4>
                </div>
                <div class="modal-body">
                    <div class="row JustifyCenter">
                        <form id="excel_form_upload" enctype="multipart/form-data">
                            <div class="col-md-1"></div>
                            <div class="col-md-8">
                                <input type="file" name="excel_input_file" id="excel_input_file" class="form-control" placeholder="Choose Excel File" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary" onclick="sampleExcelUpload()">Upload</button>
                            </div>
                        </form>
                    </div>
                    <div class="row JustifyCenter"></div>
                </div>
            </div>
        </div>
    </div>
<!-- End Excel Upload Model -->

<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>js/comman.js" type="text/javascript"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
<script>
    // Start Holiday / Event Model
        $('#holiday_firm').select2({
            dropdownParent: $('#openAddHolidayModal'),
            placeholder: "Select firm location",
            allowClear: true,
            width: '100%'
        });
        $('#holiday_day').select2({
            dropdownParent: $('#openAddHolidayModal'),
            placeholder: "Select holiday day",
            allowClear: true,
            width: '100%'
        });
        $('#holiday_name').select2({
            dropdownParent: $('#openAddHolidayModal'),
            placeholder: "Select holiday name",
            allowClear: true,
            width: '100%'
        });
        $('#holiday_color_code').select2({
            dropdownParent: $('#openAddHolidayModal'),
            placeholder: "Select holiday color code",
            allowClear: true,
            width: '100%'
        });
        $('#holiday_active_location').select2({
            dropdownParent: $('#openAddHolidayModal'),
            placeholder: "Select active holiday location",
            allowClear: true,
            width: '100%'
        });


        $('#edit_holiday_firm').select2({
            dropdownParent: $('#openEditHolidayModal'),
            placeholder: "Select firm location",
            allowClear: true,
            width: '100%'
        });
        $('#edit_holiday_day').select2({
            dropdownParent: $('#openEditHolidayModal'),
            placeholder: "Select holiday day",
            allowClear: true,
            width: '100%'
        });
        $('#edit_holiday_name').select2({
            dropdownParent: $('#openEditHolidayModal'),
            placeholder: "Select holiday name",
            allowClear: true,
            width: '100%'
        });
        $('#edit_holiday_color_code').select2({
            dropdownParent: $('#openEditHolidayModal'),
            placeholder: "Select holiday color code",
            allowClear: true,
            width: '100%'
        });
        $('#edit_holiday_active_location').select2({
            dropdownParent: $('#openEditHolidayModal'),
            placeholder: "Select active holiday location",
            allowClear: true,
            width: '100%'
        });

        $(document).ready(function() {   
            loadTableBySection('holiday-section');
            $('.section-tab').on('click', function () {
                let section = $(this).data('tab');
                loadTableBySection(section);
            });

            function loadTableBySection(section) {
                let editUrl = "<?= base_url('holiday_event_data') ?>";
                if($.fn.DataTable.isDataTable('#holidayTableId')) {
                    $('#holidayTableId').DataTable().destroy();
                }
                if ($.fn.DataTable.isDataTable('#eventTableId')) {
                    $('#eventTableId').DataTable().destroy();
                }

                if(section === 'holiday-section') {
                    $('#holidayTableId').DataTable({
                        "processing": true,
                        "serverSide": false,
                        "ajax": {
                            "url": "<?= base_url('Human_resource/getHolidayEventList') ?>",
                            "type": "POST",
                            "dataSrc": function(json) {
                                currentUserType = json.userType;
                                return json.data;
                            },
                            "data": {
                                "type": section
                            }
                        },
                        "columns": [
                            { "data": "holiday_name" },
                            { "data": "holiday_start_date" },
                            { "data": "holiday_end_date" },
                            { "data": "holiday_day" },
                            {
                                "render": function (data, type, row) {
                                    if(currentUserType == 1 || currentUserType == 3 || currentUserType == 5) { // Only show action buttons for admin, HR, and manager
                                        return `
                                            <button class="btn btn-xs btn-primary"
                                                data-id="${row.id}"
                                                data-type="singleHolidayDelete"
                                                onclick='openEditHolidayModal(${(JSON.stringify(row))})'>
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button"
                                                class="btn btn-xs btn-danger"
                                                data-type="singleHolidayDelete"
                                                data-id="${row.id}"
                                                onclick="deleteData(this)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        `;
                                    } else{
                                        return `<span class="text-muted">No actions</span>`;
                                    }
                                }
                            }
                        ],
                        "order": [[1, "asc"]],
                        "pageLength": 10,
                        // SET ROW COLOR BASED ON holiday_color_code
                        // "rowCallback": function (row, data) {
                        //     if (data.holiday_color_code) {
                        //         $(row).css("background-color", data.holiday_color_code);
                        //         $('td', row).css("background-color", data.holiday_color_code);
                        //     }
                        // }
                    });
                } else if(section === 'event-section') {
                    $('#eventTableId').DataTable({
                        "processing": true,
                        "serverSide": false,
                        "ajax": {
                            "url": "<?= base_url('Human_resource/getHolidayEventList') ?>",
                            "type": "POST",
                            "dataSrc": function(json) {
                                currentUserType = json.userType;
                                return json.data;
                            },
                            "data": {
                                "type": section
                            }
                        },
                        "columns": [
                            { "data": "event_details" },
                            { "data": "event_type" },
                            { "data": "start_date" },
                            { "data": "end_date" },
                            { "data": "event_hour" },
                            { "data": "event_note" },
                            {
                                "render": function (data, type, row) {
                                    if(currentUserType == 1 || currentUserType == 3 || currentUserType == 5) { // Only show action buttons for admin, HR, and manager
                                        return `
                                            <button class="btn btn-xs btn-primary"
                                                data-type="signleEventEditCreate"
                                                data-id="${row.id}"
                                                onclick='openEditEventModal(${JSON.stringify(row)})'>
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button"
                                                class="btn btn-xs btn-danger"
                                                data-type="singleEventDelete"
                                                data-id="${row.id}"
                                                onclick="deleteData(this)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        `;
                                    } else {
                                        return `<span class="text-muted">No actions</span>`;
                                    }
                                }
                            }
                        ],
                        "order": [[1, "asc"]],
                        "pageLength": 10,
                    });
                }
            }

        })
    // End Holiday / Event Model

    // Start Edit Holiday/Event Model
        function openEditHolidayModal(row) {
            $("#edit_holiday_id").val(row.id);
            $("#edit_holiday_firm").val(row.firm_id).trigger('change');
            $("#edit_holiday_day").val(row.holiday_day).trigger('change');
            $("#edit_holiday_name").val(row.holiday_name).trigger('change');
            $("#edit_holiday_color_code").val(row.holiday_color_code).trigger('change');
            $("#edit_holiday_active_location").val(row.edit_holiday_active_location).trigger('change');
            $("#edit_holiday_description").val(row.description);
            $("#edit_holiday_date").val(row.holiday_start_date);
            $('#openEditHolidayModal').modal('show');
        }


        function openEditEventModal(row) {
            $('#edit_event_id').val(row.id); // Store event ID
            $('#edit_firm_id').val(row.firm_id);
            $('#edit_event_user_id').val(row.user_id);
            $('#editEventName').val(row.event_details);
            $("select#edit_event_type").val(row.event_type);
            $('#edit_start_date').val(row.start_date);
            $('#edit_end_date').val(row.end_date);
            $('select#edit_event_hour').val(row.event_hour);
            $('#edit_event_note').val(row.event_note);
            $('#editEventModalId').modal('show');
        }


        function submitEventHoldiayData(event, el) {
            event.preventDefault();
            let payload = {};
            let section = $(event.currentTarget).data('type');
            let eventId = $('#edit_event_id').val();
            let holidayId = $('#edit_holiday_id').val();
            // if(eventId == '' || eventId == null) {
            //     swalMessage("Error", "Holiday record not found.", "error");
            // }
            // if(holidayId == '' || holidayId == null) {
            //     swalMessage("Error", "Holiday record not found.", "error");
            // }
            if(holidayId) {
                payload = {
                    id: id,
                    firm_id: $('#edit_holiday_firm').val(),
                    edit_holiday_day: $('#edit_holiday_day').val(),
                    edit_holiday_name: $('#edit_holiday_name').val(),
                    edit_holiday_description: $('#edit_holiday_description').val(),
                    edit_holiday_date: $('#edit_holiday_date').val(),
                    edit_holiday_color_code: $('#edit_holiday_color_code').val(),
                    edit_holiday_active_location: $('#edit_holiday_active_location').val(),
                    section: section
                };
            } else if(eventId) {
                payload = {
                    id: $("#edit_event_id").val(),
                    edit_user_id: $("#edit_event_user_id").val(),
                    edit_firm_id: $('#edit_firm_id').val(),
                    edit_event_details: $('#editEventName').val(),
                    event_type: $('select#edit_event_type').val(),
                    edit_start_date: $('#edit_start_date').val(),
                    edit_end_date: $('#edit_end_date').val(),
                    edit_event_hour: $('select#edit_event_hour').val(),
                    edit_event_note: $('#edit_event_note').val(),
                    section: section
                };
            } else {
                if(section == 'singleHolidayCreate') {
                    payload = {
                        firm_id: $('#holiday_firm').val(),
                        holiday_day: $('#holiday_day').val(),
                        holiday_name: $('#holiday_name').val(),
                        holiday_description: $('#holiday_description').val(),
                        holiday_date: $('#holiday_date').val(),
                        holiday_color_code: $('#holiday_color_code').val(),
                        holiday_active_location: $('#holiday_active_location').val(),
                        section: section
                    };
                } else if(section == "signleEventCreate") {
                    payload = {
                        section: section,
                        end_date: $('#end_date').val(),
                        firm_id: $('#event_firm_id').val(),
                        event_type: $('#event_type').val(),
                        start_date: $('#start_date').val(),
                        event_hour: $('#event_hour').val(),
                        event_note: $('#event_note').val(),
                        event_details: $('#eventName').val()
                    };
                }
            }
            
            $.ajax({
                type: "POST",
                url: "<?= base_url('holiday_event_data') ?>",
                data: {
                    payload: payload
                },
                success: function () {
                    swalMessage("Success", "Event saved successfully.", "success");
                    $('.modal').modal('hide');
                },
                error: function () {
                    swalMessage("Error", "Something went wrong.", "error");
                }
            });
        }


        function deleteData(element) {
            let type = element.getAttribute('data-type');
            let id = element.getAttribute('data-id')
            Swal.fire({
                title: 'Are you sure?',
                text: 'This record will be deleted!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "<?= base_url('delete_holiday_event_record') ?>",
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: id,
                            type: type
                        },
                        success: function (response) {
                            swalMessage('Deleted!', response.message, 'success')
                            $('#eventTable').DataTable().ajax.reload();
                        },
                        error: function () {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        }
    // End Edit Holiday/Event Model
</script>

