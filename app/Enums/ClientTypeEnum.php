<?php


namespace App\Enums;

Enum ClientTypeEnum:string {
    case CONTADO = 'CONTADO';
    case CREDITO = 'CREDITO';
    case ANTICIPO = 'ANTICIPO';


    public function label():string
    {
        return match ($this){
            self::CONTADO => 'CONTADO',
            self::CREDITO => 'CREDITO',
            self::ANTICIPO => 'ANTICIPO',
        };
    }

    public static function map():array
    {
        $map = [];
        foreach (self::cases() as $case){
            $map[$case->value] = $case->label();
        }

        return $map;
    }

    public static function options(): array
    {
        return array_map(fn (self $case) => [
            'label' => $case->label(),
            'value' => $case->value,
        ], self::cases());
    }

}
