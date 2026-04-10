<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>
<html>
<head>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/richText/rte_theme_default.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/richText/runtime/richtexteditor_content.css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css"
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/richText/res/style.css">
    <style>
        .custom-header {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            padding: 8px 0;
        }

        .main-content {
            padding-left: 0px !important;
        }
        .inputText
        {
            background-color: yellow!important;
        }
        .editorDiv
        {
            height: 80vh;
            overflow-y: auto;
        }
        iframe
        {
            padding: 2px;
            width: 210mm!important;
            border: 1px solid!important;
            margin: 1rem auto;
            /*min-height: 820px!important;*/
        }

        @media (min-width: 992px)
            #addmaterialmodal {
                max-width: 80%!important;
            }
    </style>
</head>
<body>
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Report</h3>
            <input type="hidden" name="bmr_no" id="bmr_no" value="10">
            <input type="hidden" name="production_id" id="production_id" value="10">
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-8">
                    <div class="card">
                        <form id="page_form" method="post" data-form-valid="saveHtml">
                            <div class="card-body">

                                <div class="row">
                                    <div class="form-group col-6">

                                        <input type="text" class="form-control" name="section_name" id="section_name" readonly
                                               data-valid="required"
                                               data-msg="Enter page name"/>
                                        <input type="hidden" name="update_id" id="update_id" value="<?=$id?>"/>
                                        <input type="hidden" name="type" id="type" value="<?=$type?>"/>
                                        <input type="hidden" name="page_id" id="page_id"/>
                                        <input type="hidden" name="template_id" id="template_id"/>
                                        <input type="hidden" name="elementCount" id="elementCount" value="0"/>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">

                                            <input type="hidden" id="page_type" name="page_type" value="2">
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-primary mr-1" type="button" id="templateFornBtn" style="">Submit</button>
                                        <!-- <button class="btn btn-secondary" type="reset">Reset</button> -->
                                    </div>
                                </div>

                                <div class="editorDiv">
									<textarea class="bg-secondary" id="summernote">
									</textarea>
                                </div>

                            </div>

                        </form>
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-4">
                    <div class="card">

                        <div class="card-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active show" id="section-tab" data-toggle="tab"
                                       href="#sectionTab" role="tab" aria-controls="section" aria-selected="false">Setting</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade" id="questionTab" role="tabpanel"
                                     aria-labelledby="question-tab">
                                </div>
                                <div class="tab-pane fade active show" id="sectionTab" role="tabpanel"
                                     aria-labelledby="section-tab">
                                    <div class="row">
                                        <div class="col-md-12 mt-2">
                                            <button onclick="setTextBoxCode(1)">
                                                <i class="fas fa-font"></i> Textbox
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Input Configuration</h4>
                        </div>
                        <div class="card-body">
                            <div id="keyPairsDiv">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/scripts/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>

<script src="<?php echo base_url(); ?>assets/modules/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/richText/rte.js"></script>
<script>RTE_DefaultConfig.url_base='<?php echo base_url(); ?>assets/modules/richText/'</script>
<script type="text/javascript" src='<?php echo base_url(); ?>assets/modules/richText/all_plugins.js'></script>
<script type="text/javascript" src='<?php echo base_url(); ?>assets/modules/richText/res/patch.js'></script>
<script src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/izitoast/css/iziToast.min.css">
<script src="<?php echo base_url(); ?>assets/modules/izitoast/js/iziToast.min.js"></script>
<script src="<?= base_url(); ?>assets/js/custom.js" type="text/javascript"></script>
<script src="<?= base_url(); ?>assets/modules/richText/wordReport.js"></script>
<script>var baseURL="<?php echo base_url(); ?>";</script>
<script type="text/javascript">
    $(document).ready(function () {
        app.formValidation();
        $('iframe').attr('id','reportid');
    });
    $('#templateFornBtn').on('click',function(){
        var myFrame = document.getElementById('reportid');
        console.log(myFrame);
        if(myFrame!==null){
            let iframeHeight = myFrame.contentWindow.document.documentElement.scrollHeight;
            if(iframeHeight > 730)
            {
                let text = "Data is Larger than one page!Are sure want to save?";
                if (confirm(text) == true) {
                    saveHtml();
                }

            }else{
                saveHtml();
            }
        }else{
            saveHtml();
        }


    });
</script>
</html>
