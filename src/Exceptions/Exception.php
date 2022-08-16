<?php

namespace Devesharp\Exceptions;

use Throwable;

class Exception extends \Exception
{
    const SERVER_ERROR = 1; // Erro interno
    const UNAUTHORIZED = 2; // Não autorizado
    const TOKEN_INVALID = 3; // Token não encontrado ou vencido

    const DATA_ERROR = 2000;
    const DATA_ERROR_GENERAL = 2001;

    const NOT_FOUND_RESOURCE = 3000; // Recurso não encontrado

    public $body;

    public function __construct(
        $message = '',
        $code = 0,
        Throwable $previous = null,
        $body = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->body = $body;
    }

    public function getBody()
    {
        return $this->body ?? null;
    }

    public static function getString($value, $bodyOrString = null): string
    {
        switch ($value) {
            case static::SERVER_ERROR:
                return 'Houve um erro ao executar a ação, favor entrar em contato conosco';
                break;

            case static::UNAUTHORIZED:
            case static::TOKEN_INVALID:
                return 'Não Autorizado';
                break;

            case static::DATA_ERROR:
                // Primeiro Erro
                return array_values($bodyOrString)[0][0];
                break;

            case static::DATA_ERROR_GENERAL:
                return 'Dados da requisição incorretos';
                break;

            case static::NOT_FOUND_RESOURCE:
                return $bodyOrString ?? 'Recurso não encontrado';
                break;

            default:
                return 'Erro desconhecido';
                break;
        }
    }

    /**
     * @param string $type
     * @throws static
     */
    public static function NotFound($type = '')
    {
        static::exception(static::NOT_FOUND_RESOURCE);
    }

    /**
     * @throws static
     */
    public static function Unauthorized()
    {
        static::exception(static::UNAUTHORIZED);
    }

    /**
     * @param int $errorCode
     * @param null $body
     * @throws static
     */
    public static function Exception(int $errorCode, $bodyOrString = null)
    {
        throw new static(static::getString($errorCode, $bodyOrString), $errorCode, null, $bodyOrString, );
    }
}
