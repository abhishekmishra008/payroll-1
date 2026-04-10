<?php
$this->load->view('human_resource/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin
    redirect(base_url() . 'login');
}
$session_data = $this->session->userdata('login_session');

$data['session_data'] = $session_data;
$user_id = ($session_data['user_id']);
$user_type = ($session_data['user_type']);


$page_name = 'View Calender Holiday';
$page_name2 = 'View Due Date List';
//var_dump($firm_name_dd);
//echo $firm_id_new;
?>

<style>
    span.error {
        color: red;
    }

</style>
<input type="hidden" id="user_type" name="user_type" value="<?php echo $user_type ?>">
<div class="page-fixed-main-content">
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-red-sunglo">
                        <i class="icon-settings font-red-sunglo"></i>
                        <span class="caption-subject bold uppercase">Salary & Deduction  Information</span>
                    </div>

                </div>

                <div class="portlet-body">
                    <div class="tabbable-custom ">
                        <ul class="nav nav-tabs ">
                            <li class="active">
                                <a href="#tab_5_1" data-toggle="tab"> Salary Information </a>
                            </li>
                            <li>
                                <a href="#tab_5_2" data-toggle="tab">Deduction Information</a>
                            </li>

                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_5_1">
                                <form id="add_sal_info" name="add_sal_info" type="post">
                                    <div class="panel-body">
                                        <input type="hidden" name="sal_cnt" id="sal_cnt" value="1">
                                        <input type="hidden" name="id" id="id">

                                        <hr>
                                        <div class="row" id="sal_div1">
                                            <div class="row" id="sal_div">
                                                <div class="col-md-12">
                                                    <div class="col">
                                                        <?php if ($user_type == 2) { ?>

                                                            <div class="col-md-3" id="firm_name_div">

                                                                <div class="form-group">
                                                                    <select name="firm_name" id="firm_name" onchange="get_data_salary();"  class="form-control m-select2 m-select2-general">
                                                                        <option value="">Select Branch</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        <?php } else {
                                                            ?>
                                                            <input type="hidden" id="firm_name" name="firm_name" value="">
                                                        <?php }
                                                        ?>

                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <input name="salary_type[]" id="salary_type1" placeholder="Salary Type *" type="text"class="mb-2 form-control-sm form-control"></div>

                                                    </div>
                                                    <!--                                                    <div class="col-md-3">
                                                                                                            <div class="form-group">
                                                                                                                <select name="valu_percent[]" id="valu_percent" class="aa mb-2 form-control-sm form-control">
                                                                                                                    <option value="">Select Value percent</option>
                                                                                                                </select>
                                                                                                            </div>
                                                                                                        </div>-->
                                                    <!--                                                    <div class="col-md-3">
                                                                                                            <input name="default_value[]" id="default_value1" placeholder="Salary Default Value *" type="number"class="mb-2 form-control-sm form-control"></div>-->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" id="addmore">
                                            <div class="form-group">
                                                <div class="col-md-8">
                                                    <label for="multiple"></label>
                                                    <a id='add-row-1'  onclick="get_html()">
                                                        <i class="fa fa-fw fa-plus"></i><b>Add More</b>
                                                    </a>
                                                </div>
                                            </div><br>
                                        </div>
                                        <div class="col-md-12" id="button_add" ><button class="btn btn-info" type="submit">ADD</button><hr></div>
                                        <div class="col-md-12" id="button_update" style="display: none" ><button class="btn btn-info" onclick="update_sal();" type="button">update</button><hr></div>
                                        <div  id="salary_tablediv">

                                        </div>
                                    </div>


                                </form>
                            </div>
                            <div class="tab-pane" id="tab_5_2">
                                <form id="add_ded_info" name="add_ded_info" type="post">
                                    <div class="panel-body" >
                                        <input type="hidden" name="ded_cnt" id="ded_cnt" value="1">
                                        <input type="hidden" name="id1" id="id1">
                                        <hr>
                                        <div class="row" id="ded_div1">

                                            <div class="row" id="ded_div">
                                                <div class="col-md-12">

                                                    <div class="col">
                                                        <?php if ($user_type == 2) { ?>

                                                            <div class="col-md-3" id="firm_name_div1">
                                                                <div class="form-group">
                                                                    <select name="firm_name1" id="firm_name1" onchange="get_data_deduction();"  class="form-control m-select2 m-select2-general">
                                                                        <option value="">Select Branch</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                        <?php } else {
                                                            ?>
                                                            <input type="hidden" id="firm_name1" name="firm_name1" value="">
                                                        <?php }
                                                        ?>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <input name="deduction_type[]" id="deduction_type1" placeholder="Deduction Type *" type="text"class="mb-2 form-control-sm form-control"></div>

                                                    </div>
                                                    <!--                                                    <div class="col-md-3">
                                                                                                            <div class="form-group">
                                                                                                                <select name="valu_percent_ded[]" id="valu_percent_ded1" class="aa mb-2 form-control-sm form-control">
                                                                                                                    <option value="">Select Value percent</option>
                                                                                                                </select>
                                                                                                            </div>
                                                                                                        </div>-->
                                                    <!--                                                    <div class="col-md-3">
                                                                                                            <input name="default_value_ded[]" id="default_value_ded1" placeholder="Salary Default Value *" type="number"class="mb-2 form-control-sm form-control"></div>-->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" id="addmore1">
                                            <div class="form-group">
                                                <div id="addmore"class="col-md-8" style="display:block">
                                                    <label for="multiple"></label>
                                                    <a id='add-row-2'  onclick="get_html1()">
                                                        <i class="fa fa-fw fa-plus"></i><b>Add More</b>
                                                    </a>
                                                </div>
                                            </div><br>
                                        </div>
                                        <div class="col-md-12" id="button_add1"  ><button  class="btn btn-info" type="submit">ADD</button></div>
                                        <div class="col-md-12" id="button_update1" style="display: none" ><button class="btn btn-info" onclick="update_ded();" type="button">update</button><hr></div>
                                        <hr><div  id="ded_tablediv">
                                        </div>
                                    </div>


                                </form>
                            </div>

                        </div>
                    </div>
                </div>

                <?php $this->load->view('human_resource/footer'); ?>
            </div>




























        </div>



    </div>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script>
                                            $(document).ready(function () {
                                                var $select = $(".aa");
                                                for (i = 1; i <= 100; i++) {
                                                    $select.append($('<option></option>').val(i).html(i));
                                                }
                                                get_firms();

                                                var user_type = document.getElementById("user_type").value;

                                                if (user_type == 2)
                                                {

                                                } else {

                                                    get_data_salary();

                                                    get_data_deduction();
                                                }
                                            });
                                            $("#add_sal_info").validate({//form id
                                                rules: {
                                                    "firm_name": {
                                                        required: true
                                                    },
                                                    "salary_type[]": {
                                                        required: true}
                                                },
                                                errorElement: "span",
                                                submitHandler: function (form) {
                                                    $.ajax({
                                                        url: '<?= base_url("SalaryInfoController/addSalInfo") ?>',
                                                        type: "POST",
                                                        data: $("#add_sal_info").serialize(),
                                                        success: function (success) {
                                                            success = JSON.parse(success);
                                                            if (success.message === "success") {
                                                                alert(success.body);
                                                                $("#add_sal_info")[0].reset();
                                                                get_data_salary();
                                                            } else {
                                                                alert(success.body);
                                                                //  toastr.error(success.body); //toster.error
                                                            }
                                                        },
                                                        error: function (error) {
                                                            toastr.error(success.body);
                                                            console.log(error);
                                                            errorNotify("something went to wrong");
                                                        }
                                                    });
                                                }
                                            }
                                            );
                                            function update_sal()
                                            {
                                                $.ajax({
                                                    url: '<?= base_url("SalaryInfoController/UpadteSalInfo") ?>',
                                                    type: "POST",
                                                    data: $("#add_sal_info").serialize(),
                                                    success: function (success) {
                                                        success = JSON.parse(success);
                                                        if (success.message === "success") {
                                                            alert(success.body);
                                                            location.reload();
                                                            get_data_salary();
                                                        } else {
                                                            alert(success.body);
                                                            //  toastr.error(success.body); //toster.error
                                                        }
                                                    },
                                                    error: function (error) {
                                                        toastr.error(success.body);
                                                        console.log(error);
                                                        errorNotify("something went to wrong");
                                                    }
                                                });
                                            }

                                            function get_firms()
                                            {
                                                $.ajax({
                                                    url: "<?= base_url("/Human_resource/get_ddl_firm_name") ?>",
                                                    dataType: "json",
                                                    success: function (result) {
                                                        if (result['message'] === 'success') {
                                                            var data = result.frim_data;
                                                            var ele3 = document.getElementById('firm_name');
                                                            var ele4 = document.getElementById('firm_name1');
                                                            ele3.innerHTML = "<option value=''>Select Branch</option>";
                                                            ele4.innerHTML = "<option value=''>Select Branch</option>";
                                                            for (i = 0; i < data.length; i++)
                                                            {
                                                                // POPULATE SELECT ELEMENT WITH JSON.
                                                                ele3.innerHTML = ele3.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
                                                                ele4.innerHTML = ele4.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
                                                            }
                                                        }
                                                    }
                                                });
                                            }
                                            function edit_saltype_btn(id)
                                            {
//                                                            document.getElementById("button_add").style.display = "none";
                                                $("#button_add").hide();
                                                $("#button_update").show();
                                                $("#addmore").hide();
                                                $("#firm_name_div").hide();
                                                $.ajax({
                                                    type: "POST",
                                                    url: "<?= base_url("SalaryInfoController/get_sal_details") ?>",
                                                    dataType: "json",
                                                    async: false,
                                                    cache: false,
                                                    data: {id: id},
                                                    success: function (result) {
                                                        var data = result.result;
                                                        if (result.code == 200) {

//                                                                        $("#button_add").hide();
                                                            //  document.getElementById("addmore").style.display = "none";
                                                            $("#salary_type1").val(data['pay_type']);
                                                            $("#valu_percent").val(data['Value_Type']);
                                                            $("#default_value1").val(data['Default_Value']);
                                                            $("#id").val(id);
                                                        } else {

                                                        }
                                                    },
                                                });
                                            }

                                            function edit_dedtype_btn(id)
                                            {
                                                $("#button_add1").hide();
                                                $("#button_update1").show();
                                                $("#addmore1").hide();
                                                $("#firm_name_div1").hide();

                                                $.ajax({
                                                    type: "POST",
                                                    url: "<?= base_url("SalaryInfoController/get_ded_details") ?>",
                                                    dataType: "json",
                                                    async: false,
                                                    cache: false,
                                                    data: {id: id},
                                                    success: function (result) {
                                                        var data = result.result;
                                                        if (result.code == 200) {

//                                                                        $("#button_add").hide();
                                                            //  document.getElementById("addmore").style.display = "none";
                                                            $("#deduction_type1").val(data['deduction']);
                                                            $("#valu_percent_ded1").val(data['value']);
                                                            $("#default_value_ded1").val(data['default_value']);
                                                            $("#id1").val(id);
                                                        } else {

                                                        }
                                                    },
                                                });


                                            }
                                            function get_data_salary()
                                            {
                                                var firm_name = document.getElementById("firm_name").value;
                                                $.ajax({
                                                    type: "POST",
                                                    url: "<?= base_url("SalaryInfoController/get_sal_info") ?>",
                                                    dataType: "json",
                                                    async: false,
                                                    cache: false,
                                                    data: {firm_name: firm_name},
                                                    success: function (result) {
                                                        var data = result.result_sal;
                                                        if (result.status == 200) {
                                                            $('#salary_tablediv').html(data);
                                                            $('#sal_table').DataTable();
                                                        } else {

                                                            $('#salary_tablediv').html(data);
                                                            $('#sal_table').DataTable();
                                                        }
                                                    },
                                                });
                                            }
                                            function get_data_deduction()
                                            {
                                                var firm_name = document.getElementById("firm_name1").value;
                                                $.ajax({
                                                    type: "POST",
                                                    url: "<?= base_url("SalaryInfoController/get_deduction_info") ?>",
                                                    dataType: "json",
                                                    async: false,
                                                    cache: false,
                                                    data: {firm_name: firm_name},
                                                    success: function (result) {
                                                        var data = result.result_sal;
                                                        if (result.status == 200) {
                                                            $('#ded_tablediv').html(data);
                                                            $('#ded_table').DataTable();
                                                        } else {
                                                            $('#ded_tablediv').html("");
                                                        }
                                                    },
                                                });
                                            }
                                            function delete_saltype(id)
                                            {
                                                var result = confirm("Do You Want to delete?");
                                                if (result) {
                                                    $.ajax({
                                                        url: '<?= base_url("SalaryInfoController/delete_saltype") ?>',
                                                        type: "POST",
                                                        data: {id: id},
                                                        cache: false,
                                                        async: false,
                                                        success: function (success) {
                                                            success = JSON.parse(success);
                                                            if (success.status == 200) {
                                                                alert(success.body);
                                                                get_data_salary();
                                                            } else {
                                                                alert(success.body); //toster.error
                                                            }
                                                        },
                                                        error: function (error) {
                                                            console.log(error);
                                                            alert("something went to wrong");
                                                        }
                                                    });
                                                } else {

                                                }

                                            }
                                            function delete_dedtype(id)
                                            {
                                                var result = confirm("Do You Want to delete?");
                                                if (result) {
                                                    $.ajax({
                                                        url: '<?= base_url("SalaryInfoController/delete_dedtype") ?>',
                                                        type: "POST",
                                                        data: {id: id},
                                                        cache: false,
                                                        async: false,
                                                        success: function (success) {
                                                            success = JSON.parse(success);
                                                            if (success.status == 200) {
                                                                alert(success.body);
                                                                get_data_deduction();
                                                            } else {
                                                                alert(success.body); //toster.error
                                                            }
                                                        },
                                                        error: function (error) {
                                                            console.log(error);
                                                            alert("something went to wrong");
                                                        }
                                                    });
                                                } else {

                                                }

                                            }
                                            function get_html()
                                            {

                                                var sal = [1];
                                                var sal_counter = document.getElementById('sal_cnt').value;
                                                if (sal_counter === null) {
                                                    sal_counter = 0;
                                                }


                                                sal_counter++;
                                                sal.push(sal_counter);
                                                var markup = '<div class="row" id="sal_div' + sal_counter + '"><div class="col-md-12">' +
                                                        '<div class="col-md-3">' +
                                                        '<div class="form-group">' +
                                                        '<input name="salary_type[]" id="salary_type' + sal_counter + '" placeholder="Salary Type *"type="text"class="mb-2 form-control-sm form-control"></div>' +
                                                        '</div>' +
//                                                        '<div class="col-md-3">' +
//                                                        '<div class="form-group">' +
//                                                        '<select name="valu_percent[]" id="valu_percent' + sal_counter + '" class="aa mb-2 form-control-sm form-control">' +
//                                                        '<option value="">Select Value percent</option>' +
//                                                        '</select>' +
//                                                        '</div>' +
//                                                        '</div>' +
//                                                        '<div class="col-md-3">' +
//                                                        '<div class="form-group">' +
//                                                        '<input name="default_value[]" id="default_value' + sal_counter + '" placeholder="Salary Default Value *" type="number"class="mb-2 form-control-sm form-control"></div>' +
//                                                        '</div>' +
                                                        "<div class='col-md-3'>" +
                                                        "<button type='button' id='remove" + sal_counter + "' class='btn' name='remove" + sal_counter + "' onclick='customerRemove(" + sal_counter + "," + sal + ")'><i class='fa fa-close font-red'> </i></button>" +
                                                        "</div>" +
                                                        "</div>" +
                                                        "</div>";

                                                $("#sal_div1").append(markup);
                                                $("#add_sal_info").validate();
                                                $('#salary_type' + sal_counter).rules("add", "required");
                                                document.getElementById('sal_cnt').value = sal_counter;
                                                var $select = $(".aa");
                                                for (i = 1; i <= 100; i++) {
                                                    $select.append($('<option></option>').val(i).html(i));
                                                }
                                            }
                                            function get_html1()
                                            {

                                                var sal = [1];
                                                var sal_counter = document.getElementById('ded_cnt').value;
                                                if (sal_counter === null) {
                                                    sal_counter = 0;
                                                }


                                                sal_counter++;
                                                sal.push(sal_counter);
                                                var markup = '<div class="row" id="ded_div' + sal_counter + '"><div class="col-md-12">' +
                                                        '<div class="col-md-3">' +
                                                        '<div class="form-group">' +
                                                        '<input name="deduction_type[]" id="deduction_type' + sal_counter + '" placeholder="Deduction Type *" type="text"class="mb-2 form-control-sm form-control"></div>' +
                                                        '</div>' +
//                                                        '<div class="col-md-3">' +
//                                                        '<div class="form-group">' +
//                                                        '<select name="valu_percent_ded[]" id="valu_percent_ded' + sal_counter + '" class="aa mb-2 form-control-sm form-control">' +
//                                                        '<option value="">Select Value percent</option>' +
//                                                        '</select>' +
//                                                        '</div>' +
//                                                        '</div>' +
//                                                        '<div class="col-md-3">' +
//                                                        '<div class="form-group">' +
//                                                        '<input name="default_value_ded[]" id="default_value_ded' + sal_counter + '" placeholder="Salary Default Value *" type="number"class="mb-2 form-control-sm form-control"></div>' +
//                                                        '</div>' +
                                                        "<div class='col-md-3'>" +
                                                        "<button type='button' id='remove" + sal_counter + "' class='btn' name='remove" + sal_counter + "' onclick='customerRemove1(" + sal_counter + "," + sal + ")'><i class='fa fa-close font-red'> </i></button>" +
                                                        "</div>" +
                                                        "</div>" +
                                                        "</div>";
                                                $("#ded_div1").append(markup);
                                                document.getElementById('ded_cnt').value = sal_counter;
                                                var $select = $(".aa");
                                                for (i = 1; i <= 100; i++) {
                                                    $select.append($('<option></option>').val(i).html(i));
                                                }
                                            }
                                            function customerRemove(id, sal, sal1) {
                                                document.getElementById("add-row-1").style.display = "block";
                                                var aa = [sal, sal1];
                                                var index = aa.indexOf(id);
                                                aa.splice(index, 1);
                                                $("#sal_div" + id).remove();
                                                var temp = id;
                                                var customer_counter = temp--;
                                                $('#salary_type' + temp).attr('name', 'salary_type' + id);
                                                $('#salary_type' + temp).attr('id', 'salary_type' + id);
                                                $('#valu_percent' + temp).attr('name', 'valu_percent' + id);
                                                $('#valu_percent' + temp).attr('id', 'valu_percent' + id);
                                                $('#default_value' + temp).attr('name', 'default_value' + id);
                                                $('#default_value' + temp).attr('id', 'default_value' + id);
                                                document.getElementById('cust_count').value = customer_counter;
                                            }
                                            function customerRemove1(id, sal, sal1) {
                                                document.getElementById("add-row-2").style.display = "block";
                                                var aa = [sal, sal1];
                                                var index = aa.indexOf(id);
                                                aa.splice(index, 1);
                                                $("#ded_div" + id).remove();
                                                var temp = id;
                                                var customer_counter = temp--;
                                                $('#deduction_type' + temp).attr('name', 'deduction_type' + id);
                                                $('#deduction_type' + temp).attr('id', 'deduction_type' + id);
                                                $('#valu_percent_ded' + temp).attr('name', 'valu_percent_ded' + id);
                                                $('#valu_percent_ded' + temp).attr('id', 'valu_percent_ded' + id);
                                                $('#default_value_ded' + temp).attr('name', 'default_value_ded' + id);
                                                $('#default_value_ded' + temp).attr('id', 'default_value_ded' + id);
                                                document.getElementById('sal_cnt').value = customer_counter;
                                            }
                                            $("#add_ded_info").validate({//form id

                                                rules: {
                                                    firm_name1: {required: true},
                                                    "deduction_type[]": {required: true},
                                                    "valu_percent_ded[]": {required: true},
                                                    "default_value_ded[]": {required: true},
                                                },
                                                errorElement: "span",
                                                submitHandler: function (form) {
                                                    $.ajax({
                                                        url: '<?= base_url("SalaryInfoController/addDeductionInfo") ?>',
                                                        type: "POST",
                                                        data: $("#add_ded_info").serialize(),
                                                        success: function (success) {

                                                            success = JSON.parse(success);
                                                            if (success.message === "success") {
                                                                alert(success.body);
                                                                $("#add_ded_info")[0].reset();
                                                                get_data_deduction();
                                                            } else {
                                                                alert(success.body);
                                                                //  toastr.error(success.body); //toster.error
                                                            }
                                                        },
                                                        error: function (error) {
                                                            toastr.error(success.body);
                                                            console.log(error);
                                                            errorNotify("something went to wrong");
                                                        }
                                                    });
                                                }
                                            }
                                            );

                                            function update_ded()
                                            {
                                                $.ajax({
                                                    url: '<?= base_url("SalaryInfoController/UpadteDedInfo") ?>',
                                                    type: "POST",
                                                    data: $("#add_ded_info").serialize(),
                                                    success: function (success) {
                                                        success = JSON.parse(success);
                                                        if (success.message === "success") {
                                                            alert(success.body);
                                                            location.reload();
                                                            get_data_deduction();
                                                        } else {
                                                            alert(success.body);
                                                            //  toastr.error(success.body); //toster.error
                                                        }
                                                    },
                                                    error: function (error) {
                                                        toastr.error(success.body);
                                                        console.log(error);
                                                        errorNotify("something went to wrong");
                                                    }
                                                });
                                            }
    </script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

    ​

