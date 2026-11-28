<?php

namespace App\Enums;

enum ToastMessageEnum: string
{
    case CREATED = 'created';
    case UPDATED = 'updated';
    case DELETED = 'deleted';
    case ERROR = 'error';
    case WARNING = 'warning';

    public function severity(): string
    {
        return match ($this) {
            self::CREATED,
            self::UPDATED,
            self::DELETED => 'success',

            self::WARNING => 'warn',

            self::ERROR => 'error',
        };
    }

    public function message(): string
    {
        return match ($this) {
            self::CREATED => 'Registro creado con éxito',
            self::UPDATED => 'Registro actualizado con éxito',
            self::DELETED => 'Registro eliminado con éxito',
            self::WARNING => 'Revisa los datos ingresados',
            self::ERROR => 'Ocurrió un error al procesar la solicitud',
        };
    }


    public function toastType():ToastTypeEnum{
        return match ($this) {
            self::CREATED, self::UPDATED, self::DELETED => ToastTypeEnum::SUCCESS,
            self::ERROR => ToastTypeEnum::ERROR,
            self::WARNING => ToastTypeEnum::SUCCESS
        };
    }

    public function toArray(): array
    {
        return [
            'type' => $this->value,
            'severity' => $this->severity(),
            'message' => $this->message(),
        ];
    }
}
