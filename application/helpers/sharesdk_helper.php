<?php

/**
 * url For the url address of the service
 * query The request string
 */
function sock_post($url,$query){
    $data = "";
    $info=parse_url($url);
    $fp=fsockopen($info["host"],80,$errno,$errstr,30);
    if(!$fp){
        return $data;
    }
    $head="POST ".$info['path']." HTTP/1.0\r\n";
    $head.="Host: ".$info['host']."\r\n";
    $head.="Referer: http://".$info['host'].$info['path']."\r\n";
    $head.="Content-type: application/x-www-form-urlencoded\r\n";
    $head.="Content-Length: ".strlen(trim($query))."\r\n";
    $head.="\r\n";
    $head.=trim($query);
    $write=fputs($fp,$head);
    $header = "";
    while ($str = trim(fgets($fp,4096))) {
        $header.=$str;
    }
    while (!feof($fp)) {
        $data .= fgets($fp,4096);
    }
    return $data;
}

/**
 * Template interface texting
 * apikey cloud sheet assigned apikey
 * tpl_id template id
 * tpl_value Template value
 * mobile Phone number to receive text messages
 */
function tpl_send_sms($apikey, $tpl_id, $tpl_value, $mobile){
    $url="http://yunpian.com/v1/sms/tpl_send.json";
    $encoded_tpl_value = urlencode("$tpl_value");
    $post_string="apikey=$apikey&tpl_id=$tpl_id&tpl_value=$encoded_tpl_value&mobile=$mobile";
    return sock_post($url, $post_string);
}