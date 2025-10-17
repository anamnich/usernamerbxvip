<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if(!isset($data['username']) || empty(trim($data['username']))){
    echo json_encode(['success'=>false, 'error'=>'Username kosong']);
    exit;
}

$username = trim($data['username']);
$action = isset($data['action']) ? $data['action'] : 'add';
$file = 'whitelist.json';

// Baca file JSON
if(!file_exists($file)) file_put_contents($file, json_encode([]));
$whitelist = json_decode(file_get_contents($file), true);
if(!is_array($whitelist)) $whitelist = [];

if($action === 'add'){
    if(in_array($username, $whitelist)){
        echo json_encode(['success'=>false, 'error'=>'Username sudah ada']);
        exit;
    }
    $whitelist[] = $username;
}elseif($action === 'delete'){
    if(!in_array($username, $whitelist)){
        echo json_encode(['success'=>false, 'error'=>'Username tidak ditemukan']);
        exit;
    }
    $whitelist = array_values(array_filter($whitelist, fn($u)=>$u!==$username));
}else{
    echo json_encode(['success'=>false, 'error'=>'Action tidak dikenal']);
    exit;
}

// Simpan perubahan
file_put_contents($file, json_encode($whitelist, JSON_PRETTY_PRINT));
echo json_encode(['success'=>true, 'username'=>$username]);
?>
