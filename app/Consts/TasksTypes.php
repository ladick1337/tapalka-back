<?php

namespace App\Consts;

class TasksTypes
{

    const TELEGRAM = 'telegram';
    const STARS = 'stars';
    const SOCIAL = 'social';
    const STORY = 'story';
    const KEYWORD = 'keyword';

    const HINTS = [
        self::STARS => 'Звезды',
        self::TELEGRAM => 'Подписка',
        self::SOCIAL => 'Реклама',
        self::STORY => 'Story',
        self::KEYWORD => 'KeyWord'
    ];

}
