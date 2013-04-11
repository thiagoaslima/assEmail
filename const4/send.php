<?php

if(isset($_POST["submit"])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $msg = htmlspecialchars($_POST['code']);
    // To send HTML mail, the Content-type header must be set
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

    // Additional headers
    $headers .= 'From: Thiago Lima<thiagoaslima@gmail.com>' . "\r\n";
    $headers .= 'Bcc: thiagoaslima@gmail.com' . "\r\n";
    mail($email, 'Tecsis | Assinatura de email', $msg, $headers);
}

?><html lang="pt-br">
<head>
    <meta charset='utf-8'>    
    <title>Tecsis | Confirmação de Assinatura de email</title>
    <link type="text/css" href="css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css" media="screen">
      header{ background-image: url(http://farm9.staticflickr.com/8396/8612542765_e6c62dfdb3_t.jpg); background-repeat: repeat; border-bottom: 1px solid rgb(230,230,230); padding: 20px 0; margin-bottom: 20px }
      span.close { margin-left: 10px; padding-top: 5px}
      select.control-label {width:100px !important; margin-left: 60px;}
      form {margin-top: 30px; border-top: 1px solid #D6D6D6; padding-top: 30px;}
      textarea[readonly] {cursor: default !important; background-color: #F0F0F0 !important;}
    </style>
</head>

<body>
<header>
    <div class="container row">
    <h1 class="span2"><img src="http://farm9.staticflickr.com/8103/8613639720_3793476a46_t.jpg" width="100" height="69" alt="Tecsis"></h1>
    <p class="span8"><br>Confira sua caixa de entrada e verifique se o email chegou. <br>Consulte o TI sobre como incluir a assinatura no seu programa de email.</p>
    </div>
</header>