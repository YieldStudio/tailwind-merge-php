<?php

test('tw_merge helper works correctly', function(){
    expect(tw_merge('block hidden'))->toBe('hidden');
});
