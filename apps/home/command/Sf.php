<?php
namespace app\home\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class Sf extends Command {

    protected function configure() {
        $this->setName('sf')->setDescription('Here is the smartFarm Server');
    }

    protected function execute(Input $input, Output $output) {
        //创建Server对象，监听 127.0.0.1:9501 端口
        $server = new \Swoole\Server('0.0.0.0', 8501);

        $server->set([
            // 'dispatch_mode' => 5
        ]);

        //监听连接进入事件
        $server->on('Connect', function ($server, $fd) {
            $connect = 1;
            // echo "Client: Connect.$fd.\n";
            $fd_info = $server->getClientInfo($fd);
            // 写入数据库
            $device = db('xb_device')->where(['device_ip' => $fd_info['remote_ip']])->field('id, device_no, device_pass')->find();
            $nowtime = date('Y-m-d H:i:s');
            if (empty($device)) {
                // 生成设备编号和密码
                $device_no = get_rand_str(32);
                $device_pass = get_rand_str(8);

                $device_data = [
                    'device_no'		=> $device_no,
                    'device_pass'	=> $device_pass,
                    'device_ip'		=> $fd_info['remote_ip'],
                    'device_port'	=> $fd_info['remote_port'],
                    'fd'            => $fd,
                    'create_time'	=> $nowtime,
                    'update_time'	=> $nowtime
                ];
                $device_res = db('xb_device')->insert($device_data);
                if (!$device_res) $connect = 0;
            } else {
                $device_data = [
                    'device_port'	=> $fd_info['remote_port'],
                    'fd'            => $fd,
                    'update_time'	=> $nowtime
                ];
                $device_res = db('xb_device')->where(['id' => $device['id']])->update($device_data);
                if ($device_res === false) $connect = 0;

                $device_no = $device['device_no'];
                $device_pass = $device['device_pass'];
            }
            if ($connect) {
                if ($fd_info['remote_port'] != '127.0.0.1') {
                    $server->send($fd, "DeviceNo: {$device_no} \n DevicePass: $device_pass \n");
                    echo "Client: Connect \n DeviceNo: $device_no \n DevicePass: $device_pass \n";
                }
            } else {
                $server->close($fd);
            }
        });

        //监听数据接收事件
        $server->on('Receive', function ($server, $fd, $reactor_id, $data) {
            // 截取$data，判断是否转发数据
            $data_arr = explode('-', $data);
            if (!empty($data_arr)) {
                $head = explode('=', $data_arr[0]);
                if (count($head) == 2 && $head[0] == 'fd') {
                    $receive_fd = $head[1];
                    $send_data = substr($data, strlen($data_arr[0])+1);
                    $server->send($receive_fd, "{$send_data}");
                } else {
                    $server->send($fd, "Server: {$data}");
                }
            } else {
                $server->send($fd, "Server: {$data}");
            }
        });

        //监听连接关闭事件
        $server->on('Close', function ($server, $fd) {
            echo "Client: Close.\n";
        });

        //启动服务器
        $server->start(); 
    }
}