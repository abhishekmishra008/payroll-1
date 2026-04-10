<?php
$this->load->view('human_resource/header');
$user_id = $this->session->userdata('login_session');
$user_type = $user_id['user_type'];
?>
<style>
.page-content-wrapper .page-content
{
	margin-left: unset!important;
}
@media (max-width: 800.98px) {
	.page-header-fixed .page-container {
			margin-top: 94px!important;
		}
}
</style>
<div class="clearfix"> </div>
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery.min.js" type="text/javascript"></script>

