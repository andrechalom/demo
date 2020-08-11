<?php

// Script para converter arquivos XML em CSV. Note que esse script só vai funcionar se a estrutura do XML
// for MUITO SIMPLES! Não é possível transformar um tipo de dados hierárquico em uma tabela...
// Esse script permite ler dados mesmo que o XML não tenha o mesmo número de nós em cada entrada.

$filename = 'sample.xml';
$xml = simplexml_load_file($filename);
$inputdata = $xml->children();
$outputname = $inputdata->getName() . ".csv";
$columns = [];
$outputdata = [];
// Determina as colunas a serem salvas
foreach ($inputdata as $item) {
    foreach($item as $property) {
        if (!in_array($property->getName(), $columns)) {
            $columns[] = $property->getName();
        }
    }
}

$outfile = fopen($outputname, 'w');
// Escreve o heading do arquivo
fputcsv($outfile, $columns);    

// Escreve cada item no arquivo, na ordem das colunas requisitadas
foreach ($inputdata as $item) {
    $outline = [];
    foreach($columns as $column) {
        $output = $item->xpath($column);
        if ($output) {
            $outline[] = (string) $output[0];
        } else {
            $outline[] = "";
        }
    }
    fputcsv($outfile, $outline);
}

fclose($outfile);