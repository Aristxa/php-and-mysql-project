<?php
$serverName = "localhost";  // Ose emri i serverit nëse është ndryshe
$connectionOptions = array(
    "Database" => "HospitalDB",  // Emri i bazës së të dhënave
    "CharacterSet" => "UTF-8",   // Për mbështetje të karaktereve speciale
    "TrustServerCertificate" => true,  // Lejon lidhjen me server pa çertifikatë
    "LoginTimeout" => 30
);

// Lidhja me bazën e të dhënave duke përdorur Windows Authentication
$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die("❌ Lidhja me bazën dështoi: " . print_r(sqlsrv_errors(), true));
} else {
    echo "✅ Lidhja me bazën e të dhënave është e suksesshme!";
}
?>

