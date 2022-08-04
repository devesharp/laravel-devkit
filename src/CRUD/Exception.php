<?php

namespace Devesharp\CRUD;

use Throwable;

/**
 * Exception
 */
class Exception extends \Exception
{
    const SERVER_ERROR = 1;
    const UNAUTHORIZED = 2;
    const TOKEN_INVALID = 3;

    const DATA_ERROR = 2000;
    const DATA_ERROR_GENERAL = 2001;

    const NOT_FOUND_RESOURCE = 3000;

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

    public static function getString($value, $body = null): string
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
                return array_values($body)[0][0];
                break;

            case static::DATA_ERROR_GENERAL:
                return 'Dados da requisição incorretos';
                break;

            case static::NOT_FOUND_RESOURCE:
                return 'Recurso não encontrado';
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
    public static function Exception(int $errorCode, $body = null)
    {
        throw new static(static::getString($errorCode, $body), $errorCode, null, $body, );
    }
}
