<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once "lib/mercadopago.php";

$mp = new MP("Set your access token long live");

$email_buyer = "test_user_28757719@testuser.com";

$payment_preference = array(
    "token"=> $_REQUEST['token'],
    "installments"=> 1,
    "transaction_amount"=> round((float)$_REQUEST['amount'],2),
    "external_reference"=> "order code 1234xxxx",
    "binary_mode" => true,
    "description"=> "Teste payments v1",
    "payment_method_id"=> $_REQUEST['paymentMethodId'],
    "statement_descriptor" => "*MEUTESTE",
    "binary_mode" => true , 
    "payer"=> array(
        "email"=> $email_buyer
    ),
    "additional_info"=>  array(
        "items"=> array(array(
            
                "id"=> "1234",
                "title"=> "Aqui coloca os itens do carrinho",
                "description"=> "Produto Teste novo",
                "picture_url"=> "https://google.com.br/images?image.jpg",
                "category_id"=> "others",
                "quantity"=> 1,
                "unit_price"=> round((float)$_REQUEST['amount'],2)
            )
        ),
        "payer"=>  array(
            "first_name"=> "João",
            "last_name"=> "Silva",
            "registration_date"=> "2014-06-28T16:53:03.176-04:00",
            "phone"=>  array(
                "area_code"=> "5511",
                "number"=> "3222-1000"
            ),
            "address"=>  array(
                "zip_code"=> "05303-090",
                "street_name"=> "Av. Queiroz Filho",
                "street_number"=> "213"
            )
        ),
        "shipments"=>  array(
            "receiver_address"=>  array(
                "zip_code"=> "05303-090",
                "street_name"=> "Av. Queiroz Filho",
                "street_number"=> "213",
                "floor"=> "0",
                "apartment"=> "0"
            )
        )
    )
  );

  
$response_payment = $mp->post("/v1/payments/", $payment_preference);

echo "<h3> ==== 1st Payment ===== </h3>";
echo "Payment Status:" . $response_payment["response"]["status"] . " - " . $response_payment["response"]["status_detail"];

// Check if user exist

echo "<h3> ==== Check if user exist ===== </h3>";

$check_user_exists= $mp->get("/v1/customers/search?email=$email_buyer");

$id_user=$check_user_exists["response"]["results"][0]["id"];

if (!isset($id_user)){
    
    echo "<h3> ==== Create user ===== </h3>";
    
    $user_preference = array(
        "email" => $email_buyer,
        "first_name"=>"João",
        "last_name"=> "Silva"
    );
    
    $create_users= $mp->post("/v1/customers/",$user_preference);
    
    print_r ($create_users);
    
    $id_user=$create_users["response"]["id"];

}

echo "<h3> ==== Create card ===== (Save in your datamodel) </h3>";

$card_preference = array("token"=>$_REQUEST['token']);
$create_card = $mp->post("/v1/customers/$id_user/cards",$card_preference);

echo "<pre>";
print_r ($create_card);
echo "</pre>";

$card_id = $create_card["response"]["id"];
$bandeira = $create_card["response"]["payment_method"]["id"];

echo "<a href='post_recurring.php?card_id=$card_id&payer_id=$id_user&bandeira=$bandeira' > Create recurring payment  </a>";

?>

