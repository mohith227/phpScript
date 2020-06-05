<?php
try {
    $start = microtime(true);
    $userData = array("username" => "admin", "password" => "admin123");
    $ch = curl_init("http://127.0.0.1/magento/index.php/rest/V1/integration/admin/token");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Lenght: " . strlen(json_encode($userData))));
    $token = curl_exec($ch);
    for ($i = 1; $i <= 100; $i++) {
        $email = "user".$i."@example.com";
        $customerData = [
            'customer' => [
                "email" => $email,
                "firstname" => "John",
                "lastname" => "Doe",
                "storeId" => 1,
                "websiteId" => 1
            ],
            "password" => "Demo1234"
        ];
        $ch = curl_init("http://127.0.0.1/magento/index.php/rest/V1/customers");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($customerData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($token)));
        $result = curl_exec($ch);
        $result = json_decode($result, 1);
    }
    $end = microtime(true);
    $time = number_format(($end - $start), 2);
    echo 'total execution ', $time, ' seconds';
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}
?>
d
