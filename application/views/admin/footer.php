<!-- BEGIN FOOTER -->


<!-- END QUICK SIDEBAR TOGGLER -->

<!-- END FOOTER -->

<!--jquery to export Datatable -->
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>

<!-- BEGIN CORE PLUGINS -->
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/moment.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/morris/morris.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/morris/raphael-min.js" type="text/javascript"></script>

<script src="<?php echo base_url() . "assets/"; ?>global/plugins/horizontal-timeline/horizontal-timeline.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>

<!-- END CORE PLUGINS -->

<!--jquery to export Datatable -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>

<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="<?php echo base_url() . "assets/"; ?>global/scripts/datatable.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/scripts/app.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>pages/scripts/dashboard.min.js" type="text/javascript"></script>

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>pages/scripts/components-select2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo base_url() . "assets/"; ?>pages/scripts/table-datatables-editable.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="<?php echo base_url() . "assets/"; ?>layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->

<script src="<?php echo base_url() . "assets/"; ?>global/plugins/ladda/spin.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/ladda/ladda.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/icheck/icheck.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>pages/scripts/form-icheck.min.js" type="text/javascript"></script>


<script  src="<?php echo base_url() . "assets/"; ?>global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function ()
    {
        $('#clickmewow').click(function ()
        {
            $('#radio1003').attr('checked', 'checked');
        });
        load_unread_assigenment_table();
        unread_ticket_assigenement();
    });
    function load_unread_assigenment_table() {
        var url = "<?= base_url("unread_assigenement_notification") ?>";
        $.ajax({
            url: url,
            method: "post"
        }).done(function (success) {
            success = JSON.parse(success);
            var notification_pending = $("#notification_box_counter_subheader").attr("data-counter");
            notification_pending = parseInt(notification_pending) + parseInt(success.Total);
            $("#notification_box_counter_subheader").attr("data-counter", notification_pending);
            $("#notification_box_counter_subheader").text(notification_pending);
            $("#notification_box_counter_header").text(notification_pending);
            $("#notification_message_box").append(success.notification);
        });
    }

    function unread_ticket_assigenement() {
        var url = "<?= base_url("Ticket_Generation/unread_ticket_assigenement") ?>";
        $.ajax({
            url: url,
            method: "post"
        }).done(function (success) {
            success = JSON.parse(success);
            var notification_pending = $("#notification_box_counter_subheader").attr("data-counter");
            notification_pending = parseInt(notification_pending) + parseInt(success.Total);
            $("#notification_box_counter_subheader").attr("data-counter", notification_pending);
            $("#notification_box_counter_subheader").text(notification_pending);
            $("#notification_box_counter_header").text(notification_pending);
            $("#notification_message_box").append(success.notification);
        });
    }
</script>
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/bootstrap-toastr/toastr.min.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/bootstrap-toastr/toastr.min.js" type="text/javascript"></script>

<p class="copyright-v2"> 2020 © Developed By 
    <a target="_blank" href="http://gold-berries.com/GB">Goldberries  |  Technology and Beyonds</a>
</p>
</body>
</html>