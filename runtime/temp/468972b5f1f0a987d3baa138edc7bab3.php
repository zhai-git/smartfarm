<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:88:"E:\wamp\Apache24\htdocs\smartFarm\public/../apps/index\view\index\program_edit_init.html";i:1623159336;}*/ ?>
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