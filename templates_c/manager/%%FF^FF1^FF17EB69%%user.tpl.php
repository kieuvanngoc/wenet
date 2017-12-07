<?php /* Smarty version 2.6.26, created on 2017-12-07 10:03:08
         compiled from user.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'user.tpl', 156, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="row" style="display:none;" id="blockErr">
	<div class="col-xs-12">
		<h3 class="header smaller lighter green"><i class="ace-icon fa fa-bullhorn"></i>Xảy ra lỗi sau</h3>
		<div class="alert alert-danger">
			<span id="strErr"><?php echo $this->_tpl_vars['errorTxt']; ?>
</span>
		</div>
	</div>
</div>
<?php if ($this->_tpl_vars['task'] == 'view'): ?>
	<div class="row">
		<div class="col-xs-12">
			<form class="form-horizontal" name="adminForm" method="post" action="<?php echo $this->_tpl_vars['page']; ?>
.php">
				<div class="row">
					<div class="col-xs-12 col-sm-3">
						<div class="widget-box">
							<div class="widget-header">
								<h4>Tìm kiếm theo từ khóa</h4>
								<div class="widget-toolbar">
									<a data-action="collapse" href="#">
										<i class="icon-chevron-up"></i>
									</a>
									<a data-action="close" href="#">
										<i class="icon-remove"></i>
									</a>
								</div>
							</div>
							<div class="widget-body">
								<div class="widget-body-inner" style="display: block;">
									<div class="widget-main">
										<div class="form-group">
											<div class="col-sm-12">
												<input type="text" class="form-control" size="40" value="<?php echo $this->_tpl_vars['search']; ?>
" id="search" name="search" placeholder="Nhập họ tên, email, tên đăng nhập" />
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-12">
												<button class="btn btn-purple btn-sm" onclick="this.form.submit();"><i class="icon-search icon-on-right bigger-110"></i> Tìm kiếm</button>
												<button class="btn btn-sm" onclick="document.getElementById('search').value='';document.getElementById('filter_catid').value=0;document.getElementById('filter_status').value=3;document.getElementById('filter_city_id').value='';document.getElementById('filter_district_id').value='';document.getElementById('limit').value='50';document.adminForm.p.value=1;"><i class="icon-undo bigger-110"></i> Làm lại</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-3">
						<div class="widget-box">
							<div class="widget-header">
								<h4>Tìm kiếm theo lựa chọn</h4>
								<div class="widget-toolbar">
									<a data-action="collapse" href="#">
										<i class="icon-chevron-up"></i>
									</a>
									<a data-action="close" href="#">
										<i class="icon-remove"></i>
									</a>
								</div>
							</div>
							<div class="widget-body">
								<div class="widget-body-inner" style="display: block;">
									<div class="widget-main">
										<div class="form-group">
											<div class="col-sm-12">
												<select onchange="document.adminForm.submit();" size="1" class="form-control" id="filter_group" name="filter_group">
													<option <?php if ($this->_tpl_vars['actived'] == 0): ?>selected="selected"<?php endif; ?> value="0">- Nhóm thành viên -</option>
													<?php $_from = $this->_tpl_vars['arrGroup']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['gId'] => $this->_tpl_vars['group']):
?>
														<option value="<?php echo $this->_tpl_vars['gId']; ?>
"<?php if ($this->_tpl_vars['gId'] == $this->_tpl_vars['filter_group']): ?> selected<?php endif; ?>><?php echo $this->_tpl_vars['group']; ?>
</option>
													<?php endforeach; endif; unset($_from); ?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-12">
												<select onchange="document.adminForm.submit();" size="1" class="form-control" id="filter_status" name="filter_status">
													<option <?php if ($this->_tpl_vars['filter_status'] == 0): ?>selected="selected"<?php endif; ?> value="0">- Trạng thái thành viên -</option>
													<option <?php if ($this->_tpl_vars['filter_status'] == 2): ?>selected="selected"<?php endif; ?> value="2">Đã kích hoạt</option>
													<option <?php if ($this->_tpl_vars['filter_status'] == 1): ?>selected="selected"<?php endif; ?> value="1">Chưa kích hoạt</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="space"></div>
				<div class="row">
					<div class="table-responsive">
						<table id="sample-table-1" class="table table-striped table-bordered table-hover">
							<thead>
							<tr>
								<th width="5">#</th>
								<th class="center">
									<label>
										<input type="checkbox" onclick="checkAll(50);" value="" name="toggle" class="ace">
										<span class="lbl"></span>
									</label>
								</th>
								<th class="title" nowrap="nowrap" width="15%">
									<strong>Họ và tên</strong>
								</th>
								<th class="title" nowrap="nowrap">
									<strong>Email</strong>
								</th>
								<th class="title" nowrap="nowrap" width="10%">
									<strong>Tên đăng nhập</strong>
								</th>
								<th class="title" nowrap="nowrap">
									<strong>Kiểu thành viên</strong>
								</th>
								<th class="title" nowrap="nowrap" width="30%">
									<strong>Quyền hạn</strong>
								</th>
								<th class="title" nowrap="nowrap">
									<strong>Tên site quản trị</strong>
								</th>
								<th class="title" nowrap="nowrap" width="150">
									<strong>Đăng nhập cuối</strong>
								</th>
								<th nowrap="nowrap" width="80">
									<strong>Kích hoạt</strong>
								</th>
							</tr>
							</thead>
							<tbody>
							<?php unset($this->_sections['loops']);
$this->_sections['loops']['name'] = 'loops';
$this->_sections['loops']['loop'] = is_array($_loop=$this->_tpl_vars['users']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['loops']['show'] = true;
$this->_sections['loops']['max'] = $this->_sections['loops']['loop'];
$this->_sections['loops']['step'] = 1;
$this->_sections['loops']['start'] = $this->_sections['loops']['step'] > 0 ? 0 : $this->_sections['loops']['loop']-1;
if ($this->_sections['loops']['show']) {
    $this->_sections['loops']['total'] = $this->_sections['loops']['loop'];
    if ($this->_sections['loops']['total'] == 0)
        $this->_sections['loops']['show'] = false;
} else
    $this->_sections['loops']['total'] = 0;
if ($this->_sections['loops']['show']):

            for ($this->_sections['loops']['index'] = $this->_sections['loops']['start'], $this->_sections['loops']['iteration'] = 1;
                 $this->_sections['loops']['iteration'] <= $this->_sections['loops']['total'];
                 $this->_sections['loops']['index'] += $this->_sections['loops']['step'], $this->_sections['loops']['iteration']++):
$this->_sections['loops']['rownum'] = $this->_sections['loops']['iteration'];
$this->_sections['loops']['index_prev'] = $this->_sections['loops']['index'] - $this->_sections['loops']['step'];
$this->_sections['loops']['index_next'] = $this->_sections['loops']['index'] + $this->_sections['loops']['step'];
$this->_sections['loops']['first']      = ($this->_sections['loops']['iteration'] == 1);
$this->_sections['loops']['last']       = ($this->_sections['loops']['iteration'] == $this->_sections['loops']['total']);
?>
								<tr class="row<?php if ($this->_sections['loops']['index']%2 == 0): ?>0<?php else: ?>1<?php endif; ?>">
									<td><?php echo $this->_sections['loops']['index']+1; ?>
</td>
									<td align="center">
										<input type="checkbox" onclick="isChecked(this.checked);" value="<?php echo $this->_tpl_vars['users'][$this->_sections['loops']['index']]['admin_id']; ?>
" name="cid[]" id="cb<?php echo $this->_sections['loops']['index']; ?>
">
									</td>
									<td><?php echo $this->_tpl_vars['users'][$this->_sections['loops']['index']]['admin_name']; ?>
</td>
									<td><a href="user.php?task=edit&id=<?php echo $this->_tpl_vars['users'][$this->_sections['loops']['index']]['admin_id']; ?>
" title="Click để sửa thông tin thành viên này"><?php echo $this->_tpl_vars['users'][$this->_sections['loops']['index']]['admin_email']; ?>
</a></td>
									<td><?php echo $this->_tpl_vars['users'][$this->_sections['loops']['index']]['admin_username']; ?>
</td>
									<td align="left""><?php echo $this->_tpl_vars['users'][$this->_sections['loops']['index']]['admin_group']; ?>
</td>
									<td align="left">
										<div class="user_access">
											<?php $_from = $this->_tpl_vars['users'][$this->_sections['loops']['index']]['admin_access']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['access']):
?>
												<?php if ($this->_tpl_vars['access']): ?>
													<u><b><?php echo $this->_tpl_vars['k']; ?>
</b></u><br>
													<?php echo $this->_tpl_vars['access']; ?>
<br>
												<?php endif; ?>
											<?php endforeach; endif; unset($_from); ?>
											<?php if ($this->_tpl_vars['users'][$this->_sections['loops']['index']]['admin_user_group'] != 1): ?>
												<div class="readmore"><a id="userID<?php echo $this->_tpl_vars['users'][$this->_sections['loops']['index']]['admin_id']; ?>
" data-uiid="<?php echo $this->_tpl_vars['users'][$this->_sections['loops']['index']]['admin_id']; ?>
" class="extend" href="#">Chi tiết</a></div>
											<?php endif; ?>
										</div>
									</td>
									<td align="left">
										<?php $_from = $this->_tpl_vars['users'][$this->_sections['loops']['index']]['admin_site']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['site']):
?>
											<?php if ($this->_tpl_vars['k'] > 0): ?>, <?php endif; ?><font color="#454545"><?php echo $this->_tpl_vars['site']; ?>
</font>
										<?php endforeach; endif; unset($_from); ?>
									</td>
									<td align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['users'][$this->_sections['loops']['index']]['admin_lastvisitDate'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d/%m/%Y %H:%M:%S") : smarty_modifier_date_format($_tmp, "%d/%m/%Y %H:%M:%S")); ?>
</td>
									<td align="center">
										<?php if ($this->_tpl_vars['users'][$this->_sections['loops']['index']]['admin_enabled'] == 1): ?>
											<a onclick="return listItemTask('cb<?php echo $this->_sections['loops']['index']; ?>
','unpublish')" title="Khóa">
												<i class="icon-ok" style="color: #54b234; font-size: 15px;"></i>
											</a>
										<?php else: ?>
											<a onclick="return listItemTask('cb<?php echo $this->_sections['loops']['index']; ?>
','publish')" title="Kích hoạt">
												<i class="icon-ban-circle" style="color: red; font-size: 15px;"></i>
											</a>
										<?php endif; ?>
									</td>
								</tr>
								<?php endfor; else: ?>
								<tr>
									<td colspan="10" align="center"><font color="red">Không tồn tại user nào thỏa mãn điều kiện tìm kiếm!</font></td>
								</tr>
							<?php endif; ?>
							</tbody>
							<tfoot>
							<tr>
								<td colspan="10">
									<?php echo $this->_tpl_vars['datapage']; ?>

								</td>
							</tr>
							</tfoot>
						</table>
					</div>
				</div>
				<input type="hidden" value="<?php echo $this->_tpl_vars['task']; ?>
" name="task">
				<input type="hidden" value="" name="boxchecked">
				<input type="hidden" value="<?php echo $this->_tpl_vars['totalRecords']; ?>
" name="total_record" id="total_record" />
			</form>
		</div>
	</div>
	<?php echo '
	<script type="text/javascript">
		$(\'.readmore\').click(function(){
			var data = $(this).find(\'a\').attr("class");
			var uId = $(this).find(\'a\').attr("data-uiid");

			if ( data == \'extend\' ){
				$(this).find(\'a\').attr("class", "record");
				$(this).find(\'a\').html(\'Thu lại\');
				$(this).parent().css(\'height\', \'auto\');
			}else{
				$(this).find(\'a\').attr("class", "extend");
				$(this).find(\'a\').html(\'Chi tiết\');
				$(this).parent().css(\'height\', \'50px\');
			}
			$(\'html, body\').animate({
				scrollTop: (($("#userID" + uId).offset().top) - 500)
			}, 2000);
		});
	</script>
	'; ?>

<?php elseif ($this->_tpl_vars['task'] == 'add'): ?>
	<div class="row">
		<div class="col-xs-12">
			<link rel="stylesheet" href="templates/css/multi-select.css" />
			<script type="text/javascript" src="include/js/jquery/js/jquery.multi-select.js"></script>
			<script type="text/javascript" src="include/js/jquery/js/jquery.quicksearch.js"></script>
			<form class="form-horizontal" role="form" action="<?php echo $this->_tpl_vars['page']; ?>
.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
				<div class="row">
					<div class="tabbable">
						<div class="row">
							<div class="col-xs-5">
								<div class="widget-box">
									<div class="widget-header">
										<h4>Nhập đầy đủ các thông tin</h4>
									</div>
									<div class="widget-body">
										<div class="widget-body-inner">
											<div class="widget-main">
												<div class="form-group">
													<label class="col-sm-3 control-label no-padding-right" for="form-field-fullname"> Họ và tên </label>
													<div class="col-sm-9">
														<input type="text" name="admin_name" id="admin_name" value="" class="col-xs-12 required" maxlength="150" placeholder="Họ và tên" />
													</div>
												</div>
												<div class="space-4"></div>
												<div class="form-group">
													<label class="col-sm-3 control-label no-padding-right" for="form-field-email"> Địa chỉ Email</label>
													<div class="col-sm-9">
														<input type="text" name="admin_email" id="admin_email" value="" class="col-xs-12 required" maxlength="100" placeholder="Địa chỉ email">
													</div>
												</div>
												<div class="space-4"></div>
												<div class="form-group">
													<label class="col-sm-3 control-label no-padding-right" for="form-field-username"> Tên đăng nhập</label>
													<div class="col-sm-9">
														<input type="text" name="admin_username" id="admin_username" value="" class="col-xs-12 required" maxlength="100" placeholder="Tên đăng nhập">
													</div>
												</div>
												<div class="space-4"></div>
												<div class="form-group">
													<label class="col-sm-3 control-label no-padding-right" for="form-field-password"> Mật khẩu</label>
													<div class="col-sm-9">
														<input type="password" name="admin_password" id="admin_password" value="" class="col-xs-12 required" maxlength="100" placeholder="Mật khẩu">
													</div>
												</div>
												<div class="space-4"></div>
												<div class="form-group">
													<label class="col-sm-3 control-label no-padding-right" for="form-field-group"> Thuộc nhóm</label>
													<div class="col-sm-9">
														<select onchange="objUser.onchangeGroup()" size="1" class="form-control" id="cbo_group" name="cbo_group">
															<option <?php if ($this->_tpl_vars['actived'] == 0): ?>selected="selected"<?php endif; ?> value="0">- Nhóm thành viên -</option>
															<?php $_from = $this->_tpl_vars['arrGroup']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['gId'] => $this->_tpl_vars['group']):
?>
																<?php if ($this->_tpl_vars['gId'] > $this->_tpl_vars['userInfo']['admin_group'] || $this->_tpl_vars['userInfo']['admin_group'] == 1): ?>
																	<option value="<?php echo $this->_tpl_vars['gId']; ?>
"<?php if ($this->_tpl_vars['gId'] == $this->_tpl_vars['filter_group']): ?> selected<?php endif; ?>><?php echo $this->_tpl_vars['group']; ?>
</option>
																<?php endif; ?>
															<?php endforeach; endif; unset($_from); ?>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?php if ($this->_tpl_vars['sites']): ?>
									<div class="widget-box" id="rSite" style="display:none">
										<div class="widget-box">
											<div class="widget-header">
												<h4>Quản trị website</h4>
											</div>
											<div class="widget-body">
												<div class="widget-body-inner" style="display: block;">
													<div class="widget-main">
														<div class="form-group">
															<div class="col-sm-12">
																<select name="cbo_site[]" id="cbo_site" multiple class="form-control">
																	<?php $_from = $this->_tpl_vars['sites']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['siteid'] => $this->_tpl_vars['sitename']):
?>
																		<option value="<?php echo $this->_tpl_vars['siteid']; ?>
"><?php echo $this->_tpl_vars['sitename']; ?>
</option>
																	<?php endforeach; endif; unset($_from); ?>
																</select>
																<?php echo '
																	<script type="text/javascript">
																		//$(\'#cbo_site\').multiSelect();
																		$(\'#cbo_site\').multiSelect({
																			selectableHeader: "<input type=\'text\' class=\'search-input\' autocomplete=\'off\' placeholder=\'Nhập tìm tên site\'>",
																			selectionHeader: "<input type=\'text\' class=\'search-input\' autocomplete=\'off\' placeholder=\'Nhập tìm tên site được chọn\'>",
																			afterInit: function(ms){
																				var that = this,
																						$selectableSearch = that.$selectableUl.prev(),
																						$selectionSearch = that.$selectionUl.prev(),
																						selectableSearchString = \'#\'+that.$container.attr(\'id\')+\' .ms-elem-selectable:not(.ms-selected)\',
																						selectionSearchString = \'#\'+that.$container.attr(\'id\')+\' .ms-elem-selection.ms-selected\';

																				that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
																						.on(\'keydown\', function(e){
																							if (e.which === 40){
																								that.$selectableUl.focus();
																								return false;
																							}
																						});

																				that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
																						.on(\'keydown\', function(e){
																							if (e.which == 40){
																								that.$selectionUl.focus();
																								return false;
																							}
																						});
																			},
																			afterSelect: function(){
																				this.qs1.cache();
																				this.qs2.cache();
																			},
																			afterDeselect: function(){
																				this.qs1.cache();
																				this.qs2.cache();
																			}
																		});
																	</script>
																'; ?>

															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php endif; ?>
							</div>
							<div class="col-xs-7" id="rAccess" style="display:none">
								<div class="widget-box">
									<div class="widget-header">
										<h4>Quyền truy cập</h4>
									</div>
									<div class="widget-body">
										<div class="widget-body-inner" style="display: block;">
											<div class="widget-main">
												<div class="form-group">
													<div class="col-sm-12 access_role">
														<?php $_from = $this->_tpl_vars['aryPages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['kp'] => $this->_tpl_vars['pages']):
?>
															<b style="clear: left;"><?php echo $this->_tpl_vars['kp']; ?>
</b><br />
															<?php $_from = $this->_tpl_vars['pages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['kp1'] => $this->_tpl_vars['access']):
?>
																<label style="font-size: 13px;" for="<?php echo $this->_tpl_vars['kp']; ?>
_<?php echo $this->_tpl_vars['kp1']; ?>
">
																	<?php if ($this->_tpl_vars['userInfo']['admin_group'] > 1): ?>
																		<?php $_from = $this->_tpl_vars['adminAccess']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['kp2'] => $this->_tpl_vars['access2']):
?>
																			<?php if ($this->_tpl_vars['kp'] == $this->_tpl_vars['kp2']): ?>
																				<?php $_from = $this->_tpl_vars['access2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['kp3'] => $this->_tpl_vars['access3']):
?>
																					<?php if ($this->_tpl_vars['access3'] == $this->_tpl_vars['kp1']): ?>
																						<input type="checkbox" class="ace ace-checkbox-2" value="<?php echo $this->_tpl_vars['kp1']; ?>
" name="<?php echo $this->_tpl_vars['kp']; ?>
[]" id="<?php echo $this->_tpl_vars['kp']; ?>
_<?php echo $this->_tpl_vars['kp1']; ?>
">
																						<span class="lbl"><?php echo $this->_tpl_vars['access']; ?>
</span>
																					<?php endif; ?>
																				<?php endforeach; endif; unset($_from); ?>
																			<?php endif; ?>
																		<?php endforeach; endif; unset($_from); ?>
																	<?php else: ?>
																		<span class="lbl"><?php echo $this->_tpl_vars['access']; ?>
</span>
																		<input type="checkbox" class="ace ace-checkbox-2" value="<?php echo $this->_tpl_vars['kp1']; ?>
" name="<?php echo $this->_tpl_vars['kp']; ?>
[]" id="<?php echo $this->_tpl_vars['kp']; ?>
_<?php echo $this->_tpl_vars['kp1']; ?>
">
																	<?php endif; ?>
																	<span class="lbl"></span>
																</label> &nbsp;
															<?php endforeach; endif; unset($_from); ?>
															<br><br>
														<?php endforeach; endif; unset($_from); ?>
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
				<input type="hidden" value="<?php echo $this->_tpl_vars['task']; ?>
" name="task">
				<input type="hidden" value="add" name="action">
			</form>
		</div>
	</div>
<?php elseif ($this->_tpl_vars['task'] == 'edit'): ?>
	<div class="row">
		<div class="col-xs-12">
			<link rel="stylesheet" href="templates/css/multi-select.css" />
			<script type="text/javascript" src="include/js/jquery/js/jquery.multi-select.js"></script>
			<script type="text/javascript" src="include/js/jquery/js/jquery.quicksearch.js"></script>
			<form class="form-horizontal" role="form" action="<?php echo $this->_tpl_vars['page']; ?>
.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
				<div class="row">
					<div class="tabbable">
						<div class="row">
							<div class="col-xs-5">
								<div class="widget-box">
									<div class="widget-header">
										<h4>Nhập đầy đủ các thông tin</h4>
									</div>
									<div class="widget-body">
										<div class="widget-body-inner">
											<div class="widget-main">
												<div class="form-group">
													<label class="col-sm-3 control-label no-padding-right" for="form-field-fullname"> Họ và tên </label>
													<div class="col-sm-9">
														<input type="text" name="admin_name" id="admin_name" value="<?php echo $this->_tpl_vars['users']['admin_name']; ?>
" class="col-xs-12 required" maxlength="150" placeholder="Họ và tên" />
													</div>
												</div>
												<div class="space-4"></div>
												<div class="form-group">
													<label class="col-sm-3 control-label no-padding-right" for="form-field-email"> Địa chỉ Email</label>
													<div class="col-sm-9">
														<input type="text" name="admin_email" id="admin_email" value="<?php echo $this->_tpl_vars['users']['admin_email']; ?>
" class="col-xs-12 required" maxlength="100" placeholder="Địa chỉ email">
													</div>
												</div>
												<div class="space-4"></div>
												<div class="form-group">
													<label class="col-sm-3 control-label no-padding-right" for="form-field-username"> Tên đăng nhập</label>
													<div class="col-sm-9">
														<input type="text" name="admin_username" id="admin_username" value="<?php echo $this->_tpl_vars['users']['admin_username']; ?>
" class="col-xs-12 required" <?php if ($this->_tpl_vars['admin']->admin_info['admin_group'] == 1): ?>readonly='true'<?php endif; ?> maxlength="100" placeholder="Tên đăng nhập">
													</div>
												</div>
												<div class="space-4"></div>
												<div class="form-group">
													<label class="col-sm-3 control-label no-padding-right" for="form-field-password"> Đổi mật khẩu</label>
													<div class="col-sm-9">
														<input type="password" name="admin_password" id="admin_password" value="" class="col-xs-12 required" maxlength="100" placeholder="Đổi mật khẩu">
													</div>
												</div>
												<div class="space-4"></div>
												<div class="form-group">
													<label class="col-sm-3 control-label no-padding-right" for="form-field-group"> Thuộc nhóm</label>
													<div class="col-sm-9">
														<select onchange="objUser.onchangeGroup()" size="1" class="form-control" id="cbo_group" name="cbo_group">
															<option <?php if ($this->_tpl_vars['users']['admin_group'] == 0): ?>selected="selected"<?php endif; ?> value="0">- Nhóm thành viên -</option>
															<?php $_from = $this->_tpl_vars['arrGroup']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['gId'] => $this->_tpl_vars['group']):
?>
																																<option value="<?php echo $this->_tpl_vars['gId']; ?>
" <?php if ($this->_tpl_vars['gId'] == $this->_tpl_vars['users']['admin_group']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['group']; ?>
</option>
																															<?php endforeach; endif; unset($_from); ?>
														</select>
													</div>
												</div>
												<div class="space-4"></div>
												<div class="form-group">
													<label class="col-sm-3 control-label no-padding-right" for="form-field-status"> Trạng thái</label>
													<div class="col-sm-9">
														<select name="admin_enabled" id="admin_enabled" class="form-control">
															<option value="0" <?php if ($this->_tpl_vars['users']['admin_enabled'] == 0): ?>selected='selected'<?php endif; ?>>Không được phép hoạt động</option>
															<option value="1" <?php if ($this->_tpl_vars['users']['admin_enabled'] == 1): ?>selected='selected'<?php endif; ?>>Được phép hoạt động</option>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?php if ($this->_tpl_vars['sites']): ?>
									<div class="widget-box" id="rSite" style="display:none">
										<div class="widget-box">
											<div class="widget-header">
												<h4>Quản trị trường học</h4>
											</div>
											<div class="widget-body">
												<div class="widget-body-inner" style="display: block;">
													<div class="widget-main">
														<div class="form-group">
															<div class="col-sm-12">
																<select name="cbo_site[]" id="cbo_site" multiple class="form-control">
																	<?php $_from = $this->_tpl_vars['sites']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['siteid'] => $this->_tpl_vars['sitename']):
?>
																		<option value="<?php echo $this->_tpl_vars['siteid']; ?>
"<?php $_from = $this->_tpl_vars['arySiteId']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id2'] => $this->_tpl_vars['siteId2']):
?>
																			<?php if ($this->_tpl_vars['siteId2'] == $this->_tpl_vars['siteid']): ?>selected='selected'<?php endif; ?>
																				<?php endforeach; endif; unset($_from); ?>
																		><?php echo $this->_tpl_vars['sitename']; ?>
</option>
																	<?php endforeach; endif; unset($_from); ?>
																</select>
															</div>
														</div>
														<?php echo '
															<script type="text/javascript">
																//$(\'#cbo_site\').multiSelect();
																$(\'#cbo_site\').multiSelect({
																	selectableHeader: "<input type=\'text\' class=\'search-input\' autocomplete=\'off\' placeholder=\'Nhập tìm tên trường\'>",
																	selectionHeader: "<input type=\'text\' class=\'search-input\' autocomplete=\'off\' placeholder=\'Nhập tìm tên trường được chọn\'>",
																	afterInit: function(ms){
																		var that = this,
																				$selectableSearch = that.$selectableUl.prev(),
																				$selectionSearch = that.$selectionUl.prev(),
																				selectableSearchString = \'#\'+that.$container.attr(\'id\')+\' .ms-elem-selectable:not(.ms-selected)\',
																				selectionSearchString = \'#\'+that.$container.attr(\'id\')+\' .ms-elem-selection.ms-selected\';

																		that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
																				.on(\'keydown\', function(e){
																					if (e.which === 40){
																						that.$selectableUl.focus();
																						return false;
																					}
																				});

																		that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
																				.on(\'keydown\', function(e){
																					if (e.which == 40){
																						that.$selectionUl.focus();
																						return false;
																					}
																				});
																	},
																	afterSelect: function(){
																		this.qs1.cache();
																		this.qs2.cache();
																	},
																	afterDeselect: function(){
																		this.qs1.cache();
																		this.qs2.cache();
																	}
																});
															</script>
														'; ?>

													</div>
												</div>
											</div>
										</div>
									</div>
								<?php endif; ?>
							</div>
							<div class="col-xs-7" id="rAccess" style="display:none">
								<div class="widget-box">
									<div class="widget-header">
										<h4>Quyền truy cập</h4>
									</div>
									<div class="widget-body">
										<div class="widget-body-inner" style="display: block;">
											<div class="widget-main">
												<div class="form-group">
													<div class="col-sm-12 access_role">
														<?php $_from = $this->_tpl_vars['aryPages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['kp'] => $this->_tpl_vars['pages']):
?>
															<b><?php echo $this->_tpl_vars['kp']; ?>
</b><br>
															<?php $_from = $this->_tpl_vars['pages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['kp1'] => $this->_tpl_vars['access']):
?>
																<label style="font-size: 13px;" for="<?php echo $this->_tpl_vars['kp']; ?>
_<?php echo $this->_tpl_vars['kp1']; ?>
">
																	<!--<input type="checkbox" value="<?php echo $this->_tpl_vars['kp1']; ?>
" name="<?php echo $this->_tpl_vars['kp']; ?>
[]" id="<?php echo $this->_tpl_vars['kp']; ?>
_<?php echo $this->_tpl_vars['kp1']; ?>
"
															<?php $_from = $this->_tpl_vars['aryAccess'][$this->_tpl_vars['kp']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['kp2'] => $this->_tpl_vars['access2']):
?>
																<?php if ($this->_tpl_vars['access2'] == $this->_tpl_vars['kp1']): ?>checked<?php endif; ?>
															<?php endforeach; endif; unset($_from); ?>
														> <?php echo $this->_tpl_vars['access']; ?>
-->

																	<?php if ($this->_tpl_vars['userInfo']['admin_group'] > 1): ?>
																		<?php $_from = $this->_tpl_vars['adminAccess']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['kp2'] => $this->_tpl_vars['access2']):
?>
																			<?php if ($this->_tpl_vars['kp'] == $this->_tpl_vars['kp2']): ?>
																				<?php $_from = $this->_tpl_vars['access2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['kp3'] => $this->_tpl_vars['access3']):
?>
																					<?php if ($this->_tpl_vars['access3'] == $this->_tpl_vars['kp1']): ?>
																						<span class="lbl"><?php echo $this->_tpl_vars['access']; ?>
</span>
																						<input type="checkbox" value="<?php echo $this->_tpl_vars['kp1']; ?>
" name="<?php echo $this->_tpl_vars['kp']; ?>
[]" id="<?php echo $this->_tpl_vars['kp']; ?>
_<?php echo $this->_tpl_vars['kp1']; ?>
" <?php $_from = $this->_tpl_vars['aryAccess'][$this->_tpl_vars['kp']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['kp2'] => $this->_tpl_vars['access2']):
?><?php if ($this->_tpl_vars['access2'] == $this->_tpl_vars['kp1']): ?>checked<?php endif; ?><?php endforeach; endif; unset($_from); ?>>
																					<?php endif; ?>
																				<?php endforeach; endif; unset($_from); ?>
																			<?php endif; ?>
																		<?php endforeach; endif; unset($_from); ?>
																	<?php else: ?>
																		<span class="lbl"><?php echo $this->_tpl_vars['access']; ?>
</span>
																		<input type="checkbox" value="<?php echo $this->_tpl_vars['kp1']; ?>
" name="<?php echo $this->_tpl_vars['kp']; ?>
[]" id="<?php echo $this->_tpl_vars['kp']; ?>
_<?php echo $this->_tpl_vars['kp1']; ?>
" <?php $_from = $this->_tpl_vars['aryAccess'][$this->_tpl_vars['kp']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['kp2'] => $this->_tpl_vars['access2']):
?><?php if ($this->_tpl_vars['access2'] == $this->_tpl_vars['kp1']): ?>checked<?php endif; ?><?php endforeach; endif; unset($_from); ?>>
																	<?php endif; ?>
																</label> &nbsp;
															<?php endforeach; endif; unset($_from); ?>
															<br><br>
														<?php endforeach; endif; unset($_from); ?>
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
				<input type="hidden" value="<?php echo $this->_tpl_vars['task']; ?>
" name="task">
				<input type="hidden" value="edit" name="action">
				<input type="hidden" value="<?php echo $this->_tpl_vars['adminId']; ?>
" name="id" class="admin_id" id="admin_id">
			</form>
		</div>
	</div>
<?php endif; ?>
<?php echo '
<script language="javascript">
function submitform(pressbutton){
	var action = document.adminForm.action.value;
	if (pressbutton == \'save\') {
		if (action == \'add\') {
			objUser.processAction("user.php?task=add&ajax=1");
		}
		else if (action == \'edit\') {
			objUser.processAction("user.php?task=edit&ajax=1");
		}
	}
	else {
		if (pressbutton) {
			document.adminForm.task.value=pressbutton;
		}
		document.adminForm.submit();
	}
}

if (typeof objUser == \'undefined\') {
	objUser = {
		onchangeGroup: function() {
			var group = parseInt($("#cbo_group option:selected").val());
			var admin_id = parseInt($("#admin_id").val());
			admin_id = isNaN(admin_id)?0:admin_id;
			var url = "user.php?task=change_group";
			if (group > 1) {
				$("#rSite").css("display", "");
				$("#rAccess").css("display", "");
				objUser.processActionGroupChange(url,group,admin_id);
			}else {
				$("#rSite").css("display", "none");
				$("#rAccess").css("display", "none");
			}
		},

		processAction: function(sUrl) {
			$.ajax({
				type: "POST",
				url: sUrl,
				data: $("#adminForm").serialize(),
				dataType: "json",
				success: function(xmlhttp){
					console.log(xmlhttp);
					var objData = xmlhttp;
					if (parseInt(objData.intOK) > 0) {
						document.location = "user.php";
					} else {
						$("#strErr").html(objData.strError);
						$("#blockErr").css("display", "block");
					}
				}
			});
			return false;
		},
		processActionGroupChange: function(url,group_id,admin_id) {
			if (url !== \'undefined\' && group_id !== \'undefined\' && url && group_id) {
				$.ajax({
					type: "POST",
					url: url,
					data: {group_id: group_id,admin_id:admin_id},
					dataType: "json",
					success: function (data) {
						var data = data;
						if (data.status) {
							$(".access_role").html(data.html);
						} else {

						}
					}
				});
			}
		}
	}
}
$(document).ready(function(){
	objUser.onchangeGroup();
});
</script>
'; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'footer.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>