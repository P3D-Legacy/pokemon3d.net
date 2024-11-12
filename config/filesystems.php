<?php

return [

    'disks' => [
        'player' => [
            'driver' => 'local',
            'root' => public_path().'/player',
        ],

        'skin' => [
            'driver' => 'local',
            'root' => public_path().'/img/skin',
        ],
    ],

];
