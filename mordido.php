<?php
# "Coin flip"
function foiMordido(): bool
{
    return rand(0, 1) === 1;
}

for ($i = 0; $i < 10; $i++) {
    $mordeu = foiMordido() ? " " : " NÃO ";
    # Interpolação um pouco estranha para garantir que o whitespace vai ser sempre coerente
    echo "<p>Joãozinho{$mordeu}mordeu o seu dedo!</p>";
}