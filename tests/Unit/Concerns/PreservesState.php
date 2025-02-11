<?php

use DefStudio\WiredTables\Concerns\PreservesState;
use function Livewire\invade;
use function Pest\Laravel\actingAs;

it('can generate a state key', function () {
    $class = new class () {
        use PreservesState;
        protected string $slug = '';
    };
    $class->mountPreservesState();

    $key = invade($class)->getStateKey(new User(['id' => 42]), 'baz');

    expect($key)->toBe('httplocalhost-42-state-baz');
})->skip(fn () => !function_exists('invade'));

it('can store and retrieve state', function () {
    $class = new class () {
        use PreservesState;
        protected string $slug = '';

        protected function config($key): bool
        {
            return true;
        }
    };
    $class->mountPreservesState();

    actingAs(new User(['id' => 42]));

    invade($class)->storeState('foo', 666);

    $cached = invade($class)->getState('foo');

    expect($cached)->toBe(666);
})->skip(fn () => !function_exists('invade'));
