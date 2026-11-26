<?php


namespace App\Enums;

enum UserRoleEnum: string {
    case USER = 'user';
    case SUPERVISOR = 'supervisor';
    case ADMIN = 'admin';
}
