<?php

$server = "localhost";
$user = "root";
$pass = "";
$db = "rocketbot";

try {
    $conexion = new PDO("mysql:host=$server;dbname=$db", $user, $pass);
} catch(PDOExeption $err){
    die($err);
}

$sql = $conexion -> prepare("SELECT * FROM rocket");
$sql -> execute();
$noticias = $sql -> fetchAll(PDO::FETCH_ASSOC);

$dom = new DOMDocument();
$dom->encoding = 'utf-8';
$dom->xmlVersion = '1.0';
$dom->formatOutput = true;
$xml_file_name = 'titularesLaRazon.xml';
$root = $dom->createElement('Noticias');
foreach($noticias as $noticia){
    $noticia_node = $dom->createElement('Titular');
    $attr_noticia_id = new DOMAttr('titular_id', $noticia['id']);
    $noticia_node->setAttributeNode($attr_noticia_id);
    $child_node_title = $dom->createElement('Titulo', utf8_encode($noticia['titular']));
    $noticia_node->appendChild($child_node_title);
    $child_node_link = $dom->createElement('Enlace', utf8_encode($noticia['enlace']));
    $noticia_node->appendChild($child_node_link);
    $child_node_year = $dom->createElement('Creacion', $noticia['creacion']);
    $noticia_node->appendChild($child_node_year);
    $root->appendChild($noticia_node);
    $dom->appendChild($root);
}
$dom->save($xml_file_name);

?>