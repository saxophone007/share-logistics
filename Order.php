<?php
namespace app\user\controller;
use think\Controller;
use think\Db;

class Order extends Common
{
    public function getOrderDeliveryData()
    {
        $order_id = input('order_id');
        $user_addr = Db::name('order')->where('order_id',$order_id)->value('order_address_district');
        $user_addrs = str_replace('/', '', $user_addr);
        $order_express_no = input('order_express_no');
        $host = "https://wuliu.market.alicloudapi.com";
        $path = "/kdi";
        $method = "GET";
        $appcode = "你自己的APPcode";
        $headers = array("Authorization:APPCODE " . $appcode);
        $querys = "no=" . $order_express_no . "&type=";
        $url = $host . $path . "?" . $querys;
    
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$".$host, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
    
        // 执行请求并获取响应
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            return json(['code' => 400, 'msg' => 'CURL 错误: ' . curl_error($curl)]);
        }
        curl_close($curl);
    
        // 解析响应
        $result = json_decode($result, true);
        if (!$result) {
            return json(['code' => 400, 'msg' => '接口网络错误，请重试！']);
        }
    
        // 检查响应数据并返回
        if ($result['status'] == 0) {
            return json(['code' => 200, 'addr' => $user_addrs,'data' => $result['result']]);
        }
        $messages = [
            201 => '快递单号错误',
            203 => '快递公司不存在',
            204 => ' 快递公司识别失败',
            205 => '信息错误',
            207 => '物流接口错误，请联系客服',
        ];
        return json(['code' => 400, 'msg' => $messages[$result['status']] ?? '未知错误']);
    }

}