<?php
error_reporting(E_ALL);
/** 
 * Retorna o valor do campo presente no $_POST. Use em forms para manter o valor do
 * campo que foi submetido. Dentro de um framework, isso poderia ser feito de forma mais elegante,
 * evitando o acesso direto ao superglobal $_POST...
 * 
 * @param string $field Nome do campo, corresponde ao atributo name no input html.
 * 
 * @return string 
 */
function old(string $field): string
{
    return array_key_exists($field, $_POST) ? $_POST[$field] : "";
}

/**
 * Indica se um campo foi submetido com erro. Dentro de um framework, isso poderia ser feito de forma
 * mais elegante, sem o uso de uma variável global...
 * 
 * @param string $field Nome do campo, corresponde ao atributo name no input html.
 * 
 * @return string "trouble" se houve erro relacionado a esse campo, "" caso contrário
 */
function trouble(string $field): string
{
    global $troubles;
    return (isset($troubles) && in_array($field, $troubles)) ? "trouble" : "";
}

/**
 * Função muito simples para construir um input do tipo texto ou password.
 * 
 * @param string $fieldname Nome do input. Será usado como id e name no html gerado.
 * @param string $label Texto para ser mostrado como label ao lado do input
 * (não use : pois isso será incluído automaticamente)
 * @param bool $secret Use para gerar campos de password
 * 
 * @return string 
 */
function textInput(string $fieldname, string $label, bool $secret = false): string
{
    $elementType = $secret ? "password" : "text";
    $input = "<label for='$fieldname'>$label:</label>";
    $input .= "<input
        type='$elementType'
        id='$fieldname'
        name='$fieldname'
        class='" . trouble($fieldname) . "'";
    if (!$secret) {
        $input .= "value='" . old($fieldname) . "'";
    }
    $input .= "><br>";
    return $input;
}

/**
 * Validator: o campo tem algum valor não-vazio?
 * 
 * @param array $entry Array com os dados do formulario
 * @param string $field Nome do campo
 * 
 * @return bool
 */
function exists(array $entry, string $field): bool
{
    return isset($entry[$field]) && !empty($entry[$field]);
}

function email(array $entry, string $field): bool
{
    return true;
}
function phone(array $entry, string $field): bool
{
    return true;
}
function unique(array $entry, string $field): bool
{
    return false;
}

/**
 * Aplica um conjunto de validadores de dados a uma entrada, retornando true se a entrada
 * for considerada válida e false se a algum validador retornar erro. Como efeito colateral,
 * adiciona à variável global $troubles a lista de campos que não passou na validação.
 * 
 * @param array $entry Array contendo os dados a serem validados.
 * @param array $ruleset Array cujas chaves são o nome dos campos a serem validados e os 
 * valores são arrays contendo o nome dos validadores que devem ser aplicados
 * 
 * @return bool
 */
function validateInput(array $entry, array $ruleset): bool
{
    global $troubles;
    if (!isset($troubles)) {
        $troubles = [];
    }
    $valid = true;
    foreach($ruleset as $field => $rules) {
        foreach ($rules as $rule) {
            $thisRuleResult = call_user_func($rule, $entry, $field);
            if (!$thisRuleResult) {
                $valid = false;
                $troubles[] = $field;
            }
        }
    }
    return $valid;
}

// Detecta se estamos recebendo um input ou não. Dentro de um framework, isso seria tratado melhor
// usando routes, mas para criar uma única página isso é uma solução adequada... 
if (array_key_exists("submit", $_POST)) {
    $ruleset = [
        "fname" => ["exists"],
        "lname" => ["exists"],
        "email" => ["exists", "email", "unique"],
        "phone" => ["phone"],
        "login" => ["exists", "unique"],
        "password" => ["exists"],
    ];
    $valid = validateInput($_POST, $ruleset);
    var_dump($_POST);

}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Exemplo de formulário</title>
<style>
    form {border-radius: 5px; background-color: #f2f2f2; padding: 20px; width: 25%}
input[type=text], input[type=password] {width: 100%; padding: 12px 20px; margin: 8px 0; display: inline-block; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;}
input[type=submit] {width: 100%; background-color: #4CAF50; color: white; padding: 14px 20px; margin: 8px 0; border: none; border-radius: 4px; cursor: pointer;}
input[type=submit]:hover {background-color: #45a049;}
input.trouble{border-color: lightcoral;}
</style>
    </head>
    <body>
        <form action="/simple-form.php" method="POST">
            <?= textInput("fname", "Nome") ?>
            <?= textInput("lname", "Sobrenome") ?>
            <?= textInput("email", "E-mail") ?>
            <?= textInput("phone", "Telefone") ?>
            <?= textInput("login", "Login") ?>
            <?= textInput("password", "Senha", true) ?>
            <input type="submit" name="submit" value="Cadastrar">
        </form>
    </body>
</html>