<?php

test('merges classes with per-side border colors correctly')->twMerge([
    'border-t-some-blue border-t-other-blue' => 'border-t-other-blue',
    'border-t-some-blue border-some-blue' => 'border-some-blue',
]);