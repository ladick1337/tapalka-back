<?php

namespace App\Consts;

class Permissions
{

    const PERMISSION_EMPLOYERS = 'employers';
    const PERMISSION_ROLES = 'roles';
    const PERMISSION_SETTINGS = 'settings';
    const PERMISSION_TASKS = 'tasks';
    const PERMISSION_CLIENTS = 'clients';
    const PERMISSION_SENGINGS = 'sendings';
    const PERMISSION_VIDEOLINKS = 'Видео-ссылки';

    const HINTS = [
        self::PERMISSION_SENGINGS => 'Рассылка',
        self::PERMISSION_SETTINGS => 'Настройки',
        self::PERMISSION_EMPLOYERS => 'Сотрудники',
        self::PERMISSION_ROLES => 'Роли',
        self::PERMISSION_CLIENTS => 'Клиенты',
        self::PERMISSION_TASKS => 'Задачи',
        self::PERMISSION_VIDEOLINKS => 'Видео-ссылки'
    ];

}
