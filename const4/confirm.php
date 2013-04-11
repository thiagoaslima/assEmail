<?php
$stringFields = ['name', 'email', 'job'];
$arrayFields =  ['typeTel', 'inputTel', 'ramalTel'];
$name = $email = $job = '';
$tel = []; 
$cel = [];
$fax = [];
$send = true;

function validate($field) {
    global $name, $email, $job, $send;

    switch ($field) {
        case 'name':
        case 'job':
            $$field = filter_var($_POST[$field], FILTER_SANITIZE_STRING);
            if ($$field === '') {
                $$field = '<span style="color:red">Erro no '.$field.'</span>';
                $send = false;
            }
            break;

        case 'email':
            $value = filter_var($_POST[$field], FILTER_SANITIZE_EMAIL);
            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $email = $value;
            } else {
                $email = false;
            }
        
        default:
            # code...
            break;
    }
}

function checkType($value) {
    $v = filter_var($value, FILTER_SANITIZE_STRING);
    return ($v === 'cel' or $v === 'tel' or $v === 'fax');
}

function checkTel($value) {
    $v = filter_var($value, FILTER_SANITIZE_NUMBER_INT);

    // minor length possible 1234 5678 > 8dig - no DDI or DDD
    // max length: 55 11 12345 6789 > 13dig - DDI, DDD and 9d cell phone
    // other possibilities:
    // 9dig > 9d cell phone (SP already have it)
    // 10dig > DDD + 8dig
    // 11dig > DDD + 9dig
    $strV = strlen($v);
    return ($strV > 7 and $strV < 14);
}

function formatTel($value, $ramal) {
    $value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    $len = strlen($value);
    $ramal = trim(str_replace('ramal ', '', $ramal));
    $tel = '';

    switch($len) {
        case '8': // 1234 5678
            $tel = '+55 (15) ' . substr($value, 0, 4) . '-' . substr($value, 4);
            if ($ramal) {
                $tel .= " ramal: " . $ramal;
            }
            break;

        case '9': // 12345 6789
            $tel = '+55 (15) ' . substr($value, 0, 5) . '-' . substr($value, 5);
            if ($ramal) {
                $tel .= " ramal: " . $ramal;
            }
            break;

        case '10': // 15 1234 5678
            $tel = '+55 (' . substr($value,0,2). ') ' . substr($value, 2, 4) . '-' . substr($value, 6);
            if ($ramal) {
                $tel .= " ramal: " . $ramal;
            }
            break;

        case '11': // 15 12345 6789
            $tel = '+55 (' . substr($value,0,2). ') ' . substr($value, 2, 5) . '-' . substr($value, 7);
            if ($ramal) {
                $tel .= " ramal: " . $ramal;
            }
            break;

        case '12': // +55 15 1234 5678
            $tel = '+ ' . substr($value, 0, 2) . ' (' . substr($value, 2, 4). ') ' . substr($value, 4, 4) . '-' . substr($value, 8);
            if ($ramal) {
                $tel .= " ramal: " . $ramal;
            }
            break;

        case '13': // +55 15 12345 6789 
            $tel = '+ ' . substr($value, 0, 2) . ' (' . substr($value, 2, 4). ') ' . substr($value, 4, 5) . '-' . substr($value, 9);
            if ($ramal) {
                $tel .= " ramal: " . $ramal;
            }
            break;
    }

    return $tel;
}

function renderPhones() {
    global $tel, $cel, $fax;

    $types = ['tel', 'cel', 'fax'];
    $dom = '';

    foreach ($types as $type) {
        if(count($$type)>0) {
            $numbers = $$type;
            $len = count($numbers);
            $dom .= '<span style="font-family:Tahoma, Geneva, sans-serif; font-size:11px; line-height:16px; font-weight:normal; color:#404040">' . $type . '&nbsp;</span><span style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; line-height:16px; font-weight:normal; color:#006991">' . $numbers[0] . '</span><br>';
            for($i = 1; $i < $len; $i++) {
                $dom .= '<span style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; line-height:16px; font-weight:normal; color:#006991">&nbsp;&nbsp;&nbsp;&nbsp;' . $numbers[$i] . '</span><br>';
            }
            
        }
    }

    echo $dom;
}


if ( isset($_POST['submit'])) {
    foreach ($stringFields as $field) {
        if (isset($_POST[$field]) and $_POST[$field] !== '') {
            validate($field);
        }
    }

    $i = 1;
    while ( isset($_POST['typeTel'.$i]) and isset($_POST['inputTel'.$i]) ) {

        if ( checkType($_POST['typeTel'.$i]) and checkTel($_POST['inputTel'.$i]) ){
            $type = filter_var($_POST['typeTel'.$i], FILTER_SANITIZE_STRING);
            $ramal = filter_var($_POST['ramalTel'.$i], FILTER_SANITIZE_STRING);
            $number = formatTel($_POST['inputTel'.$i], $ramal);

            array_push($$type, $number);
        }
        $i++;
    }
}

?><!doctype html>
<html lang="pt-br">
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
    <p class="span8"><br><br>Confira se os dados de sua assinatura estão corretos.<br> Copie o código na área cinza ou aperte enviar para receber o código em seu email.</p>
    </div>
</header>

<div class="container row ">
    <div class="span6 offset2">
        <table bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse">
            <tr>
                <td colspan="3" height="10"><img border="0" style="border:none; display:block" src="http://farm9.staticflickr.com/8528/8611501744_2ee9356fd4_t.jpg" width="10" height="10" alt=""></td> 
            </tr>

            <tr>
                <td width="88" height="58"  valign="bottom"><img align="right" border="0" style="border:none; display:block" src="http://farm9.staticflickr.com/8243/8610394783_68933da501_t.jpg" width="88" height="58" alt="Tecsis"></td>
                <td rowspan="4" valign="top" width="15"><img border="0" style="border:none; display:block" src="http://farm9.staticflickr.com/8112/8610394745_52cc3f5b1f_t.jpg" width="15" height="70" alt=""></td>
                <td valign="bottom" style="font-family:Tahoma, Geneva, sans-serif"><span class="fieldNome" style="font-family:Tahoma, Geneva, sans-serif; font-size:14px; line-height:20px; font-weight:bold; color:#006991"><?php echo $name ?></span><br><span  class="fieldJob" style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; line-height:16px; font-weight:normal; color:#404040"><?php echo $job ?></span><br></td>
            </tr>

            <tr>
                <td width="88"><img border="0" style="border:none; display:block" align="right" src="http://farm9.staticflickr.com/8528/8611501744_2ee9356fd4_t.jpg" width="10" height="10" alt=""></td>
                <td><img border="0" style="border:none; display:block" src="http://farm9.staticflickr.com/8528/8611501744_2ee9356fd4_t.jpg" width="10" height="10" alt=""></td>
            </tr>

            <tr>
                <td width="88" valign="bottom" align="right"><a href="http://www.tecsis.com.br" rel="external" target="_blank" style="font-family:Tahoma, Geneva, sans-serif; font-size:11px; line-height:16px; font-weight:normal; color:#006991; text-decoration:none"><span style="color:#006991">www.tecsis.com.br</span></a></td>
                <td valign="bottom" align="left"><?php renderPhones(); ?></td>
            </tr>

            <tr>
                <td width="88" height="10"><img border="0" style="border:none; display:block" src="http://farm9.staticflickr.com/8528/8611501744_2ee9356fd4_t.jpg" width="10" height="10" alt=""></td>
                <td width="295" height="10"><img border="0" style="border:none; display:block" src="http://farm9.staticflickr.com/8528/8611501744_2ee9356fd4_t.jpg" width="10" height="10" alt=""></td>
            </tr>

            <tr>
                <td valign="top" colspan="3"><span style="font-family:Tahoma, Geneva, sans-serif; font-size:10px; line-height:16px; font-weight:bold; color:#9A9A9A">Antes de imprimir, verifique se é mesmo indispensável. O planeta agradece.</span></td>
            </tr>
        </table>
    </div>


    <form class="form-horizontal span12" method="post" action="send.php">

        <div class="control-group span6">
            <label class="control-label" for="email">Email</label>
            <div class="controls">
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" placeholder="email">
            </div> 
        </div>

        <input class="btn btn-primary" type="submit" name="submit" value="Enviar código para meu email" />

        <textarea name="code" class="muted span12" readonly="readonly" rows="6" style="font-size:82%; line-height:120%"><table bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse"><tr><td colspan="3" height="10"><img border="0" style="border:none;display:block" src="http://farm9.staticflickr.com/8528/8611501744_2ee9356fd4_t.jpg" width="10" height="10" alt=""></td></tr><tr><td width="88" height="58" valign="bottom"><img align="right" border="0" style="border:none;display:block" src="http://farm9.staticflickr.com/8243/8610394783_68933da501_t.jpg" width="88" height="58" alt="Tecsis"></td><td rowspan="4" valign="top" width="15"><img border="0" style="border:none;display:block" src="http://farm9.staticflickr.com/8112/8610394745_52cc3f5b1f_t.jpg" width="15" height="70" alt=""></td><td valign="bottom" style="font-family:Tahoma, Geneva, sans-serif"><span class="fieldNome" style="font-family:Tahoma, Geneva, sans-serif;font-size:14px;line-height:20px;font-weight:bold;color:#006991"><?php echo $name ?></span><br><span class="fieldJob" style="font-family:Tahoma, Geneva, sans-serif;font-size:12px;line-height:16px;font-weight:normal;color:#404040"><?php echo $job ?></span><br></td></tr><tr><td width="88"><img border="0" style="border:none;display:block" align="right" src="http://farm9.staticflickr.com/8528/8611501744_2ee9356fd4_t.jpg" width="10" height="10" alt=""></td><td><img border="0" style="border:none;display:block" src="http://farm9.staticflickr.com/8528/8611501744_2ee9356fd4_t.jpg" width="10" height="10" alt=""></td></tr><tr><td width="88" valign="bottom" align="right"><a href="http://www.tecsis.com.br" rel="external" target="_blank" style="font-family:Tahoma, Geneva, sans-serif;font-size:11px;line-height:16px;font-weight:normal;color:#006991;text-decoration:none"><span style="color:#006991">www.tecsis.com.br</span></a></td><td valign="bottom" align="left"><?php renderPhones();?></td></tr><tr><td width="88" height="10"><img border="0" style="border:none;display:block" src="http://farm9.staticflickr.com/8528/8611501744_2ee9356fd4_t.jpg" width="10" height="10" alt=""></td><td width="295" height="10"><img border="0" style="border:none;display:block" src="http://farm9.staticflickr.com/8528/8611501744_2ee9356fd4_t.jpg" width="10" height="10" alt=""></td></tr><tr><td valign="top" colspan="3"><span style="font-family:Tahoma, Geneva, sans-serif;font-size:10px;line-height:16px;font-weight:bold;color:#9A9A9A">Antes de imprimir, verifique se é mesmo indispensável. O planeta agradece.</span></td></tr></table></textarea>


    </form>

</div>
</body>
</html>