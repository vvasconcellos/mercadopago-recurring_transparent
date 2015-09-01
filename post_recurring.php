<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once "lib/mercadopago.php";

$mp = new MP("Set your Access Token Long Live");

$public_key = "Set your public key";

$card_id = $_GET['card_id'];
$id_user = $_GET['payer_id'];
$bandeira = $_GET['bandeira'];

$card_preference=array("card_id"=>$card_id);

$card_token = $mp->post("/v1/card_tokens?public_key=$public_key", $card_preference);

//print_r($card_token);

$payment_preference = array(
    "token"=> $card_token["response"]["id"],
    "installments"=> 1,
    "transaction_amount"=> 10.00,
    "external_reference"=> "order code 1234xxxx",
    "binary_mode" => true,
    "description"=> "Teste Recurring v1",
    "payment_method_id"=> $bandeira,
    "statement_descriptor" => "*MEUTESTE",
    "binary_mode" => true , 
    "payer"=> array(
        "id"=> $id_user
    ),
    "additional_info"=>  array(
        "items"=> array(array(
            
                "id"=> "1234",
                "title"=> "Aqui coloca os itens do carrinho",
                "description"=> "Produto Teste novo",
                "picture_url"=> "https://google.com.br/images?image.jpg",
                "category_id"=> "others",
                "quantity"=> 1,
                "unit_price"=> 10.00
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

echo "<h3> ==== Recurring Payment ===== </h3>";
echo "Payment Status:" . $response_payment["response"]["status"] . " - " . $response_payment["response"]["status_detail"];


echo "<pre>";
print_r ($response_payment);
echo "</pre>";



?>

