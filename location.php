<?php
# Criando um array associativo chamado "location", consistindo em chaves (paises) => valores (capitais)
$location = ["Brasil" => "Brasília", "EUA" => "Washington", "Espanha" => "Madrid", "Omã" => "Mascate"];

# Adicionando novos elementos ao array; há várias formas de realizar isso
$location["Argentina"] = "Buenos Aires";
$location += ["Chile" => "Santiago"];
$location = array_merge($location, ["Reino Unido" => "Londres"]);

# Ordenando o array pelos seus valores (ou seja, capitais). ksort poderia ser usado para ordenar pelo nome do país
asort($location);

# Determinando o artigo correto para cada país...
$artigo = [
    "Brasil" => "do",
    "EUA" => "dos",
    "Espanha" => "da",
    "Omã" => "de",
    "Argentina" => "da",
    "Chile" => "do",
    "Reino Unido" => "do",
];

foreach($location as $country => $capital) {
    echo "<p>A capital {$artigo[$country]} $country é $capital</p>";
}
