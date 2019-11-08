<?php

  $nonce=$_GET['nonce'];
  $token='imooc';
  $timestamp=$_GET['timestamp'];
  $echostr=$_GET['echostr'];
  $signature=$_GET['signature'];
//形成数组，然后按字典序排序
$array=array();
$array=array($nonce,$timestamp,$token);
sort($array);
//拼接成字符串，sha1加密，然后与signature进行检验
$str=sha1(implode($array));
if($str==$signature&&$echostr)
{
header('content-type:text');
//第一次接入微信API接口时候验证合法性
echo $echostr;
exit;
}
else
{
responseMsg();
}

  function responseMsg()
{
//1.获取到微信推送过来post数据（xml格式）
$postArr=$GLOBALS['HTTP_RAW_POST_DATA'];
//2.处理消息类型，并设置回复类型和内容
$postObj=simplexml_load_string($postArr);
        //判断该数据包是否是订阅de事件推送
        if(strtolower($postObj->MsgType)=='event')
        {
        //如果是关注 subscribe事件
        if(strtolower($postObj->Event)=='subscribe')
        {
        $toUser    =$postObj->FromUserName;
        $fromUser  =$postObj->ToUserName;
        $time      =time();
        $msgType   ='text';
        $content   ='欢迎关注我的微信公众号！';
        $template="<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    </xml>";
        $info=sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
        echo $info;
        }
        }

}
