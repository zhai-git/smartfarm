<?php
namespace app\index\controller;

class Index extends Base {

	public $_socket;

	public function __construct() {
		parent::__construct();
	}

	// 设备控制首页
	public function index() {
		if (input('param.connect_device/d', 0) == 1) {			// 连接设备
			$device_ip = input('param.device_ip/s', '');
			$device_port = input('param.device_port/s', '');
			if (strlen($device_ip) <= 0 || strlen($device_port) <= 0) {
				die(json_encode(['code' => 1, 'msg' => '请输入必填项']));
			}

			$device_id = db('xb_device')->where(['device_no' => $device_ip, 'device_pass' => $device_port])->value('id');
			if (!$device_id) {
				$device_id = db('xb_device')->where(['device_ip' => $device_ip])->value('id');
			}
			if (!$device_id) {
				die(json_encode(['code' => 1, 'msg' => '未检测到相关设备']));
			}

			// 查询程序监控相关内容
			$device_cmd = db('xb_device_cmd')->where(['device_id' => $device_id, 'program' => 0])->field('cmd_split, plc, power_on_state')->find();
			$power_on_state = 0;
			$cmd_list = [];
			if (!empty($device_cmd)) {
				$cmd = strlen($device_cmd['cmd_split']) > 0 ? explode(',', $device_cmd['cmd_split']) : [];
				foreach($cmd as $key => $val) {
					$cmd_list[] = [
						'line_no'	=> $key < 10 ? '00'.$key : ($key < 100 ? '0'.$key : $key),
						'cmd_val'	=> $val,
						'cmd_text'	=> $this->get_cmd_text(0, $val)
					];
				}
				$power_on_state = $device_cmd['power_on_state'];
			}

			$this->assign('power_on_state', $power_on_state);
			$this->assign('cmd_list', $cmd_list);
			
			$add_line = count($cmd_list);
			if ($add_line >= 160) {
				$add_disabled = 1;
			} else {
				$add_disabled = 0;
				$this->assign('add_line', $add_line < 10 ? '00'.$add_line : ($add_line < 100 ? '0'.$add_line : $add_line));
			}
			$this->assign('add_disabled', $add_disabled);

			$program_view_html = $this->fetch('program_view');

			// 程序设置相关内容
			$program_edit_html = $this->fetch('program_edit');
			$data = [
				'view_html'		=> $program_view_html,
				'edit_html'		=> $program_edit_html,
				'device_key'	=> $device_id
			];
			die(json_encode(['code' => 0, 'msg' => '', 'data' => $data]));

		} elseif (input('param.layout_connect/d', 0) == 1) {	// 断开连接
			$device_key = input('param.device_key/d', 0);
			
			// 查询程序监控相关内容
			$program_view_html = $this->fetch('program_view_init');

			// 程序设置相关内容
			$program_edit_html = $this->fetch('program_edit_init');

			$data = [
				'view_html'		=> $program_view_html,
				'edit_html'		=> $program_edit_html,
				'device_key'	=> 0
			];
			die(json_encode(['code' => 0, 'msg' => '', 'data' => $data]));
		} elseif (input('param.change_program/d', 0) == 1) {	// 切换功能
			$program = input('param.program/d', -1);
			if (!in_array($program, [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17])) {
				die(json_encode(['code' => 1, 'msg' => '请选择程序组']));
			}

			$device_key = input('param.device_key/d', 0);
			if (!$device_key) {
				die(json_encode(['code' => 1, 'msg' => '请求参数错误']));
			}

			$view_page = input('param.view_page/d', 0);
			
			$cmd_html = '';
			$cmd_count = 0;
			$power_on_state = 0;
			$device_cmd = db('xb_device_cmd')->where(['device_id' => $device_key, 'program' => $program])->field('cmd_split, plc, power_on_state')->find();
			if (!empty($device_cmd)) {
				$cmd = strlen($device_cmd['cmd_split']) > 0 ? explode(',', $device_cmd['cmd_split']) : [];
				foreach($cmd as $key => $val) {
					$line_no = $key < 10 ? '00'.$key : ($key < 100 ? '0'.$key : $key);
					$cmd_text = $this->get_cmd_text($program, $val);
					$cmd_html .= '<tr data-cmd="'.$val.'"><td>'.$line_no.'</td><td>'.$cmd_text.'</td></tr>';
				}
				$cmd_count = count($cmd);
				$power_on_state = $device_cmd['power_on_state'];
			}
			if ($cmd_count < 160 && !$view_page) {
				$line_no = $cmd_count < 10 ? '00'.$cmd_count : ($cmd_count < 100 ? '0'.$cmd_count : $cmd_count);
				$cmd_html .= '<tr class="add_command_item"><td>'.$line_no.'</td><td>点击添加程序</td></tr>';
			}
			
			$data = [
				'power_on'	=> $power_on_state,
				'cmd_html'	=> $cmd_html
			];
			die(json_encode(['code' => 0, 'msg' => '', 'data' => $data]));
		} elseif (input('param.set_program/d', 0) == 1) {		// 设置程序
			$program = input('param.program_key/d', -1);
			if (!in_array($program, [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17])) {
				die(json_encode(['code' => 1, 'msg' => '请选择程序组']));
			}

			$device_key = input('param.device_key/d', 0);
			if (!$device_key) {
				die(json_encode(['code' => 1, 'msg' => '设备连接异常']));
			}

			$cmd_key = input('param.cmd_key/d', -1);
			if ($cmd_key < 0 || $cmd_key >= 160) {
				die(json_encode(['code' => 1, 'msg' => '程序行数超出范围']));
			}

			$cmd_val = input('param.cmd_val/a', []);
			if (empty($cmd_val)) {
				die(json_encode(['code' => 1, 'msg' => '请设置程序']));
			}
			$cmd_val = implode('_', $cmd_val);

			// 是否插队
			$jump_in_line = input('param.jump_in_line/d', -1);
			if (!in_array($jump_in_line, [0,1])) {
				die(json_encode(['code' => 1, 'msg' => '请求参数错误']));
			}

			$device_cmd_db = db('xb_device_cmd');
			$device_cmd = $device_cmd_db->where(['device_id' => $device_key, 'program' => $program])->field('id, cmd_split, plc')->find();
			$nowtime = date('Y-m-d H:i:s');
			if (!empty($device_cmd)) {
				$cmd_split = strlen($device_cmd['cmd_split']) ? explode(',', $device_cmd['cmd_split']) : [];

				if ($jump_in_line) {
					array_splice($cmd_split, $cmd_key+1, 0, $cmd_val);
				} else {
					$cmd_split[$cmd_key] = $cmd_val;
				}

				$plc = '';
				if (!empty($cmd_split)) {
					foreach ($cmd_split as $cmd) {
						$plc .= $this->get_plc($program, $cmd);
					}
				}
				
				$update_data = [
					'cmd_split'			=> implode(',', $cmd_split),
					'plc'				=> $plc,
					'update_time'		=> $nowtime
				];

				$update_res = $device_cmd_db->where(['id' => $device_cmd['id']])->update($update_data);
				if ($update_res === false) {
					die(json_encode(['code' => 1, 'msg' => '保存程序失败']));
				}
				
			} else {
				
				$plc = $this->get_plc($program, $cmd_val);
				$insert_data = [
					'device_id'			=> $device_key,
					'program'			=> $program,
					'cmd_split'			=> $cmd_val,
					'plc'				=> $plc,
					'power_on_state'	=> 0,
					'create_time'		=> $nowtime,
					'update_time'		=> $nowtime
				];
				$insert_res = $device_cmd_db->insert($insert_data);
				if (!$insert_res) {
					die(json_encode(['code' => 1, 'msg' => '保存程序失败']));
				}
			}

			$cmd_text = $this->get_cmd_text($program, $cmd_val);

			$data = ['cmd_val' => $cmd_val, 'cmd_text' => $cmd_text];
			die(json_encode(['code' => 0, 'msg' => 'ok', 'data' => $data]));
		} elseif (input('param.del_program/d', 0) == 1) {		// 删除程序
			$program = input('param.program_key/d', -1);
			if (!in_array($program, [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17])) {
				die(json_encode(['code' => 1, 'msg' => '请选择程序组']));
			}

			$device_key = input('param.device_key/d', 0);
			if (!$device_key) {
				die(json_encode(['code' => 1, 'msg' => '设备连接异常']));
			}

			$cmd_key = input('param.cmd_key/d', -1);
			if ($cmd_key < 0 || $cmd_key >= 160) {
				die(json_encode(['code' => 1, 'msg' => '程序行数超出范围']));
			}

			$device_cmd_db = db('xb_device_cmd');
			$device_cmd = $device_cmd_db->where(['device_id' => $device_key, 'program' => $program])->field('id, cmd_split, plc')->find();
			$nowtime = date('Y-m-d H:i:s');
			if (!empty($device_cmd)) {
				$cmd_split = strlen($device_cmd['cmd_split']) ? explode(',', $device_cmd['cmd_split']) : [];
				if (isset($cmd_split[$cmd_key])) unset($cmd_split[$cmd_key]);
				
				$plc = '';
				if (!empty($cmd_split)) {
					foreach ($cmd_split as $cmd) {
						$plc .= $this->get_plc($program, $cmd);
					}
				}

				$update_data = [
					'cmd_split'			=> implode(',', $cmd_split),
					'plc'				=> $plc,
					'update_time'		=> $nowtime
				];

				$update_res = $device_cmd_db->where(['id' => $device_cmd['id']])->update($update_data);
				if ($update_res === false) {
					die(json_encode(['code' => 1, 'msg' => '保存程序失败']));
				}
			}
			die(json_encode(['code' => 0, 'msg' => 'ok']));
		} elseif (input('param.clear_program/d', 0) == 1) {		// 清空当前组
			$program_key = input('param.program_key/d', -1);
			if (!in_array($program_key, [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17])) {
				die(json_encode(['code' => 1, 'msg' => '请选择程序组']));
			}

			$device_key = input('param.device_key/d', 0);
			if (!$device_key) {
				die(json_encode(['code' => 1, 'msg' => '设备连接异常']));
			}

			$update_data = [
				'cmd_split'		=> '',
				'plc'			=> '',
				'update_time'	=> date('Y-m-d H:i:s')
			];

			$update_res = db('xb_device_cmd')->where(['device_id' => $device_key, 'program' => $program_key])->update($update_data);
			if ($update_res === false) {
				die(json_encode(['code' => 1, 'msg' => '清空程序组失败']));
			}
			die(json_encode(['code' => 0, 'msg' => 'ok']));
		} elseif (input('param.set_power_on/d', 0) == 1) {		// 设置上电状态
			$program = input('param.program/d', -1);
			if (!in_array($program, [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17])) {
				die(json_encode(['code' => 1, 'msg' => '请选择程序组']));
			}

			$device_key = input('param.device_key/d', 0);
			if (!$device_key) {
				die(json_encode(['code' => 1, 'msg' => '设备连接异常']));
			}

			$power_on = input('param.power_on/d', -1);
			if (!in_array($power_on, [0,1,2])) {
				die(json_encode(['code' => 1, 'msg' => '请选择上电状态']));
			}
			
			$device_cmd_db = db('xb_device_cmd');
			$device_cmd_id = $device_cmd_db->where(['device_id' => $device_key, 'program' => $program])->value('id');
			$nowtime = date('Y-m-d H:i:s');
			if ($device_cmd_id) {
				$update_data = [
					'power_on_state'	=> $power_on,
					'update_time'		=> $nowtime
				];

				$update_res = $device_cmd_db->where(['id' => $device_cmd_id])->update($update_data);
				if ($update_res === false) {
					die(json_encode(['code' => 1, 'msg' => '设置上电状态失败']));
				}
				
			} else {
				$insert_data = [
					'device_id'			=> $device_key,
					'program'			=> $program,
					'cmd_split'			=> '',
					'plc'				=> '',
					'power_on_state'	=> $power_on,
					'create_time'		=> $nowtime,
					'update_time'		=> $nowtime
				];
				$insert_res = $device_cmd_db->insert($insert_data);
				if (!$insert_res) {
					die(json_encode(['code' => 1, 'msg' => '设置上电状态失败']));
				}
			}

			die(json_encode(['code' => 0, 'msg' => 'ok']));
		} elseif (input('param.start_program/d', 0) == 1) {		// 启动程序
			$program = input('param.program/d', -1);
			if (!in_array($program, [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17])) {
				die(json_encode(['code' => 1, 'msg' => '请选择程序组']));
			}

			$device_key = input('param.device_key/d', 0);
			if (!$device_key) {
				die(json_encode(['code' => 1, 'msg' => '设备连接异常']));
			}

			$plc = db('xb_device_cmd')->where(['device_id' => $device_key, 'program' => $program])->value('plc');
			$device_fd = db('xb_device')->where(['id' => $device_key])->value('fd');

			$client = new \Swoole\Client(SWOOLE_SOCK_TCP);
			if (!$client->connect('127.0.0.1', 8501, -1)) {
				// exit("connect failed. Error: {$client->errCode}\n");
				die(json_encode(['code' => 1, 'msg' => '发送程序失败'.$client->errCode]));
			}
			$client->send('fd='.$device_fd.'-'.$plc);
			$recv = $client->recv();
			$client->close();
			die(json_encode(['code' => 0, 'msg' => '', 'data' => []]));
		}

		// 返回设备控制界面
		return view('index/index');
	}
}
