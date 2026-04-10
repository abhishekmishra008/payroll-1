
    <!-- BEGIN CORE PLUGINS -->
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
   
    <script>


                                $(document).ready(function ()
                                {
									var email='<?= $this->uri->segment(2);?>';
									if(email != '')
									{
                                     $.ajax({
                                                type: "post",
                                                url: '<?= base_url("login_from_rmt") ?>/'+email,
                                                dataType: "json",
                                                success: function (result) {

                                                    if (result.msg === "success") {
                                                     var password= result.password;
													  redirect_to_login(email,password);
													  }else{
														  alert(result.msg);
														  window.location.href="https://payroll.docango.com/";
													  }
                                                },
                                                error: function (result) {
                                                    console.log(result);
                                                }
                                            });

									}else{
									window.location.href="https://payroll.docango.com/";	
									}


                                });
								
								function redirect_to_login(email,password)
								{
									 $.ajax({
                                                type: "post",
                                                url: "<?= base_url("Login/verify_and_start_session") ?>",
                                                dataType: "json",
												data:{user_id:email,password:password},
                                                success: function (result) {

                                                    if (result.status === 200) {
                                                        $(location).attr('href', '<?= base_url("calendar") ?>');
                                                    }
                                                },
                                                error: function (result) {
                                                    console.log(result);
                                                }
                                            });
								}
                               

    </script>
</body>

</html>