<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
      integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    .download_button {
        border: none;
        color: #fff;
        background-color: #337ab7;
        border-color: #2e6da4;
        padding: 8px 22px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 5px;
    }
</style>
<!--<button type="button" id="export-report" class="btn btn-primary btn-sm download_button"
        style="margin-right: 10px;" onclick="bmrReport()"><i class="fa fa-file-pdf" aria-hidden="true"></i> Download
</button>-->
<a href="javascript:void(0)" onclick="bmrReport()"><img src="<?=base_url()?>assets/img/pdf_download1.png" width="7%"></a>
<div class="queryParamDiv">
    <input type="hidden" name="queryParameters" id="queryParameters" value="">
</div>
<form id="saveReportPage" name="saveReportPage">
    <input type="hidden" id="wordReportTemplateId">
    <div id="" class="to-bottom" style="overflow: auto;width:50%">
        <input type="hidden" id="page_id" name="page_id" value="1">
        <input type="hidden" id="report_id" name="report_id" value="">
        <input type="hidden" id="page_input" name="page_input">
        <input type="hidden" id="user_id" name="user_id" value="<?= $user_id; ?>">
        <input type="hidden" id="firm_id" name="firm_id" value="<?= $firm_id; ?>">
        <input type="hidden" id="month" name="month" value="<?= $month; ?>">
        <input type="hidden" id="year" name="year" value="<?= $year; ?>">
        <div id="wordReportDiv">
        </div>
    </div>
</form>


<?php //$this->load->view('human_resource/footer'); ?>
<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>

<script src="<?= base_url(); ?>assets/js/custom.js" type="text/javascript"></script>
<script src="<?= base_url(); ?>assets/modules/richText/wordReportCreation.js"></script>

<script>
    var base_url = '<?php echo base_url()?>';
    var baseURL = '<?php echo base_url()?>';
    $(document).ready(function () {
        var user_id = $('#user_id').val();
        var firm_id = $('#firm_id').val();
        var month = $('#month').val();
        var year = $('#year').val();
        fetch_salaryReport(user_id,firm_id,month,year);
    });

    function fetch_salaryReport(user_id,firm_id,month,year)
    {
        $("#wordReportDiv").html('');
        $("#wordReportDiv").show();
       // $("#wordReportTemplateId").val(temp_id);
        //$("#report_id").val(temp_id);
        var form_data = new FormData();
        form_data.set('user_id', user_id);
        form_data.set('firm_id', firm_id);
        form_data.set('month', month);
        form_data.set('year', year);
        $.ajax({
            type: "POST",
            url: base_url + "fetch_salaryReportData",
            dataType: "json",
            data: form_data,
            contentType: false,
            processData: false,
            success: function (result) {

                if (result.status === 200) {
                    var date    = new Date(result.user_data.date_of_birth),
                        yr      = date.getFullYear(),
                        month   = date.getMonth(),
                        day     = date.getDate(),
                        dob = day + '-' + month + '-' + yr;
                    var html = '';
                    $("#wordReportTemplate").html(result.body.name);

                   /* let pagesData = JSON.parse(result.body.html);
                    pagesData = pagesData[0].pages;*/

                    let pagesData = JSON.parse(result.body.code);
                    pagesData = pagesData[0].pages;

                    pageContent = pagesData;
                    $("#queryParameters").val('');

                    let params = {
                        user_id: user_id,
                        firm_id: firm_id,
                        employee_name:result.user_data.employee_name,
                        spouse_name:result.user_data.spouse_name,
                        department:result.user_data.department,
                        date_of_join:result.user_data.date_of_join,
                        gender:result.user_data.gender,
                        dob:result.user_data.dob,
                        bank_name:result.user_data.bank_name,
                        user_code:result.user_data.user_code,
                        designation:result.user_data.designation,
                        uan:result.user_data.uan,
                        pan_no:result.user_data.pan_no,
                        adhar_no:result.user_data.adhar_no,
                        address:result.user_data.address,
                        account_no:result.user_data.account_no,
                        salary_month:result.user_data.salary_month,
                        salary_payable:result.user_data.salary_payable,
                        in_rupee:result.user_data.in_rupee,
                        total_deduction:result.user_data.total_deduction,
                        salary_table:result.user_data.salary_table
                    };
                    params = btoa(JSON.stringify(params));
                    $("#queryParameters").val(params);
                    $("#report_id").val(result.body.id);

                    showPage(0, 1);
                } else {

                }
            }
        });
    }

</script>

<script>
    /*
 * jQuery helper plugin for examples and tests
 */
    (function ($) {
        $.fn.html2canvas = function (options) {
            var date = new Date(),
                $message = null,
                timeoutTimer = false,
                timer = date.getTime();
            html2canvas.logging = options && options.logging;
            html2canvas.Preload(this[0], $.extend({
                complete: function (images) {
                    var queue = html2canvas.Parse(this[0], images, options),
                        $canvas = $(html2canvas.Renderer(queue, options)),
                        finishTime = new Date();

                    $canvas.css({ position: 'absolute', left: 0, top: 0 }).appendTo(document.body);
                    $canvas.siblings().toggle();

                    $(window).click(function () {
                        if (!$canvas.is(':visible')) {
                            $canvas.toggle().siblings().toggle();
                            throwMessage("Canvas Render visible");
                        } else {
                            $canvas.siblings().toggle();
                            $canvas.toggle();
                            throwMessage("Canvas Render hidden");
                        }
                    });
                    throwMessage('Screenshot created in ' + ((finishTime.getTime() - timer) / 1000) + " seconds<br />", 4000);
                }
            }, options));

            function throwMessage(msg, duration) {
                window.clearTimeout(timeoutTimer);
                timeoutTimer = window.setTimeout(function () {
                    $message.fadeOut(function () {
                        $message.remove();
                    });
                }, duration || 2000);
                if ($message)
                    $message.remove();
                $message = $('<div ></div>').html(msg).css({
                    margin: 0,
                    padding: 10,
                    background: "#000",
                    opacity: 0.7,
                    position: "fixed",
                    top: 10,
                    right: 10,
                    fontFamily: 'Tahoma',
                    color: '#fff',
                    fontSize: 12,
                    borderRadius: 12,
                    width: 'auto',
                    height: 'auto',
                    textAlign: 'center',
                    textDecoration: 'none'
                }).hide().fadeIn().appendTo('body');
            }
        };
    })(jQuery);
    </script>

