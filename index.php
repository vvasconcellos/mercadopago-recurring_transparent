<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Pagar</title>

    <script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="https://secure.mlstatic.com/org-img/checkout/custom/1.0/checkout.js"></script>
  </head>

  <body>

  <h3>Cartão Teste</h3>
  <p><img src="http://img.mlstatic.com/org-img/MP3/API/logos/visa.gif" align="center" style="margin:10px;" > 4235647728025682 </p>
  <p><img src="http://img.mlstatic.com/org-img/MP3/API/logos/master.gif" align="center" style="margin:10px;"> 5031433215406351 </p>
  <p><img src="http://img.mlstatic.com/org-img/MP3/API/logos/amex.gif" align="center" style="margin:10px;">375365153556885 </p> 
    
     <form action="post_1stpayment.php" method="post" id="form-pagar-mp">
    <p>Número do cartão: <input data-checkout="cardNumber" type="text" /><span id="bandeira"></span></p>
    <p>Código de segurança: <input data-checkout="securityCode" type="text" value="123" /></p>
    <p>Mês de vencimento: <input data-checkout="cardExpirationMonth" type="text" value="05"  /></p>
    <p>Ano de vencimento: <input data-checkout="cardExpirationYear" type="text" value="2018" /></p>
    <p>Titular do cartão: <input data-checkout="cardholderName" type="text" value="APRO" /></p>
    <p>Número do documento: <input data-checkout="docNumber" type="text" value="19119119100" /></p>

    <input data-checkout="paymentMethod" type="hidden" name="paymentMethod" />
    <input data-checkout="docType" type="hidden" value="CPF"/>
    <input data-checkout="siteId" type="hidden" value="MLB"/>
    <input type="hidden" name="amount" id="amount" value=""/>

    <p><input type="submit" value="Concluir pagamento"></p>
</form>  
   
   
  <br>
  <br>
  
   <script type="text/javascript">
    Checkout.setPublishableKey("Set your public key");
    
    $(document).ready(function() {
    $("#amount").val(Math.floor(Math.random() * 600) + 10)
    });
    
     $("input[data-checkout='cardNumber']").bind("keyup blur",function(){
      var bin = $(this).val().replace(/ /g, '').replace(/-/g, '').replace(/\./g, '');
      if (bin.length >= 6){
        Checkout.getPaymentMethod(bin,setPaymentMethodInfo);

      }
    });

    //Estabeleça a informação do meio de pagamento obtido
    function setPaymentMethodInfo(status, result){
      $.each(result, function(p, r){
	 var img = "<img src='" + r.thumbnail + "' align='center' style='margin-left:10px;' ' >";
	 $("#bandeira").empty();
	 $("#bandeira").append(img);
	 $("input[data-checkout='paymentMethod']").attr("value", r.id)
	 Checkout.getInstallments(r.id ,parseFloat($("#amount").val()), setInstallmentInfo);
          return;
	   });
    };
    
    $("#form-pagar-mp").submit(function( event ) {
    var $form = $(this);
    console.log($form);
    Checkout.createToken($form, mpResponseHandler);
    event.preventDefault();
    return false;
    });
    
     // Mostre as parcelas disponíveis no div 'installmentsOption'
      function setInstallmentInfo(status, installments){
          var html_options = "";
	  for(i=0; installments && installments[i]!= undefined &&  i<installments.length; i++){
              html_options += "<option value='"+installments[i].installments+"'>"+installments[i].installments +" de "+installments[i].share_amount+" ("+installments[i].total_amount+")</option>";
          };
          $("#installmentsOption").html(html_options);
        };

    

var mpResponseHandler = function(status, response) {
  var $form = $('#form-pagar-mp');
  if (response.error) {
    console.log (response);
    alert("Ocorreu um erro:" + response.error);
  } else {
    var card_token_id = response.id;
    $form.append($('<input type="hidden" id="card_token_id" name="card_token_id"/>').val(card_token_id));
    $form.get(0).submit();
  }
};
    
    
  </script>
  </body>
</html>