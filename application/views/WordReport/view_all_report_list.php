<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
          integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title><?= $title ?></title>
</head>
<?php //$this->load->view('all_header/sample_old.php'); ?>
<style>
    #template_detail_div {
        list-style: none;
    }

    #template_detail_div li {
        background-color: #f2f4f6;
        margin-bottom: 2px;
        padding: 2px 12px;
    }

    /*header stylesheet*/
    #myDropdown {
        width: 523px !important;
    }

    #side-padding {
        padding-left: 0 !important;
    }

    .app-theme-white.fixed-header .app-header__logo {
        background: none !important;
    }

    ::-webkit-scrollbar {
        height: 20px !important;
        background-color: #f5f4f4 !important;
        width: 6px !important;
        overflow: visible !important;
        display: block !important;

    }

    ::-webkit-scrollbar-thumb {
        -webkit-border-radius: 10px;
        border-radius: 10px;
        background: gray !important;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.5);
    }

    .modal {
        overflow: auto !important;
        margin-top: 36px;
    }

    /*.select2.select2-container.select2-container--bootstrap4 {*/
    /*    display: inline-block !important;*/

    /*}*/
    /*.select2-container {*/
    /*    position: absolute !important;*/
    /*    width: 90% !important;*/
    /*}*/
    .select2-container {
        width: 100% !important;
    }

    .select2-container .select2-selection--multiple .select2-selection__rendered {
        display: flex !important;
        flex-direction: column !important;

    }

    .board_tab {
        margin-left: 40px;
        background-color: #891635;
        color: white;
        padding: 10px 20px 10px 20px;
        font-size: 18px;
    }

    .app-header {
        background: transparent;
    }

    .app_width1 {
        min-width: 100% !important;
    }

    <?php if($this->session->user_session->user_type == 6){ ?>
    .app-main .app-main__inner {
        padding: 10px 30px 0;
        flex: 1;
        /*min-width: 100% !important;*/
    }

    .logo.py-2.text-center {
        text-align: left !important;
    }

    @media (min-width: 992px) {
        .modal-lg, .modal-xl {
            max-width: 957px;
        }
    }

    .modal-body {
        background-color: #ebecf0;
    }

    #boardModalPanel {
        /*width: 85%;*/
        /*float: right;*/
        background-color: #ebecf0;
    }

    .board-card, .board-card1 {
        width: 357px !important;
    }

    .card {
        box-shadow: 0 0.26875rem 2.1875rem rgb(4 9 20 / 3%), 0 0 0 rgb(4 9 20 / 3%), 0 0.25rem 0.53125rem rgb(4 9 20 / 5%), 0 0.125rem 0.1875rem rgb(4 9 20 / 3%) !important;
        border-width: 0 !important;
        transition: all .2s !important;
    }

    <?php } else {?>
    .app-main .app-main__inner {
        padding: 10px 30px 0;
        flex: 1;
        /*min-width: 5041px !important;*/
    }

    <?php } ?>
</style>

<style type="text/css">
    /* override css  */
    .template_header {
        display: flex !important;
        justify-content: end !important;
    }

    #Logout {
        margin-right: 40px !important;
    }

    /* Icons */
    .icon::before {
        z-index: 10;
        display: inline-block;
        margin: 0 0.4em 0 0;
        vertical-align: middle;
        text-transform: none;
        font-weight: normal;
        font-variant: normal;
        font-size: 1.3em;
        font-family: 'stroke7pixeden';
        line-height: 1;
        speak: none;
        -webkit-backface-visibility: hidden;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    /* Content */
    .content-wrap {
        position: relative;
    }

    .content-wrap section {
        display: none;
        /*margin: 0 auto;*/
        padding: 0px !important;
        max-width: 100%;
        /*text-align: center;*/
    }

    .content-wrap section.content-current {
        display: block;
    }

    .content-wrap section p {
        margin: 0;
        padding: 0.75em 0;
        /*color: rgba(40, 44, 42, 0.05);*/
        font-weight: 900;
        /*font-size: 4em;*/
        line-height: 1;
        color: black;
        font-size: 1rem
    }

    /* Fallback */
    .no-js .content-wrap section {
        display: block;
        padding-bottom: 2em;
        border-bottom: 1px solid rgba(255, 255, 255, 0.6);
    }

    .no-flexbox nav ul {
        display: block;
    }

    .no-flexbox nav ul li {
        min-width: 15%;
        display: inline-block;
    }

    /*****************************/
    /* Underline */
    /*****************************/

    .tabs-style-underline nav {
        background: #fff;
    }

    .tabs-style-underline nav a {
        padding: 0.25em 0 0.5em !important;
        border-left: 1px solid #e7ecea;
        -webkit-transition: color 0.2s;
        transition: color 0.2s;
    }

    .tabs-style-underline nav li:last-child a {
        border-right: 1px solid #e7ecea;
    }

    .tabs-style-underline nav li a::after {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: #891635;
        content: '';
        -webkit-transition: -webkit-transform 0.3s;
        transition: transform 0.3s;
        -webkit-transform: translate3d(0, 150%, 0);
        transform: translate3d(0, 150%, 0);
    }

    .tabs-style-underline nav li.tab-current a::after {
        -webkit-transform: translate3d(0, 0, 0);
        transform: translate3d(0, 0, 0);
    }

    .tabs-style-underline nav a span {
        font-weight: 700;
    }

    .tabs-style-underline nav a:hover {
        text-decoration: none !important;
        color: #74777b !important;
    }

    .fa_class {
        font-size: 22px !important;
    }

    @media screen and (max-width: 58em) {
        .tabs nav a.icon span {
            display: none;
        }

        .tabs nav a:before {
            margin-right: 0;
        }
    }

    #dynamicDataTableFilter_0 {
        margin-top: 6px !important;
    }

    .themeChange {
        box-shadow: none !important;
        border: 1px solid #dee2e6;
        border-width: 1px !important;
        background-color: white !important;
    }

    #group_users .form-control[multiple] {
        height: calc(2.25rem + 2px);
    }

    #handonTableModal .modal-body {
        padding: 0px;
    }

    #handonTableModal .modal-header {
        padding: 5px !important;
    }

    .remove_row {
        background-color: #f2f4f6;
    }

    #template_detail_div {
        padding-left: 15px;
        padding-right: 15px;
    }

    .spreadSheetpage {

        padding: 10px 10px 10px 0px !important;
    }
</style>


<!-- <body onclick="closeNav()">-->
<body style="background-color:white;">
<!--<body onclick="closeNav()">-->
<!--<div class="app-header" style="height: 2.8125rem;margin-left: 1% !important;background:transparent;padding-top:40px;">
    <div class="board_tab" style="">
        <div class="">Board</div>
    </div>
    <div class="app-header__content mt-2">
        <div class="app-header-left">
            <ul class="nav nav-tabs border-0 m-0" id="tab_pane">
                <?php
if (isset($tabTitle)) {
    ?>
                    <li class="nav-item">
                        <a data-toggle="tab" href="#tab-eg10-0" class="active nav-link" id="first_tab">
                            <?= $tabTitle ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>

    </div>
</div>-->

<?php
//print_r($_SESSION);
//print_r($this->session->login_session['emp_id']);
//die;?>
<div id="main_page" class="app-main__inner px-4 mt-3 app_width" style="min-width: 100%!important;">
    <!--<input type="hidden" id="firm_id" name="firm_id" value="<?php /*echo $this->session->user_session->firm_id; */?>">-->
    <!--<input type="hidden" id="user_type" name="user_type" value="<?php /*echo $this->session->user_session->user_type; */?>">-->
    <!--<input type="hidden" id="user_type" name="user_type" value="<?php /*echo $this->session->user_session->user_type; */?>">-->
    <input type="hidden" id="user_id" name="user_id" value="<?php echo $this->session->login_session['emp_id']; ?>">
    <input type="hidden" id="user_email" name="user_email" value="<?php echo $this->session->login_session['user_id']; ?>">

    <div id="main_board_content" style="padding-left:30px;">
        <div class="main-card card" style="background-color: transparent!important;">
            <div class="card-body">
                <div id="" class="content-wrap">

                    <section class="content-current" id="tab-template">
                        <div class="content-page">
                            <div class="content">
                                <div class="">

                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="page-title-box">
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card-box">
                                                <div class="card-header clearfix">
                                                    <div class="card-title clearfix" style="display: flex;">
                                                        <h4 class="m-t-0 m-b-20 header-title"><b>Report Template</b>
                                                        </h4>

                                                        <button type="button" onclick="editReport()"
                                                                class="btn btn-primary btn-sm roundCornerBtn4"
                                                                style="margin-left: auto;margin-right: 20px;"><i class="fa fa-plus"></i>
                                                            New Template
                                                        </button>

                                                    </div>
                                                </div>
                                                <br>

                                                <div class="card">
                                                    <table id="table_lists" class="display">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Company</th>
                                                            <!--<th>Branch</th>-->
                                                            <th>Report Name</th>
                                                           <!-- <th>Maker</th>
                                                            <th>Checker</th>-->
                                                            <th>Created On</th>
                                                            <th>Action</th>
                                                            <th>Edit</th>

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
                    </section>


                </div>
            </div>
        </div>


    </div>
</div>

<!-- New BMR ADD MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" id="wordReportModal"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Report Template</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <form id="BMRForm" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12">

                                    <input type="hidden" name="bmr_update_id" id="bmr_update_id">
                                    <div class="form-group" id="bmr-list">
                                        <label for="">Copy Report Template</label>
                                        <select name="bmr_list" id="bmr_list" class="form-control select2"
                                                onchange="getBMRNameList(this.value)"></select>
                                    </div>


                                    <div class="form-group">
                                        <label for="">Company</label>
                                        <select name="company_list" id="company_list"
                                                class="form-control select2"></select>
                                    </div>

                                    <div class="form-group">
                                        <label>Report Name</label>
                                        <input type="text" name="bmr_name" class="form-control" id="bmr_name">
                                    </div>

                                   <!-- <div class="form-group">
                                        <label for="">Branch</label>
                                        <select name="branch_list" id="branch_list"
                                                class="form-control select2"></select>
                                    </div>-->
                                    <!--<div class="form-group">
                                        <label for="">Maker</label>
                                        <select name="maker_user_list[]" id="maker_user_list"
                                                class="form-control select2" multiple></select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Checker</label>
                                        <select name="checker_user_list[]" id="checker_user_list"
                                                class="form-control select2" multiple></select>
                                    </div>-->

                                    <div class="form-group">
                                        <label>is Dashboard Template</label>
                                        <select name="is_dashboard" class="form-control" id="is_dashboard">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Exchange rate</label>
                                        <input type="text" name="exchange_rate" class="form-control" id="exchange_rate"
                                               value="1">
                                    </div>

                                    <button type="button" class="btn btn-primary float-right" onclick="addNewBMR()">SAVE
                                    </button>
                                </div>


                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="<?= base_url(); ?>assets/modules/select2/dist/js/select2.full.min.js"></script>
<script src="<?= base_url(); ?>assets/js/cbpFWTabs.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/izitoast/css/iziToast.min.css">
<script src="<?php echo base_url(); ?>assets/modules/izitoast/js/iziToast.min.js"></script>

<script src="<?= base_url(); ?>assets/js/custom.js" type="text/javascript"></script>
<!-- <script src="<?= base_url(); ?>assets/js/custom.js" type="text/javascript"></script> -->

<script type="text/javascript">
    var baseURL = "<?php echo base_url(); ?>";
    $(document).ready(function () {
        getReportTableList();
    });

    function getReportTableList() {
        app.request("getReportTableList", null).then(res => {
            console.log("table lists response = " + res);
            $("#table_lists").DataTable({
                destroy: true,
                order: [],
                data: res.data,
                "pagingType": "simple_numbers",
                columns: [
                    {data: 0},
                    {data: 5},
                    {data: 1},
                    {data: 7}, //---created on date--
                    /*{
                        data: 9,
                        render: (d, t, r, m) => {

                            const copy = [];
                            if (d != null) {
                                if (d.includes(',')) {
                                    let namUser = d.split(",");
                                    namUser.forEach(function (item) {
                                        //console.log('test');
                                        //console.log(item);
                                        //copy.push(item + item+2);
                                        let name1 = item;
                                        let rgx = new RegExp(/(\p{L}{1})\p{L}+/, 'gu');

                                        let initials1 = [...name1.matchAll(rgx)] || [];

                                        initials1 = (
                                            (initials1.shift()?.[1] || '') + (initials1.pop()?.[1] || '')
                                        ).toUpperCase();
                                        copy.push(initials1);
                                    });
                                } else {
                                    let name1 = d;
                                    let rgx = new RegExp(/(\p{L}{1})\p{L}+/, 'gu');

                                    let initials1 = [...name1.matchAll(rgx)] || [];

                                    initials1 = (
                                        (initials1.shift()?.[1] || '') + (initials1.pop()?.[1] || '')
                                    ).toUpperCase();
                                    copy.push(initials1);
                                }
                            }
                            //var names =copy.join(", ");
                            return copy;
                        },
                    },*/ //----- For Maker User

                    /*{
                        data: 10,
                        render: (d, t, r, m) => {
                            const copy = [];
                            if (d != null) {
                                if (d.includes(',')) {
                                    let namUser = d.split(",");
                                    namUser.forEach(function (item) {
                                        //console.log('test');
                                        //console.log(item);
                                        //copy.push(item + item+2);
                                        let name1 = item;
                                        let rgx = new RegExp(/(\p{L}{1})\p{L}+/, 'gu');

                                        let initials1 = [...name1.matchAll(rgx)] || [];

                                        initials1 = (
                                            (initials1.shift()?.[1] || '') + (initials1.pop()?.[1] || '')
                                        ).toUpperCase();
                                        copy.push(initials1);
                                    });
                                } else {
                                    let name1 = d;
                                    let rgx = new RegExp(/(\p{L}{1})\p{L}+/, 'gu');

                                    let initials1 = [...name1.matchAll(rgx)] || [];

                                    initials1 = (
                                        (initials1.shift()?.[1] || '') + (initials1.pop()?.[1] || '')
                                    ).toUpperCase();
                                    copy.push(initials1);
                                }
                            }
                            //var names =copy.join(", ");
                            return copy;
                        },
                    },*/  //------- For Checker User


                    {
                        data: 3,
                        render: (d, t, r, m) => {
                            var status = 'Active';
                            if (d == 1) {
                                status = 'Active';
                            } else {
                                status = 'Inactive';
                            }
                            return `<button type="button" onclick="changeStatus(${r[2]},${d})" class="btn btn-link">${status}</button>`
                        }
                    },
                    {
                        data: 2,
                        render: (d, t, r, m) => {

                            /*let MakerUser, checkerUser = '';
                            if (r[11] != null && r[11] != '') {
                                MakerUser = r[11].split(',');
                            }
                            if (r[12] != null && r[12] != '') {
                                checkerUser = r[12].split(',');
                            }
                            console.log(MakerUser, checkerUser);*/

                            return `<button type="button" onclick="editReport(${d},'${r[1]}','${r[4]}','${r[6]}','${r[8]}')" class="btn btn-link"><i class="fa fa-pencil"></i></button>
									<a class="btn btn-link" href="${baseURL}report_list/1/${d}"><i class="fa fa-file"></i></a>`
                        }
                    },
                ],
                fnRowCallback: (nRow, aData, iDisplayIndex, iDisplayIndexFull) => {
                    var status = 'Activ';
                    if (aData[3] == 1) {
                        status = 'Active';
                    } else {
                        status = 'Inactive';
                    }

                  /*  let MakerUser, checkerUser = '';
                    if (aData[11] != null && aData[11] != '') {
                        MakerUser = aData[11].split(',');
                    }
                    if (aData[12] != null && aData[12] != '') {
                        checkerUser = aData[12].split(',');
                    }
                    console.log(MakerUser, checkerUser);*/

                    $('td:eq(7)', nRow).html(`<button type="button" onclick="changeStatus(${aData[2]},${aData[3]})" class="btn btn-link">${status}</button>`);
                    $('td:eq(8)', nRow).html(`<button type="button" onclick="editReport(${aData[2]},'${aData[1]}','${aData[4]}','${aData[6]}','${aData[8]}')" class="btn btn-link"><i class="fa fa-pencil"></i></button>
											<a class="btn btn-link" href="${baseURL}report_list/1/${aData[2]}"><i class="fa fa-file"></i></a>
											<a class="btn btn-link" href="${baseURL}all_bmr_report_view/1/${aData[2]}"><i class="fa fa-eye"></i></a>`);
                }
            });
        }).catch((e) => {
            console.log(e);
        });
    }

    function addNewBMR() {
        let name = $("#bmr_name").val();
        //let exchange_rate = $("#exchange_rate").val();

        if (name != null && name != '') {
            let formd = document.getElementById('BMRForm');
            let formdata = new FormData(formd);
            app.request('addNewBMR', formdata).then(res => {
                if (res.status === 200) {
                    $("#newBMRModal").modal('hide');
                    app.successToast(res.body);
                    getReportTableList();
                } else {
                    app.errorToast(res.body);
                }
            }).catch(error => console.log(error));
        } else {
            //app.errorToast('BMR Name cannot be Empty');
            app.errorToast('BMR Name & Exchange Rate cannot be Empty');
        }
    }

    function editReport(id = null, name = null, is_dashboard = null, company = null,exchange_rate = null) {
        $("#bmr_update_id").val('');
        $("#bmr_name").val('');
        if (id != null) {
            $("#bmr_update_id").val(id);
            $("#bmr_name").val(name);
            $("#bmr-list").hide();
            $("#is_dashboard").val(is_dashboard).trigger('change');
            getCompanyList().then(e => {
                $("#company_list").val(company).trigger('change');
            });
           /* getBranchList().then(e => {
                $("#branch_list").val(branch).trigger('change');
            });*/
            // getMakerUserOption().then(e => {
            //
            //     $("#maker_user_list").val((maker_user.split(','))).trigger('change');
            // });
            // getCheckerUserOption().then(e => {
            //     $("#checker_user_list").val(checker_user.split(',')).trigger('change');
            // });
         if (exchange_rate != null) {
                $("#exchange_rate").val(exchange_rate);
            } else {
                $("#exchange_rate").val("1");
             }

        } else {
            $("#bmr-list").show();
            $("#is_dashboard").val(0).trigger('change');
            getBMRList();
            getCompanyList();
            //getBranchList();
            // getMakerUserOption();
            // getCheckerUserOption();
        }
        $("#wordReportModal").modal('show');
    }

    function getBMRList() {
        app.request("getBMRList", null).then(res => {
            if (res.status === 200) {
                $("#bmr_list").html(res.data);
                $("#bmr_list").select2({});
            } else {
                app.errorToast(res.body);
            }
        }).catch(error => console.log(error));
    }

    function getCompanyList() {
        return new Promise(function (resolve, reject) {
            app.request("getCompanyListOption", null).then(res => {
                if (res.status === 200) {
                    $("#company_list").html(res.data);
                    $("#company_list").select2({});

                    resolve(true);
                } else {
                    app.errorToast(res.body);
                }
            }).catch(error => console.log(error));
        });
    }


    function getBMRNameList(id) {
        let formdata = new FormData();
        formdata.set('id', id);
        app.request("getBMRNameList", formdata).then(res => {
            if (res.status === 200) {
                let copydata = '_copy';
                $("#bmr_name").val(res.data + copydata);
                // app.successToast(res.body);
            } else {
                app.errorToast(res.body);
            }
        }).catch(error => console.log(error));
    }

    function changeStatus(id, status) {
        let formdata = new FormData();
        formdata.set('id', id);
        formdata.set('status', status);
        app.request("changeStatus", formdata).then(res => {
            if (res.status === 200) {
                getReportTableList();
                // app.successToast(res.body);
            } else {
                app.errorToast(res.body);
            }
        }).catch(error => console.log(error));
    }

    /*New add on 13/2/2023*/
    function getBranchList() {
        return new Promise(function (resolve, reject) {
            app.request("getBranchListOption", null).then(res => {
                if (res.status === 200) {
                    $("#branch_list").html(res.data);
                    $("#branch_list").select2({});

                    resolve(true);
                } else {
                    app.errorToast(res.body);
                }
            }).catch(error => console.log(error));
        });
    }

</script>
</html>
