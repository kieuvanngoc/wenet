<?php /* Smarty version 2.6.26, created on 2017-12-07 09:51:42
         compiled from account.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'account.tpl', 30, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php if ($this->_tpl_vars['errorTxt']): ?>
	<fieldset class="adminform">
		<legend>
		<?php if ($this->_tpl_vars['errFlag']): ?> Xảy ra lỗi sau <?php else: ?> Thông báo<?php endif; ?></legend>
		<table class="admintable" width="100%">
			<tr>
				<td><font color="Red"><?php echo $this->_tpl_vars['errorTxt']; ?>
</font></td>
			</tr>
		</table>
	</fieldset>
<?php endif; ?>
<div class="row">
	<div class="col-xs-12">
		<form class="form-horizontal" role="form" action="<?php echo $this->_tpl_vars['page']; ?>
.php" method="post" name="adminForm" id="adminForm">
			<div class="row">
				<div class="tabbable">
					<div class="row">
						<div class="col-xs-5">
							<div class="widget-box">
								<div class="widget-header">
									<h4>Sửa thông tin cá nhân</h4>
								</div>
								<div class="widget-body">
									<div class="widget-body-inner">
										<div class="widget-main">
											<div class="form-group">
												<label class="col-sm-3 control-label no-padding-right" for="form-field-fullname"> Họ và tên </label>
												<div class="col-sm-9">
													<input type="text" name="admin_name" id="admin_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['users']['admin_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" placeholder="Họ và tên" class="col-xs-12">
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label no-padding-right" for="form-field-fullname"> Avatar </label>
												<div class="col-sm-9 avatar_home_user">
																										<img class="my_avatar my_avatar_event" data-toggle="modal" data-target="#myModal" src="<?php echo $this->_tpl_vars['avatar_arr']['admin_avatar_url_crop']; ?>
" width="150" height="150" style="cursor: pointer;"/>
												</div>
											</div>
											<div class="space-4"></div>
											<div class="form-group">
												<label class="col-sm-3 control-label no-padding-right" for="form-field-email"> Địa chỉ Email</label>
												<div class="col-sm-9">
													<input type="text" name="admin_email" id="admin_email" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['users']['admin_email'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" class="col-xs-12" placeholder="Địa chỉ email">
												</div>
											</div>
											<div class="space-4"></div>
											<div class="form-group">
												<label class="col-sm-3 control-label no-padding-right" for="form-field-username"> Tên đăng nhập</label>
												<div class="col-sm-9">
													<?php echo ((is_array($_tmp=$this->_tpl_vars['users']['admin_username'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

												</div>
											</div>
											<div class="space-4"></div>
											<div class="form-group">
												<label class="col-sm-3 control-label no-padding-right" for="form-field-username"> Nhóm</label>
												<div class="col-sm-9">
													<?php echo ((is_array($_tmp=$this->_tpl_vars['users']['admin_group'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

												</div>
											</div>
											<?php if ($this->_tpl_vars['users']['admin_group'] > 1): ?>
											<div class="space-4"></div>
											<div class="form-group">
												<label class="col-sm-3 control-label no-padding-right" for="form-field-username"> Quyền hạn</label>
												<div class="col-sm-9">
													<?php echo $this->_tpl_vars['users']['admin_access']; ?>

												</div>
											</div>
											<?php endif; ?>
											<div class="space-4"></div>
											<div class="form-group">
												<label class="col-sm-3 control-label no-padding-right" for="form-field-username"> Ngày đăng ký</label>
												<div class="col-sm-9">
													<?php echo $this->_tpl_vars['users']['admin_registerDate']; ?>

												</div>
											</div>
											<div class="space-4"></div>
											<div class="form-group">
												<label class="col-sm-3 control-label no-padding-right" for="form-field-username"> Lần truy cập cuối</label>
												<div class="col-sm-9">
													<?php echo $this->_tpl_vars['users']['admin_lastvisitDate']; ?>

												</div>
											</div>
											<input type="hidden" name="admin_group" value="<?php echo $this->_tpl_vars['users']['admin_group']; ?>
">
											<input type="hidden" name="admin_username" value="<?php echo $this->_tpl_vars['users']['admin_username']; ?>
">
											<input type="hidden" name="admin_registerDate" value="<?php echo $this->_tpl_vars['users']['admin_registerDate']; ?>
">
											<input type="hidden" name="admin_lastvisitDate" value="<?php echo $this->_tpl_vars['users']['admin_lastvisitDate']; ?>
">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-5">
							<div class="widget-box">
								<div class="widget-header">
									<h4>Thay đổi mật khẩu</h4>
								</div>
								<div class="widget-body">
									<div class="widget-body-inner" style="display: block;">
										<div class="widget-main">
											<div class="form-group">
												<label class="col-sm-3 control-label no-padding-right" for="form-field-password"> Nhập mật khẩu cũ</label>
												<div class="col-sm-9">
													<input type="password" name="admin_password_old" id="admin_password_old" value="<?php echo $this->_tpl_vars['users']['admin_password_old']; ?>
" class="col-xs-12">
												</div>
											</div>
											<div class="space-4"></div>
											<div class="form-group">
												<label class="col-sm-3 control-label no-padding-right" for="form-field-password"> Nhập mật khẩu mới</label>
												<div class="col-sm-9">
													<input type="password" name="admin_password_new" id="admin_password_new" value="<?php echo $this->_tpl_vars['users']['admin_password_new']; ?>
" class="col-xs-12">
												</div>
											</div>
											<div class="space-4"></div>
											<div class="form-group">
												<label class="col-sm-3 control-label no-padding-right" for="form-field-password"> Nhập lại mật khẩu mới</label>
												<div class="col-sm-9">
													<input type="password" name="admin_password_conf" id="admin_password_conf" value="<?php echo $this->_tpl_vars['users']['admin_password_conf']; ?>
" class="col-xs-12">
												</div>
											</div>
											<div class="space-4"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		   	<input type="hidden" value="<?php echo $this->_tpl_vars['task']; ?>
" name="task">
			<input type="hidden" value="<?php echo $this->_tpl_vars['adminId']; ?>
" name="adminId" id="adminId">
		</form>
	</div>
</div>



<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'footer.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>