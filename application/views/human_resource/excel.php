<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>-->
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://rawgit.com/davidstutz/bootstrap-multiselect/master/dist/css/bootstrap-multiselect.css">
<script src="https://rawgit.com/davidstutz/bootstrap-multiselect/master/dist/js/bootstrap-multiselect.js"></script>-->
<?php
// $this->load->view('admin/header');
$this->load->view('human_resource/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin
    redirect(base_url() . 'login');
}
$username = $this->session->userdata('login_session');
?>
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/dropdownjs/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />

<div class="page-content-wrapper">
    <div class="page-content">

        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="#">
                        <?php if ($firm_name_nav !== "") { ?>
                            <!--<h3 class="uppercase"style="font-size:23px;">-->
                            <?php echo $firm_name_nav; ?>

                            <!--</h3>-->
                            <?php
                        } else {
                            echo 'HR';
                        }
                        ?>

                    </a>
                    <i class="fa fa-circle" style="font-size: 5px;margin: 0 5px;position: relative;top: -3px; opacity: .4;"></i>
                </li>
            </ul>
            <div class="page-toolbar">
                <ul class="page-breadcrumb">
                    <li class="<?= ($this->uri->segment(1) === 'hr_dashboard') ? 'active' : '' ?>">
                        <a href="<?= base_url() ?>hr_dashboard">Home</a>
                        <i class="fa fa-circle" style="font-size: 5px;margin: 0 5px;position: relative;top: -3px; opacity: .4;"></i>
                    </li>
                    <li>
                        <a href="#"><?php echo $prev_title; ?></a>
                        <i class="fa fa-circle" style="font-size: 5px; margin: 0 5px; position: relative;top: -3px; opacity: .4;"></i>
                    </li>


                </ul>

            </div>
        </div>
        <h1 class="page-title">
        </h1>
        <div class="row"></div>
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="icon-settings font-red-sunglo"></i>
                    <span class="caption-subject bold uppercase">Link Survey</span>
                    <h6>

                    </h6>
                </div>

                <div class="portlet-body">

                    <form method="post" enctype="multipart/form-data" id="import_excel" name="import_excel">
                        <input type="file" id="excel_file" class="form-control" name="excel_file" required accept=".xls,.xlsx">

                        <br>
                        <button type="button" onclick="load_file();" class="btn btn-primary" id="upload" name="upload">Upload</button>
                    </form>
                    <div id="tbl_data" name="tbl_data">

                    </div>
                </div>
            </div>
        </div>
    </div>



    <?php $this->load->view('human_resource/footer'); ?>

<!--<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>-->
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/dropdownjs/bootstrap-multiselect.js" type="text/javascript"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <!--<script src="<?php echo base_url() . "assets/"; ?>global/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js" type="text/javascript"></script>-->
    <!-- END PAGE LEVEL SCRIPTS -->
    <script>
//                            $(document).ready(function () {
//                                var cnf = confirm("Are sure to call this function");
//
//                                if (cnf) {
//                                    load_data();
//                                } else {
//
//                                }
//                            });
//
//                            function load_data() {
//                                $.ajax({
//                                    url: "<?= base_url("Excel_Upload_hr/fetch") ?>",
//                                    dataType: "json",
//                                    success: function (result) {
//                                        if (result['message'] === 'success') {
//                                            var data = result.data_print;
//                                            $('#tbl_data').html(data);
//                                        }
//                                    }
//                                });
//                            }






                            function load_file() {
                               
                                var formid = document.getElementById("import_excel");

                                $.ajax({
                                    type: "post",
                                    url: "<?= base_url("Excel_Upload_hr/import") ?>",
                                    dataType: "json",
                                    data: new FormData(formid), //form data
                                    processData: false,
                                    contentType: false,
                                    cache: false,
                                    async: false,
                                    success: function (result) {
                                        alert("akshay");
                                            
                                        // alert(result.error);
                                        if (result.status === true) {
                                            var data=result.data_print;
                                            console.log(data);
                                            $('#tbl_data').html(data);
//                                            alert('Data Submitted Successfully');
//                                            // return;
//                                            location.reload();
                                        } else if (result.status === false) {
//                                            alert('something went wrong');

                                        } else {
                                            $('#' + result.id + '_error').html(result.error);
                                            $('#message').html(result.error);
                                           

                                        }

                                    }
                                });

                            }

    </script>
