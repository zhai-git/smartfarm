<?php
namespace app\index\controller;

use think\Controller;

class Base extends Controller {

    public $param_data = [];
    
    public function __construct() {
        parent::__construct();
        $this->param_data = input('param.');
    }

    // 根据功能程序获取相应功能描述文本
    public function get_cmd_text($program, $cmd) {
        $cmd_text = ''; $cmd = explode('_', $cmd); $cmd_count = count($cmd);
		if ($cmd_count > 0) {
			if (in_array($program, [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15])) {
				switch($cmd[0]) {
                    case 1:     // 输出
                        $cmd_text .= '输出';
                        $num = $cmd[2] < 10 ? '0'.$cmd[2] : $cmd[2];
                        $cmd_text .= $cmd[1] == 1 ? ' 端口Y'.$num : ' 程序Z'.$num;
                        if ($cmd[1] == 1) { // 端口Y
                            if ($cmd[3] == 1) {
                                $cmd_text .= '';
                            } elseif ($cmd[3] == 2) {
                                $num = $cmd[4] < 10 ? '0'.$cmd[4] : $cmd[4];
                                $cmd_text .= ' 到 端口Y'.$num;
                            } elseif ($cmd[3] == 3) {
                                $cmd_text .= ' 到 端口Y31';
                            } else {
                                $cmd_text .= ' 到 端口Y00';
                            }
                            if ($cmd[$cmd_count-1] == 1) {
                                $cmd_text .= ' 闭合';
                            } elseif ($cmd[$cmd_count-1] == 2) {
                                $cmd_text .= ' 断开';
                            } else {
                                $cmd_text .= ' 翻转';
                            }
                        } else {            // 程序Z
                            if ($cmd[$cmd_count-1] == 1) {
                                $cmd_text .= ' 运行';
                            } elseif ($cmd[$cmd_count-1] == 2) {
                                $cmd_text .= ' 停止';
                            } else {
                                $cmd_text .= ' 暂停';
                            }
                        }
                        break;
                    case 2:     // 延时
                        $cmd_text .= '延时';
                        if ($cmd[1] == 1) {
                            $cmd_text .= ' '.$cmd[2].' 时 '.$cmd[3].' 分 '.$cmd[4].' 秒';
                        } else {
                            $num = $cmd[2] < 10 ? '0'.$cmd[2] : $cmd[2];
                            $cmd_text .= ' 定时器T'.$num;
                            if ($cmd[3] == 1) {
                                $cmd_text .= ' 递增';
                            } elseif ($cmd[3] == 2) {
                                $cmd_text .= ' = '.$cmd[4].' 时 '.$cmd[5].' 分 '.$cmd[6].' 秒';
                            } elseif ($cmd[3] == 3) {
                                $cmd_text .= ' + '.$cmd[4].' 时 '.$cmd[5].' 分 '.$cmd[6].' 秒';
                            } elseif ($cmd[3] == 4) {
                                $cmd_text .= ' - '.$cmd[4].' 时 '.$cmd[5].' 分 '.$cmd[6].' 秒';
                            } else {
                                $num = $cmd[4] < 10 ? '0'.$cmd[4] : $cmd[4];
                                $cmd_text .= ' : 人机参数DT'.$num;
                            }
                        }
                        break;
                    case 3:     // 计数
                        $num = $cmd[2] < 10 ? '0'.$cmd[2] : $cmd[2];
                        $cmd_text .= '计数 计数器C'.$num;
                        if ($cmd[3] == 1) {
                            $cmd_text .= ' 递增';
                        } elseif ($cmd[3] == 2) {
                            $cmd_text .= ' = '.$cmd[4];
                        } elseif ($cmd[3] == 3) {
                            $cmd_text .= ' + '.$cmd[4];
                        } else {
                            $cmd_text .= ' - '.$cmd[4];
                        }
                        break;
                    case 4:     // 循环
                        $line_no  = $cmd[1] < 10 ? '00'.$cmd[1] : ($cmd[1] < 100 ? '0'.$cmd[1] : $cmd[1]);
                        $cmd_text .= '循环 '.$line_no.' 步 '.$cmd[2].' 次';
                        break;
                    case 5:     // 如果
                        $cmd_text .= '如果';
                        $num = $cmd[2] < 10 ? '0'.$cmd[2] : $cmd[2];
                        $cmd_text .= $cmd[1] == 1 ? ' 端口X'.$num : ' 端口Y'.$num;
                        $cmd_text .= $cmd[3] == 1 ? ' 闭合' : ' 断开';
                        if ($cmd[4] == 1) {
                            $cmd_text .= ' 向下';
                        } else {
                            $line_no  = $cmd[5] < 10 ? '00'.$cmd[5] : ($cmd[5] < 100 ? '0'.$cmd[5] : $cmd[5]);
                            $cmd_text .= ' 跳转'.$line_no;
                        }
                        break;
                    default:
                        break;
                }
			} elseif ($program == 16) {
				if ($cmd[0] == 1) {
					$cmd_text .= '定日　20'.$cmd[1].' - 20'.$cmd[2].'年　'.$cmd[3].' - '.$cmd[4].'月　'.$cmd[5].' - '.$cmd[6].'日';
				} elseif ($cmd[0] == 2) {
					$cmd_text .= '定时　'.($cmd[1] ? '周日 ' : '').($cmd[2] ? '周1 ' : '').($cmd[3] ? '周2 ' : '').($cmd[4] ? '周3 ' : '').($cmd[5] ? '周4 ' : '').($cmd[6] ? '周5 ' : '').($cmd[7] ? '周6 ' : '').'<br/>'.$cmd[8].'时 '.$cmd[9].'分 '.$cmd[10].'秒　－　'.$cmd[11].'时 '.$cmd[12].'分 '.$cmd[13].'秒';
				} elseif ($cmd[0] == 3) {
					$cmd_text .= '输出';
					if ($cmd[1] == 1) {
						$num = $cmd[2] < 10 ? '0'.$cmd[2] : $cmd[2];
						$cmd_text .= ' 端口Y'.$num;
						if (in_array($cmd[3], [2,3,4])) {
							if ($cmd[3] == 2) {
								$num = $cmd[4] < 10 ? '0'.$cmd[4] : $cmd[4];
							} elseif ($cmd[3] == 3) {
								$num = '31';
							} else {
								$num = '00';
							}
							$cmd_text .= ' 到 Y'.$num;
						}
						
						if ($cmd[$cmd_count-1] == 1) {
							$cmd_text .= ' 闭合';
						} else {
							$cmd_text .= ' 断开';
						}
					} else {
						$num = $cmd[2] < 10 ? '0'.$cmd[2] : $cmd[2];
						$cmd_text .= ' 程序Z'.$num;
						if ($cmd[3] == 1) {
							$cmd_text .= ' 运行';
						} elseif ($cmd[3] == 2) {
							$cmd_text .= ' 停止';
						} else {
							$cmd_text .= ' 暂停';
						}
					}
				} elseif ($cmd[0] == 4) {
					$cmd_text .= '与';
					$num = $cmd[2] < 10 ? '0'.$cmd[2] : $cmd[2];
					$cmd_text .= $cmd[1] == 1 ? ' 端口X'.$num : ' 端口Y'.$num;
					if ($cmd[3] == 2) {
						$num = $cmd[4] < 10 ? '0'.$cmd[4] : $cmd[4];
						$cmd_text .= $cmd[1] == 1 ? ' 到 X'.$num : ' 到 Y'.$num;
					}
					if ($cmd[$cmd_count-1] == 1) {
						$cmd_text .= ' 闭合';
					} else {
						$cmd_text .= ' 断开';
					}
				} else {
					$cmd_text .= '校时';
				}
			} else {
				if (in_array($cmd[0], [1,2,3])) {		// 如果 与 或
					$cmd_text .= $cmd[0] == 1 ? '如果' : ($cmd[0] == 2 ? '与' : '或');
					if (in_array($cmd[1], [1,2])) {
						$num = $cmd[2] < 10 ? '0'.$cmd[2] : $cmd[2];
						$cmd_text .= $cmd[1] == 1 ? ' 端口X'.$num : ' 端口Y'.$num;
						if ($cmd[3] == 2) {
							$num = $cmd[4] < 10 ? '0'.$cmd[4] : $cmd[4];
							$cmd_text .= $cmd[1] == 1 ? ' 到 X'.$num : ' 到 Y'.$num;
						}
						if ($cmd[$cmd_count-1] == 1) {
							$cmd_text .= ' 闭合';
						} elseif ($cmd[$cmd_count-1] == 2) {
							$cmd_text .= ' 断开';
						} elseif ($cmd[$cmd_count-1] == 3) {
							$cmd_text .= ' 下跳';
						} else {
							$cmd_text .= ' 上跳';
						}
					} elseif ($cmd[1] == 3) {
						$num = $cmd[2] < 10 ? '0'.$cmd[2] : $cmd[2];
						$cmd_text .= ' 计数器C'.$num;


						if ($cmd[3] == 1) {
							$cmd_text .= ' >= '.$cmd[4];
						} else {
							$cmd_text .= ' < '.$cmd[4];
						}

						/* if ($cmd[3] == 1) {
							$cmd_text .= ' <= '.$cmd[4];
						} elseif ($cmd[3] == 2) {
							$cmd_text .= ' = '.$cmd[4];
						} elseif ($cmd[3] == 3) {
							$cmd_text .= ' >= '.$cmd[4];
						} elseif ($cmd[3] == 4) {
							$num = $cmd[4] < 10 ? '0'.$cmd[4] : $cmd[4];
							$cmd_text .= ' <=人机参数 DC'.$num;
						} elseif ($cmd[3] == 5) {
							$num = $cmd[4] < 10 ? '0'.$cmd[4] : $cmd[4];
							$cmd_text .= ' =人机参数 DC'.$num;
						} else {
							$num = $cmd[4] < 10 ? '0'.$cmd[4] : $cmd[4];
							$cmd_text .= ' >=人机参数 DC'.$num;
						} */
					} else {
						$num = $cmd[2] < 10 ? '0'.$cmd[2] : $cmd[2];
						$cmd_text .= ' 计时器T'.$num;

						if ($cmd[3] == 1) {
							$cmd_text .= ' >= '.$cmd[4].' 时 '.$cmd[5].' 分 '.$cmd[6].' 秒';
						} else {
							$cmd_text .= ' < '.$cmd[4].' 时 '.$cmd[5].' 分 '.$cmd[6].' 秒';
						}

						/* if ($cmd[3] == 1) {
							$cmd_text .= ' <= '.$cmd[4].' 时 '.$cmd[5].' 分 '.$cmd[6].' 秒';
						} elseif ($cmd[3] == 2) {
							$cmd_text .= ' = '.$cmd[4].' 时 '.$cmd[5].' 分 '.$cmd[6].' 秒';
						} elseif ($cmd[3] == 3) {
							$cmd_text .= ' >= '.$cmd[4].' 时 '.$cmd[5].' 分 '.$cmd[6].' 秒';
						} elseif ($cmd[3] == 4) {
							$num = $cmd[4] < 10 ? '0'.$cmd[4] : $cmd[4];
							$cmd_text .= ' <=人机参数 DT'.$num;
						} elseif ($cmd[3] == 5) {
							$num = $cmd[4] < 10 ? '0'.$cmd[4] : $cmd[4];
							$cmd_text .= ' =人机参数 DT'.$num;
						} else {
							$num = $cmd[4] < 10 ? '0'.$cmd[4] : $cmd[4];
							$cmd_text .= ' >=人机参数 DT'.$num;
						} */
					}
				} elseif ($cmd[0] == 4) {
					$cmd_text .= '输出';
					if ($cmd[1] == 1) {
						$num = $cmd[2] < 10 ? '0'.$cmd[2] : $cmd[2];
						$cmd_text .= ' 端口Y'.$num;
						if (in_array($cmd[3], [2,3,4])) {
							if ($cmd[3] == 2) {
								$num = $cmd[4] < 10 ? '0'.$cmd[4] : $cmd[4];
							} elseif ($cmd[3] == 3) {
								$num = '31';
							} else {
								$num = '00';
							}
							$cmd_text .= ' 到 Y'.$num;
						}
						
						if ($cmd[$cmd_count-1] == 1) {
							$cmd_text .= ' 闭合';
						} elseif ($cmd[$cmd_count-1] == 2) {
							$cmd_text .= ' 断开';
						} elseif ($cmd[$cmd_count-1] == 3) {
							$cmd_text .= ' 翻转';
						} else {
							$cmd_text .= ' 点动';
						}
					} else {
						$num = $cmd[2] < 10 ? '0'.$cmd[2] : $cmd[2];
						$cmd_text .= ' 程序Z'.$num;
						if ($cmd[3] == 1) {
							$cmd_text .= ' 运行';
						} elseif ($cmd[3] == 2) {
							$cmd_text .= ' 停止';
						} elseif ($cmd[3] == 3) {
							$cmd_text .= ' 暂停';
						} elseif ($cmd[3] == 4) {
							$cmd_text .= ' 驻停';
						} elseif ($cmd[3] == 5) {
							$cmd_text .= ' 重启';
						} elseif ($cmd[3] == 6) {
							$cmd_text .= ' 快进';
						} elseif ($cmd[3] == 7) {
							$cmd_text .= ' 运行/停止';
						} else {
							$cmd_text .= ' 暂停/运行';
						}
					}
				} elseif ($cmd[0] == 5) {
					$cmd_text .= '延时';
					$num = $cmd[2] < 10 ? '0'.$cmd[2] : $cmd[2];
					$cmd_text .= ' 计时器T'.$num;
					if ($cmd[3] == 1) {
						$cmd_text .= ' 递减';
					} elseif ($cmd[3] == 2) {
						$cmd_text .= ' = '.$cmd[4].' 时 '.$cmd[5].' 分 '.$cmd[6].' 秒';
					} elseif ($cmd[3] == 3) {
						$cmd_text .= ' + '.$cmd[4].' 时 '.$cmd[5].' 分 '.$cmd[6].' 秒';
					} else {
						$cmd_text .= ' - '.$cmd[4].' 时 '.$cmd[5].' 分 '.$cmd[6].' 秒';
					}
				} else {
					$cmd_text .= '计数';
					$num = $cmd[2] < 10 ? '0'.$cmd[2] : $cmd[2];
					$cmd_text .= ' 计数器C'.$num;
					if ($cmd[3] == 1) {
						$cmd_text .= ' 递增';
					} elseif ($cmd[3] == 2) {
						$cmd_text .= ' = '.$cmd[4];
					} elseif ($cmd[3] == 3) {
						$cmd_text .= ' + '.$cmd[4];
					} else {
						$cmd_text .= ' - '.$cmd[4];
					}
				}
			}
		}
      
        return $cmd_text;
    }

	// 根据cmd转换成PLC指令(16进制)
	public function get_plc($program, $cmd) {
		$plc = '';
		$cmd = explode('_', $cmd); $cmd_count = count($cmd);
		if ($cmd_count > 0) {
			if (in_array($program, [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15])) {
				if ($cmd[0] == 1) {	// 输出
					$plc .= '10';
					if ($cmd[1] == 1) {	// 端口Y
						$plc .= '01';

						$bin = '';
						if ($cmd[3] == 1) {	// 单路
							for($i = 0; $i <= 31; $i++) {
								$bin .= (31-$i) == $cmd[2] ? '1' : '0';
							}
						} else {
							if ($cmd[3] == 2) {	// 到
								$max = max($cmd[2], $cmd[4]);
								$min = min($cmd[2], $cmd[4]);
							} elseif ($cmd[3] == 3) {	// +
								$max = 31; $min = $cmd[2];
							} else {	// -
								$max = $cmd[2]; $min = 0;
							}
							for($i = 0; $i <= 31; $i++) {
								if ((31-$i) >= $min && (31-$i) <= $max) {
									$bin .= '1';
								} else {
									$bin .= '0';
								}
							}
						} 

						// 二进制转换为16进制，高位补0
						$hex = dechex(bindec($bin));
						$plc .= str20($hex, 8);

						if ($cmd[$cmd_count-1] == 1) {	// 闭合
							$plc .= '01';
						} elseif ($cmd[$cmd_count-1] == 2) {	// 断开
							$plc .= '02';
						} else {	// 翻转
							$plc .= '03';
						}

					} else { // 程序Z
						$plc .= '02';
						$hex = dechex($cmd[2]);
						$plc .= str20($hex, 8);
						if ($cmd[$cmd_count-1] == 1) {	// 运行
							$plc .= '01';
						} elseif ($cmd[$cmd_count-1] == 2) {	// 停止
							$plc .= '02';
						} else {	// 暂停
							$plc .= '03';
						}
					}
				} elseif ($cmd[0] == 2) {	// 延时
					$plc .= '11';
					if ($cmd[1] == 1) {	// 立即数
						$plc .= '01';
						// 总秒数
						$second = $cmd[2]*3600 + $cmd[3]*60 + $cmd[4];
						$hex = dechex($second * 100);
						$plc .= str20($hex, 8);
						$plc .= '00';
					} else {	// 定时器T
						$plc .= '02';
						$plc .= str20(dechex($cmd[2]), 2);
						if ($cmd[3] == 1) {	// 递增
							$plc .= '00000001';
						} else {
							// 总秒数
							$second = $cmd[4]*3600 + $cmd[5]*60 + $cmd[6];
							$hex = dechex($second * 100);
							$plc .= str20($hex, 6);

							if ($cmd[3] == 2) {	// =
								$plc .= '02';
							} elseif ($cmd[3] == 3) {	// +
								$plc .= '03';
							} else {	// -
								$plc .= '04';
							}
						}
					}
				} elseif ($cmd[0] == 3) {	// 计数
					$plc .= '1201';
					$plc .= str20(dechex($cmd[2]), 2);
					if ($cmd[3] == 1) {		// 递增
						$plc .= '00000001';
					} else {
						$plc .= str20(dechex($cmd[4]), 6);
						if ($cmd[3] == 2) {	// =
							$plc .= '02';
						} elseif ($cmd[3] == 3) {	// +
							$plc .= '03';
						} else {	// -
							$plc .= '04';
						}
					}

				} elseif ($cmd[0] == 4) {	// 循环
					$plc .= '1401';
					$plc .= str20(dechex($cmd[2]), 6);
					$plc .= str20(dechex($cmd[1]), 2);
					$plc .= '00';
				} else {	// 如果
					$plc .= '13';
					if ($cmd[3] == 1 && $cmd[4] == 1) {	// 闭合向下
						$plc .= '01';
					} elseif ($cmd[3] == 2 && $cmd[4] == 1) {	// 断开向下
						$plc .= '02';
					} elseif ($cmd[3] == 1 && $cmd[4] == 2) {	// 闭合跳转
						$plc .= '03';
					} else {	// 断开跳转
						$plc .= '04';
					}
					if ($cmd[1] == 1) {		// 端口X
						$plc .= str20(dechex($cmd[2]), 8);
					} else {	// 端口Y
						$dec = $cmd[2] + 32;
						$plc .= str20(dechex($dec), 8);
					}
					if ($cmd[4] == 1) {
						$plc .= '00';
					} else {
						$plc .= str20(dechex($cmd[5]), 2);
					}
				}
			} elseif ($program == 16) {		// 日历定时
				if ($cmd[0] == 1) {	// 定日
					$plc .= '3301';

					$bin1 = str20(decbin($cmd[1]), 5);
					$bin2 = str20(decbin($cmd[2]), 5);
					$bin3 = str20(decbin($cmd[3]), 5);
					$bin4 = str20(decbin($cmd[4]), 5);
					$bin5 = str20(decbin($cmd[5]), 5);
					$bin6 = str20(decbin($cmd[6]), 5);

					$bin = '00'.$bin1.$bin2.$bin3.$bin4.$bin5.$bin6;
					$hex = dechex(bindec($bin));
					$plc .= str20($hex, 8);
					$plc .= '00';
				} elseif ($cmd[0] == 2) {	// 定时
					$plc .= '32';

					$bin1 = str20(decbin($cmd[8]), 5);
					$bin2 = str20(decbin($cmd[9]), 6);
					$bin3 = str20(decbin($cmd[10]), 6);
					$bin4 = str20(decbin($cmd[11]), 5);
					$bin5 = str20(decbin($cmd[12]), 6);
					$bin6 = str20(decbin($cmd[13]), 6);
					$bin = '000000'.$bin1.$bin2.$bin3.$bin4.$bin5.$bin6;
					$hex = dechex(bindec($bin));
					$plc .= str20($hex, 10);

					$day = '0'.$cmd[7].$cmd[6].$cmd[5].$cmd[4].$cmd[3].$cmd[2].$cmd[1];
					$hex = dechex(bindec($day));
					$plc .= str20($hex, 2);
				} elseif ($cmd[0] == 3) {	// 输出
					$plc .= '35';

					if ($cmd[1] == 1) {	// 端口Y
						$plc .= '01';

						$bin = '';
						if ($cmd[3] == 1) {	// 单路
							for($i = 0; $i <= 31; $i++) {
								$bin .= (31-$i) == $cmd[2] ? '1' : '0';
							}
						} else {
							if ($cmd[3] == 2) {	// 到
								$max = max($cmd[2], $cmd[4]);
								$min = min($cmd[2], $cmd[4]);
							} elseif ($cmd[3] == 3) {	// +
								$max = 31; $min = $cmd[2];
							} else {	// -
								$max = $cmd[2]; $min = 0;
							}
							for($i = 0; $i <= 31; $i++) {
								if ((31-$i) >= $min && (31-$i) <= $max) {
									$bin .= '1';
								} else {
									$bin .= '0';
								}
							}
						} 

						// 二进制转换为16进制，高位补0
						$hex = dechex(bindec($bin));
						$plc .= str20($hex, 8);

						if ($cmd[$cmd_count-1] == 1) {	// 闭合
							$plc .= '01';
						} else {	// 断开
							$plc .= '02';
						}

					} else { // 程序Z
						$plc .= '02';
						$hex = dechex($cmd[2]);
						$plc .= str20($hex, 8);
						if ($cmd[$cmd_count-1] == 1) {	// 运行
							$plc .= '01';
						} elseif ($cmd[$cmd_count-1] == 2) {	// 停止
							$plc .= '02';
						} else {	// 暂停
							$plc .= '03';
						}
					}
				} elseif ($cmd[0] == 4) {	// 与
					$plc .= '34';

					if ($cmd[$cmd_count-1] == 1) {	// 闭合
						$plc .= '01';
					} else {	// 断开
						$plc .= '02';
					}

					if ($cmd[1] == 1) {		// 端口X
						$plc .= str20(dechex($cmd[2]), 8);
					} else {	// 端口Y
						$dec = $cmd[2] + 32;
						$plc .= str20(dechex($dec), 8);
					}
					
					$plc .= '00';

				} else {	// 校时
					$plc .= '3101';
					$nowtime = time();
					$y = date('y', $nowtime);
					$m = date('m', $nowtime);
					$d = date('d', $nowtime);
					$h = date('H', $nowtime);
					$i = date('i', $nowtime);
					$s = date('s', $nowtime);

					$bin1 = str20(decbin($y), 5);
					$bin2 = str20(decbin($m), 5);
					$bin3 = str20(decbin($d), 5);
					$bin4 = str20(decbin($h), 5);
					$bin5 = str20(decbin($i), 6);
					$bin6 = str20(decbin($s), 6);

					$bin = $bin1.$bin2.$bin3.$bin4.$bin5.$bin6;
					$hex = dechex(bindec($bin));
					$plc .= str20($hex, 8);

					$plc .= '00';
				}

			} else {		// 逻辑控制
				if (in_array($cmd[0], [1,2,3])) {	// 如果与或
					if ($cmd[0] == 1) {
						$plc .= '20';
					} elseif ($cmd[0] == 2) {
						$plc .= '21';
					} else {
						$plc .= '22';
					}

					if (in_array($cmd[1], [1,2])) {
						if ($cmd[$cmd_count-1] == 1) {	// 闭合
							$plc .= '01';
						} elseif ($cmd[$cmd_count-1] == 2) {	// 断开
							$plc .= '02';
						} elseif ($cmd[$cmd_count-1] == 3) {	// 上跳
							$plc .= '03';
						} else {	// 下跳
							$plc .= '04';
						}

						if ($cmd[1] == 1) {		// 端口X
							$plc .= str20(dechex($cmd[2]), 8);
						} else {	// 端口Y
							$dec = $cmd[2] + 32;
							$plc .= str20(dechex($dec), 8);
						}
						$plc .= '00';
					} elseif ($cmd[1] == 3) {	// 计数器C
						$plc .= '06';
						$plc .= str20(dechex($cmd[2]), 2);
						$plc .= str20(dechex($cmd[4]), 6);
						$plc .= $cmd[3] == 1 ? '00' : '01';
					} else {	// 计时器T
						$plc .= '07';
						$plc .= str20(dechex($cmd[2]), 2);
						// 总秒数
						$second = $cmd[4]*3600 + $cmd[5]*60 + $cmd[6];
						$hex = dechex($second * 100);
						$plc .= str20($hex, 6);
						$plc .= $cmd[3] == 1 ? '00' : '01';
					}

				} elseif ($cmd[0] == 4) {	// 输出
					$plc .= '23';

					if ($cmd[1] == 1) {	// 端口Y
						$plc .= '01';

						$bin = '';
						if ($cmd[3] == 1) {	// 单路
							for($i = 0; $i <= 31; $i++) {
								$bin .= (31-$i) == $cmd[2] ? '1' : '0';
							}
						} else {
							if ($cmd[3] == 2) {	// 到
								$max = max($cmd[2], $cmd[4]);
								$min = min($cmd[2], $cmd[4]);
							} elseif ($cmd[3] == 3) {	// +
								$max = 31; $min = $cmd[2];
							} else {	// -
								$max = $cmd[2]; $min = 0;
							}
							for($i = 0; $i <= 31; $i++) {
								if ((31-$i) >= $min && (31-$i) <= $max) {
									$bin .= '1';
								} else {
									$bin .= '0';
								}
							}
						} 

						// 二进制转换为16进制，高位补0
						$hex = dechex(bindec($bin));
						$plc .= str20($hex, 8);

						if ($cmd[$cmd_count-1] == 1) {	// 闭合
							$plc .= '01';
						} elseif ($cmd[$cmd_count-1] == 2) {	// 断开
							$plc .= '02';
						} else {	// 翻转
							$plc .= '03';
						}

					} else { // 程序Z
						$plc .= '02';
						$hex = dechex($cmd[2]);
						$plc .= str20($hex, 8);
						if ($cmd[$cmd_count-1] == 1) {	// 运行
							$plc .= '01';
						} elseif ($cmd[$cmd_count-1] == 2) {	// 停止
							$plc .= '02';
						} else {	// 暂停
							$plc .= '03';
						}
					}

				} elseif ($cmd[0] == 5) {	// 延时
					$plc .= '2402';
					$plc .= str20(dechex($cmd[2]), 2);
					if ($cmd[3] == 1) {	// 递减
						$plc .= '00000001';
					} else {
						// 总秒数
						$second = $cmd[4]*3600 + $cmd[5]*60 + $cmd[6];
						$hex = dechex($second * 100);
						$plc .= str20($hex, 6);

						if ($cmd[3] == 2) {	// =
							$plc .= '02';
						} elseif ($cmd[3] == 3) {	// +
							$plc .= '03';
						} else {	// -
							$plc .= '04';
						}
					}
				} else {	// 计数
					$plc .= '2501';
					$plc .= str20(dechex($cmd[2]), 2);
					if ($cmd[3] == 1) {		// 递增
						$plc .= '00000001';
					} else {
						$plc .= str20(dechex($cmd[4]), 6);
						if ($cmd[3] == 2) {	// =
							$plc .= '02';
						} elseif ($cmd[3] == 3) {	// +
							$plc .= '03';
						} else {	// -
							$plc .= '04';
						}
					}
				}
			}
		}
		return $plc;
	}
}