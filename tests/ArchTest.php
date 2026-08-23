<?php

declare(strict_types=1);

arch('models')
    ->expect('App\Models')
    ->toExtend('Illuminate\Database\Eloquent\Model');

arch('enums')
    ->expect('App\Enums')
    ->toBeEnums();
