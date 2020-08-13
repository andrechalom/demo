<?php

namespace Framework;

use Framework\Exception\RequestException;

class Request
{
    private array $data = [];
    /**
     * Cria um objeto "Request" com os dados recebidos do cliente. Os dados devem estar em formato
     * JSON, caso contrário uma exception será lançada.
     */
    public function __construct()
    {
        $json_params = json_decode(file_get_contents("php://input"), true, 512, JSON_UNESCAPED_UNICODE);
        if (empty($json_params)) {
            return;
        }
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RequestException("Malformed JSON input! Make sure your request is valid JSON");
        }
        $this->data = $json_params;
    }

    /**
     * Aplica um conjunto de validadores de dados à entrada recebida. Se alguma regra não for
     * obedecida, lança um RequestException.
     * 
     * @param array $ruleset Array cujas chaves são o nome dos campos a serem validados e os 
     * valores são arrays contendo o nome dos validadores que devem ser aplicados.
     * @param string $classname Nome do model que está sendo validado. 
     */
    function validate(array $ruleset, string $classname): void
    {
        foreach($ruleset as $field => $rules) {
            foreach ($rules as $rule) {
                if (!is_callable(["Framework\\Validator", $rule])) {
                    throw new RequestException("Validation rule $rule not found!");
                }
                $thisRuleResult = call_user_func(["Framework\\Validator", $rule], $this->data, $field, $classname);
                if (!$thisRuleResult) {
                    throw new RequestException("Validation rule $rule failed for field $field!");
                }
            }
        }
    }

    /** Retorna os dados recebidos pelo request */
    function data(): array
    {
        return $this->data;
    }
}