<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:83:"E:\wamp\Apache24\htdocs\smartFarm\public/../apps/index\view\index\program_edit.html";i:1623805132;}*/ ?>
<button type="button" class="layui-btn clear_program" style="margin-left:5px;">清空当前组</button>
<!-- <button type="button" class="layui-btn" style="margin-left:5px;">保存当前组</button> -->
<fieldset class="layui-elem-field">
    <legend>&nbsp;</legend>
    <div class="layui-field-box">
        <form class="layui-form" lay-filter="program_edit_form" action="">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label" style="width:45px;">程序：</label>
                    <div class="layui-input-inline">
                        <select name="program_key" lay-filter="program_key_set">
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
                        <select name="power_on_state" lay-filter="power_on_set">
                            <option value="1" <?php if($power_on_state == '1'): ?>selected<?php endif; ?>>运行</option>
                            <option value="0" <?php if($power_on_state == '0'): ?>selected<?php endif; ?>>停止</option>
                            <option value="2" <?php if($power_on_state == '2'): ?>selected<?php endif; ?>>暂停</option>
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
                <?php if(!(empty($cmd_list) || (($cmd_list instanceof \think\Collection || $cmd_list instanceof \think\Paginator ) && $cmd_list->isEmpty()))): if(is_array($cmd_list) || $cmd_list instanceof \think\Collection || $cmd_list instanceof \think\Paginator): $i = 0; $__LIST__ = $cmd_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
                        <tr data-cmd="<?php echo $val['cmd_val']; ?>">
                            <td><?php echo $val['line_no']; ?></td>
                            <td><?php echo $val['cmd_text']; ?></td>
                        </tr>
                    <?php endforeach; endif; else: echo "" ;endif; endif; if($add_disabled == '0'): ?>
                    <tr class="add_command_item">
                        <td><?php echo $add_line; ?></td>
                        <td>点击添加程序</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
    </div>
</fieldset>

<script>
    $(function() {
        var device_key = $('#connect_device_key').val();

        // 获取命令程序行号
        var line_no_fmt = function(line_no) {
            return line_no < 10 ? '00' + line_no : (line_no < 100 ? '0' + line_no : line_no);
        }

        // 编辑命令时加载
        var get_command_html = function(program, cmd) {
            var cmdval = cmd.split('_');
			if ($.inArray(program, ['0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15']) >= 0) {
				var command_html = cmd_func.cmd_sel('sel_1', cmdval[0]);
				switch(cmdval[0]) {
					case '1':     // 输出
						command_html += cmd_func.cmd_sel('sel_2', cmdval[1]);
						command_html += cmdval[1] == 1 ? cmd_func.cmd_inp('inp_1', cmdval[2]) : cmd_func.cmd_inp('inp_3', cmdval[2]);
						if (cmdval[1] == 1) {
							command_html += cmd_func.cmd_sel('sel_3', cmdval[3]);
							if (cmdval[3] == 2) {
								command_html += cmd_func.cmd_inp('inp_2', cmdval[4]) + cmd_func.cmd_sel('sel_4', cmdval[5]);
							} else {
								command_html += cmd_func.cmd_sel('sel_4', cmdval[4]);
							}
						} else {
							command_html += cmd_func.cmd_sel('sel_5', cmdval[3]);
						}
						break;
					case '2':     // 延时
						command_html += cmd_func.cmd_sel('sel_6', cmdval[1]);
						if (cmdval[1] == 1) {
							command_html += cmd_func.cmd_inp('inp_4', cmdval[2]) + cmd_func.cmd_inp('inp_5', cmdval[3]) + cmd_func.cmd_inp('inp_6', cmdval[4]);
						} else {
							command_html += cmd_func.cmd_inp('inp_7', cmdval[2]) + cmd_func.cmd_sel('sel_7', cmdval[3]);
							if (cmdval[3] == 2 || cmdval[3] == 3 || cmdval[3] == 4) {
								command_html += cmd_func.cmd_inp('inp_4', cmdval[4]) + cmd_func.cmd_inp('inp_5', cmdval[5]) + cmd_func.cmd_inp('inp_6', cmdval[6]);
							} else if (cmdval[3] == 5) {
								command_html += cmd_func.cmd_inp('inp_8', cmdval[4]);
							}
						}
						break;
					case '3':       // 计数
						command_html += cmd_func.cmd_sel('sel_8', cmdval[1]) + cmd_func.cmd_inp('inp_9', cmdval[2]) + cmd_func.cmd_sel('sel_9', cmdval[3]);
						if (cmdval[3] == 2 || cmdval[3] == 3 || cmdval[3] == 4) {
							command_html += cmd_func.cmd_inp('inp_10', cmdval[4]);
						}
						break;
					case '4':       // 循环
						command_html += cmd_func.cmd_inp('inp_11', cmdval[1]) + cmd_func.cmd_inp('inp_12', cmdval[2]);
						break;
					case '5':       // 如果
						command_html += cmd_func.cmd_sel('sel_10', cmdval[1]) + cmd_func.cmd_inp('inp_13', cmdval[2]);
						command_html += cmd_func.cmd_sel('sel_11', cmdval[3]) + cmd_func.cmd_sel('sel_12', cmdval[4]);
						if (cmdval[4] == 2) {
							command_html += cmd_func.cmd_inp('inp_14', cmdval[5]);
						}
						break;
					default:
						break;
				}
			} else if (program == '16') {
				var command_html = cal_func.cmd_sel('sel_1', cmdval[0]);
				switch (cmdval[0]) {
					case '1':
						command_html += cal_func.cmd_inp('inp_1', cmdval[1], cmdval[2], cmdval[3], cmdval[4], cmdval[5], cmdval[6]);
						break;
					case '2':
						command_html += cal_func.cmd_inp('inp_2', cmdval[1], cmdval[2], cmdval[3], cmdval[4], cmdval[5], cmdval[6], cmdval[7]);
						command_html += cal_func.cmd_inp('inp_3', cmdval[8], cmdval[9], cmdval[10], cmdval[11], cmdval[12], cmdval[13]);
						break;
					case '3':
						command_html += cal_func.cmd_sel('sel_2', cmdval[1]);
						if (cmdval[1] == 1) {
							command_html += cal_func.cmd_inp('inp_4', cmdval[2]) + cal_func.cmd_sel('sel_3', cmdval[3]);
							if (cmdval[3] == 2) {
								command_html += cal_func.cmd_inp('inp_5', cmdval[4]) + cal_func.cmd_sel('sel_4', cmdval[5]);
							} else {
								command_html += cal_func.cmd_sel('sel_4', cmdval[4]);
							}
						} else {
							command_html += cal_func.cmd_inp('inp_6', cmdval[2]) + cal_func.cmd_sel('sel_5', cmdval[3]);
						}
						break;
					case '4':
						command_html += cal_func.cmd_sel('sel_6', cmdval[1]);
						if (cmdval[1] == 1) {
							command_html += cal_func.cmd_inp('inp_7', cmdval[2]) + cal_func.cmd_sel('sel_7', cmdval[3]);
							if (cmdval[3] == 1) {
								command_html += cal_func.cmd_sel('sel_8', cmdval[4]);
							} else {
								command_html += cal_func.cmd_inp('inp_8', cmdval[4]) + cal_func.cmd_sel('sel_8', cmdval[5]);
							}
						} else {
							command_html += cal_func.cmd_inp('inp_7', cmdval[2]) + cal_func.cmd_sel('sel_9', cmdval[3]);
							if (cmdval[3] == 1) {
								command_html += cal_func.cmd_sel('sel_8', cmdval[4]);
							} else {
								command_html += cal_func.cmd_inp('inp_9', cmdval[4]) + cal_func.cmd_sel('sel_8', cmdval[5]);
							}
						}
						break;
					default:
						break;
				}
			} else {
				var command_html = logic_func.cmd_sel('sel_1', cmdval[0]);
				switch (cmdval[0]) {
					case '1':
					case '2':
					case '3':
						command_html += logic_func.cmd_sel('sel_2', cmdval[1]);
						if (cmdval[1] == 1) {
							command_html += logic_func.cmd_inp('inp_1', cmdval[2]) + logic_func.cmd_sel('sel_3', cmdval[3]);
							if (cmdval[3] == 1) {
								command_html += logic_func.cmd_sel('sel_4', cmdval[4]);
							} else {
								command_html += logic_func.cmd_inp('inp_2', cmdval[4]) + logic_func.cmd_sel('sel_4', cmdval[5]);
							}
						} else if (cmdval[1] == 2) {
							command_html += logic_func.cmd_inp('inp_1', cmdval[2]) + logic_func.cmd_sel('sel_15', cmdval[3]);
							if (cmdval[3] == 1) {
								command_html += logic_func.cmd_sel('sel_4', cmdval[4]);
							} else {
								command_html += logic_func.cmd_inp('inp_3', cmdval[4]) + logic_func.cmd_sel('sel_4', cmdval[5]);
							}
						} else if (cmdval[1] == 3) {
							command_html += logic_func.cmd_inp('inp_4', cmdval[2]) + logic_func.cmd_sel('sel_5', cmdval[3]);
							if (cmdval[3] == 1 || cmdval[3] == 2 || cmdval[3] == 3) {
								command_html += logic_func.cmd_inp('inp_5', cmdval[4]);
							} else {
								command_html += logic_func.cmd_inp('inp_6', cmdval[4]);
							}
						} else {
							command_html += logic_func.cmd_inp('inp_4', cmdval[2]) + logic_func.cmd_sel('sel_6', cmdval[3]);
							if (cmdval[3] == 1 || cmdval[3] == 2 || cmdval[3] == 3) {
								command_html += logic_func.cmd_inp('inp_7', cmdval[4]) + logic_func.cmd_inp('inp_8', cmdval[5]) + logic_func.cmd_inp('inp_9', cmdval[6]);
							} else {
								command_html += logic_func.cmd_inp('inp_19', cmdval[4]);
							}
						}
						break;
					case '4':
						command_html += logic_func.cmd_sel('sel_7', cmdval[1]);
						if (cmdval[1] == 1) {
							command_html += logic_func.cmd_inp('inp_10', cmdval[2]) + logic_func.cmd_sel('sel_8', cmdval[3]);
							if (cmdval[3] == 2) {
								command_html += logic_func.cmd_inp('inp_11', cmdval[4]) + logic_func.cmd_sel('sel_9', cmdval[5]);
							} else {
								command_html += logic_func.cmd_sel('sel_9', cmdval[4]);
							}
						} else {
							command_html += logic_func.cmd_inp('inp_12', cmdval[2]) + logic_func.cmd_sel('sel_10', cmdval[3]);
						}
						break;
					case '5':
						command_html += logic_func.cmd_sel('sel_11', cmdval[1]) + logic_func.cmd_inp('inp_13', cmdval[2]) + logic_func.cmd_sel('sel_12', cmdval[3]);
						if (cmdval[3] == 2 || cmdval[3] == 3 || cmdval[3] == 4) {
							command_html += logic_func.cmd_inp('inp_14', cmdval[4]) + logic_func.cmd_inp('inp_15', cmdval[5]) + logic_func.cmd_inp('inp_16', cmdval[6]);
						}
						break;
					case '6':
						command_html += logic_func.cmd_sel('sel_13', cmdval[1]) + logic_func.cmd_inp('inp_17', cmdval[2]) + logic_func.cmd_sel('sel_14', cmdval[3]);
						if (cmdval[3] == 2 || cmdval[3] == 3 || cmdval[3] == 4) {
							command_html += logic_func.cmd_inp('inp_18', cmdval[4]);
						}
						break;
					default:
						break;
				}
			}
            
            command_html += btn_html;
            return command_html;
        }

        layui.use(['element', 'form'], function() {
            var form = layui.form;
            form.render();

            // 切换程序
            form.on('select(program_key_set)', function(data) {
                $.ajax({
                    type: 'post',
                    url: '/',
                    data: {'change_program': 1, 'program': data.value, 'device_key': device_key},
                    beforeSend: function() {
                        load_index = layer.load(2);
                    },
                    success: function(result) {
                        if(!result) {
                            layer.msg('网络异常，请检查网络后重试');
                        } else if(result.code == 1) {
                            layer.msg(result.msg);
                        } else {
							// $('select[name="power_on_state"]').val(result.data.power_on);
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
            });

            // 修改上电状态
            form.on('select(power_on_set)', function(data) {
				console.log(data.value);
				var program_key = $('select[name="program_key"]').val();
				$.ajax({
                    type: 'post',
                    url: '/',
                    data: {'set_power_on': 1, 'power_on': data.value, 'program': program_key, 'device_key': device_key},
                    beforeSend: function() {
                        load_index = layer.load(2);
                    },
                    success: function(result) {
                        if(!result) {
                            layer.msg('网络异常，请检查网络后重试');
                        } else if(result.code == 1) {
                            layer.msg(result.msg);
                        } else {
							
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
            });

            // 添加或编辑程序
            $('#plc_command_items').on('click', 'tr', function() {
                var _this = $(this), _index = _this.index();

                // 将其他所有编辑中的取消编辑
                _this.siblings('.editing').each(function() {
                    $(this).find('.cancel_btn').click();
                });
				
				var program_key = $('select[name="program_key"]').val();
                // 判断是新增程序还是编辑程序
                if (_this.hasClass('add_command_item')) {   // 新增
                    var len = $('#plc_command_items').find('tr').length;
                    if (len <= 160) {
                        if (program_key == '16') {  // 日历定时
							var new_td = cal_func.cmd_sel('sel_1', 2) + cal_func.cmd_inp('inp_2', 1, 1, 1, 1, 1, 1, 1) + cal_func.cmd_inp('inp_3') + btn_html;
                        } else if (program_key == '17') {   // 逻辑控制
                            var new_td = logic_func.cmd_sel('sel_1') + logic_func.cmd_sel('sel_2') + logic_func.cmd_inp('inp_1') + logic_func.cmd_sel('sel_3') + logic_func.cmd_sel('sel_4') + btn_html;
                        } else {    // 延时控制
                            var new_td = cmd_func.cmd_sel('sel_1') + cmd_func.cmd_sel('sel_2') + cmd_func.cmd_inp('inp_1') + cmd_func.cmd_sel('sel_3') + cmd_func.cmd_sel('sel_4') + btn_html;
                        }
                        
                        _this.before('<tr class="editing"><td>' + line_no_fmt(len-1) + '</td><td>' + new_td + '</td></tr>');
                        len < 160 ? _this.find('td:first').html(line_no_fmt(len)) : _this.remove();
                    } else {
                        _this.remove();
                    }
                } else if (_this.hasClass('editing')) { // 编辑中
                   
                } else {    // 编辑
                    _this.addClass('editing');
                    _this.attr('data-text', _this.find('td:last').html());
                    var command_html = get_command_html(program_key, _this.attr('data-cmd'));
                    _this.find('td:last').html(command_html);
                }
                form.render();
            });

            // 顺序/延时控制功能切换
            $('#plc_command_items').on('change', 'select', function() {
                var program_key = $('select[name="program_key"]').val();
                var sel = $(this).attr('class'), v = $(this).val();
                var after = '';
                if ($.inArray(program_key, ['0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15']) >= 0) {
                    switch (sel) {
                        case 'sel_1':
                            switch (v) {
                                case '1':   // 输出
                                    after = cmd_func.cmd_sel('sel_2') + cmd_func.cmd_inp('inp_1') + cmd_func.cmd_sel('sel_3') + cmd_func.cmd_sel('sel_4');
                                    break;
                                case '2':   // 延时
                                    after = cmd_func.cmd_sel('sel_6') + cmd_func.cmd_inp('inp_4') + cmd_func.cmd_inp('inp_5') + cmd_func.cmd_inp('inp_6');
                                    break;
                                case '3':   // 计数
                                    after = cmd_func.cmd_sel('sel_8') + cmd_func.cmd_inp('inp_9') + cmd_func.cmd_sel('sel_9');
                                    break;
                                case '4':   // 循环
                                    after = cmd_func.cmd_inp('inp_11') + cmd_func.cmd_inp('inp_12');
                                    break;
                                case '5':   // 如果
                                    after = cmd_func.cmd_sel('sel_10') + cmd_func.cmd_inp('inp_13') + cmd_func.cmd_sel('sel_11') + cmd_func.cmd_sel('sel_12');
                                    break;
                            }
                            break;
                        case 'sel_2':
                            switch (v) {
                                case '1':
                                    after = cmd_func.cmd_inp('inp_1') + cmd_func.cmd_sel('sel_3') + cmd_func.cmd_sel('sel_4');
                                    break;
                                case '2':
                                    after = cmd_func.cmd_inp('inp_3') + cmd_func.cmd_sel('sel_5');
                                    break;
                            }
                            break;
                        case 'sel_3':
                            switch (v) {
                                case '1':
                                    after = cmd_func.cmd_sel('sel_4');
                                    break;
                                case '2':
                                    after = cmd_func.cmd_inp('inp_2') + cmd_func.cmd_sel('sel_4');
                                    break;
                                case '3':
                                case '4':
                                    after = cmd_func.cmd_sel('sel_4');
                                    break;
                            }
                            break;
                        case 'sel_6':
                            switch (v) {
                                case '1':
                                    after = cmd_func.cmd_inp('inp_4') + cmd_func.cmd_inp('inp_5') + cmd_func.cmd_inp('inp_6');
                                    break;
                                case '2':
                                    after = cmd_func.cmd_inp('inp_7') + cmd_func.cmd_sel('sel_7');
                                    break;
                            }
                            break;
                        case 'sel_7':
                            switch (v) {
                                case '1':
                                    after = '';
                                    break;
                                case '2':
                                case '3':
                                case '4':
                                    after = cmd_func.cmd_inp('inp_4') + cmd_func.cmd_inp('inp_5') + cmd_func.cmd_inp('inp_6');
                                    break;
                                case '5':
                                    after = cmd_func.cmd_inp('inp_8');
                                    break;
                            }
                            break;
                        case 'sel_9':
                            switch (v) {
                                case '1':
                                    after = '';
                                    break;
                                case '2':
                                case '3':
                                case '4':
                                    after = cmd_func.cmd_inp('inp_10');
                                    break;
                            }
                            break;
                        case 'sel_10':
                            switch (v) {
                                case '1':
                                case '2':
                                    after = cmd_func.cmd_inp('inp_13') + cmd_func.cmd_sel('sel_11') + cmd_func.cmd_sel('sel_12');
                                    break;
                            }
                            break;
                        case 'sel_11':
                            switch (v) {
                                case '1':
                                case '2':
                                    after = cmd_func.cmd_sel('sel_12');
                                    break;
                            }
                            break;
                        case 'sel_12':
                            switch (v) {
                                case '1':
                                    after = '';
                                    break;
                                case '2':
                                    after = cmd_func.cmd_inp('inp_14');
                                    break;
                            }
                            break;
                        default:
                            break;
                    }
                } else if (program_key == '16') {
					switch (sel) {
						case 'sel_1':
							if (v == '1') {
								after = cal_func.cmd_inp('inp_1');
							} else if (v == '2') {
								after = cal_func.cmd_inp('inp_2', 1, 1, 1, 1, 1, 1, 1, 1) + cal_func.cmd_inp('inp_3');
							} else if (v == '3') {
								after = cal_func.cmd_sel('sel_2') + cal_func.cmd_inp('inp_4') + cal_func.cmd_sel('sel_3') + cal_func.cmd_sel('sel_4');
							} else if (v == '4') {
								after = cal_func.cmd_sel('sel_6') + cal_func.cmd_inp('inp_7') + cal_func.cmd_sel('sel_7') + cal_func.cmd_sel('sel_8');
							} else {
								after = '';
							}
							break;
						case 'sel_2':
							if (v == '1') {
								after = cal_func.cmd_inp('inp_4') + cal_func.cmd_sel('sel_3') + cal_func.cmd_sel('sel_4');
							} else {
								after = cal_func.cmd_inp('inp_6') + cal_func.cmd_sel('sel_5');
							}
							break;
						case 'sel_3':
							if (v == '1' || v == '3' || v == '4') {
								after = cal_func.cmd_sel('sel_4');
							} else if (v == '2') {
								after = cal_func.cmd_inp('inp_5') + cal_func.cmd_sel('sel_4');
							}
							break;
						case 'sel_6':
							if (v == '1') {
								after = cal_func.cmd_inp('inp_7') + cal_func.cmd_sel('sel_7') + cal_func.cmd_sel('sel_8');
							} else {
								after = cal_func.cmd_inp('inp_7') + cal_func.cmd_sel('sel_9') + cal_func.cmd_sel('sel_8');
							}
							break;
						case 'sel_7':
							if (v == '1') {
								after = cal_func.cmd_sel('sel_8');
							} else {
								after = cal_func.cmd_inp('inp_8') + cal_func.cmd_sel('sel_8');
							}
							break;
						case 'sel_9':
							if (v == '1') {
								after = cal_func.cmd_sel('sel_8');
							} else {
								after = cal_func.cmd_inp('inp_9') + cal_func.cmd_sel('sel_8');
							}
							break;
						default:
							break;
					}
                } else {
                    switch(sel) {
                        case 'sel_1':
							if (v == '1' || v == '2' || v == '3') {
								after = logic_func.cmd_sel('sel_2') + logic_func.cmd_inp('inp_1') + logic_func.cmd_sel('sel_3') + logic_func.cmd_sel('sel_4');
							} else if (v == '4') {
								after = logic_func.cmd_sel('sel_7') + logic_func.cmd_inp('inp_10') + logic_func.cmd_sel('sel_8') + logic_func.cmd_sel('sel_9');
 							} else if (v == '5') {
								after = logic_func.cmd_sel('sel_11') + logic_func.cmd_inp('inp_13') + logic_func.cmd_sel('sel_12');
							} else {
								after = logic_func.cmd_sel('sel_13') + logic_func.cmd_inp('inp_17') + logic_func.cmd_sel('sel_14');
							}
							break;
						case 'sel_2':
							if (v == '1') {
								after = logic_func.cmd_inp('inp_1') + logic_func.cmd_sel('sel_3') + logic_func.cmd_sel('sel_4');
							} else if (v == '2') {
								after = logic_func.cmd_inp('inp_1') + logic_func.cmd_sel('sel_15') + logic_func.cmd_sel('sel_4');
							} else if (v == '3') {
								after = logic_func.cmd_inp('inp_4') + logic_func.cmd_sel('sel_5') + logic_func.cmd_inp('inp_5');
							} else {
								after = logic_func.cmd_inp('inp_4') + logic_func.cmd_sel('sel_6') + logic_func.cmd_inp('inp_7') + logic_func.cmd_inp('inp_8') + logic_func.cmd_inp('inp_9');
							}
							break;
						case 'sel_3':
							if (v == '1') {
								after = logic_func.cmd_sel('sel_4');
							} else {
								after = logic_func.cmd_inp('inp_2') + logic_func.cmd_sel('sel_4');
							}
							break;
						case 'sel_5':
							if (v == '1' || v == '2' || v == '3') {
								after = logic_func.cmd_inp('inp_5');
							} else {
								after = logic_func.cmd_inp('inp_6');
							}
							break;
						case 'sel_6':
							if (v == '1' || v == '2' || v == '3') {
								after = logic_func.cmd_inp('inp_7') + logic_func.cmd_inp('inp_8') + logic_func.cmd_inp('inp_9');
							} else {
								after = logic_func.cmd_inp('inp_19');
							}
							break;
						case 'sel_7':
							if (v == '1') {
								after = logic_func.cmd_inp('inp_10') + logic_func.cmd_sel('sel_8') + logic_func.cmd_sel('sel_9');
							} else {
								after = logic_func.cmd_inp('inp_12') + logic_func.cmd_sel('sel_10');
							}
							break;
						case 'sel_8':
							if (v == '1' || v == '3' || v == '4') {
								after = logic_func.cmd_sel('sel_9');
							} else {
								after = logic_func.cmd_inp('inp_11') + logic_func.cmd_sel('sel_9');
							}
							break;
						case 'sel_12':
							if (v == '1') {
								after = '';
							} else {
								after = logic_func.cmd_inp('inp_14') + logic_func.cmd_inp('inp_15') + logic_func.cmd_inp('inp_16');
							}
							break;
						case 'sel_14':
							if (v == '1') {
								after = '';
							} else {
								after = logic_func.cmd_inp('inp_18');
							}
							break;
						case 'sel_15':
							if (v == '1') {
								after = logic_func.cmd_sel('sel_4');
							} else {
								after = logic_func.cmd_inp('inp_3') + logic_func.cmd_sel('sel_4');
							}
							break;
						default:
							break;
                    }
                }
                $(this).nextAll().remove();
                $(this).after(after+btn_html);
            });

            // input 获得焦点事件
            $('#plc_command_items').on('focus', 'input', function() {
				if (!$(this).hasClass('check')) {
					$(this).css({
						'border' : '1px solid #666',
						'height' : '28px',
						'width' : '62px',
						'border-radius' : '2px'
					});
				}
            });
            
            // input 失去焦点事件
            $('#plc_command_items').on('blur', 'input', function(){
                var num = $(this).val(), max = $(this).data('max'), min = $(this).data('min');
                if (isNaN(num)) {
                    $(this).val('');
                    return false;
                }
				min = min ? min : 0;
                if (num < min) $(this).val(min);
                if (num > max) $(this).val(max);
            });

            // 确认
            $('#plc_command_items').on('click', '.ok_btn', function(event) {
                var _l = $(this).closest('tr'), jump_in_line = _l.hasClass('jump_in_line');
                var program_key = $('select[name="program_key"]').val();
                var line_no = _l.find('td:eq(0)').text(), cmd_key = parseInt(line_no);

                var cmdval = [];
                $(this).siblings('select, input').each(function(i) {
					if ($(this).prop('tagName').toLowerCase() === 'input' && $(this).val() == '' && !$(this).hasClass('check')) {
                        $(this).css({'border' : '1px solid red', 'height' : '28px', 'width' : '62px', 'border-radius' : '2px'});
                        return false;
                    }
					
					var cval = $(this).val();
					if ($(this).prop('tagName').toLowerCase() === 'input' && !$(this).hasClass('check')) {
                        var v = $(this).val(), min = $(this).data('min'), max = $(this).data('max');
						min = min ? min : 0;
						if (parseInt(v) < parseInt(min)) {
							cval = min;
						}
						if (parseInt(v) > parseInt(max)) {
							cval = max;
						}
                    }
					if ($(this).hasClass('check')) {
                        // console.log($(this).prop('checked'));
						cval = $(this).prop('checked') ? 1 : 0;
					}
                    cmdval[i] = cval;
                });
                // console.log(cmdval);
                var post_data = {
                        'set_program' : 1,
                        'device_key' : device_key,
                        'program_key' : program_key,
                        'cmd_key' : cmd_key,
                        'cmd_val' : cmdval,
                        'jump_in_line': jump_in_line ? 1 : 0
                    };
                
                // 保存程序
                $.ajax({
                    type: 'post',
                    url: '/',
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
                            // _l.data('cmd', result.data.cmd_val);
                            _l.attr('data-cmd', result.data.cmd_val);
                            _l.find('td:last').html(result.data.cmd_text);
                            _l.removeClass('editing');
                            _l.removeClass('jump_in_line');
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
                event.stopPropagation();    //  阻止事件冒泡
            });

            // 取消
            $('#plc_command_items').on('click', '.cancel_btn', function(event) {
                var _l = $(this).closest('tr'), cmd = _l.attr('data-cmd');
                if (cmd == undefined) { // 新增取消，删除当前行
                    // 删除当前行
                    _l.find('.del_btn').click();
                } else {                // 编辑取消，退出编辑状态
                    _l.find('td:last').html(_l.attr('data-text'));
                    _l.removeClass('editing');
                    event.stopPropagation();    //  阻止事件冒泡
                }
            });

            // 删除
            $('#plc_command_items').on('click', '.del_btn', function(event) {
                var _l = $(this).closest('tr'), cmd = _l.attr('data-cmd');
                if (cmd == undefined) { // 新增删除，删除当前行
                    // 之后的行重新编号
                    var cur_line = parseInt(_l.find('td:eq(0)').text());
                    $('#plc_command_items').find('tr').each(function(i) {
                        var line_no  = parseInt($(this).find('td:eq(0)').text());
                        if (line_no > cur_line) {
                            var new_line_no = line_no_fmt(line_no-1);
                            $(this).find('td:eq(0)').html(new_line_no);
                        }
                    });
                    _l.remove();
                } else {                // 编辑删除
                    var program_key = $('select[name="program_key"]').val();
                    var line_no = _l.find('td:eq(0)').text(), cmd_key = parseInt(line_no);

                    $.ajax({
                        type: 'post',
                        url: '/',
                        data: {'del_program': 1, 'device_key': device_key, 'program_key': program_key, 'cmd_key': cmd_key},
                        beforeSend: function() {
                            load_index = layer.load(2);
                        },
                        success: function(result) {
                            if(!result) {
                                layer.msg('网络异常，请检查网络后重试');
                            } else if(result.code == 1) {
                                layer.msg(result.msg);
                            } else {
                                // 之后的行重新编号
                                var cur_line = parseInt(_l.find('td:eq(0)').text());
                                $('#plc_command_items').find('tr').each(function(i) {
                                    var line_no  = parseInt($(this).find('td:eq(0)').text());
                                    if (line_no > cur_line) {
                                        var new_line_no = line_no_fmt(line_no-1);
                                        $(this).find('td:eq(0)').html(new_line_no);
                                    }
                                });
                                _l.remove();
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
                event.stopPropagation();    //  阻止事件冒泡
            });

            // 插入，提交当前行，并在其后增加一行
            $('#plc_command_items').on('click', '.insert_btn', function(event) {
                var _l = $(this).closest('tr');
                // 提交当前行
                _l.find('.ok_btn').click();
                // 在其后新增一行
                var len = $('#plc_command_items').find('tr').length;
                if (len < 160 || (len == 160 && $('.add_command_item').length > 0)) {
                    var cur_line = parseInt(_l.find('td:eq(0)').text()), new_line = cur_line + 1;
                    $('#plc_command_items').find('tr').each(function(i) {
                        var line_no  = parseInt($(this).find('td:eq(0)').text());
                        if (line_no > cur_line) {
                            var new_line_no = line_no_fmt(line_no+1);
                            $(this).find('td:eq(0)').html(new_line_no);
                        }
                    });
					
					var program_key = $('select[name="program_key"]').val();
					if (program_key == '16') {  // 日历定时
						var new_td = cal_func.cmd_sel('sel_1', 2) + cal_func.cmd_inp('inp_2', 1, 1, 1, 1, 1, 1, 1) + cal_func.cmd_inp('inp_3') + btn_html;
					} else if (program_key == '17') {   // 逻辑控制
						var new_td = logic_func.cmd_sel('sel_1') + logic_func.cmd_sel('sel_2') + logic_func.cmd_inp('inp_1') + logic_func.cmd_sel('sel_3') + logic_func.cmd_sel('sel_4') + btn_html;
					} else {    // 延时控制
						var new_td = cmd_func.cmd_sel('sel_1') + cmd_func.cmd_sel('sel_2') + cmd_func.cmd_inp('inp_1') + cmd_func.cmd_sel('sel_3') + cmd_func.cmd_sel('sel_4') + btn_html;
					}
					
                    _l.after('<tr class="editing jump_in_line"><td>' + line_no_fmt(new_line) + '</td><td>' + new_td + '</td></tr>');
                }
                event.stopPropagation();    //  阻止事件冒泡
            });

            // 清空当前组
            $('.clear_program').on('click', function() {
                var program_key = $('select[name="program_key"]').val();

                $.ajax({
                    type: 'post',
                    url: '/',
                    data: {'clear_program': 1, 'device_key': device_key, 'program_key': program_key},
                    beforeSend: function() {
                        load_index = layer.load(2);
                    },
                    success: function(result) {
                        if(!result) {
                            layer.msg('网络异常，请检查网络后重试');
                        } else if(result.code == 1) {
                            layer.msg(result.msg);
                        } else {
                            $('#plc_command_items').html('<tr class="add_command_item"><td>000</td><td>点击添加程序</td></tr>');
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
            });
        });
    })
</script>