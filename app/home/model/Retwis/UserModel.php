<?php
/**
 * Created by PhpStorm.
 * User: xuehao
 * Date: 2017/11/17
 * Time: 下午15:28
 */
namespace home\model\Retwis;
use core\Redis;
/*
 * 用户模型
 */
class UserModel {
	private $_username_key;   //username
	private $_userpassword_key;  //password
	private $_user_id_from_username_key;  //通过username查找user_id
	private $_global_user_incr_key;  //user表自增id
	private $_redis;  //redis对象
	
	/**
     * __construct 初始化
     * @access public
     * @return void
     */
	public function __construct(){
		$this->_username_key = "user:user_id:%d:username";
		$this->_userpassword_key = "user:user_id:%d:password";
		$this->_global_user_incr_key = "user:incr:key";
		$this->_user_id_from_username_key = "user:username:%s:user_id";
		$this->_redis = Redis::getInstance();
	}
	/**
     * register 注册
     * @access public
     * @return void
     */
	public function register($username, $password){
		$result = ['status'=>'success','msg'=>''];
		
		$exists = $this->check_user_exists($username);
		
		if($exists){
			$result['status'] = 'error';
			$result['msg'] = '该用户已存在';
			return $result;
		}
		
		$primary_key = $this->getUserPrimaryKey();
		$user_name_redis_key = sprintf($this->_username_key,$primary_key);
		$user_password_redis_key = sprintf($this->_userpassword_key,$primary_key);
		$this->_redis->set($user_name_redis_key,$username);
		$this->_redis->set($user_password_redis_key,$password);
		//加入冗余数据,用于用username查找user_id
		$user_id_from_user_name_redis_key = sprintf($this->_user_id_from_username_key,$username);  //用于判断username是否存在
		$this->_redis->set($user_id_from_user_name_redis_key,$primary_key);
		return $result;
	}
	/**
     * login 登录
     * @access public
     * @return void
     */
	public function login($username, $password){
		$result = ['status'=>'success','msg'=>''];
		$user_id = $this->check_user_exists($username);
		if(!$user_id){
			$result['status'] = 'error';
			$result['msg'] = '该用户不存在';
			return $result;
		}
		$user_password_redis_key = sprintf($this->_userpassword_key,$user_id);
		$password_from_redis = $this->_redis->get($user_password_redis_key);
		if($password != $password_from_redis){
			$result['status'] = 'error';
			$result['msg'] = '用户密码错误';
			return $result;
		}
		//写入cookie操作
		return $result;
	} 
	/**
     * check_user_exists 检测用户是否存在
     * @access public
     * @return void
     */
	public function check_user_exists($username){
		$user_id_from_user_name_redis_key = sprintf($this->_user_id_from_username_key,$username); //用于判断username是否存在
		$user_id = $this->_redis->get($user_id_from_user_name_redis_key);
		return $user_id;
	}
	/**
     * getUserPrimaryKey 获取user表的主键
     * @access private
     * @return void
     */
	private function getUserPrimaryKey(){
		return $this->_redis->incr($this->_global_user_incr_key);
	}
		
    
}