<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Pagar</title>

    <script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
  </head>

  <body>
    
  <h3>Credit Card Test</h3>
  <p><img src="http://img.mlstatic.com/org-img/MP3/API/logos/visa.gif" align="center" style="margin:8px;" > 4235647728025682 </p>
  <p><img src="http://img.mlstatic.com/org-img/MP3/API/logos/master.gif" align="center" style="margin:8px;"> 5031433215406351 </p>
  <p><img src="http://img.mlstatic.com/org-img/MP3/API/logos/amex.gif" align="center" style="margin:8px;">375365153556885 </p> 
    
    
    <form action="post_1stpayment.php" method="post" id="pay" name="pay" >
    <fieldset>
        <ul>
            <li>
                <label for="email">Email</label>
                <input id="email" name="email" value="test_user_28757719@testuser.com" type="email" placeholder="your email"/>
            </li>
            <li>
                <label for="cardNumber">Credit card number:</label>
                <input type="text" id="cardNumber" data-checkout="cardNumber" placeholder="4509 9535 6623 3704" />
                <span id="bandeira"></span>
            </li>
            <li>
                <label for="securityCode">Security code:</label>
                <input type="text" id="securityCode" data-checkout="securityCode" placeholder="123" value="123" />
            </li>
            <li>
                <label for="cardExpirationMonth">Expiration month:</label>
                <input type="text" id="cardExpirationMonth" data-checkout="cardExpirationMonth" placeholder="12" value="12" />
            </li>
            <li>
                <label for="cardExpirationYear">Expiration year:</label>
                <input type="text" id="cardExpirationYear" data-checkout="cardExpirationYear" placeholder="2015" value="2018" />
            </li>
            <li>
                <label for="cardholderName">Card holder name:</label>
                <input type="text" id="cardholderName" data-checkout="cardholderName" placeholder="APRO" value="APRO" />
            </li>
            <input data-checkout="docType" type="hidden" value="CPF"/>
            <input data-checkout="siteId" type="hidden" value="MLB"/>
            <li>
                <label for="docNumber">Document number:</label>
                <input type="text" id="docNumber" data-checkout="docNumber" placeholder="12345678" value="19119119100" />
            </li>

        </ul>
        <input data-checkout="siteId" type="hidden" value="MLB"/>
        <input type="hidden" name="amount" id="amount" value=""/>
        <input type="submit" value="Pay!" />
    </fieldset>
</form>
   
  <br>
  <br>

  <script type="text/javascript">
    Mercadopago.setPublishableKey("Set your public key");
    
    $(document).ready(function() {
    $("#amount").val(Math.floor(Math.random() * 600) + 10)
    });
    
    function addEvent(el, eventName, handler){
    if (el.addEventListener) {
           el.addEventListener(eventName, handler);
    } else {
        el.attachEvent('on' + eventName, function(){
          handler.call(el);
        });
    }
  };

    function getBin() {
        var ccNumber = document.querySelector('input[data-checkout="cardNumber"]');
        return ccNumber.value.replace(/[ .-]/g, '').slice(0, 6);
    };
    
    function guessingPaymentMethod(event) {
        var bin = getBin();
    
        if (event.type == "keyup") {
            if (bin.length >= 6) {
                Mercadopago.getPaymentMethod({
                    "bin": bin
                }, setPaymentMethodInfo);
            }
        } else {
            setTimeout(function() {
                if (bin.length >= 6) {
                    Mercadopago.getPaymentMethod({
                        "bin": bin
                    }, setPaymentMethodInfo);
                }
            }, 100);
        }
    };
    
    function setPaymentMethodInfo(status, response) {
        if (status == 200) {
            // do somethings ex: show logo of the payment method
            var form = document.querySelector('#pay');
    
            if (document.querySelector("input[name=paymentMethodId]") == null) {
                var paymentMethod = document.createElement('input');
                paymentMethod.setAttribute('name', "paymentMethodId");
                paymentMethod.setAttribute('type', "hidden");
                paymentMethod.setAttribute('value', response[0].id);
                form.appendChild(paymentMethod);

            } else {
                document.querySelector("input[name=paymentMethodId]").value = response[0].id;
            }
            
                var img = "<img src='" + response[0].thumbnail + "' align='center' style='margin-left:10px;' ' >";
                $("#bandeira").empty();
                $("#bandeira").append(img);
               
                
        }
    };
    
    addEvent(document.querySelector('input[data-checkout="cardNumber"]'), 'keyup', guessingPaymentMethod);
    addEvent(document.querySelector('input[data-checkout="cardNumber"]'), 'change', guessingPaymentMethod);
    
    doSubmit = false;
    addEvent(document.querySelector('#pay'),'submit',doPay);
    
    function doPay(event){
    event.preventDefault();
      if(!doSubmit){
          var $form = document.querySelector('#pay');
          
          Mercadopago.createToken($form, sdkResponseHandler); // The function "sdkResponseHandler" is defined below
  
          return false;
      }
    };
    
    function sdkResponseHandler(status, response) {
    if (status != 200 && status != 201) {
        alert("verify filled data");
    }else{
       
        var form = document.querySelector('#pay');

        var card = document.createElement('input');
        card.setAttribute('name',"token");
        card.setAttribute('type',"hidden");
        card.setAttribute('value',response.id);
        form.appendChild(card);
        doSubmit=true;
        form.submit();
    }
  };

 

 
    
    
  </script>
  </body>
</html>