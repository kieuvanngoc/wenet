{include file='header.tpl'}
<div class="row" style="display:none;" id="blockErr">
	<div class="col-xs-12">
		<h3 class="header smaller lighter green"><i class="ace-icon fa fa-bullhorn"></i>Xảy ra lỗi sau</h3>
		<div class="alert alert-danger">
			<span id="strErr">{$errorTxt}</span>
		</div>
	</div>
</div>
{if $task=="view"}
	<div class="row">
		<div class="col-xs-12">
			<form class="form-horizontal" name="adminForm" method="post" action="{$page}.php">
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
												<input type="text" class="form-control" size="40" value="{$search}" id="search" name="search" placeholder="Nhập họ tên, email, tên đăng nhập" />
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
													<option {if $actived==0}selected="selected"{/if} value="0">- Nhóm thành viên -</option>
													{foreach from=$arrGroup key=gId item=group}
														<option value="{$gId}"{if $gId==$filter_group} selected{/if}>{$group}</option>
													{/foreach}
												</select>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-12">
												<select onchange="document.adminForm.submit();" size="1" class="form-control" id="filter_status" name="filter_status">
													<option {if $filter_status==0}selected="selected"{/if} value="0">- Trạng thái thành viên -</option>
													<option {if $filter_status==2}selected="selected"{/if} value="2">Đã kích hoạt</option>
													<option {if $filter_status==1}selected="selected"{/if} value="1">Chưa kích hoạt</option>
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
							{section name=loops loop=$users}
								<tr class="row{if $smarty.section.loops.index%2==0}0{else}1{/if}">
									<td>{$smarty.section.loops.index+1}</td>
									<td align="center">
										<input type="checkbox" onclick="isChecked(this.checked);" value="{$users[loops].admin_id}" name="cid[]" id="cb{$smarty.section.loops.index}">
									</td>
									<td>{$users[loops].admin_name}</td>
									<td><a href="user.php?task=edit&id={$users[loops].admin_id}" title="Click để sửa thông tin thành viên này">{$users[loops].admin_email}</a></td>
									<td>{$users[loops].admin_username}</td>
									<td align="left"">{$users[loops].admin_group}</td>
									<td align="left">
										<div class="user_access">
											{foreach from=$users[loops].admin_access key=k item=access}
												{if $access}
													<u><b>{$k}</b></u><br>
													{$access}<br>
												{/if}
											{/foreach}
											{if $users[loops].admin_user_group != 1}
												<div class="readmore"><a id="userID{$users[loops].admin_id}" data-uiid="{$users[loops].admin_id}" class="extend" href="#">Chi tiết</a></div>
											{/if}
										</div>
									</td>
									<td align="left">
										{foreach from=$users[loops].admin_site key=k item=site}
											{if $k>0}, {/if}<font color="#454545">{$site}</font>
										{/foreach}
									</td>
									<td align="center">{$users[loops].admin_lastvisitDate|date_format:"%d/%m/%Y %H:%M:%S"}</td>
									<td align="center">
										{if $users[loops].admin_enabled == 1}
											<a onclick="return listItemTask('cb{$smarty.section.loops.index}','unpublish')" title="Khóa">
												<i class="icon-ok" style="color: #54b234; font-size: 15px;"></i>
											</a>
										{else}
											<a onclick="return listItemTask('cb{$smarty.section.loops.index}','publish')" title="Kích hoạt">
												<i class="icon-ban-circle" style="color: red; font-size: 15px;"></i>
											</a>
										{/if}
									</td>
								</tr>
								{sectionelse}
								<tr>
									<td colspan="10" align="center"><font color="red">Không tồn tại user nào thỏa mãn điều kiện tìm kiếm!</font></td>
								</tr>
							{/section}
							</tbody>
							<tfoot>
							<tr>
								<td colspan="10">
									{$datapage}
								</td>
							</tr>
							</tfoot>
						</table>
					</div>
				</div>
				<input type="hidden" value="{$task}" name="task">
				<input type="hidden" value="" name="boxchecked">
				<input type="hidden" value="{$totalRecords}" name="total_record" id="total_record" />
			</form>
		</div>
	</div>
	{literal}
	<script type="text/javascript">
		$('.readmore').click(function(){
			var data = $(this).find('a').attr("class");
			var uId = $(this).find('a').attr("data-uiid");

			if ( data == 'extend' ){
				$(this).find('a').attr("class", "record");
				$(this).find('a').html('Thu lại');
				$(this).parent().css('height', 'auto');
			}else{
				$(this).find('a').attr("class", "extend");
				$(this).find('a').html('Chi tiết');
				$(this).parent().css('height', '50px');
			}
			$('html, body').animate({
				scrollTop: (($("#userID" + uId).offset().top) - 500)
			}, 2000);
		});
	</script>
	{/literal}
{elseif $task=="add"}
	<div class="row">
		<div class="col-xs-12">
			<link rel="stylesheet" href="templates/css/multi-select.css" />
			<script type="text/javascript" src="include/js/jquery/js/jquery.multi-select.js"></script>
			<script type="text/javascript" src="include/js/jquery/js/jquery.quicksearch.js"></script>
			<form class="form-horizontal" role="form" action="{$page}.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
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
															<option {if $actived==0}selected="selected"{/if} value="0">- Nhóm thành viên -</option>
															{foreach from=$arrGroup key=gId item=group}
																{if $gId > $userInfo.admin_group || $userInfo.admin_group==1}
																	<option value="{$gId}"{if $gId==$filter_group} selected{/if}>{$group}</option>
																{/if}
															{/foreach}
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								{if $sites}
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
																	{foreach from=$sites key=siteid item=sitename}
																		<option value="{$siteid}">{$sitename}</option>
																	{/foreach}
																</select>
																{literal}
																	<script type="text/javascript">
																		//$('#cbo_site').multiSelect();
																		$('#cbo_site').multiSelect({
																			selectableHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Nhập tìm tên site'>",
																			selectionHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Nhập tìm tên site được chọn'>",
																			afterInit: function(ms){
																				var that = this,
																						$selectableSearch = that.$selectableUl.prev(),
																						$selectionSearch = that.$selectionUl.prev(),
																						selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
																						selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

																				that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
																						.on('keydown', function(e){
																							if (e.which === 40){
																								that.$selectableUl.focus();
																								return false;
																							}
																						});

																				that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
																						.on('keydown', function(e){
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
																{/literal}
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								{/if}
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
														{foreach from=$aryPages key=kp item=pages}
															<b style="clear: left;">{$kp}</b><br />
															{foreach from=$pages key=kp1 item=access}
																<label style="font-size: 13px;" for="{$kp}_{$kp1}">
																	{if $userInfo.admin_group>1}
																		{foreach from=$adminAccess key=kp2 item=access2}
																			{if $kp==$kp2}
																				{foreach from=$access2 key=kp3 item=access3}
																					{if $access3==$kp1}
																						<input type="checkbox" class="ace ace-checkbox-2" value="{$kp1}" name="{$kp}[]" id="{$kp}_{$kp1}">
																						<span class="lbl">{$access}</span>
																					{/if}
																				{/foreach}
																			{/if}
																		{/foreach}
																	{else}
																		<span class="lbl">{$access}</span>
																		<input type="checkbox" class="ace ace-checkbox-2" value="{$kp1}" name="{$kp}[]" id="{$kp}_{$kp1}">
																	{/if}
																	<span class="lbl"></span>
																</label> &nbsp;
															{/foreach}
															<br><br>
														{/foreach}
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
				<input type="hidden" value="{$task}" name="task">
				<input type="hidden" value="add" name="action">
			</form>
		</div>
	</div>
{elseif $task=="edit"}
	<div class="row">
		<div class="col-xs-12">
			<link rel="stylesheet" href="templates/css/multi-select.css" />
			<script type="text/javascript" src="include/js/jquery/js/jquery.multi-select.js"></script>
			<script type="text/javascript" src="include/js/jquery/js/jquery.quicksearch.js"></script>
			<form class="form-horizontal" role="form" action="{$page}.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
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
														<input type="text" name="admin_name" id="admin_name" value="{$users.admin_name}" class="col-xs-12 required" maxlength="150" placeholder="Họ và tên" />
													</div>
												</div>
												<div class="space-4"></div>
												<div class="form-group">
													<label class="col-sm-3 control-label no-padding-right" for="form-field-email"> Địa chỉ Email</label>
													<div class="col-sm-9">
														<input type="text" name="admin_email" id="admin_email" value="{$users.admin_email}" class="col-xs-12 required" maxlength="100" placeholder="Địa chỉ email">
													</div>
												</div>
												<div class="space-4"></div>
												<div class="form-group">
													<label class="col-sm-3 control-label no-padding-right" for="form-field-username"> Tên đăng nhập</label>
													<div class="col-sm-9">
														<input type="text" name="admin_username" id="admin_username" value="{$users.admin_username}" class="col-xs-12 required" {if $admin->admin_info.admin_group  == 1}readonly='true'{/if} maxlength="100" placeholder="Tên đăng nhập">
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
															<option {if $users.admin_group==0}selected="selected"{/if} value="0">- Nhóm thành viên -</option>
															{foreach from=$arrGroup key=gId item=group}
																{*{if $gId >= $users.admin_group || $users.admin_group==1}*}
																<option value="{$gId}" {if $gId==$users.admin_group} selected="selected"{/if}>{$group}</option>
																{*{/if}*}
															{/foreach}
														</select>
													</div>
												</div>
												<div class="space-4"></div>
												<div class="form-group">
													<label class="col-sm-3 control-label no-padding-right" for="form-field-status"> Trạng thái</label>
													<div class="col-sm-9">
														<select name="admin_enabled" id="admin_enabled" class="form-control">
															<option value="0" {if $users.admin_enabled == 0}selected='selected'{/if}>Không được phép hoạt động</option>
															<option value="1" {if $users.admin_enabled == 1}selected='selected'{/if}>Được phép hoạt động</option>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								{if $sites}
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
																	{foreach from=$sites key=siteid item=sitename}
																		<option value="{$siteid}"{foreach from=$arySiteId key=id2 item=siteId2}
																			{if $siteId2 == $siteid}selected='selected'{/if}
																				{/foreach}
																		>{$sitename}</option>
																	{/foreach}
																</select>
															</div>
														</div>
														{literal}
															<script type="text/javascript">
																//$('#cbo_site').multiSelect();
																$('#cbo_site').multiSelect({
																	selectableHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Nhập tìm tên trường'>",
																	selectionHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Nhập tìm tên trường được chọn'>",
																	afterInit: function(ms){
																		var that = this,
																				$selectableSearch = that.$selectableUl.prev(),
																				$selectionSearch = that.$selectionUl.prev(),
																				selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
																				selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

																		that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
																				.on('keydown', function(e){
																					if (e.which === 40){
																						that.$selectableUl.focus();
																						return false;
																					}
																				});

																		that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
																				.on('keydown', function(e){
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
														{/literal}
													</div>
												</div>
											</div>
										</div>
									</div>
								{/if}
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
														{foreach from=$aryPages key=kp item=pages}
															<b>{$kp}</b><br>
															{foreach from=$pages key=kp1 item=access}
																<label style="font-size: 13px;" for="{$kp}_{$kp1}">
																	<!--<input type="checkbox" value="{$kp1}" name="{$kp}[]" id="{$kp}_{$kp1}"
															{foreach from=$aryAccess.$kp key=kp2 item=access2}
																{if $access2 == $kp1}checked{/if}
															{/foreach}
														> {$access}-->

																	{if $userInfo.admin_group>1}
																		{foreach from=$adminAccess key=kp2 item=access2}
																			{if $kp==$kp2}
																				{foreach from=$access2 key=kp3 item=access3}
																					{if $access3==$kp1}
																						<span class="lbl">{$access}</span>
																						<input type="checkbox" value="{$kp1}" name="{$kp}[]" id="{$kp}_{$kp1}" {foreach from=$aryAccess.$kp key=kp2 item=access2}{if $access2 == $kp1}checked{/if}{/foreach}>
																					{/if}
																				{/foreach}
																			{/if}
																		{/foreach}
																	{else}
																		<span class="lbl">{$access}</span>
																		<input type="checkbox" value="{$kp1}" name="{$kp}[]" id="{$kp}_{$kp1}" {foreach from=$aryAccess.$kp key=kp2 item=access2}{if $access2 == $kp1}checked{/if}{/foreach}>
																	{/if}
																</label> &nbsp;
															{/foreach}
															<br><br>
														{/foreach}
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
				<input type="hidden" value="{$task}" name="task">
				<input type="hidden" value="edit" name="action">
				<input type="hidden" value="{$adminId}" name="id" class="admin_id" id="admin_id">
			</form>
		</div>
	</div>
{/if}
{literal}
<script language="javascript">
function submitform(pressbutton){
	var action = document.adminForm.action.value;
	if (pressbutton == 'save') {
		if (action == 'add') {
			objUser.processAction("user.php?task=add&ajax=1");
		}
		else if (action == 'edit') {
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

if (typeof objUser == 'undefined') {
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
			if (url !== 'undefined' && group_id !== 'undefined' && url && group_id) {
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
{/literal}
{include file='footer.tpl'}