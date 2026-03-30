<?php
/*

Powered by parablan
Hector Alejandro Parada Blanco
Password Generator

*/

require_once __DIR__ . '/vendor/autoload.php';

use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

// Obtener el user agent
$user_agent = $_SERVER['HTTP_USER_AGENT'];

// Determinar qué hoja de estilo usar
if (preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|Opera Mini|IEMobile/i', $user_agent)) {
    $css_file = 'css/estilo_movil.css';
} else {
    $css_file = 'css/estilo.css';
}

// Cargar estilos CSS
$stylesheet = file_get_contents($css_file);

function generar_contraseña($longitud = 12, $usar_simbolos = True, $usar_numeros = True)
{
    $expresion_regular = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/';
    $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if ($usar_simbolos) {
        $caracteres .= '*.-+#@*.-+#@*.-+#@*.-+#@';
    }
    if ($usar_numeros) {
        $caracteres .= '0123456789';
    }

    $contraseña = '';
    $max = strlen($caracteres) - 1;
    for ($i = 0; $i < $longitud; $i++) {
        $contraseña .= $caracteres[random_int(0, $max)];
    }

    if (preg_match($expresion_regular, $contraseña)) {
        return $contraseña;
    } else {
        return generar_contraseña($longitud, $usar_simbolos, $usar_numeros);
    }
}

$clave = generar_contraseña(12, True, True);
$year = date('Y');
$fecha = date('d/m/Y');
$fecha_pdf = date('dmY');
// Contenido HTML
$html = "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Generator</title>
</head>
<body>
    <br>
    <div id='titulo' name='titulo' style='text-align: center;'>
        <h2>SECRET KEY</h2>
    </div>
    <br>
    <div id='sub_titulo' name='sub_titulo' style='text-align: center;'>
        <small>Generated on $fecha</small>
    </div>
    <div id='clave' name='clave'>
        <h3>" . $clave . "</h3>
        <div style='text-align: right; padding-right: 20px; line-height: 0;'>
            <img src='icon-key.png' style='width: 40px;'>
        </div>
    </div>
    <br>
    <div id='hash' name='hash'>
        <p><b>MD5:</b> " . md5($clave) . "</p>
        <p><b>SHA256:</b> " . hash('sha256', $clave) . "</p>
    </div>
    <br>
    <p style='text-align: justify; padding: 0 4%;'>Password generated under digital security standards, with high entropy and complexity. Its structure complies with <b>NIST</b> and <b>OWASP</b> guidelines to prevent unauthorized access. The system employs a <b>zero-knowledge</b> approach, ensuring that generated keys are never stored in any database or persistent storage.</p>
    <br>
    <div id='footer' name='footer' style='text-align: center;'>
        <small>© $year - parablan</small>
    </div>
    <br>
</body>
</html>
";

// Configuración de fuentes personalizadas
$defaultConfig = (new ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new Mpdf([
    'fontDir' => array_merge($fontDirs, [
        __DIR__ . '/Fuente/Nunito/static',
    ]),
    'fontdata' => $fontData + [
        'nunito' => [
            'R' => 'Nunito-Regular.ttf',
            'B' => 'Nunito-Bold.ttf',
        ]
    ],
    'default_font' => 'nunito'
]);

$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($html);
$mpdf->Output('secret_key_' . $fecha_pdf . '.pdf', 'D');
?>