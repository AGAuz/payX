<?php

include "autoload.php";

if($tx=="/start"){
    $get_balance = get_balance($id);
    bot('SendMessage',[
        'chat_id'=>$id,
        'text'=>"<b>payX</b> tizimining sinov boti!
        
💰 Hisobingizda: $get_balance UZS",
        'parse_mode' => "html",
        'reply_markup' => $add_balance
    ]);
    update_step($id, "none");
    exit();
}

if($inline == "cash"){
    bot('editMessageText',[
        'chat_id' => $id2,
        'message_id' => $mid2,
        'text' => "<b>Xisobingizni qanchaga to‘ldirmoqchisiz?</b>

<i>✅ Qabul qilinadi: 5000, 10.000
❌ Qabul qilinmaydi: 10,000 so‘m, 15.000 сум</i>",
        'parse_mode' => "html",
        'reply_markup' => $back_main
    ]);
    update_step($id2, "summa");
}

$get_step = get_step($id);

if($get_step == "summa"){
    $get_invoice = get_invoice($tx);
    if($get_invoice == true){
        $url = "https://c466.coresuz.ru/AGAdev/@payXuz_bot/invoice/pay.php?summa=".$tx."&id=".$id;
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        $payUrl = $data['pay_url'];
        bot('SendMessage',[
           'chat_id' => $id,
           'text' => "To`lovni ushbu link orqali tasdiqlanadi!",
           'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                [['text'=>"✅ Tasdiqlash","url"=>"$payUrl"],],
                ]
                ])
        ]);
        update_step($id, "none");
    }else{
        bot('SendMessage',[
            'chat_id' => $id,
            // 'message_id' => $mid2,
            'text' => "<b>Noto`g`ri format kiritdingiz!</b>

<i>✅ Qabul qilinadi: 5000, 10.000
❌ Qabul qilinmaydi: 10,000 so‘m, 15.000 сум</i>",
            'parse_mode' => "html",
            'reply_markup' => $back_main
        ]);
    }
}

if ($inline=="back_main") {
    $get_balance = get_balance($id2);

    update_step($id2, "none");
    bot('editMessageText',[
        'chat_id'=>$id2,
        'message_id' => $mid2,
        'text'=>"<b>payX</b> tizimining sinov boti!
        
💰 Hisobingizda: $get_balance UZS",
        'parse_mode' => "html",
        'reply_markup' => $add_balance
    ]);
}