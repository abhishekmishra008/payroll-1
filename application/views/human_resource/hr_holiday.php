<?php
// $this->load->view('admin/header');
$this->load->view('human_resource/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin
    redirect(base_url() . 'login');
}
$username_array = $this->session->userdata('login_session');

$username = $username_array['user_id'];
//$username = $this->session->userdata('login_session');
?>
<link href="<?php echo base_url() . "assets/"; ?>global/css/mystyle1.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<STYLE>

    a {
        color: #0254EB
    }
    a:visited {
        color: #0254EB
    }
    #header h2 {
        color: white;
        margin:0px;
        padding: 5px;
    }
    .comment {
        width: 300px;
    }
    a.morelink {
        text-decoration:none;
        outline: none;
    }
    .morecontent span {
        display: none;

    }
</STYLE>
<div class="page-content-wrapper">

    <div class="page-content">
        <div class="col-md-12 ">
            <div class="row wrapper-shadow">
                <div class="portlet light portlet-fit portlet-form">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-red"></i>
                            <span class="caption-subject font-red sbold uppercase">Holiday Management</span>
                        </div>
                        <div class="actions">
                            <button type="button" class="btn btn-info btn-simplebtn blue-hoki btn-outline sbold uppercase popovers" data-toggle="modal" data-target="#myModal" data-container="body" data-placement="left" data-trigger="hover" data-content="Add Holiday"><i class="fa fa-plus"></i> </button>

                        </div>
                    </div>
                    <div class="portlet-body">

                        <div class="col-md-4">
                            <label>Office Name
                            </label>

                            <select class="form-control m-select2 m-select2-general" id="ddl_firm_name_fetch" name="ddl_firm_name_fetch" onchange="get_sorted_data()">
                                <option value="">Select option</option>
                            </select>
                            <span class="required" id="ddl_firm_name_fetch_error"></span>   <br>
                        </div>
                        <input type="hidden" id="fetched_firm_id" name="fetched_firm_id" value="<?php echo $fetched_firm_id; ?>">
                        <div class="row well-lg " id="table_well">
                            <table class="table-striped table-bordered table-hover order-column dtr-inline display dataTable no-footer" id="sample_1" role="grid" aria-describedby="sample_1_info">
                                <thead>
                                    <tr role="row">
                                        <!--<th scope="col"> Sr No</th>-->
                                        <th style="text-align: center;" scope="col" style="width:100px !important;">Holiday Name</th>
                                        <!--<th style="text-align: center;" scope="col">Start Date</th>-->
                                        <th style="text-align: center;" scope="col">Date</th>
                                        <!--<th style="text-align: center;" scope="col">End Date</th>-->
                                        <!--<th style="text-align: center;" scope="col">Description</th>-->
                                        <th style="text-align: center;" scope="col">Color</th>
                                        <th style="text-align: center;" scope="col">Image</th>
                                        <!--<th style="text-align: center;">Edit</th>-->
                                        <th style="text-align: center;">Delete</th>

                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
//                                        var_dump($result_event);
                                    if ($result_event !== '') {
                                        foreach ($result_event as $row) {
                                            ?>
                                            <tr>

                                                <td style="text-align: center;"><?php
                                                    echo $row->holiday_name
                                                    ?></td>
                                                <td style="text-align: center;"><?php
                                                    $originalDate = $row->holiday_date;
                                                    $newDate = date("d-m-Y", strtotime($originalDate));
                                                    echo $newDate;
                                                    ?></td>
        <!--                                            <td style="text-align: center;"><?php /*
                                              $originalDate1 = $row->main_date;
                                              $newDate1 = date("d-m-Y", strtotime($originalDate1));
                                              echo $newDate1; */
                                                    ?></td>-->
        <!--                                            <td style="text-align: center;"><?php /*
                                          $originalDate2 = $row->end_date;
                                          $newDate2 = date("d-m-Y", strtotime($originalDate2));
                                          echo $newDate2; */
                                                    ?></td>-->
        <!--                                                    <td class="comment more" style="text-align: center;"><?php /*
                                          if ($row->description !== '') {
                                          echo $row->description;

                                          } else {
                                          echo 'Not Given!';
                                          } */ ?></td>-->
                                                <td class="comment more" style="text-align: center;"><?php
                                                    if ($row->color !== '') {
                                                        echo "<span style='color:" . $row->color . ";' >&#9724;</span>";
                                                    } else {
                                                        echo 'Not Given!';
                                                    }
                                                    ?></td>
                                                <td style="text-align: center;"><?php if ($row->holiday_image !== '') { ?>
                                                        <a href="<?php echo base_url() . 'uploads/gallery/' . $row->holiday_image; ?>" target="_blank"><img width="50px" id="myImg" height="50px" id="event_img"    margin="auto"  padding-left="20px !important"  src="<?php echo base_url() . 'uploads/gallery/' . $row->holiday_image; ?>" alt=""/></a>
                                                        <?php
                                                    } else {
                                                        echo 'Not Given!';
                                                        ?>
                                                        <?php } ?></td>
        <!--<td style="text-align: center;"><a id="sample_new" class="btn blue btn-circle btn-md" data-toggle="modal" data-target="#Edit_event_modal" data-event_id1="<?php echo base64_encode($row->holiday_id); ?>" ><i class="fa fa-pencil"></i></a></td>-->
                                                <td style="text-align: center;"><a id="sample_new" class="btn red btn-circle btn-md" onclick="del_event('<?php echo $row->holiday_id ?>')" ><i class="fa fa-close"></i></a></td>
                                            </tr>

                                        <?php }
                                        ?>

                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->load->view('human_resource/footer'); ?>
        </div>
    </div>
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Holiday</h4>
                </div>
                <div class="modal-body">
                    <div class="form-body">
                        <form id="add_event_form" name="add_event_form" method="post">
                            <input type="hidden" name="hdn_user_id" id="hdn_user_id" value="<?php echo $username ?>">
                            <div class="form-group">
                                <lable>Select Branch</lable>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-bank"></i>
                                    </span>
                                    <select class="form-control select2-multiple"  multiple id="ddl_firm_name" onchange="remove_error('ddl_firm_name')" name="ddl_firm_name[]" >
                                        <option value="">Select Branch</option>
                                    </select>
                                </div>
                                <span class="required" id="ddl_firm_name_error"></span>
                            </div>
                            <div class="form-group">
                                <label>Holiday Name</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                    <input type="text" id="event" name="event" onkeyup="remove_error('event')" class="form-control" placeholder="Event Name">
                                </div>
                                <span class="required" id="event_error"></span>
                            </div>
                            <div class="form-group">
                                <label>Holiday On</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="date" name="main_date" id="main_date"  onchange="remove_error('main_date')"  class="form-control" placeholder=""> </div>
                                <span class="required" id="main_date_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="color" class="col-sm-2 control-label">Color</label>
                                <select name="color" class="form-control">
                                    <option value="">Choose</option>
                                    <option style="color:#0071c5;" value="#0071c5">&#9724; Dark blue</option>
                                    <option style="color:#40E0D0;" value="#40E0D0">&#9724; Turquoise</option>
                                    <option style="color:#008000;" value="#008000">&#9724; Green</option>
                                    <option style="color:#FFD700;" value="#FFD700">&#9724; Yellow</option>
                                    <option style="color:#FF8C00;" value="#FF8C00">&#9724; Orange</option>
                                    <option style="color:#FF0000;" value="#FF0000">&#9724; Red</option>
                                    <option style="color:#000;" value="#000">&#9724; Black</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile1">Upload Image</label>
                                <input type="file" id="image_file"  onchange="remove_error('image_file')" name="image_file">
                                <p class="help-block"> choose image for event. </p>
                                <span class="required" id="image_file_error"></span>
                            </div>
                            <div class="loading" id="loaders8" style="display:none;"></div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="add_event_btn" name="add_event_btn"class="btn btn-info " >Add Holiday</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>

            </div>

        </div>
    </div>

    <div class="modal fade" id="Edit_event_modal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Event</h4>
                </div>
                <div class="modal-body">
                    <form id="edit_event_form" name="edit_event_form" method="post">
                        <input type="hidden" id="event_id1" name="event_id1">
                        <div id="event_data_div" name="event_data_div"></div>
                    </form>

                </div>

                <div class="modal-footer">
                    <button type="button" id="update_event" name="update_event" class="btn btn-info">Update Event</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script>
                                    function get_sorted_data() {

                                        var firm_id_fetch = document.getElementById('ddl_firm_name_fetch').value;
                                        window.location.href = "<?= base_url("/Human_resource/hr_holiday/") ?>" + firm_id_fetch;

                                    }
                                    $(document).ready(function () {
                                        $.ajax({
                                            url: "<?= base_url("/Human_resource/get_ddl_firm_name") ?>",
                                            dataType: "json",
                                            success: function (result) {
                                                if (result['message'] === 'success') {
                                                    var data = result.frim_data;
                                                    $('#boss_id').val(data[0]['reporting_to']);
                                                    var ele3 = document.getElementById('ddl_firm_name_fetch');
                                                    var ele4 = document.getElementById('ddl_firm_name');
                                                    for (i = 0; i < data.length; i++)
                                                    {
                                                        ele4.innerHTML = ele4.innerHTML +
                                                                //'<option value="' + data[i]['firm_id'] + ',' + data[i]['firm_name'] + '">' + data[i]['firm_name'] + '</option>';
                                                                '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';

                                                        ele3.innerHTML = ele3.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
                                                    }
                                                }
                                            }
                                        });
                                    });
                                    $("#add_event_btn").click(function () {
                                        var $this = $(this);
                                        $this.button('loading');
                                        setTimeout(function () {
                                            $this.button('reset');
                                        }, 2000);
                                        document.getElementById('loaders8').style.display = "block";
                                        var formid = document.getElementById("add_event_form");
                                        $.ajax({
                                            type: "post",
                                            url: "<?= base_url("Calendar/add_event_hq") ?>",
                                            dataType: "json",
                                            data: new FormData(formid), //form data
                                            processData: false,
                                            contentType: false,
                                            cache: false,
                                            async: false,
                                            success: function (result) {
                                                if (result.status === true) {

                                                    alert('Event created successfully.');
                                                    document.getElementById('loaders8').style.display = "none";

                                                    location.reload();
                                                } else if (result.status === false) {

                                                    alert('Something went wrong.');
                                                    document.getElementById('loaders8').style.display = "none";

                                                } else {

                                                    $('#' + result.id + '_error').html(result.error);
                                                    $('#message').html(result.error);
                                                    document.getElementById('loaders8').style.display = "none";
                                                }
                                            },
                                            //                                                                error: function (result) {
                                            //                                                                    console.log(result);
                                            //                                                                    if (result.status === 500) {
                                            //                                                                        alert('Internal error: ' + result.responseText);
                                            //                                                                        document.getElementById('loaders8').style.display = "none";
                                            //                                                                    } else {
                                            //                                                                        alert('Unexpected error.');
                                            //                                                                        document.getElementById('loaders8').style.display = "none";
                                            //                                                                    }
                                            //                                                                }
                                        });
                                    });

                                    function del_event(id)
                                    {
                                        var ddl_firm_id = document.getElementById('fetched_firm_id').value;
                                        var event_id = id;
                                        //        alert(ddl_firm_id);return;
                                        var result = confirm("Want to delete this event?");
                                        if (result) {
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("Calendar/delete_event") ?>",
                                                dataType: "json",
                                                data: {event_id: event_id, firm_id: ddl_firm_id},
                                                success: function (result) {
                                                    if (result.status === true) {
                                                        alert('Event Deleted successfully');
                                                        // return;
                                                        location.reload();
                                                    } else {
                                                        alert('something went wrong')
                                                    }
                                                }

                                            });
                                        } else {
                                        }

                                    }

                                    function  remove_error(id) {
                                        $('#' + id + '_error').html("");
                                    }
    </script>