<?php
/*

Powered by parablan
Hector Alejandro Parada Blanco
Password Generator

*/

$user_agent = $_SERVER['HTTP_USER_AGENT'];
$year = date('Y');
$fecha = date('d/m/Y');
echo ("
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Document</title>
    ");
if (preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|Opera Mini|IEMobile/i', $user_agent)) {
    echo "<link rel='stylesheet' type='text/css' href='css/estilo_movil.css'/>";
} else {
    echo "<link rel='stylesheet' type='text/css' href='css/estilo.css'/>";
}
echo ("
</head>
<body style='width: 70%; margin: auto; background-color: #e2e2e2ff;'>
    <br>
    <br>
    <br>
    <div id='div_principal' name='div_principal'>
        <div id='titulo' name='titulo' style='text-align: center;'>
            <h2>SECRET KEY</h2>
        </div>
        <br>
        <br>
        <br>
        <center>
            <img src='qr parablan secretkey.png' alt='key' width='250' height='250'>
            <br>
            <br>
            <button id='btn_generar' name='btn_generar' onclick='window.location.href=\"certificado.php\"'>Download</button>
        <br>
        <br>
        <br>
        <p style='text-align: justify; padding: 0 4%;'>Password generated under digital security standards, with high entropy and complexity. Its structure complies with <b>NIST</b> and <b>OWASP</b> guidelines to prevent unauthorized access. The system employs a <b>zero-knowledge</b> approach, ensuring that generated keys are never stored in any database or persistent storage.</p>
        <br>
        <br>
            <small>© $year - parablan</small>
        </center>
        <br>
    </div>
    <br>
    <br>
</body>
</html>
");
?>