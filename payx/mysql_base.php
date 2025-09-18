<?php

include "autoload.php";

$ok_insert_user = mysqli_query($connect,"CREATE TABLE `users` (
   `id` INT NOT NULL AUTO_INCREMENT,
   `user_id` BIGINT NOT NULL,
   `lang` VARCHAR(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
   `date` DATE NOT NULL,
   `step` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
   PRIMARY KEY (`id`)
);");
if ($ok_insert_user) {
    echo "✅ `users` insert bo`ldi!<br>";
}

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domain = $_SERVER['HTTP_HOST'];
$path = $_SERVER['REQUEST_URI'];
$dirname = rtrim(dirname($path), '/');
$new_path = $dirname . '/main.php';

$full_url = $protocol . $domain . $new_path;

$ok_webhook = file_get_contents("https://api.telegram.org/bot".$token."/setWebhook?url=".$full_url);
if($ok_webhook){
    echo "✅ Webhook ulandi!<br>";
}