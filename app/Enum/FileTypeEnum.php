<?php

namespace App\Enum;
enum FileTypeEnum:string
{
    case PDF = "application/pdf";
    /*
    case DOC = "application/msword";
    case DOCX = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
    case TXT = "text/plain";
    */
    public static function fromCase($case)
    {
        return (new \ReflectionEnum(self::class))->getCase($case)->getValue()->value;
    }
}
