<?php

namespace App\Enum;
enum QueuesEnum:string
{
    case DEFAULT = 'default';
    case EMBEDDING = 'embedding';
    case BATCH = 'batch';
    public static function fromCase($case)
    {
        return (new \ReflectionEnum(self::class))->getCase($case)->getValue()->value;
    }
}
