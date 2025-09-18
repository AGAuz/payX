<?php
include_once('../inc/mysql.php');

if (!$connect) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Database connection failed']);
    exit;
}

function get_auth_header() {
    if (function_exists('getallheaders')) {
        $h = getallheaders();
        if (isset($h['Authorization'])) return $h['Authorization'];
        if (isset($h['AUTHORIZATION'])) return $h['AUTHORIZATION'];
    }
    foreach ($_SERVER as $k => $v) {
        if ($k === 'HTTP_AUTHORIZATION') return $v;
    }
    return null;
}

$auth = get_auth_header();
if (!$auth || stripos($auth, 'Bearer ') !== 0) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Unauthorized']);
    exit;
}
$token = substr($auth, 7);
$expectedToken = 'TOKEN';
if ($token !== $expectedToken) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Invalid token']);
    exit;
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Invalid JSON']);
    exit;
}

$requiredFields = ['user_id', 'amount', 'currency', 'transaction_id', 'timestamp'];
foreach ($requiredFields as $field) {
    if (!isset($data[$field])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['message' => "Missing required field: $field"]);
        exit;
    }
}

$user_id = isset($data['user_id']) ? (int)$data['user_id'] : 0;
$amount = isset($data['amount']) ? (int)$data['amount'] : 0;
$currency = isset($data['currency']) ? $data['currency'] : '';
$transaction_id = isset($data['transaction_id']) ? $data['transaction_id'] : '';
$timestamp = isset($data['timestamp']) ? (int)$data['timestamp'] : time();
$status = "plus";

if ($user_id <= 0 || $amount <= 0) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(["status" => "error", "message" => "user_id yoki amount noto‘g‘ri"]);
    exit;
}


$sql_check = "SELECT COUNT(*) as count FROM transactions WHERE transaction_id = ?";
$stmt_check = mysqli_prepare($connect, $sql_check);
mysqli_stmt_bind_param($stmt_check, "s", $transaction_id);
mysqli_stmt_execute($stmt_check);
$result = mysqli_stmt_get_result($stmt_check);
$row = mysqli_fetch_assoc($result);
if ($row['count'] > 0) {
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode([
        "status" => "success",
        "message" => "Transaction already processed",
        "transaction_id" => $transaction_id,
        "amount" => $amount,
        "currency" => $currency
    ]);
    mysqli_close($connect);
    exit;
}

mysqli_begin_transaction($connect);

try {
    $sql_update = "UPDATE users SET balance = balance + ? WHERE user_id = ?";
    $stmt1 = mysqli_prepare($connect, $sql_update);
    mysqli_stmt_bind_param($stmt1, "ii", $amount, $user_id);
    mysqli_stmt_execute($stmt1);

    if (mysqli_stmt_affected_rows($stmt1) < 1) {
        throw new Exception("User topilmadi yoki balans yangilanmadi");
    }

    $sql_insert = "INSERT INTO transactions (user_id, app, amount, status, transaction_id) 
                   VALUES (?, ?, ?, ?, ?)";
    $stmt2 = mysqli_prepare($connect, $sql_insert);
    mysqli_stmt_bind_param($stmt2, "isiss", $user_id, $currency, $amount, $status, $transaction_id);
    mysqli_stmt_execute($stmt2);

    mysqli_commit($connect);

    $logMessage = sprintf(
        "Balance update: user_id=%d, amount=%d, currency=%s, transaction_id=%s, timestamp=%d\n",
        $user_id,
        $amount,
        $currency,
        $transaction_id,
        $timestamp
    );
    file_put_contents('webhook.log', $logMessage, FILE_APPEND);

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode([
        "status" => "success",
        "message" => "Balans yangilandi va transaction yozildi",
        "transaction_id" => $transaction_id,
        "amount" => $amount,
        "currency" => $currency
    ]);

    $text =  "Balansingizga " . $amount . " UZS qabul qilindi ✅";
    file_get_contents("https://api.telegram.org/bot8368274781:AAGyyYdFzjGWDe8m6QdXC3M4mWyeyqzOk9o/sendMessage?chat_id=" . $user_id . "&parse_mode=HTML&text=" . urlencode($text));

} catch (Exception $e) {
    mysqli_rollback($connect);
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}

mysqli_close($connect);
?>