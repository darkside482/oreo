<?php

namespace App\Manager;

use DateTime;

class TimeManager
{
    public function getTime(): DateTime
    {
        return new DateTime('now', null);
    }
}
