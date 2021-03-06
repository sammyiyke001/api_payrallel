<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Redis_Model  extends CI_Model {

    private $redis;
    static  $prefix = "yun_";
    static  $fd ='f_';
    static  $mac ='m_';
    static  $dv_attr ='a_';  //device attr
    static  $dv_data ='d_';  //device data
    // Parameters passed using a named array:


    public function __construct($host = '127.0.0.1', $port = 6379, $timeout = 0.0){
        //$redis = new \redis;
        //$redis->connect($host, $port, $timeout);
        //$this->redis = $redis;

        //changed redis to predis

        
        
        //try to catch the error if redis databse connection fails
        try {
                $redis = new Predis\Client([
            'scheme' => 'tcp',
            'host'   => '10.0.0.121',
            'port'   => 62121379,
            ]);
                $this->redis = $redis;
                
            } catch (Exception $e) {

                 return 001;
                
            }
        }




    public function setDevice($key,array $data){
        $this->redis->hMset(self::$prefix.self::$dv_attr.$key,$data);
    }

    public function getDevice($key){
        return $this->redis->hGetAll(self::$prefix.self::$dv_attr.$key);
    }

    public function checkDevice($key){
        return $this->redis->exists(self::$prefix.self::$dv_attr.$key);
    }

    public function getDeviceAttr($key,$field){
        return $this->redis->hget(self::$prefix.self::$dv_attr.$key,$field);
    }

    public function getDeviceData($key){
        return $this->redis->hGetAll(self::$prefix.self::$dv_data.$key);
    }

    public function setDeviceData($key,array $value){
        $this->redis->hMset(self::$prefix.self::$dv_data.$key,$value);
    }

    public function setLimit($key,array $value){
        $this->redis->hMset($key,$value);
        $this->redis->expire($key,'3600');
    }

    public function getLimit($key,$field){
        return $this->redis->hget($key,$field);
    }
    
    public function setToken($key,$value){
        $this->redis->set($key,$value);
        $this->redis->expire($key,'7200');
    }

    public function getToken($key){
        return $this->redis->get($key);
    }

    public function delToken($key){
        return $this->redis->del($key);
    }

}