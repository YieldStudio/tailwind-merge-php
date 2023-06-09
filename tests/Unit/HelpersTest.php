<?php

declare(strict_types=1);

test('tw_merge helper works correctly', function () {
    expect(tw_merge('block hidden'))->toBe('hidden');
});
