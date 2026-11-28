<?php

namespace App\Enums;

enum ToastTypeEnum: string
{
    case SUCCESS = 'success';
    case ERROR = 'error';
    case INFO = 'info';
    case WARNING = 'warning';

    public function label(): string
    {
        return match ($this) {
            self::SUCCESS => 'Éxito',
            self::ERROR => 'Error',
            self::INFO => 'Información',
            self::WARNING => 'Advertencia',
        };
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    public static function options(): array
    {
        return array_map(fn (self $type) => [
            'value' => $type->value,
            'label' => $type->label(),
        ], self::cases());
    }

}
