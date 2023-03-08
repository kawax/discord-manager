<?php

declare(strict_types=1);

namespace Revolution\DiscordManager\Support;

enum ButtonStyle: int
{
    case PRIMARY = 1;
    case SECONDARY = 2;
    case SUCCESS = 3;
    case DANGER = 4;
    case LINK = 5;
}
