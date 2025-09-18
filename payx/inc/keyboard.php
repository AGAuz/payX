<?php

$resize_keyboard = json_encode([
	'resize_keyboard'=>true,
		'keyboard'=>[
			[['text'=>"KEY"],],
		]
]);

$inline_keyboard = json_encode([
	'inline_keyboard'=>[
		[['text'=>"KEY1","callback_data"=>"INLINE1"],],
	]
]);

$add_balance = json_encode([
	'inline_keyboard'=>[
		[['text'=>"➕ Hisobni to`ldirish","callback_data"=>"cash"],],
		[['text'=>"payX tizimiga ulanish","url"=>"https://payx.uz"],],
	]
]);

$back_main = json_encode([
	'inline_keyboard'=>[
		[['text'=>"◀️ Ortga","callback_data"=>"back_main"],],
	]
]);