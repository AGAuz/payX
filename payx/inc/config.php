<?php
date_default_timezone_set("asia/Tashkent");
$token = 'BOT_TOKEN';


function bot($method,$datas=[]){
global $token;
    $url = "https://api.telegram.org/bot".$token."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}


$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$tx = $message->text;
$mid = $message->message_id;
$id = $message->chat->id;
$fid = $message->from->id;
$type = $message->chat->type;
$username = $message->chat->username;
$fname = $message->chat->first_name;
$today = date("Y-m-d");

$inline = $update->callback_query->data;
$id2 = $update->callback_query->message->chat->id;
$mid2 = $update->callback_query->message->message_id;