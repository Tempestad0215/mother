<?php


namespace App\Enums;

Enum UserRoleEnum: string {
    case USER = 'user';
    case SUPERVISOR = 'supervisor';
    case ADMIN = 'admin';
}
