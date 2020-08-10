<?php

$files = ["music.mp4", "video.mov", "imagem.jpeg"];

# Extrai a extensão de cada arquivo usando a função nativa pathinfo. 
$extensions = array_map(
    function(string $filename): string {
        return pathinfo($filename, PATHINFO_EXTENSION);
    },
    $files
);

sort($extensions);

echo "<ol>";
foreach($extensions as $extension) {
    echo "<li>$extension</li>";
}
echo "</ol>";