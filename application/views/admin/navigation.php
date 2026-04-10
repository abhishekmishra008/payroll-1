
<?php $this->load->view('admin/header'); ?>
<div class="clearfix"> </div>

<div class="page-container" style="background-color: #343434 !important;">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <!-- BEGIN SIDEBAR -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <div class="page-sidebar navbar-collapse collapse" style="background-color: #343434 !important;">
            <!-- BEGIN SIDEBAR MENU -->
            <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
            <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
            <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
            <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
            <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
            <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
            <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" >



                <li class="nav-item  <?= ($this->uri->segment(1) === 'show_firm') ? 'active' : '' ?>">
                    <a href="<?= base_url() ?>show_firm" class="nav-link">
                        <i class="fa fa-building-o"></i>
                        <span class="title">HR Admin List </span>
                        <span class="arrow"></span>
                    </a>
                </li>            
                <li class="<?= ($this->uri->segment(1) === 'hq_show_firm_hr') ? 'active open' : '' ?>">
                    <a href="<?= base_url() ?>hq_show_firm_hr" class="nav-link nav-toggle">
                        <i class="icon-puzzle"></i>
                        <span class="title">List of Office</span>
                        <!--<span class="arrow"></span>-->
                    </a>

                </li>

                <li class="nav-item  <?= ($this->uri->segment(1) === 'Login/admin_logout') ? 'active' : '' ?>">
                    <a href="<?= base_url() ?>Login/admin_logout" class="nav-link nav-toggle">
                        <i class="icon-logout"></i>
                        <span class="title">Logout</span>
                        <span class="arrow"></span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- END SIDEBAR -->
    </div>
    <!--</div>-->