<?php
$this->load->view('human_resource/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin
    redirect(base_url() . 'login');
}
$username = $this->session->userdata('login_session');
$data['session_data'] = $session_data;
$user_id = ($session_data['user_id']);
$emp_id = ($session_data['emp_id']);
$user_type = ($session_data['user_type']);
$user_type;
?>  <link  href="<?php echo base_url() . "assets/"; ?>pages/css/profile.min.css" rel="stylesheet" type="text/css" />
<script>
    let baseUrl = '<?php echo base_url(); ?>';
</script>
<style>
	table.dataTable thead th, table.dataTable thead td {
		padding: 10px 18px;
		border-bottom: 1px solid #ffffff;
	}
	table.dataTable.no-footer {
		border-bottom: 1px solid #ffffff;
	}

	.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
		padding: 8px;
		line-height: 1.42857;
		vertical-align: top;
		border-top: 1px solid #ffffff;
	}
	.portlet.light>.portlet-title>.nav-tabs>li>a {
	margin:none !important; }

	.tabbable-custom>.nav-tabs>li>a {
		margin-right: 0;
	color: black !important;}
	.tabbable-custom>.nav-tabs>li {
		margin-right: 2px;
		background-color: #7cabb7 !important;
		border-top: 2px solid #f9f3f3b5;}
</style>
<style type="text/css">

	.tabs {
		position: relative;
		overflow: hidden;
		margin: 0 auto;
		width: 100%;
		font-weight: 300;
		font-size: 1.0em;
	}

	/* Nav */
	.tabs nav {
		text-align: center;
	}

	.tabs nav ul {
		position: relative;
		display: -ms-flexbox;
		display: -webkit-flex;
		display: -moz-flex;
		display: -ms-flex;
		display: flex;
		margin: 0 auto;
		padding: 0;
	//	max-width: 1200px;
		list-style: none;
		-ms-box-orient: horizontal;
		-ms-box-pack: center;
		-webkit-flex-flow: row wrap;
		-moz-flex-flow: row wrap;
		-ms-flex-flow: row wrap;
		flex-flow: row wrap;
		-webkit-justify-content: center;
		-moz-justify-content: center;
		-ms-justify-content: center;
		justify-content: center;
	}

	.tabs nav ul li {
		position: relative;
		z-index: 1;
		display: block;
		margin: 0;
		text-align: center;
		-webkit-flex: 1;
		-moz-flex: 1;
		-ms-flex: 1;
		flex: 1;
	}

	.tabs nav a {
		position: relative;
		display: block;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		line-height: 2.5;
		color: #891635;
	}

	.tabs nav a span {
		vertical-align: middle;
		font-size: 1em;
	}

	.tabs nav li.tab-current a {
		color: #74777b;
	}

	.tabs nav a:focus {
		outline: none;
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
		//text-align: center;
	}

	.content-wrap section.content-current {
		display: block;
	}

	.content-wrap section p {
		margin: 0;
		padding: 0.75em 0;
		color: rgba(40, 44, 42, 0.05);
		font-weight: 900;
		font-size: 4em;
		line-height: 1;
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
</style>
<style>
	@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
	.personal-top {
		position: relative;
		padding: 1.5rem;
		border-radius: 1.6rem;
		font-family: 'Poppins', sans-serif;
		z-index: 2;
		border-bottom: 1px solid #0003;
		margin-bottom: 1.5rem;
	}
	.personal-top::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		height: 50%;
		width: 100%;
		background: #d2454d;
		z-index: -1;
		border-radius: 5px !important;
	}

	.personal-top .personal-info {
		/* margin-top: 2rem; */
		display: flex;
		gap: 1.5rem;
	}

	.personal-top .personal-info .right {
		display: flex;
		flex-direction: column;
		gap: 2rem;
		justify-content: center;
		font-size: 1.8rem;
		font-weight: 400;
		color: #000;
		flex: 1;
	}
	.personal-top .personal-info .right .info {
		color: #fff;
		display: flex;
		justify-content: space-between;
		align-items: center;
		flex-wrap: wrap;
		gap: 0.5rem;
	}
	.personal-top .personal-info .right .info .name {
		line-height: 1;
		font-size: clamp(1.4rem, 3vw, 1.8rem);
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}
	.personal-top .personal-info .right .info .join {
		line-height: 1;
		font-size: 1.4rem;
	}
	.personal-top .personal-info .right .role {
		font-size: 1.4rem;
		line-height: 1;
		padding: 0.75rem 1rem;
		border-radius: 10px !important;
		background: #0001;
		width: fit-content;
		outline: 1px solid #3336;
		color: #333;
		font-weight: 500;
	}

	.personal-top .avatar {
		height: min(12rem, 20dvw);
		min-height: min(12rem, 20dvw);
		width: min(12rem, 20dvw);
		min-width: min(12rem, 20dvw);
		border-radius: 100% !important;
		background: #fff;
		color: #d2454d;
		font-size: 3rem;
		font-weight: 600;
		display: grid;
		place-items: center;
		text-transform: uppercase;
		outline: 2px solid #d2454d;
	}
	.personal-top .details {
		display: flex;
		flex-direction: column;
		gap: 0.25rem;
		padding: 1.5rem;
		border-radius: 10px !important;
		background: #d2454d1c;
	}
	.personal-top .details .item {
		font-size: 1.4rem;
		font-weight: 500;
		color: #9f171f;
	}
	.personal-top .details .item span {
		margin-left: 0.25rem;
		font-weight: 400;
		color: #222;
	}

	.more-info {
		/* padding-block: 1.5rem; */
		/* margin-inline: 1.5rem; */
		display: flex;
		flex-direction: column;
		gap: 0.5rem;
		font-family: 'Poppins', sans-serif;
	}
	.more-info li {
		display: flex;
		gap: 0.5rem;
		list-style: none;
		font-weight: 600;
		color: #333;
		padding: 1rem;
		border-radius: 10px !important;
		background: #0001;
		overflow: hidden;
		text-overflow: ellipsis;
	}
	.more-info li:hover {
		background: #00000018;
	}
	.more-info li span {
		font-weight: 400;
		color: #444;
	}

	/* attendance */
	.grid-content {
		font-family: 'Poppins', sans-serif;
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(max(300px, 30dvw), 1fr));
		gap: 0.5rem;
	}
	.grid-item {
		display: flex;
		gap: 0.5rem;
		list-style: none;
		font-weight: 600;
		color: #333;
		padding: 1rem;
		border-radius: 10px !important;
		background: #0001;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.grid-item:hover {
		background: #00000018;
	}
	.grid-item span {
		font-weight: 400;
		color: #444;
	}

	/* Salary details */
	.all-details-container {
		display: flex;
		flex-direction: column;
		gap: 2rem;
	}
	.details-container {
		font-family: 'Poppins', sans-serif;
	}
	.salary-details-table-container {
		border: 1px solid #0002;
		border-radius: 10px !important;
		border-top-left-radius: 0 !important;
		overflow: auto;

	}

	.details-container .title {
		width: fit-content;
		padding: .75rem 1.25rem;
		font-size: 1.4rem;
		font-weight: 500;
		color: #fff;
		background: #d2454d;
		border-radius: 5px !important;
		border-bottom-left-radius: 0 !important;
		border-bottom-right-radius: 0 !IMPORTANT;
	}
	.details-container table {
		border-radius: 10px !important;
		border-top-left-radius: 0 !important;
		background: #0001;
		font-size: 1.5rem;
		font-weight: 400;
		width: 100%;
	}
	.details-container table thead th {
		font-weight: 600;
		color: #333;
		border-bottom: 1px solid #0002;
		white-space: nowrap;
	}
	.details-container table thead th,
	.details-container table tbody td {
		padding: 1rem !important;
		border-right: 1px solid #0002;
	}

	.details-container table thead th:last-child,
	.details-container table tbody td:last-child  {
		border-right: none;
	}
</style>

<div class="page-content-wrapper">
    <div class="page-content" >
        <!--<div class="page-content" style="background-color: #eef1f5;">-->
        <div class="col-md-12 ">

		<div class="page-bar">

            <div class="page-toolbar">
                <ul class="page-breadcrumb">
                    <li class="<?= ($this->uri->segment(1) === 'show_firm') ? 'active' : '' ?>">
						Home
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
            <div class="row wrapper-shadow" id="HTMLtoPDF">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light portlet-fit portlet-form">
                        <div class="portlet-title" style="display:flex; justify-content:space-between;">
                            <div class="caption font-red-sunglo">
                                <i class="icon-settings font-red-sunglo"></i>
                                <span class="caption-subject bold uppercase">User Profile</span>
                            </div>
							<div></div> <div></div> <div></div> <div></div> <div></div> <div></div> <div></div> <div></div>
							<div></div> <div></div> <div></div> <div></div> <div></div> <div></div> <div></div> <div></div> <div></div> <div></div> <div></div>
							<div></div> <div></div> <div></div> <div></div> <div></div> <div></div> <div></div> <div></div> <div></div> <div></div> <div></div>
							<div></div> <div></div> <div></div> <div></div> <div></div> <div></div> <div></div> <div></div> <div></div> <div></div> <div></div>
							<div></div> <div></div> <div></div> <div></div> <div></div> <div></div> <div></div> <div></div> <div></div> <div></div> <div></div>
							<div>
								<?php if ($user_type == 5): ?>
									<div class="show_user_pdf" style="margin-top: 0.5rem;">
										<?php if (!empty($pdf_url)): ?>
											<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#companyPolicyPdf" data-firm-id="<?= $firm_id ?>"
												style="background: none; border-left: 0; border-right: 0; border-top: 0; border-bottom: 0; font-weight:700; font-size:1.5rem; color: #E26A6A;">
												<i class="fas fa-file-pdf red-pdf-icon"></i> UPLOAD PDF
											</button>
										<?php else: ?>
										<?php endif; ?>
									</div>
								<?php else: ?>
								<?php endif; ?>
							</div>
                        </div>
					</div>
				</div>
			</div>
            <h1><input type="hidden"  id="emp_idx" value="<?php echo $emp_id ?>"></h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="profile-content">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN PORTLET -->
                            <div class="portletlight " style=".portlet.light {padding: 0px !important;}">
                                <div class="portlet-title tabbable-custom ">
                                    <div class="caption caption-md">
                                        <i class="icon-globe theme-font hide"></i>

										<div class="profile-usertitle-name"> <?php// echo $user_id; ?></div>

                                  </div>
									<div class="tabs tabs-style-underline">
										<nav style="margin-bottom: 15px;">

											<ul>
												<?php if($user_type==2){?>
													<li class="tab-current"><a id="tab-0"  href="#tab_1_1"><i
																	class="fa fa-users mr-1 fa_class"></i> <span> Personal Info</span></a>
													</li>
													<li class=""><a id="tab-0"  href="#tab_1_3"><i
																	class="fa fa-users mr-1 fa_class"></i> <span> Salary Details</span></a>
													</li>
												<?php }?>
												<?php if($user_type==4){?>
													<li class="tab-current"><a id="tab-0"  href="#tab_1_1"><i
																	class="fa fa-users mr-1 fa_class"></i> <span> Personal Info</span></a>
													</li>
													<li class=""><a id="tab-0"  href="#tab_1_3"><i
																	class="fa fa-users mr-1 fa_class"></i> <span> Salary Details</span></a>
													</li>
													<li class=""><a id="tab-0"  href="#tab_1_2"><i
																	class="fa fa-users mr-1 fa_class"></i> <span> Leave Details</span></a>
													</li>
													<li class=""><a id="tab-0"  href="#tab_1_4"><i
																	class="fa fa-users mr-1 fa_class"></i> <span> Attendance Details</span></a>
													</li>

                                                    <li class=""><a id="tab-0"  href="#tab_1_5"><i
                                                                    class="fa fa-users mr-1 fa_class"></i> <span> Assets managment </span></a>
                                                    </li>

												<?php }?>
												<?php if ($user_type == 5) { ?>
													<li class="tab-current"><a id="tab-0"  href="#tab_1_1"><i
																	class="fa fa-users mr-1 fa_class"></i> <span> Personal Info</span></a>
													</li>
												<?php } else { ?>

												<?php } ?>

											</ul>
										</nav>

										<div class="content-wrap">
											<section class="content-current" id="tab_1_1">
												
												<!-- <form role="form" action="#">
													<?php foreach ($result as $row) ?>
													<div class="form-group">
														<label class="control-label">First Name</label>
														<input type="text" readonly  value="<?php echo $row->user_name; ?>"class="form-control"> </div>

													<div class="form-group">
														<label class="control-label">Mobile Number</label>
														<input type="text" readonly value="<?php echo $row->mobile_no; ?>" class="form-control"> </div>
													<div class="form-group">
														<label class="control-label">Email id</label>
														<input type="text" readonly value="<?php echo $row->email; ?>" class="form-control"> </div>
													<div class="form-group">
														<label class="control-label">Address</label>
														<input type="text" readonly value="<?php echo $row->address; ?> ,City: <?php echo $row->city ?> ,State: <?php echo $row->state ?>  ,Country:<?php echo $row->country ?>" class="form-control"> </div>
													<div class="form-group">
														<label class="control-label">Date of Joining</label>
														<input type="text"  class="form-control" readonly="" value="<?php
														//$newDate = date("d-m-Y", strtotime($row->date_of_joining));

														//echo $newDate;
														?>">
													</div>
												</form> -->


												<div class="personal-top">
													<div class="personal-info">
														<div class="avatar">
															<?php echo substr($row->user_name, 0, 2); ?>
														</div>

														<div class="right">
															<div class="info">
																<div class="name"><?php echo $row->user_name; ?></div>
																<div style=" display: flex; justify-content: space-between; align-items: baseline;">
																	<!-- <div class="show_user_pdf" style="margin-top: -2rem;">
																		<a onclick='showUserDetailsPdf(<?= json_encode($emp_id) ?>)' style="text-decoration: none; cursor:pointer;">Show PDF</a>
																	</div> -->
																	<div class="join mb-4" style="margin-right: 1rem;">Date of joining: <?php
																		$newDate = date("d-m-Y", strtotime($row->date_of_joining));
																		echo $newDate;?>
																	</div>
																	<?php if ($user_type == 5): ?>
																		<div class="show_user_pdf" style="margin-top: -2rem;">
																			<?php if (!empty($pdf_url)): ?>
																				<a href="<?= $pdf_url ?>" target="_blank" style="text-decoration: none; color:#fff; padding: 0.5rem;">Company Policy</a>
																			<?php else: ?>
																			<?php endif; ?>
																		</div>
																	<?php else: ?>
																	<?php endif; ?>
																</div>
															</div>
															<div class="role"> <?php echo $record_desig[0]->designation_name; ?></div>
														</div>
													</div>
												</div>

												<div class="more-info">
													<li>
														Senior Name:
														<span><?php echo isset($row->senior_name) && !empty($row->senior_name) ? $row->senior_name : "Not Assigned"; ?></span>
													</li>
													<li>
														Mobile No:
														<span><?php echo $row->mobile_no; ?></span>
													</li>
													<li>
														Email:
														<span><?php echo $row->email; ?></span>
													</li>
													<li>
														Address:
														<span><?php echo $row->address; ?></span>
													</li>
													<li>
														City:
														<span><?php echo $row->city ?></span>
													</li>
													<li>
														State:
														<span><?php echo $row->state ?></span>
													</li>
												</div>
											</section>

											<section class="" id="tab_1_3">
												<!-- <div class="portlet-body">
													<div class="panel-group accordion" id="accordion3">
														<div class="panel panel-default">
															<div class="panel-heading">
																<h4 class="panel-title">
																	<a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_1" aria-expanded="false">Salary Details</a>
																</h4>
															</div>
															<div id="collapse_3_1" class="panel-collapse collapse in" aria-expanded="false" style="">
																<div class="panel-body">
																	<form id="salary_details" name="salary_details" method="POST">
																		<input type="hidden" name="emp_id2" id="emp_id2" value="">
																		<input type="hidden" name="firm_id_a2" id="firm_id_a2" value="">

																		<div class="form-group">
																			<table class="table  no-bordered table-hover  dtr-inline dt-responsive dataTable " id="sample3" role="grid"  aria-describedby="sample_3_info">
																				<thead>
																				<tr id="rw">
																					<th>Type</th>
																					<th>Amount</th>
																				</tr>
																				</thead>
																				<?php
																				if ($record1 != '') {
																					foreach ($record1 as $row) {
																						//
																						?>


																						<tbody>
																						<tr >
																							<td><?php echo $row->pay_type; ?>  </td>
																							<td><?php echo $row->value; ?></td>
																						</tr>
																						</tbody>

																						<?php
																					}
																				} else {
																					?>
																					<tbody>
																					<style type="text/css">#rw{display:none !important;}</style>
																					<tr>
																						<td colspan="12"><?php echo 'No data'; ?>  </td>
																					</tr>
																					</tbody>

																				<?php  } ?>
																			</table> -->
																			<!--                                                                    <label>Type</label>
                                                                                                                                            <input type="text" readonly class="form-control" placeholder="" id="pay_type" name="pay_type" value="<?php echo $pay_type; ?>">-->

																		<!-- </div> -->
																		<!--<button type="button" class="btn btn-info btn-simplebtn blue-hoki btn-outline sbold uppercase popovers"  data-original-title title data-content="Show History"     onclick="get_view_salary_details()"><i class="fa fa-plus"  ></i></button>-->

																		<!-- <div id="data_salry_Details"></div>
																	</form>                                                    </div>
															</div>
														</div>
														<div class="panel panel-default">
															<div class="panel-heading">
																<h4 class="panel-title">
																	<a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_2" aria-expanded="false"> Deduction Details</a>
																</h4>
															</div>
															<div id="collapse_3_2" class="panel-collapse collapse" aria-expanded="true" style="height: 0px;">
																<div class="panel-body" style="height:200px; overflow-y:auto;">
																	<form id="due_details" name="due_details" method="POST">

																		<input type="hidden" name="emp_id3" id="emp_id3" value="">
																		<input type="hidden" name="firm_id_a3" id="firm_id_a3" value="">
																		<table  class="table  no-bordered table-hover  dtr-inline dt-responsive dataTable no-footer" id="sample2" role="grid" aria-describedby="sample_2_info">
																			<thead>
																			<tr id="dw">
																				<th>Deduction </th>
																				<th>Value </th>
																			</tr>
																			</thead>
																			<?php
																			if ($record != '') {
																				foreach ($record as $row) {
																					//
																					?>



																					<tbody>
																					<tr>
																						<td><?php echo $row->deduction; ?>  </td>
																						<td><?php echo $row->value; ?></td>
																					</tr>
																					</tbody>
																					<?php
																				}
																			} else {

																				?>
																				<tbody>
																				<style type="text/css">#dw{display:none !important;}</style>
																				<tr>
																					<td colspan="12"><?php echo 'No data'; ?>  </td>
																				</tr>
																				</tbody>
																			<?php } ?>
																		</table> -->
																		<!--<button type="button" class="btn btn-info btn-simplebtn blue-hoki btn-outline sbold uppercase popovers"  data-original-title title data-content="Show History"    onclick="get_view_Deu()"><i class="fa fa-plus"  ></i></button>-->



																		<!-- <div id="show_due_details"></div>
																	</form>
																</div>
															</div>
														</div>
														<div class="panel panel-default">
															<div class="panel-heading">
																<h4 class="panel-title">
																	<a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_3" aria-expanded="false">Loan Details </a>
																</h4>
															</div>
															<div id="collapse_3_3" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
																<div class="panel-body table-scrollable">
																	<form id="due_details" name="due_details" method="POST">

																		<input type="hidden" name="emp_id3" id="emp_id3" value="">
																		<input type="hidden" name="firm_id_a3" id="firm_id_a3" value="">
																		<table  class="table  table-bordered table-hover  dtr-inline dt-responsive dataTable no-footer"  id="sample5" role="grid" aria-describedby="sample_5_info">
																			<thead>
																			<tr>
																				<th>Loan Details</th>
																				<th>Loan Amount</th>
																				<th>EMI Amount</th>
																				<th>Start Month and Year</th>
																				<th>Total Month</th>
																				<th>Sanction Date</th>
																			</tr>
																			</thead>
																			<?php
																			if ($record_loan != '') {
																				foreach ($record_loan as $row) {
																					?>


																					<tbody>
																					<tr>
																						<td><?php echo $row->loan_detail; ?></td>
																						<td><?php echo $row->amount; ?></td>
																						<td><?php echo $row->EMI_amt; ?></td>

																						<td><?php echo $row->from_month; ?> ,<?php echo $row->Fyear; ?></td>
																						<td><?php echo $row->total_month; ?></td>
																						<td><?php echo $row->sanction_date; ?></td>
																					</tr>
																					</tbody>

																					<?php
																				}
																			} else {
																				?>
																			<?php } ?>

																		</table> -->

																		<!--<button type="button" class="btn btn-info btn-simplebtn blue-hoki btn-outline sbold uppercase popovers"  data-original-title title data-content="Show History"     onclick="get_staffloan()"><i class="fa fa-plus"  ></i></button>-->


																		<!-- <div id="get_staffloan1"></div>
																	</form>
																</div>
															</div>
														</div>
														<div class="panel panel-default">
															<div class="panel-heading">
																<h4 class="panel-title">
																	<a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_4" aria-expanded="true"> Performance  Details</a>
																</h4>
															</div>
															<div id="collapse_3_4" class="panel-collapse collapse " aria-expanded="false" style="height: 0px;">
																<div class="panel-body table-scrollable">
																	<form id="due_details" name="due_details" method="POST">

																		<input type="hidden" name="emp_id3" id="emp_id3" value="">
																		<input type="hidden" name="firm_id_a3" id="firm_id_a3" value="">
																		<table   class="table table-bordered table-hover dtr-inline dt-responsive dataTable no-footer" id="sample_editable_1 " role="grid" aria-describedby="sample_editable_1_info">
																			<thead>
																			<tr>
																				<th>Performance Bonus</th>

																				<th>Start Month </th><th> Year</th>

																				<th> Date</th>
																			</tr>
																			</thead>
																			<?php
																			if ($record_pa != '') {
																				foreach ($record_pa as $row) {
																					//
																					?>


																					<tbody>
																					<tr>
																						<td><?php echo $row->value; ?></td>

																						<td><?php echo $row->Month; ?> </td><td><?php echo $row->FYear; ?></td>
																						<td><?php echo $row->Date_of_PA; ?></td>
																					</tr>
																					</tbody>

																					<?php
																				}
																			} else {
																				?>
																			<?php } ?>

																		</table> -->
																		<!--<button type="button" class="btn btn-info btn-simplebtn blue-hoki btn-outline sbold uppercase popovers"  data-original-title title data-content="Show History"    onclick="get_pa()"><i class="fa fa-plus"  ></i></button>-->
																		<!-- <div id="get_pa"></div>
																	</form>
																</div>
															</div>
														</div>
													</div>
												</div> -->


												<div class="all-details-container">
													<div class="details-container">
														<div class="title">Salary Details</div>
														<div class="salary-details-table-container">
															<table id="sample3">
																<thead>
																	<tr id="rw">
																		<th style="width: 50%">Type</th>
																		<th>Amount</th>
																	</tr>
																</thead>
																<?php if ($record1 != '') {
																	foreach ($record1 as $row) { ?>
																		<tbody>
																			<tr >
																				<td><?php echo $row->pay_type; ?>  </td>
																				<td><?php echo $row->value; ?></td>
																			</tr>
																		</tbody>
																<?php }
																	} else { ?>
																	<tbody>
																		<style type="text/css">#rw{display:none !important;}</style>
																		<tr>
																			<td colspan="12"><?php echo 'No data'; ?>  </td>
																		</tr>
																	</tbody>
																<?php  } ?>
															</table>
														</div>
													</div>

													<div class="details-container">
														<div class="title">Deduction Details</div>
														<div class="salary-details-table-container">
															<table id="sample2">
																<thead>
																	<tr>
																		<th  style="width: 50%">Deduction</th>
																		<th>Amount</th>
																	</tr>
																</thead>
																<?php if ($record != '') {
																	foreach ($record as $row) { ?>
																		<tbody>
																			<tr >
																				<td><?php echo $row->deduction; ?>  </td>
																				<td><?php echo $row->value; ?></td>
																			</tr>
																		</tbody>
																<?php }
																	} else { ?>
																	<tbody>
																		<style type="text/css">#rw{display:none !important;}</style>
																		<tr>
																			<td colspan="12"><?php echo 'No data'; ?>  </td>
																		</tr>
																	</tbody>
																<?php  } ?>
															</table>
														</div>
													</div>

													<div class="details-container">
														<div class="title">Loan Details</div>
														<div class="salary-details-table-container">
															<table id="sample5">
																<thead>
																	<tr>
																		<th>Loan Details</th>
																		<th>Loan Amount</th>
																		<th>EMI Amount</th>
																		<th>Start Month and Year</th>
																		<th>Total Month</th>
																		<th>Sanction Date</th>
																	</tr>
																</thead>

																<?php if (!empty($record_loan)) {
																	foreach ($record_loan as $row) { ?>
																		<tbody>

																			<tr>
																				<td><?php echo $row->loan_detail; ?></td>
																				<td><?php echo $row->amount; ?></td>
																				<td><?php echo $row->EMI_amt; ?></td>

																				<td><?php echo $row->from_month; ?> ,<?php echo $row->Fyear; ?></td>
																				<td><?php echo $row->total_month; ?></td>
																				<td><?php echo $row->sanction_date; ?></td>
																			</tr>
																		</tbody>
																<?php }
																	} else { ?>
																	<tbody>
																		<tr>
																			<td style="text-align: center" colspan="12"><?php echo 'No data'; ?>  </td>
																		</tr>
																	</tbody>
																<?php  } ?>
															</table>
														</div>
													</div>

													<div class="details-container">
														<div class="title">Performance Details</div>
														<input type="hidden" name="emp_id3" id="emp_id3" value="">
														<input type="hidden" name="firm_id_a3" id="firm_id_a3" value="">
														<div class="salary-details-table-container">
															<table >
																<thead>
																<tr>
																	<th>Performance Bonus</th>
																	<th>Start Month </th><th> Year</th>
																	<th> Date</th>
																</tr>
																</thead>
																<?php
																if (!empty($record_pa)) {
																	foreach ($record_pa as $row) {  ?>
																		<tbody>
																			<tr>
																				<td><?php echo $row->value; ?></td>
																				<td><?php echo $row->Month; ?> </td><td><?php echo $row->FYear; ?></td>
																				<td><?php echo $row->Date_of_PA; ?></td>
																			</tr>
																		</tbody>

																		<?php
																	}
																} else { ?>
																	<tbody>
																		<tr>
																			<td style="text-align: center" colspan="12"><?php echo 'No data'; ?>  </td>
																		</tr>
																	</tbody>
																<?php } ?>

															</table>
														</div>
													</div>
												</div>
											</section>

											<section class="" id="tab_1_2">
												<!-- <form action="#" role="form">
													<div class="row">
														<div class="col-md-12">
															<div class="portlet light bordered">
																<?php
																$record_dec_leave = $record_leave_user[0]->total_leaves;
																$record_dec_leave11 = number_format((float) $record_dec_leave, 2, '.', '');
																$senuser_name = '';
																?>

																<?php  if (count($record_desig) > 0) { ?>
																<div class="row">
																	<div class="col-md-12">
																		<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Your Designation &nbsp;: </lable>&nbsp; <?php echo $record_desig[0]->designation_name; ?></div>
																		<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Opening Leaves Balance &nbsp;: </lable>&nbsp; <?php echo $record_dec_leave11; ?></div>
																	</div>
																</div>
																<div class="row"><br>
																	<div class="col-md-12">
																		<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;"> Monthly Leaves &nbsp;: </lable>&nbsp; <?php echo round($record_desig[0]->total_monthly_leaves, 2);
																			?></div>
																		<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Senior Authority &nbsp;: </lable>&nbsp; <?php echo $senuser_name; ?></div>

																	</div>
																</div>
																<div class="row"><br>
																	<div class="col-md-12">
																		<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;"> Leaves Taken&nbsp;: </lable><?php echo ($recordsq->leave_taken); ?></div>
																		<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Remaining Leaves &nbsp;: </lable>&nbsp; <?php echo ($recordsq->total_leave_available);
																			?> </div>

																	</div>
																</div>
																		<?php } else { echo "No Data Found"; } ?> -->
																<!-- </div> -->

																<!-- <div class="row"><br>

																	<div class="col-md-12 table-scrollable"> -->

																		<!--<table class="table table-striped table-bordered table-hover dtr-inline  dataTable no-footer" id="sample1" role="grid" aria-describedby="sample_1_info">
																			<thead>
																			<tr role="row">
																				<th style="text-align:center" scope="col">Type</th>
																				<th style="text-align:center" scope="col">No of days</th>
																				<th style="text-align:center" scope="col">Carry Forward</th>
																				<th style="text-align:center" scope="col">Avail Leaves</th>
																				<th style="text-align:center" scope="col">Leave in Approve</th>
																				<th>Remaining  Leaves </th>

																			</tr>
																			</thead>
																			<tbody>


																			</tbody>


																			</tbody>                                                                        <tbody>
																																					</tbody>

																		</table>-->


																		<!--                                                                        <table class="table table-striped table-bordered table-hover dtr-inline  dataTable no-footer" id="sample_1" role="grid" aria-describedby="sample_1_info">
																			<thead>
																				<tr role="row">
																					<th style="text-align:center" scope="col">Type</th>
																					<th style="text-align:center" scope="col">No of days</th>
																					<th style="text-align:center" scope="col">Carry Forward</th>
																					<th style="text-align:center" scope="col">Avail Leaves</th>
																					<th style="text-align:center" scope="col">Leave in Approve</th>
																					<th>Remaining  Leaves </th>
																					<th style="text-align:center" scope="col">Request Before</th>
																				</tr>
																			</thead>
																			<tbody>
																	?>
																			</tbody>

																		</table>-->
																	<!-- </div>
																</div>

															</div>
														</div>
													</div>

												</form> -->

												<div class="grid-content">
													<div class=""></div>
													<div class=""></div>
													<div class="show_user_pdf" style="margin-top: 2rem; margin-bottom: 2rem; margin-left: 47rem;">
														<?php if (!empty($pdf_url)): ?>
															<a href="<?= $pdf_url ?>" target="_blank" style="text-decoration: none; background-color: #337ab7; color:#fff; padding: 1rem;">Company Policy</a>
														<?php else: ?>
														<?php endif; ?>
													</div>
													<div class="grid-item">Your Designation &nbsp;: <span>&nbsp; <?php echo $record_desig[0]->designation_name; ?></span></div>
													<div class="grid-item">Opening Leaves Balance &nbsp;: <span>&nbsp; <?php echo $record_dec_leave11; ?></span></div>
													<!-- <div class="grid-item">Monthly Leaves &nbsp;: <span>&nbsp; <?php echo round($record_desig[0]->total_monthly_leaves, 2); ?></span></div> -->
													<div class="grid-item">Senior Authority &nbsp;: <span>&nbsp; <?php echo $senuser_name; ?></span></div>
													<div class="grid-item">Leaves Taken&nbsp;: <span><?php echo ($recordsq->leave_taken); ?></span></div>
													<div class="grid-item" >Remaining Leaves &nbsp;:
														<div class="">
															<span>PL : &nbsp; <?php echo ($type_balance->type1_balance); ?></span> , 
														</div>
														<div class="">
															<span>EL : &nbsp; <?php echo ($type_balance->type2_balance); ?></span> , 
														</div>
														<div class="">
															<span>SL : &nbsp; <?php echo ($type_balance->type3_balance); ?></span>
														</div>
												
													</div>
												</div>

											</section>

											<section class="tab-pane" id="tab_1_4">
												<form action="#" role="form">
													<input type="hidden" name="emp_id2" id="emp_id2" value="<?php echo $emp_id; ?>">
													<!--<input type="text" name="firm_id_a2" id="firm_id_a2" value="<?php echo $user_id; ?>">-->

													<!-- <div class="row">
														<div class="col-md-12"> -->

															<?php
															$applicable_sts = $result[0]->applicable_status;

															if ($applicable_sts == 1) {
																$applicable_sts1 = "Punch In Applicable";
															} else if ($applicable_sts == 2) {
																$applicable_sts2 = "Punch In Not Applicable";
															} else if ($applicable_sts == 3) {
																$applicable_sts3 = "Not Applicable & Leave Request";
															} else if ($applicable_sts == 4) {
																$applicable_sts4 = "Outside Punch Applicable";
															} else if ($applicable_sts == 5) {
																$applicable_sts5 = "Shift Applicable";
															} else {
																$applicable_sts1 = '';
															}

															$work_hour_status = $result[0]->work_hour_status;
															if ($work_hour_status == 1) {
																$work_hour_status1 = "Yes";
															} else {
																$work_hour_status2 = "No";
															}

															$fixed_time = $result[0]->fixed_time;


															$work_schedule_status = $result[0]->work_schedule_status;
															if ($work_schedule_status == 1) {
																$work_schedule_status1 = "Fix";
															} else if ($work_schedule_status == 2) {
																$work_schedule_status2 = "Day Wise";
															} else {

															}

															// $late_applicable_sts = $result[0]->late_applicable_sts;
															// if($late_applicable_sts ==0){
															//     $late_applicable_sts1 = "Not Allowed";
															// }else{
															//     $late_applicable_sts1 ="Allowed";
															// }

															$overtime_applicable_sts =$result[0]->overtime_applicable_sts;
															if($overtime_applicable_sts==1){
																$overtime_applicable_sts1="Yes";
															}else if($overtime_applicable_sts==0){
																$overtime_applicable_sts1="No";
															}else{
																$overtime_applicable_sts1="Not Set!";
															}

															$salary_calculation =$result[0]->salary_calculation;
															if($salary_calculation==1){
																$salary_calculation1 ="Daily";
															}else if($salary_calculation==2){
																$salary_calculation1 ="Monthly";
															}else{
																$salary_calculation1 ="Not Set!";
															}

															/* Outside punch applicable*/
															$outside_punch_applicable = $result[0]->outside_punch_applicable;
															if($outside_punch_applicable==1){
																$outside_punch_applicable1="Yes";
															}else{
																$outside_punch_applicable1="No";
															}

															/*Shift applicable */
															$shift_applicable = $result[0]->shift_applicable;
															if($shift_applicable==1){
																$shift_applicable1="Yes";
															}else{
																$shift_applicable1="No";
															}

															/*Holiday working applicable*/
															$holiday_working_sts = $result[0]->holiday_working_sts;
															if($holiday_working_sts==1){
																$holiday_working_sts1="Applicable";
															}else if($holiday_working_sts==2){
																$holiday_working_sts1="Not Applicable";
															}else{
																$holiday_working_sts1="Applicable & Approval required";
															}


															/*Late Applicable Status*/
															$late_applicable_sts = $result[0]->late_applicable_sts;
															if($late_applicable_sts==0){
																$result_late_applicable="No";
															}else{
																$late_applicable_sts1 = $result[0]->late_salary_cut_days;
																$late_applicable_sts2 = $result[0]->late_salary_days_count;
																$late_applicable_sts3 = $result[0]->late_minute_count;
																$result_late_applicable ="If you are late by $late_applicable_sts3 minutes for $late_applicable_sts1 days then  $late_applicable_sts2 days salary will be deducted from your salary.";
															}



															$fixed_hour = $result[0]->fixed_hour;
															?>

															<?php if ($applicable_sts == 1 && $work_hour_status == 1 && $work_schedule_status == 1) { ?>
																<div class="grid-content">
																	<div class="grid-item"> Attendance : <span><?php echo $applicable_sts1; ?></span> </div>
																	<div class="grid-item"> Fix In time Applicable : <span> <?php echo $work_hour_status1; ?>&nbsp;<b>( <?php echo $fixed_time; ?>) </span> </div>
																	<div class="grid-item"> Outside Punch Applicable : <span> <?php echo $outside_punch_applicable1; ?> </span> </div>
																	<div class="grid-item"> Shift Applicable : <span> <?php echo $shift_applicable1; ?> </span> </div>
																	<div class="grid-item"> Schedule Type : <span> <?php echo $work_schedule_status1; ?> </span> </div>
																	<div class="grid-item"> Overtime Status : <span> <?php echo $overtime_applicable_sts1; ?> </span> </div>
																	<div class="grid-item"> Salary Status : <span> <?php echo $salary_calculation1; ?> </span> </div>
																	<div class="grid-item"> Holiday Working : <span> <?php echo $holiday_working_sts1; ?> </span> </div>
																	<div class="grid-item"> Late Applicable : <span> <?php echo $result_late_applicable; ?> </span> </div>
																</div>

																		<!--<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Attendance  &nbsp;: </lable>&nbsp; <?php echo $result[0]->user_name; ?></div>-->
																<!-- <div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Attendance  &nbsp;: </lable>&nbsp; <?php echo $applicable_sts1; ?></div>
																<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Fix In time Applicable: &nbsp;: </lable>&nbsp; <?php echo $work_hour_status1; ?>&nbsp;<b>( <?php echo $fixed_time; ?>)</b></div><hr> -->
																		<!--outside punch applicable-->
																<!-- <div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Outside Punch Applicable: &nbsp;: </lable>&nbsp; <?php echo $outside_punch_applicable1; ?>&nbsp;</div> -->
																		<!--Shift applicable-->
																<!-- <div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Shift Applicable: &nbsp;: </lable>&nbsp; <?php echo $shift_applicable1; ?>&nbsp;</div><hr><hr>
																<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Schedule Type  &nbsp;: </lable>&nbsp; <?php echo $work_schedule_status1; ?>&nbsp;<b>(<?php echo $fixed_hour; ?>)</b></div>

																<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Overtime Status  &nbsp;: </lable>&nbsp; <?php echo $overtime_applicable_sts1; ?>&nbsp;</div><hr><hr>
																<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Salary Status  &nbsp;: </lable>&nbsp; <?php echo $salary_calculation1; ?>&nbsp;</div>
																<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Holiday Working   &nbsp;: </lable>&nbsp; <?php echo $holiday_working_sts1; ?>&nbsp;</div><hr><hr>
																<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Late Applicable   &nbsp;: </lable>&nbsp; <?php echo $result_late_applicable; ?>&nbsp;</div> -->
																		<!--<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Fixed Hour  &nbsp;: </lable>&nbsp; <?php echo $fixed_hour; ?></div>--><hr>
															<?php } else if ($applicable_sts == 1  && $work_schedule_status == 2) { ?>
																<div class="grid-content">
																	<div class="grid-item"> Attendance : <span><?php echo $applicable_sts1; ?></span> </div>
																	<div class="grid-item"> Outside Punch Applicable : <span> <?php echo $outside_punch_applicable1; ?> </span> </div>
																	<div class="grid-item"> Shift Applicable : <span> <?php echo $shift_applicable1; ?> </span> </div>
																	<div class="grid-item"> Work Schedule : <span> <?php echo $work_schedule_status2; ?> </span> </div>
																	<div class="grid-item"> Overtime Status : <span> <?php echo $overtime_applicable_sts1; ?> </span> </div>
																	<div class="grid-item"> Salary Status : <span> <?php echo $salary_calculation1; ?> </span> </div>
																	<div class="grid-item"> Holiday Working : <span> <?php echo $holiday_working_sts1; ?> </span> </div>
																	<div class="grid-item"> Late Applicable : <span> <?php echo $result_late_applicable; ?> </span> </div>
																</div>

																<!-- <div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Attendance  &nbsp;: </lable>&nbsp; <?php echo $applicable_sts1; ?></div> -->
																		<!--<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Fix In time Applicable: &nbsp;: </lable>&nbsp; <?php echo $work_hour_status2; ?></div><hr>-->
																		<!--outside punch applicable-->
																<!-- <div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Outside Punch Applicable: &nbsp;: </lable>&nbsp; <?php echo $outside_punch_applicable1; ?>&nbsp;</div><hr> -->
																		<!--Shift applicable-->
																<!-- <div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Shift Applicable: &nbsp;: </lable>&nbsp; <?php echo $shift_applicable1; ?>&nbsp;</div>
																<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Work Schedule  &nbsp;: </lable>&nbsp; <?php echo $work_schedule_status2; ?></div><hr><hr>
																<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Overtime Status  &nbsp;: </lable>&nbsp; <?php echo $overtime_applicable_sts1; ?>&nbsp;</div>
																<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Salary Status  &nbsp;: </lable>&nbsp; <?php echo $salary_calculation1; ?>&nbsp;</div><hr><hr>
																<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Holiday Working  &nbsp;: </lable>&nbsp; <?php echo $holiday_working_sts1; ?>&nbsp;</div>
																<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Late Applicable   &nbsp;: </lable>&nbsp; <?php echo $result_late_applicable; ?>&nbsp;</div> -->
																		<!--If work hour schedule type is 2-->
																<br>  <br>
																<div class="">
																<?php
																if ($result_emp_time != '') {
																	?>
																	<table class="table table-striped table-bordered table-hover dtr-inline  dataTable footer" id="sample_7" role="grid" aria-describedby="sample_7_info">
																		<thead>
																		<tr>
																			<th>Week Day</th>
																			<th>Type</th>
																			<td>Time</td>
																			<td>In time</td>
																		</tr>
																		</thead>
																		<?php
																		$arr_day = array(
																				"day0" => "Monday",
																				"day1" => "Tuesday",
																				"day2" => "Wednesday",
																				"day3" => "Thursday",
																				"day4" => "Friday",
																				"day5" => "Saturday",
																				"day6" => "Sunday",);
																		// $cnt = 1;
																		for ($i = 0; $i < count($result_emp_time); $i++) {
																		?>

																		<?php
																		$week_type_check = $result_emp_time[$i]->type;
																		if ($week_type_check == 1) {
																			$week_type_name = 'Working';
																		} else {
																			$week_type_name = 'Off';
																		}

																		$in_time_applicable_sts = $result_emp_time[$i]->in_time_applicable_sts;
																		if ($in_time_applicable_sts == 1) {
																			$in_time_applicable_check = 'Yes';
																		} else {
																			$in_time_applicable_check = 'No';
																		}

																		?>

																		<tbody>
																		<tr>

																			<td><?php echo $arr_day['day' . $i] ?></td>
																			<td><?php echo $week_type_name; ?></td>
																			<?php if ($week_type_check == 1) { ?>
																				<td><?php echo $result_emp_time[$i]->fixed_hour; ?></td>
																			<?php } else { ?>
																				<td>Any Time </td>
																			<?php } ?>


																			<?php if ($in_time_applicable_sts == 1) { ?>
																				<td><?php echo $result_emp_time[$i]->in_fixed_time; ?></td>
																			<?php } else { ?>
																				<td>Any Time </td>
																			<?php } ?>
																		</tr>

																		<?php
																		//                                                                    $cnt ++;
																		}
																		?></tbody>
																	</table></div>
																<?php } else { ?><?php } ?>

															<?php } else if ($applicable_sts == 2) { ?>
																<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Attendance  &nbsp;: </lable>&nbsp; <?php echo $applicable_sts2; ?></div>
															<?php } else if ($applicable_sts == 1 && $work_hour_status == 2 && $work_schedule_status == 1) { ?>
																<div class="grid-content">
																	<div class="grid-item"> Attendance : <span><?php echo $applicable_sts1; ?></span> </div>
																	<div class="grid-item"> Fix In time Applicable : <span> <?php echo $work_hour_status2; ?></span> </div>
																	<div class="grid-item"> Outside Punch Applicable : <span> <?php echo $outside_punch_applicable1; ?> </span> </div>
																	<div class="grid-item"> Shift Applicable : <span> <?php echo $shift_applicable1; ?> </span> </div>
																	<div class="grid-item"> Fixed Hour : <span> <?php echo $fixed_hour; ?> </span> </div>
																	<div class="grid-item"> Schedule Type : <span> <?php echo $work_schedule_status1; ?> </span> </div>
																	<div class="grid-item"> Overtime Status : <span> <?php echo $overtime_applicable_sts1; ?> </span> </div>
																	<div class="grid-item"> Salary Status : <span> <?php echo $salary_calculation1; ?> </span> </div>
																	<div class="grid-item"> Holiday Working : <span> <?php echo $holiday_working_sts1; ?> </span> </div>
																	<div class="grid-item"> Late Applicable : <span> <?php echo $result_late_applicable; ?> </span> </div>
																</div>

																<!-- <div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Attendance  &nbsp;: </lable>&nbsp; <?php echo $applicable_sts1; ?></div>
																<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Fix In time Applicable:  &nbsp;: </lable>&nbsp; <?php echo $work_hour_status2; ?></div><hr> -->
																		<!--outside punch applicable-->
																<!-- <div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Outside Punch Applicable: &nbsp;: </lable>&nbsp; <?php echo $outside_punch_applicable1; ?>&nbsp;</div> -->
																		<!--Shift applicable-->
																<!-- <div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Shift Applicable: &nbsp;: </lable>&nbsp; <?php echo $shift_applicable1; ?>&nbsp;</div><hr><hr> -->
																		<!--<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Fixed Hour  &nbsp;: </lable>&nbsp; <?php echo $fixed_hour; ?></div>-->
																<!-- <div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Schedule Type  &nbsp;: </lable>&nbsp; <?php echo $work_schedule_status1; ?>&nbsp;<b>(<?php echo $fixed_hour; ?>)</b></div>
																<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Overtime Status  &nbsp;: </lable>&nbsp; <?php echo $overtime_applicable_sts1; ?>&nbsp;</div><hr><hr>
																<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Salary Status  &nbsp;: </lable>&nbsp; <?php echo $salary_calculation1; ?>&nbsp;</div>
																<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Holiday Working   &nbsp;: </lable>&nbsp; <?php echo $holiday_working_sts1; ?>&nbsp;</div><hr><hr>
																<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Late Applicable   &nbsp;: </lable>&nbsp; <?php echo $result_late_applicable; ?>&nbsp;</div> -->
															<?php } else if ($applicable_sts == 0 && $work_hour_status == 0 && $work_schedule_status == 0) { ?>
																<div class="col-md-6"><lable style="font-weight: bold;font-size: 15px;">Warning  &nbsp;: </lable>Attendance Information Not given!</div>
															<?php }else { ?><?php }?>

														<!-- </div>
													</div> -->
												</form>
											</section>

                                            <section class="tab-pane" id="tab_1_5">
                                                <input type="hidden" id="emp_id" name="emp_id" value="<?php echo $emp_id; ?>">
                                                <div class="container-fluid">
                                                    <table id="assets_datatable" class="table table-striped table-bordered" style="width:100%">
                                                        <thead>
                                                        <tr>
                                                            <th>SR No. </th>
                                                            <th> Assets Type </th>
                                                            <th> Assets Details  </th>
                                                            <th> Brand Name </th>
                                                            <th> Model Name </th>
                                                            <th> Specification </th>
                                                            <th>Description</th>
                                                            <th> Purchase Date </th>
                                                            <th> Created By </th>
                                                            <th> Status </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </section>
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
</div>
</div>


<div class="modal fade" id="companyPolicyPdf" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="companyPolicyPdfLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <div style="display: flex;justify-content: space-between;">
                <h5 class="modal-title" id="exampleModalToggleLabel">Company Policy Pdf</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" style="border: 0;">&times;</button>
            </div>
        </div>
        <div class="modal-body">
			<form id="uploadCompanyPolicyPdf" enctype="multipart/form-data">
				<input type="hidden" name="firm_id" id="firm_id">
				<div class="form-group">
					<label for="pdf_url">Upload PDF</label>
					<input name="pdf_url" id="pdf_url" type="file" class="form-control" rows="4" placeholder="Select Pdf">
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" onclick="uploadCompanyPolicyPdfBtn(event)">Submit</button>
				</div>
			</form>
		</div>
    </div>
</div>


<script src="<?= base_url() ?>assets/js/cbpFWTabs.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>js/script.js" type="text/javascript"></script>
<script>
	$(document).ready(function () {
		[].slice.call(document.querySelectorAll('.tabs')).forEach(function (el) {
			new CBPFWTabs(el);
		});
	});
    function get_view_Deu() {
		//
        var emp_id = document.getElementById('emp_idx').value;
        $.ajax({
            type: "POST",
            url: "<?= base_url("UserProfile/view_Deu") ?>",
            dataType: "JSON",
            async: false,
            cache: false,
            data: {emp_id3: emp_id},
            success: function (result) {
                var data = result.result_data3;
                if (result.status == 200) {
                    $('#show_due_details').html(data);
                    $('#result_data3').DataTable();
                } else {
                    $('#show_due_details').html("");
                }
            }
        });
    }

    function get_view_salary_details() {
        var emp_id = document.getElementById('emp_idx').value;
        $.ajax({
            type: "POST",
            url: "<?= base_url("UserProfile/view_salary_details") ?>",
            dataType: "JSON",
            async: false,
            cache: false,
            data: {emp_id2: emp_id},
            success: function (result) {
                var data = result.result_data1;
                if (result.status == 200) {
                    $('#data_salry_Details').html(data);
                    $('#data_table2').DataTable();
                } else {
                    $('#data_salry_Details').html("");
                }
            }
        });
    }


    function get_sal_type_list() {
        var firm_id = document.getElementById('firm_id').value;
        $.ajax({
            type: "POST",
            url: "<?= base_url("SalaryInfoController/get_sal_type_list") ?>",
            dataType: "JSON",
            async: false,
            cache: false,
            data: {firm_id: firm_id},
            success: function (result) {
                //                                                                     result = JSON.parse(result);
                var data = result.sal_options;
                if (result.code == 200) {
                    $('#sal_options').html(data);
                } else {
                    $('#sal_options').html("");
                }
            },
        });
    }

    function get_ded_type_list() {
        var firm_id = document.getElementById('firm_id').value;
        $.ajax({
            type: "POST",
            url: "<?= base_url("SalaryInfoController/get_ded_type_list") ?>",
            dataType: "json",
            async: false,
            cache: false,
            data: {firm_id: firm_id},
            success: function (result) {
				// result = JSON.parse(result);
                var data = result.ded_options;
                if (result.code == 200) {
                    $('#ded_options').html(data);
                } else {
                    $('#ded_options').html("");
                }
            },
        });
    }

    function get_staffloan() {
        var emp_id = document.getElementById('emp_idx').value;
        $.ajax({
            type: "POST",
            url: "<?= base_url("UserProfile/get_staffloan") ?>",
            dataType: "json",
            async: false,
            cache: false,
            data: {emp_id4: emp_id},
            success: function (result) {
                var data = result.result_data3;
                if (result.status == 200) {
                    $('#get_staffloan1').html(data);
                    $('#data_table4').DataTable();
                } else {
                    $('#get_staffloan1').html("");
                }
            }
        });
    }

    function get_pa() {
        var emp_id = document.getElementById('emp_idx').value;
        $.ajax({
            type: "POST",
            url: "<?= base_url("UserProfile/get_pa") ?>",
            dataType: "json",
            async: false,
            cache: false,
            data: {emp_id5: emp_id},
            success: function (result) {
                var data = result.result_data3;
                if (result.status == 200) {
                    $('#get_pa').html(data);
                    $('#data_table5').DataTable();
                } else {
                    $('#get_pa').html("");
                }
            }
        });

    }

	function showUserDetailsPdf(empId) {
		window.open("<?= base_url('UserProfile/showUserPdf?user_id=') ?>" + empId, "_blank");
	}
  
	function showUserDetailsPdf(empId) {
		window.open("<?= base_url('show_user_pdf/') ?>" + empId, "_blank");
	}


	$('#companyPolicyPdf').on('show.bs.modal', function (event) {
		let button = $(event.relatedTarget); 
		let firmId = button.data('firm-id');
		$('#firm_id').val(firmId); 
	});

	function uploadCompanyPolicyPdfBtn1(e) {
		e.preventDefault();
		let url = '<?php base_url() ?>' + 'upload_company_policy_pdf';
		let firm_id = document.getElementById('firm_id').value;
		let pdf_url = document.getElementById('pdf_url').value;
		// alert("Firm Id is : " + firm_id + "\n" + "Pdf Url is : " + pdf_url);
		$.ajax({
			url: url,
			type: 'POST',
			data: {
				firm_id: firm_id,
				pdf_url: pdf_url
			},
			success: function (res) {
				alert(res.message);
				$('#companyPolicyPdf').modal('hide');
			},
		});
	}

	function uploadCompanyPolicyPdfBtn(e) {
		e.preventDefault();
		let url = '<?php echo base_url("upload_company_policy_pdf"); ?>';
		let firm_id = document.getElementById('firm_id').value;
		let pdf_file = document.getElementById('pdf_url').files[0];
		let formData = new FormData();
		formData.append('firm_id', firm_id);
		formData.append('pdf_url', pdf_file);
		$.ajax({
			url: url,
			type: 'POST',
			data: formData,
			contentType: false,
			processData: false,
			success: function (res) {
				let response = JSON.parse(res);
				alert(response.message);
				$('#companyPolicyPdf').modal('hide');
			},
			error: function (xhr) {
				try {
					let err = JSON.parse(xhr.responseText); // ye json ke liye hai, agar backend JSON bhej raha hai
					alert("Error: " + err.message);
				} catch (e) {
					alert("Unexpected Error: " + xhr.responseText); // agar JSON nahi hai
				}
			}
		});
	}



	let data = JSON.parse('<?php echo json_encode($result); ?>')
	console.log(data[0]);


</script>
