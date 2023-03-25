<?php

test('merges content utilities correctly')->twMerge([
    'content-[\'hello\'] content-[attr(data-content)]' => 'content-[attr(data-content)]'
]);
