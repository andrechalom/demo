<?php

/** Classe simples para gerar um select field */
class SelectField {
    private array $options = [];
    private ?int $default = null;
    private ?string $label = null;
    private string $name;
    
    /**
     * Cria um novo objeto da classe Select.
     * 
     * @param string $name Nome do elemento, será usado no atributo name e como id.
     *
     * @return self
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Determina a descrição do campo no form. Retorna o próprio objeto, para permitir chamadas encadeadas.
     * 
     * @param string $label Forneça uma string para ser usada como label para o select
     *
     * @return self
     */
    public function setLabel(string $label): self {
        $this->label = $label;
        return $this;
    }

    /**
     * Determina as opções que estarão disponíveis. Retorna o próprio objeto, para permitir chamadas encadeadas.
     * 
     * @param array $options Array associativo de opções. As chaves do array serão usadas como os valores do select
     *
     * @return self
     */
    public function setOptions(array $options): self {
        $this->options = $options;
        return $this;
    }

    /**
     * Determina o valor padrão do select. Retorna o próprio objeto, para permitir chamadas encadeadas.
     * 
     * @param int $default Valor padrão do select, virá pré-selecionado quando o usuário abrir a tela
     *
     * @return self
     */
    public function setDefault(int $default): self {
        $this->default = $default;
        return $this;
    }

    /** Converte um objeto desta classe em string para que possa ser renderizado na tela */
    public function __toString(): string
    {
        $return = "";
        if ($this->label) {
            $return .= "<label for='{$this->name}'>{$this->label}</label> ";
        }
        $return .= "<select name='{$this->name}' id='{$this->name}'>";
        foreach ($this->options as $key => $option) {
            $thisIsDefault = isset($this->default) && $this->default === $key;
            $return .= "<option value='{$key}' " . ($thisIsDefault ? "selected" : "") . ">{$option}</option>";
        }
        $return .= "</select>";
        return $return;
    }

    /**
     * Helper para gerar um campo select em uma única linha.
     * @param string $name Nome do elemento, será usado no atributo name e como id.
     * @param array $options Array associativo de opções. As chaves do array serão usadas como os valores do select
     * @param string $label Forneça uma string para ser usada como label para o select
     * @param int $default Valor padrão do select, virá pré-selecionado quando o usuário abrir a tela
     * 
     * @return string
     */
    public static function simpleSelect(string $name, array $options, string $label = "", int $default = 0): string {
        $mySelect = (new SelectField($name))
            ->setLabel($label)
            ->setOptions($options)
            ->setDefault($default);
        return (string) $mySelect;
    }
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Exemplo de formulário com Select field</title>
        <style>
            /** Classes bem simples para o form, inspiradas no estilo da www.w3schools.com */
            form {border-radius: 5px; background-color: #f2f2f2; padding: 20px; width: 25%}
            input[type=text], select {width: 100%; padding: 12px 20px; margin: 8px 0; display: inline-block; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;}
            input[type=submit] {width: 100%; background-color: #4CAF50; color: white; padding: 14px 20px; margin: 8px 0; border: none; border-radius: 4px; cursor: pointer;}
        </style>
    </head>
    <body>
        <form action="#" method="POST">
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" value="Zé">
            <br>
            <?= SelectField::simpleSelect("cor", [1 => "Azul", 2 => "Amarelo", 3 => "Vermelho"], "Cor favorita", 3) ?>
            <br>
            <input type="submit" value="Cadastrar">
        </form>
    </body>
</html>