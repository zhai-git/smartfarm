<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:76:"E:\wamp\Apache24\htdocs\smartFarm\public/../apps/index\view\index\index.html";i:1623808494;s:47:"../apps/index/view/index/program_view_init.html";i:1623159559;s:47:"../apps/index/view/index/program_edit_init.html";i:1623159336;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>智慧农业-设备控制</title>
	<link type='text/css' rel="stylesheet" href="/static/common/lib/layui/css/layui.css">
	<style>
		h1 {
            /* background-color: pink; */
			font-size: 24px;
			text-align: center;
			margin: 20px auto;
		}
		.device_inout_port .layui-badge-dot {
			width: 20px;
			height: 20px;
			vertical-align: middle;
		}

		.hide {
			display: none;
		}

		#plc_command_items select {
			height: 30px;
		}

		#plc_command_items input {
			height: 26px;
			width: 60px;
		}
		
		#plc_command_items .check {
			height: 26px;
			width: 32px;
			vertical-align: middle;
		}
	</style>
</head>
<body>

	<div class="layui-container">
		<h1>智慧农业-设备控制（V1.0）</h1>
		<hr class="layui-border-green">
		
		<form class="layui-form" lay-filter="device_form" action="<?php echo url('/'); ?>" method="post">
			<div class="layui-form-item">
				<!-- <div class="layui-inline">
					<label class="layui-form-label">设备编号</label>
					<div class="layui-input-inline" style="width:280px;">
						<input type="text" name="deviceid" lay-verify="required" placeholder="请输入设备编号" autocomplete="off" class="layui-input">
					</div>
				</div>
				<div class="layui-inline">
					<label class="layui-form-label">设备密码</label>
					<div class="layui-input-inline" style="width:280px;">
						<input type="password" name="pass" lay-verify="required|pass" placeholder="请输入密码" autocomplete="off" class="layui-input">
					</div>
				</div> -->

				<div class="layui-inline">
					<label class="layui-form-label">设备IP</label>
					<div class="layui-input-inline" style="width:280px;">
						<input type="text" name="device_ip" value="" lay-verify="required" placeholder="请输入设备IP地址" autocomplete="off" class="layui-input">
					</div>
				</div>
				<div class="layui-inline">
					<label class="layui-form-label">端口</label>
					<div class="layui-input-inline" style="width:280px;">
						<input type="text" name="device_port" value="" lay-verify="required|pass" placeholder="请输入设备端口号" autocomplete="off" class="layui-input">
					</div>
				</div>
				
				<div class="layui-inline">
					<div class="layui-input-inline">
						<button type="submit" id="connect_device_btn" class="layui-btn" lay-submit="" lay-filter="connect_device_btn">连接设备</button>
					</div>
				</div>
			</div>
		</form>
		
		<hr class="layui-border-green">
		
		<div class="layui-tab layui-tab-card" lay-filter="device-info-tab">
			<ul class="layui-tab-title" id="device_info_tab" data-url="<?php echo url('/Index/changetab'); ?>">
				<li class="layui-this">程序监控</li>
				<li>程序设置</li>
			</ul>
			<div class="layui-tab-content">
				<div class="layui-tab-item layui-show">
					<fieldset class="layui-elem-field device_inout_port">
    <legend>输入端口</legend>
    <div class="layui-field-box">
        <div class="layui-row layui-col-space8">
            <div class="layui-col-md1">
                <div class="layui-panel">
                    <div style="padding: 10px;">X00：<span class="layui-badge-dot layui-bg-black"></span></div>
                </div>   
            </div>
            <div class="layui-col-md1">
                <div class="layui-panel">
                    <div style="padding: 10px;">X01：<span class="layui-badge-dot layui-bg-black"></span></div>
                </div>   
            </div>
            <div class="layui-col-md1">
                <div class="layui-panel">
                    <div style="padding: 10px;">X02：<span class="layui-badge-dot layui-bg-black"></span></div>
                </div>   
            </div>
            <div class="layui-col-md1">
                <div class="layui-panel">
                    <div style="padding: 10px;">X03：<span class="layui-badge-dot layui-bg-black"></span></div>
                </div>   
            </div>
            <div class="layui-col-md1">
                <div class="layui-panel">
                    <div style="padding: 10px;">X04：<span class="layui-badge-dot layui-bg-black"></span></div>
                </div>   
            </div>
            <div class="layui-col-md1">
                <div class="layui-panel">
                    <div style="padding: 10px;">X05：<span class="layui-badge-dot layui-bg-black"></span></div>
                </div>   
            </div>
            <div class="layui-col-md1">
                <div class="layui-panel">
                    <div style="padding: 10px;">X06：<span class="layui-badge-dot layui-bg-black"></span></div>
                </div>   
            </div>
            <div class="layui-col-md1">
                <div class="layui-panel">
                    <div style="padding: 10px;">X07：<span class="layui-badge-dot layui-bg-black"></span></div>
                </div>   
            </div>
        </div>
    </div>
</fieldset>

<fieldset class="layui-elem-field device_inout_port">
    <legend>输出端口</legend>
    <div class="layui-field-box">
        <div class="layui-row layui-col-space8">
            <div class="layui-col-md1">
                <div class="layui-panel">
                    <div style="padding: 10px;">Y00：<span class="layui-badge-dot layui-bg-black"></span></div>
                </div>   
            </div>
            <div class="layui-col-md1">
                <div class="layui-panel">
                    <div style="padding: 10px;">Y01：<span class="layui-badge-dot layui-bg-black"></span></div>
                </div>   
            </div>
            <div class="layui-col-md1">
                <div class="layui-panel">
                    <div style="padding: 10px;">Y02：<span class="layui-badge-dot layui-bg-black"></span></div>
                </div>   
            </div>
            <div class="layui-col-md1">
                <div class="layui-panel">
                    <div style="padding: 10px;">Y03：<span class="layui-badge-dot layui-bg-black"></span></div>
                </div>   
            </div>
            <div class="layui-col-md1">
                <div class="layui-panel">
                    <div style="padding: 10px;">Y04：<span class="layui-badge-dot layui-bg-black"></span></div>
                </div>   
            </div>
            <div class="layui-col-md1">
                <div class="layui-panel">
                    <div style="padding: 10px;">Y05：<span class="layui-badge-dot layui-bg-black"></span></div>
                </div>   
            </div>
            <div class="layui-col-md1">
                <div class="layui-panel">
                    <div style="padding: 10px;">Y06：<span class="layui-badge-dot layui-bg-black"></span></div>
                </div>   
            </div>
            <div class="layui-col-md1">
                <div class="layui-panel">
                    <div style="padding: 10px;">Y07：<span class="layui-badge-dot layui-bg-black"></span></div>
                </div>   
            </div>
            <div class="layui-col-md1">
                <div class="layui-panel">
                    <div style="padding: 10px;">Y08：<span class="layui-badge-dot layui-bg-black"></span></div>
                </div>   
            </div>
            <div class="layui-col-md1">
                <div class="layui-panel">
                    <div style="padding: 10px;">Y09：<span class="layui-badge-dot layui-bg-black"></span></div>
                </div>   
            </div>
            <div class="layui-col-md1">
                <div class="layui-panel">
                    <div style="padding: 10px;">Y10：<span class="layui-badge-dot layui-bg-black"></span></div>
                </div>   
            </div>
            <div class="layui-col-md1">
                <div class="layui-panel">
                    <div style="padding: 10px;">Y11：<span class="layui-badge-dot layui-bg-black"></span></div>
                </div>   
            </div>
            <div class="layui-col-md1">
                <div class="layui-panel">
                    <div style="padding: 10px;">Y12：<span class="layui-badge-dot layui-bg-black"></span></div>
                </div>   
            </div>
            <div class="layui-col-md1">
                <div class="layui-panel">
                    <div style="padding: 10px;">Y13：<span class="layui-badge-dot layui-bg-black"></span></div>
                </div>   
            </div>
            <div class="layui-col-md1">
                <div class="layui-panel">
                    <div style="padding: 10px;">Y14：<span class="layui-badge-dot layui-bg-black"></span></div>
                </div>   
            </div>
            <div class="layui-col-md1">
                <div class="layui-panel">
                    <div style="padding: 10px;">Y15：<span class="layui-badge-dot layui-bg-black"></span></div>
                </div>   
            </div>
        </div>
    </div>
</fieldset>
<!-- <hr class="layui-border-green"> -->
<button type="button" class="layui-btn layui-btn-disabled" style="margin-left:5px;">启动当前组</button>
<fieldset class="layui-elem-field">
    <legend>&nbsp;</legend>
    <div class="layui-field-box">
        <form class="layui-form" action="">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label" style="width:45px;">程序：</label>
                    <div class="layui-input-inline">
                        <select>
                            <option value="0" selected>Z00-延时控制</option>
                            <option value="1">Z01-延时控制</option>
                            <option value="2">Z02-延时控制</option>
                            <option value="3">Z03-延时控制</option>
                            <option value="4">Z04-延时控制</option>
                            <option value="5">Z05-延时控制</option>
                            <option value="6">Z06-延时控制</option>
                            <option value="7">Z07-延时控制</option>
                            <option value="8">Z08-延时控制</option>
                            <option value="9">Z09-延时控制</option>
                            <option value="10">Z10-延时控制</option>
                            <option value="11">Z11-延时控制</option>
                            <option value="12">Z12-延时控制</option>
                            <option value="13">Z13-延时控制</option>
                            <option value="14">Z14-延时控制</option>
                            <option value="15">Z15-延时控制</option>
                            <option value="16">Z16-日历定时</option>
                            <option value="17">Z17-逻辑控制</option>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label" style="width:45px;">上电：</label>
                    <div class="layui-input-inline">
                        <select name="shangdian">
                            <option value="Z00" selected="">运行</option>
                            <option value="Z01">停止</option>
                            <option value="Z02">暂停</option>
                        </select>
                    </div>
                </div>
                
                <div class="layui-inline">
                    <label class="layui-form-label">状态：<strong>停止</strong></label>
                </div>
            </div>
        </form>
    </div>
</fieldset>
				</div>
				<div class="layui-tab-item">
					<button type="button" class="layui-btn layui-btn-disabled" style="margin-left:5px;">清空当前组</button>
<!-- <button type="button" class="layui-btn layui-btn-disabled" style="margin-left:5px;">保存当前组</button> -->
<fieldset class="layui-elem-field">
    <legend>&nbsp;</legend>
    <div class="layui-field-box">
        <form class="layui-form" action="">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label" style="width:45px;">程序：</label>
                    <div class="layui-input-inline">
                        <select>
                            <option value="0" selected>Z00-延时控制</option>
                            <option value="1">Z01-延时控制</option>
                            <option value="2">Z02-延时控制</option>
                            <option value="3">Z03-延时控制</option>
                            <option value="4">Z04-延时控制</option>
                            <option value="5">Z05-延时控制</option>
                            <option value="6">Z06-延时控制</option>
                            <option value="7">Z07-延时控制</option>
                            <option value="8">Z08-延时控制</option>
                            <option value="9">Z09-延时控制</option>
                            <option value="10">Z10-延时控制</option>
                            <option value="11">Z11-延时控制</option>
                            <option value="12">Z12-延时控制</option>
                            <option value="13">Z13-延时控制</option>
                            <option value="14">Z14-延时控制</option>
                            <option value="15">Z15-延时控制</option>
                            <option value="16">Z16-日历定时</option>
                            <option value="17">Z17-逻辑控制</option>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label" style="width:45px;">上电：</label>
                    <div class="layui-input-inline">
                        <select name="shangdian">
                            <option value="1" selected="">运行</option>
                            <option value="0">停止</option>
                            <option value="2">暂停</option>
                        </select>
                    </div>
                </div>
                
                <div class="layui-inline">
                    <label class="layui-form-label">状态：<strong>停止</strong></label>
                </div>
            </div>
        </form>
        
        <table class="layui-table" lay-even="" lay-size="lg">
            <colgroup>
                <col width="88">
                <col>
            </colgroup>
            <tbody id="plc_command_items">
                <tr class="add_command_item">
                    <td>000</td>
                    <td>点击添加程序</td>
                </tr>
            </tbody>
        </table>
    </div>
</fieldset>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" id="connect_device_key" value="0" />
	<script type='text/javascript' src="/static/common/lib/layui/layui.js"></script>
	<script type='text/javascript' src='/static/common/js/jquery-3.3.1.min.js'></script>
	<script type='text/javascript' src="/static/common/js/init.js"></script>

    <script>
        $(function() {
            var func = {
				// 自动连接设备
				auto_conncet_device: function() {
					if ($('#connect_device_key').val() == 0 && $('input[name="device_ip"]').val().length > 0 && $('input[name="device_port"]').val().length > 0) {
						$('#connect_device_btn').click();
					}
				}
            }

			layui.use(['element', 'form'], function() {
				var form = layui.form;
				form.on('submit(connect_device_btn)', function(data) {
					var _this = $(this), device_key = $('#connect_device_key').val();
					if (device_key > 0) {	// 断开连接
						var post_data = {'layout_connect': 1, 'device_key': device_key};
						var btn_next_text = '连接设备';
					} else {				// 连接设备
						data.field['connect_device'] = 1;
						var post_data = data.field;
						var btn_next_text = '断开连接';
					}

					$.ajax({
						type: 'post',
						url: $(data.form).attr('action'),
						data: post_data,
						beforeSend: function() {
							load_index = layer.load(2);
						},
						success: function(result) {
							if(!result) {
								layer.msg('网络异常，请检查网络后重试');
							} else if(result.code == 1) {
								layer.msg(result.msg);
							} else {
								$('.layui-tab-content .layui-tab-item:eq(0)').html(result.data.view_html);
								$('.layui-tab-content .layui-tab-item:eq(1)').html(result.data.edit_html);
								$('#connect_device_key').val(result.data.device_key);
								_this.html(btn_next_text);
								form.render();
							}
							layer.close(load_index);
						},
						error: function() {
							layer.msg('网络异常，请检查网络后重试');
							layer.close(load_index);
							return;
						},
						dataType: 'json'			
					});
					return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
				});

				// 初始化，设备编号为0
				$('#connect_device_key').val(0);
				// 设备自动连接
				func.auto_conncet_device();

				//一些事件触发
				var element = layui.element;
				element.on('tab(device-info-tab)', function(data) {
					var device_key = $('#connect_device_key').val();
					if (device_key <= 0) return;
					if (data.index == '0') {
						var program_key = $('select[name="program_key"]').val();
						// console.log(program_key);
						form.val("program_view_form", {'current_program': program_key});
						$.ajax({
							type: 'post',
							url: '/',
							data: {'change_program': 1, 'program': program_key, 'device_key': device_key, 'view_page': 1},
							beforeSend: function() {
								load_index = layer.load(2);
							},
							success: function(result) {
								if(!result) {
									layer.msg('网络异常，请检查网络后重试');
								} else if(result.code == 1) {
									layer.msg(result.msg);
								} else {
									form.val("program_view_form", {'power_on_flag': result.data.power_on});
									$('#command_items_view').html(result.data.cmd_html);
								}
								layer.close(load_index);
							},
							error: function() {
								layer.msg('网络异常，请检查网络后重试');
								layer.close(load_index);
								return;
							},
							dataType: 'json'
						});
						
					} else {
						var current_program = $('select[name="current_program"]').val();
						// console.log(current_program);
						form.val("program_edit_form", {'program_key': current_program});

						$.ajax({
							type: 'post',
							url: '/',
							data: {'change_program': 1, 'program': current_program, 'device_key': device_key},
							beforeSend: function() {
								load_index = layer.load(2);
							},
							success: function(result) {
								if(!result) {
									layer.msg('网络异常，请检查网络后重试');
								} else if(result.code == 1) {
									layer.msg(result.msg);
								} else {
									form.val("program_edit_form", {'power_on_state': result.data.power_on});
									$('#plc_command_items').html(result.data.cmd_html);
								}
								layer.close(load_index);
							},
							error: function() {
								layer.msg('网络异常，请检查网络后重试');
								layer.close(load_index);
								return;
							},
							dataType: 'json'
						});
					}
				});
			});
        })
    </script>
</body>
</html>