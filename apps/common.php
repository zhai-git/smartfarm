<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

if(!function_exists('get_rand_str')) {
	function get_rand_str($length){
		//字符组合
		$str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$len = strlen($str)-1;
		$randstr = '';
		for ($i = 0; $i < $length; $i++) {
			$num = mt_rand(0, $len);
			$randstr .= $str[$num];
		}
		return $randstr;
	}
}

// 连接设备
if(!function_exists('connect_device_socket')) {
	function connect_device_socket($service_port, $address) {
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die('could not create socket!');
		$timeout = 2;
		//设置超时时间
		$time = time();
		//设置非阻塞模式
		@socket_set_nonblock($socket);
		//超时判断
		while (!@socket_connect($socket, $address, $service_port)) {
			$err = socket_last_error($socket);
			//连接成功，跳出循环
			if ($err === 10056) {
				break;
			} 
			//连接失败，判断超时时间，停止
			if ((time() - $time) >= $timeout) {
				socket_close($socket);
				return false;
				exit();
			} 
			//刷新频率（250毫秒）
			usleep(250000);
		}
		//设置阻塞模式
		// @socket_set_block($socket);
		//输出返回值
		return $socket;
	}
}

// 断开连接
if(!function_exists('close_device_socket')) {
	function close_device_socket($socket) {
		socket_close($socket);
		return true;
	}
}

// 执行plc指令
if (!function_exists('exec_socket_plc')) {
	function exec_socket_plc($socket, $in) {
		//设置阻塞模式
		@socket_set_block($socket);
		//发送命令到设备
		socket_write($socket, $in, strlen($in));
		//接收设备命令返回数据
		$buffer = socket_read($socket, 1024, PHP_NORMAL_READ);
		//关闭连接
		socket_close($socket);
		//输出返回值
		return $buffer;
	}
}


/**
 * 采用php socket技术使用TCP/IP连接设备
 * @param string $service_port 连接端口
 * @param string $address      发送IP地址
 * @param string $in           发送命令
 * @return string/boolean 返回值
 */
if(!function_exists('send_socket_connect')) {
	function send_socket_connect($service_port, $address, $in) {
		// $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die('could not create socket!');

		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if (!$socket) return false;

		$timeout = 2;
		//设置超时时间
		$time = time();
		//设置非阻塞模式
		@socket_set_nonblock($socket);
		//超时判断
		while (!@socket_connect($socket, $address, $service_port)) {
			$err = socket_last_error($socket);
			//连接成功，跳出循环
			if ($err === 10056) {
				break;
			} 
			//连接失败，判断超时时间，停止
			if ((time() - $time) >= $timeout) {
				socket_close($socket);
				return false;
				exit();
			} 
			//刷新频率（250毫秒）
			usleep(250000);
		}
		//设置阻塞模式
		@socket_set_block($socket);
		//发送命令到设备
		socket_write($socket, $in, strlen($in));
		//接收设备命令返回数据
		// $buffer = @socket_read($socket, 1024, PHP_NORMAL_READ);
		//关闭连接
		socket_close($socket);
		//输出返回值
		// return $buffer;
		return true;
	}
}

/**
 * 采用php socket技术使用UDP协议连接设备
 * @param string $service_port 连接端口
 * @param string $address      连接IP地址
 * @param string $in           发送命令
 * @return string/boolean 返回值
 */
if(!function_exists('send_socket_xdcoder_udp')) {
	function send_socket_xdcoder_udp($service_port, $address, $in) {
		//采用php socket技术使用UDP协议连接设备
		$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
		@socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec" => 2, "usec" => 0));
		//发送命令
		@socket_sendto($socket, $in, strlen($in), 0, $address, $service_port);
		@socket_recvfrom($socket, $buffer, 1024, MSG_WAITALL, $address, $service_port);
		//关闭连接
		socket_close($socket);
		if (!empty($buffer)) {
			return $buffer;
		} else {
			return false;
		}
	}
}

/**
 * 采用RS232串口技术连接设备，实现通讯和发送命令
 * @param  [type] $com      串口号
 * @param  [type] $baudrate 波特率
 * @param  [type] $in       发送命令
 */
if(!function_exists('rs232_connect')) {
	function rs232_connect($com, $baudrate, $in) {
		@set_time_limit(10);
		//使用 'windows下设置窜口参数;
		exec("mode " . $com . " BAUD=" . $baudrate . " PARITY=n DATA=8 STOP=1 odsr=off");
		//打开端口串口$com
		$f = @fopen($com, 'w+');
		//判断串口是否正常打开
		if(!$f) {
			die("Error when open $com");
		} 
		//向串口发送数据;
		fwrite($f, $in);
		//关闭端口    
		fclose($f);
	}
}

/**
 * 16进制转字符串
 * @param  string $hex 
 * @return str      返回值
 */
if(!function_exists('hex2str')) {
	function hex2str($hex) {
		$str = "";
		for ($i = 0; $i < strlen($hex) - 1; $i+=2) {
			$str .= chr(hexdec($hex[$i] . $hex[$i + 1]));
		}
		return $str;
	}
}

/**
 * 字符串转16进制
 * 传入字符串格式：001624180101
 */
if(!function_exists('str2hex')) {
	function str2hex($str) {
		$hex = "";
		for ($i = 0; $i < strlen($str); $i++) {
			$hex .= dechex(ord($str[$i]));
		}
		$hex = strtoupper($hex);
		return $hex;
	}
}

// 给指定字符串前面补0，达到指定位数
if(!function_exists('str20')) {
	function str20($str, $bitnum) {
		$newstr = $str;
		for($i = 0; $i < $bitnum-strlen($str); $i++) {
			$newstr = '0'.$newstr;
		}
		return $newstr;
	}
}
