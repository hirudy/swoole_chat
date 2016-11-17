<?php
/**
 *
 * function description
 *
 * @author: rudy
 * @date: 2016/11/16
 */

include_once "THttp.php";

class RobotRequest{

    public static function request($userId, $data){
        $result = array(
            "isOk" => 0,
            "message" => ""
        );
        do{
            if(strlen($data) >= 30){
                $result["isOk"] = -1;
                $result["message"] = "字符串太长";
                break;
            }
            try{
                $rel = THttp::simpleResponseRequest(
                    "http://www.tuling123.com/openapi/api",
                    array(
                        "key" => "a36244b5e60c40138cae60a356e3ae2a",
                        "userid" => $userId,
                        "info" => $data
                    )
                );
                $data = json_decode($rel,true);
            }catch (Exception $e){}

            if(empty($data)){
                $result["isOk"] = -2;
                $result["message"] = "请求出错了";
                break;
            }

            switch ($data["code"]) {
                case 100000:
                    $result["message"] = $data["text"];
                    break;
                case 200000:
                    $result["message"] = $data["url"];
                    break;
                case 302000: {
                    $result["message"] = $data["list"][0]["article"];
                }
                    break;
                default: {
                    $result["message"] = "您的请求未能完成";
                }
            }
        }while(false);
        return $result;
    }
}