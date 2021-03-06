<?php
/** NOTA! Estou ignorando as PSR nesse código por brevidade. Em um projeto real, cada classe deveria estar
 * em um arquivo separado, com namespaces próprios!
 */
/** 
 * Classe com algumas funções básicas para auxiliar a escrever um form. 
 */
class FormHelper {
    /**
     * @static array Contém os nomes dos campos que tiveram algum erro ao validar o form, usado para indicar ao
     * usuário qual foi o erro.
     */
    public static $troubles = [];

    /** 
     * Retorna o valor do campo presente no $_POST. Use em forms para manter o valor do
     * campo que foi submetido. Dentro de um framework, isso poderia ser feito de forma mais elegante,
     * evitando o acesso direto ao superglobal $_POST...
     * 
     * @param string $field Nome do campo, corresponde ao atributo name no input html.
     * 
     * @return string 
     */
    public static function old(string $field): string
    {
        return array_key_exists($field, $_POST) ? $_POST[$field] : "";
    }
    /**
     * Indica se um campo foi submetido com erro. 
     * 
     * @param string $field Nome do campo, corresponde ao atributo name no input html.
     * 
     * @return string "trouble" se houve erro relacionado a esse campo, "" caso contrário
     */
    public static function isTrouble(string $field): bool
    {
        return in_array($field, self::$troubles);
    }

    /**
     * Indica que um campo foi submetido com erro.
     * 
     * @param string $field Nome do campo, corresponde ao atributo name no input html.
     */
    public static function setTrouble(string $field): void
    {
        self::$troubles[] = $field;
    }

    /**
     * Função muito simples para construir um input do tipo texto ou password. Ela mostra o dado que foi submetido
     * originalmente e indica em vermelho os campos que apresentaram problema. Idealmente, esse alerta 
     * poderia incluir uma mensagem para indicar qual foi o problema, mas não vou implementar isso aqui 
     * para não ficar extenso demais!
     * 
     * @param string $fieldname Nome do input. Será usado como id e name no html gerado.
     * @param string $label Texto para ser mostrado como label ao lado do input
     * (não use : pois isso será incluído automaticamente)
     * @param bool $secret Use para gerar campos de password
     * 
     * @return string 
     */
    public static function textInput(string $fieldname, string $label, bool $secret = false): string
    {
        $elementType = $secret ? "password" : "text";
        $input = "<label for='$fieldname'>$label:</label>";
        $input .= "<input
            type='$elementType'
            id='$fieldname'
            name='$fieldname'
            class='" . (self::isTrouble($fieldname) ? "trouble" : "") . "'";
        if (!$secret) {
            $input .= "value='" . self::old($fieldname) . "'";
        }
        $input .= "><br>";
        return $input;
    }
}

/** Classe contendo funções de validação de dados. */
class Validator {
    /**
     * O campo tem algum valor não-vazio?
     * 
     * @param array $entry Array com os dados do formulario
     * @param string $field Nome do campo
     * 
     * @return bool
     */
    public static function exists(array $entry, string $field): bool
    {
        return isset($entry[$field]) && !empty($entry[$field]);
    }

    /**
     * O campo contém um e-mail válido?
     * 
     * @param array $entry Array com os dados do formulario
     * @param string $field Nome do campo
     * 
     * @return bool
     */
    public static function email(array $entry, string $field): bool
    {
        if (!$entry[$field]) {
            return true;
        }
        return filter_var($entry[$field], FILTER_VALIDATE_EMAIL);
    }
    
    /**
     * O campo contém um telefone válido? Considera DDD e telefones de 8 ou 9 posições. Esse regex
     * é bastante permissivo com o uso de hífen ou espaço para formatação do número.
     * 
     * @param array $entry Array com os dados do formulario
     * @param string $field Nome do campo
     * 
     * @return bool
     */
    public static function phone(array $entry, string $field): bool
    {
        if (!$entry[$field]) {
            return true;
        }
        $phone = str_replace(' ', '', str_replace('-', '', $entry[$field]));
        return !!preg_match('/^(\(\d{2}\))?\d{8,9}$/', $phone);
    }

    /**
     * O campo contém um valor único? Usa a classe Storage para verificar se já existe um registro do campo "field" com
     * esse valor
     * 
     * @param array $entry Array com os dados do formulario
     * @param string $field Nome do campo
     * 
     * @return bool
     */
    public static function unique(array $entry, string $field): bool
    {
        if (!$entry[$field]) {
            return true;
        }
        $storage = (new Storage())->getContents();
        // Extrai a coluna que queremos que seja única:
        $column = array_column($storage, $field);
        return !in_array($entry[$field], $column);
    }
}

/** Solução simples para criptografar dados (mão única!) */
class Crypt {
    private static $salt = 'SERVERSALT123';

    /**
     * Criptografa um string usando um salt fixo
     * 
     * @param string $data Texto a ser criptografado
     */
    public static function encrypt(string $data): string
    {
        return sha1(self::$salt . $data);
    }
}

/** Abstração simples para um sistema de arquivo */
class Storage {
    private static $filename = "./registros.txt";
    private array $data = [];

    /**
     * Cria um novo objeto da classe Storage, contendo os dados lidos a partir do disco.
     */
    public function __construct() {
        if (!file_exists(self::$filename)) {
            return;
        }
        $fileContents = file_get_contents(self::$filename);
        $this->data = json_decode($fileContents, true);
    }

    /**
     * Retorna um array associativo contendo todos os dados já salvos
     */
    public function getContents(): array {
        return $this->data;
    }

    /**
     * Inclui um novo registro em memória, use "save" para persistir os dados em disco
     * 
     * @param array $entry Array associativo contendo os dados a serem salvos
     */
    public function addEntry(array $entry): void
    {
        $this->data[] = $entry;
    }

    /**
     * Salva em disco o conteúdo desse storage.
     */
    public function save(): void
    {
        $file = fopen(self::$filename, "w");
        fwrite($file, json_encode($this->data));
    }
}

class ValidatorException extends Exception {}

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
            if (!is_callable(["Validator", $rule])) {
                throw new ValidatorException("Regra de validação $rule não localizada!");
            }
            $thisRuleResult = call_user_func(["Validator", $rule], $entry, $field);
            if (!$thisRuleResult) {
                $valid = false;
                // Marca o campo que estamos validando como tendo um erro, para permitir feedback ao usuário
                FormHelper::setTrouble($field);
            }
        }
    }
    return $valid;
}

/**
 * Aplica as validações e transformações de dados necessários e salva um registro. 
 * Retorna "true" se o registro foi salvo, "false" se houve um erro.
 * 
 * @param array $entry Array contendo os dados a serem salvos
 */
function saveEntry(array $entry): bool
{
    // Validações
    $ruleset = [
        "fname" => ["exists"],
        "lname" => ["exists"],
        "email" => ["exists", "email", "unique"],
        "phone" => ["phone"],
        "login" => ["exists", "unique"],
        "password" => ["exists"],
    ];
    $valid = validateInput($entry, $ruleset);

    // Transforma o valor do password para ser salvo encriptado.
    $entry["password"] = Crypt::encrypt($entry["password"]);

    if ($valid) {
        $storage = new Storage();
        $storage->addEntry($entry);
        $storage->save();
        return true;
    }
    return false;
}

// Detecta se estamos recebendo um input ou não. Dentro de um framework, isso seria tratado melhor
// usando routes, mas para criar uma única página isso é uma solução adequada... 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (saveEntry($_POST)) {
        $_POST = [];
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Exemplo de formulário</title>
        <style>
            /** Classes bem simples para o form, inspiradas no estilo da www.w3schools.com */
            form {border-radius: 5px; background-color: #f2f2f2; padding: 20px; width: 25%}
            input[type=text], input[type=password] {width: 100%; padding: 12px 20px; margin: 8px 0; display: inline-block; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;}
            input[type=submit] {width: 100%; background-color: #4CAF50; color: white; padding: 14px 20px; margin: 8px 0; border: none; border-radius: 4px; cursor: pointer;}
            input[type=submit]:hover {background-color: #45a049;}
            input.trouble{border-color: lightcoral;}
        </style>
    </head>
    <body>
        <form action="/simple-form.php" method="POST">
        <?php if (!empty($_POST)) { ?>
            <div>Houve um erro ao processar os dados! Verifique os campos em vermelho...</div><br>
         <?php } ?>
            <?= FormHelper::textInput("fname", "Nome") ?>
            <?= FormHelper::textInput("lname", "Sobrenome") ?>
            <?= FormHelper::textInput("email", "E-mail") ?>
            <?= FormHelper::textInput("phone", "Telefone") ?>
            <?= FormHelper::textInput("login", "Login") ?>
            <?= FormHelper::textInput("password", "Senha", true) ?>
            <input type="submit" value="Cadastrar">
        </form>
    </body>
</html>