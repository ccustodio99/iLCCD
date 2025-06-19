<?php

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('returns booleans for true and false strings and integers', function () {
    Setting::set('string_true', 'true');
    Setting::set('string_false', 'false');
    Setting::set('int_true', 1);
    Setting::set('int_false', 0);

    expect(Setting::get('string_true'))->toBeTrue();
    expect(Setting::get('string_false'))->toBeFalse();
    expect(Setting::get('int_true'))->toBeTrue();
    expect(Setting::get('int_false'))->toBeFalse();
});
