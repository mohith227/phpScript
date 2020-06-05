<?php
try {
    $start = microtime(true);
    /**
     * Magento BaseUrl
     */
    $baseUrl = "http://127.0.0.1/magento/";
    /**
     * Magento AdminUserName
     */
    $userName = "admin";
    /**
     * Magento AdminPassword
     */
    $passWord = "admin123";
    /**
     * Number Of Customers Need Tobe Created
     */
    $numberOfCustomers = 10;
    $userData = array("username" => $userName, "password" => $passWord);
    $adminTokenUrl = $baseUrl . "index.php/rest/V1/integration/admin/token";
    $customerUrl = $baseUrl . "index.php/rest/V1/customers";
    $ch = curl_init($adminTokenUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Lenght: " . strlen(json_encode($userData))));
    $token = curl_exec($ch);
    $multiCurl = [];
    $result = [];
    $mh = curl_multi_init();
    for ($i = 1; $i <= $numberOfCustomers; $i++) {
        $email = "user" . $i . "@example.com";
        $customerData = [
            'customer' => [
                "email" => $email,
                "firstname" => "John",
                "lastname" => "Doe",
                "storeId" => 1,
                "websiteId" => 1,
                "groupId" => 1,
                "gender" => 1,
                "dob" => "06/04/2000  2:31:22 PM",
                "addresses" => [
                    [
                        "firstname" => "John",
                        "lastname" => "Doe",
                        "countryId" => "KW",
                        "street" => [
                            "Andalus",
                            "Jeddah"
                        ],
                        "company" => "test",
                        "telephone" => "96596650342",
                        "fax" => "01010101101010101",
                        "postcode" => "12345",
                        "city" => "Jeddah",
                        "defaultBilling" => true
                    ],
                    [
                        "firstname" => "John",
                        "lastname" => "Doe",
                        "countryId" => "KW",
                        "street" => [
                            "Andalus",
                            "Jeddah"
                        ],
                        "company" => "test",
                        "telephone" => "96596650342",
                        "fax" => "00000000000000",
                        "postcode" => "12345",
                        "city" => "Jeddah",
                        "defaultShipping" => true
                    ]
                ]
            ],
            "password" => "test1234"
        ];
        $multiCurl[$i] = curl_init($customerUrl);
        curl_setopt($multiCurl[$i], CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($multiCurl[$i], CURLOPT_RETURNTRANSFER, true);
        curl_setopt($multiCurl[$i], CURLOPT_POSTFIELDS, json_encode($customerData));
        curl_setopt($multiCurl[$i], CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($token)));
        curl_multi_add_handle($mh, $multiCurl[$i]);
    }
    $index = null;
    do {
        curl_multi_exec($mh, $index);
    } while ($index > 0);
    // get content and remove handles
    foreach ($multiCurl as $k => $mch) {
        $result[$k] = curl_multi_getcontent($mch);
        curl_multi_remove_handle($mh, $mch);
    }
    // close
    curl_multi_close($mh);
    $end = microtime(true);
    $time = number_format(($end - $start), 2);
    echo 'total execution ', $time, ' seconds';
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}
?>
