<div id="container">
		<div id="header">
				<div id="header-container">
						<div id="header-left">
								<?php echo lang('index_heading');?>
						</div>
						<div id="header-content">
							<?php echo lang('index_subheading');?>
						</div>
						<div id="header-right">
								<a class="nav" href="/auth/logout"><?php echo lang('exit');?></a>
						</div>
				</div>
		</div>
	  <div id="main">
				<div class="container">
						<div class="table">
								<div class="tr">
										<div class="td td-center">
												<?php echo $message;?>
										</div>
								</div>
						</div>
						<?php echo form_open("auth/login");?>
						<div class="table">
								<div class="tr">
										<div class="td td-right td-40"><?php echo lang('login_identity_label', 'identity');?></div>
										<div class="td"><?php echo form_input($identity);?></div>
								</div>
								<div class="tr">
										<div class="td td-right td-40"><?php echo lang('login_password_label', 'password');?></div>
										<div class="td"><?php echo form_input($password);?></div>
								</div>
						</div>
						<div class="table">
								<div class="tr">
										<div class="td td-center">
												<?php echo lang('login_remember_label', 'remember');?>
													<?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
										</div>
								</div>
								<div class="tr">
										<div class="td td-center"><?php echo form_submit('submit', lang('login_submit_btn'), 'class="submit"');?></div>
								</div>
								<div class="tr">
										<div class="td td-center"><a href="forgot_password"><?php echo lang('login_forgot_password');?></a></div>
								</div>
						</div>
						<?php echo form_close();?>
				</div>
	  </div>
