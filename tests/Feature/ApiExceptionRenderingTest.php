<?php

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Exceptions\UnauthorizedException;

beforeEach(function () {
    seedPermissions();
});

test('missing api records render a json not found response', function () {
    createApiUserWithPermissions(['user.show']);

    $this->getJson('/api/v1/user/999999999')
        ->assertNotFound()
        ->assertExactJson([
            'message' => 'No records found.',
        ]);
});

test('api permission failures render a json unauthorized response', function () {
    $subject = User::factory()->create();
    $caller = createApiUserWithPermissions([]);
    $caller->syncPermissions(['api']);

    $this->getJson("/api/v1/user/{$subject->id}")
        ->assertForbidden()
        ->assertExactJson([
            'error' => 'Unauthorized',
        ]);
});

test('json authorization failures render a json unauthorized response', function () {
    Route::get('/__exception/denied', function () {
        throw new AuthorizationException;
    });

    $this->getJson('/__exception/denied')
        ->assertForbidden()
        ->assertExactJson([
            'error' => 'Unauthorized',
        ]);
});

test('json spatie authorization failures render a json unauthorized response', function () {
    Route::get('/__exception/spatie', function () {
        throw UnauthorizedException::forPermissions(['post.create']);
    });

    $this->getJson('/__exception/spatie')
        ->assertForbidden()
        ->assertExactJson([
            'error' => 'Unauthorized',
        ]);
});

test('html authorization failures still render the branded page', function () {
    $this->withoutVite();

    Route::get('/__exception/denied-page', function () {
        throw new AuthorizationException;
    });

    $this->get('/__exception/denied-page')
        ->assertForbidden()
        ->assertSee('Access denied', false);
});
