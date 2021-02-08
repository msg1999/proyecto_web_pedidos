<html>
    <head>
        <meta charset="UTF-8">
        <title> Alta Pedidos </title>
    </head>
    <body>
<?php
    include "../api/apiRedsys.php";  
    include "funciones.php";
    #comprar();

    $miObj = new RedsysAPI;
 
    $amount = $_GET['total'];    
    $url_tpv = 'https://sis-t.redsys.es:25443/sis/realizarPago';
    $version = "HMAC_SHA256_V1"; 
    $clave = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'; //poner la clave SHA-256 facilitada por el banco
    $name = 'TU NOMBRE DE COMERCIO';
    $code = '999008881';
    $terminal = '1';
    $order = date('ymdHis');
    $amount = $amount * 100;
    $currency = '978';
    $consumerlng = '001';
    $transactionType = '0';
    $urlMerchant = 'http://your-domain.com/';
    $urlweb_ok = 'http://your-domain.com/tpv_ok.php';
    $urlweb_ko = 'http://your-domain.com/tpv_ko.php';
 
    $miObj->setParameter("DS_MERCHANT_AMOUNT", $amount);
    $miObj->setParameter("DS_MERCHANT_CURRENCY", $currency);
    $miObj->setParameter("DS_MERCHANT_ORDER", $order);
    $miObj->setParameter("DS_MERCHANT_MERCHANTCODE", $code);
    $miObj->setParameter("DS_MERCHANT_TERMINAL", $terminal);
    $miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE", $transactionType);
    $miObj->setParameter("DS_MERCHANT_MERCHANTURL", $urlMerchant);
    $miObj->setParameter("DS_MERCHANT_URLOK", $urlweb_ok);      
    $miObj->setParameter("DS_MERCHANT_URLKO", $urlweb_ko);
    #$miObj->setParameter("DS_MERCHANT_MERCHANTNAME", $name);
    $miObj->setParameter("DS_MERCHANT_CONSUMERLANGUAGE", $consumerlng);    
 
    $params = $miObj->createMerchantParameters();
    $signature = $miObj->createMerchantSignature($clave);
    ?>
    <!---->
    <form class="form-amount" action="form2.php" method="post">
        <div class="form-group">
            <input type="hidden" id="amount" name="amount" class="form-control" placeholder="Por ejemplo: 50.00">
        </div>
        <input class="btn btn-lg btn-primary btn-block" name="submitPayment" type="hidden" value="Pagar">
    </form>
    <!---->
    <form id="realizarPago" action="<?php echo $url_tpv; ?>" method="post">
        <input type='hidden' name='Ds_SignatureVersion' value='<?php echo $version; ?>'> 
        <input type='hidden' name='Ds_MerchantParameters' value='<?php echo $params; ?>'> 
        <input type='hidden' name='Ds_Signature' value='<?php echo $signature; ?>'> 
        <input type="submit" value="PAGAR">
    </form>

</body>
<html>