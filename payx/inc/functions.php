<?php

function auth($user_id){
    global $connect, $type, $today;

    if($type == "private"){
        $user_id = intval($user_id);

        $result = mysqli_query($connect,"SELECT * FROM users WHERE user_id = $user_id");
        $rew = mysqli_fetch_assoc($result);

        if (!$rew) {
            mysqli_query($connect,"INSERT INTO users(user_id, lang, date, balance, step) VALUES ($user_id, 'uz', '$today', 0, 'none')");
        }
    }
}

function language($user_id) {
    global $connect;

    $user_id = intval($user_id);

    $query = "SELECT lang FROM users WHERE user_id = $user_id LIMIT 1";
    $result = mysqli_query($connect, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['lang'];
    } else {
        return null;
    }
}


function get_balance($user_id) {
    global $connect;

    $user_id = intval($user_id);

    $query = "SELECT balance FROM users WHERE user_id = $user_id LIMIT 1";
    $result = mysqli_query($connect, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['balance'];
    } else {
        return null;
    }
}

function get_step($user_id) {
    global $connect;

    $user_id = intval($user_id);

    $query = "SELECT step FROM users WHERE user_id = $user_id LIMIT 1";
    $result = mysqli_query($connect, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['step'];
    } else {
        return null;
    }
}

function update_step($user_id, $new_step) {
    global $connect;

    $user_id = intval($user_id);
    $new_step = mysqli_real_escape_string($connect, $new_step);

    $query = "UPDATE users SET step = '$new_step' WHERE user_id = $user_id";
    return mysqli_query($connect, $query);
}

function get_invoice($tx) {
    global $connect;

    if (!is_numeric($tx) || intval($tx) != $tx) {
        return false;
    }
    return true;
}
