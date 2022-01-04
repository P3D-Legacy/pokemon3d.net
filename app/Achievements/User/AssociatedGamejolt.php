<?php
declare(strict_types=1);

namespace App\Achievements\User;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\User
 */
class AssociatedGamejolt extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'AssociatedGamejolt';

    /*
     * A small description for the achievement
     */
    public $description = 'User associated their account with Gamejolt';

    /*
     * The amount of "points" this user need to obtain in order to complete this achievement
     */
    public $points = 1;
}
