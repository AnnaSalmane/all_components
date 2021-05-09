<?php

header('Content-type: application/json');

$to      = '4vitalik4@gmail.com';
$subject = 'the subject';
$message = 'hello';
$headers = 'From: 4vitalik4@gmail.com' . "\r\n" .
    'Reply-To: 4vitalik4@gmail.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);

include "DB.php";
$db = new DB('localhost', 'root', 'root', 'contact_managment');

$data = $db->fetchAll('tickets')->getAll();

if (is_array($data)) {
    foreach ($data as &$row) {
        if (array_key_exists('priority', $row)) {
            switch ($row['priority']) {
                case 1:
                    $row['priority'] = 'low';
                    break;
                case 2:
                    $row['priority'] = 'normal';
                    break;
                case 3:
                    $row['priority'] = 'high';
                    break;
            }
        }

        if (array_key_exists('date_time', $row)) {
            $datatime = new Datetime($row['date_time']);
            $row['date'] = $datatime->format('M d, Y');
            $row['time'] = $datatime->format('h:i A');
            unset($row['date_time']);
        }
    }
}

$menu = $db->fetchAll('side_menu')->getAll();
$sorted_menu = [];
foreach ($menu as $item) {
    $group = $item['link_group'];
    $order = $item['link_order'];
    unset($item['id'], $item['link_group'], $item['link_order']);
    $sorted_menu[$group][$order] = $item;
}

$headers = $db->fetchAll('headers')->getAll();
$sorted_headers = [];
foreach ($headers as $header) {
    $order = $header['header_order'];
    $sorted_headers[$order] = $header['header'];
}

$data = [
    'tickets' => array_values($data),
    'menu' => $sorted_menu,
    'headers' => $sorted_headers
];




echo json_encode($data, JSON_PRETTY_PRINT);
