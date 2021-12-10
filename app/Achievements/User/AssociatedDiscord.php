<?php
declare(strict_types=1);

namespace App\Achievements\User;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\User
 */
class AssociatedDiscord extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'AssociatedDiscord';

    /*
     * A small description for the achievement
     */
    public $description = 'User associated their account with a Discord';
}
