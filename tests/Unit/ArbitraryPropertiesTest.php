<?php

it('handles arbitrary property conflicts correctly')->twMerge([
    '[paint-order:markers] [paint-order:normal]' => '[paint-order:normal]',
    '[paint-order:markers] [--my-var:2rem] [paint-order:normal] [--my-var:4px]' => '[paint-order:normal] [--my-var:4px]'
]);

it('handles arbitrary property conflicts with modifiers correctly')->twMerge([
    '[paint-order:markers] hover:[paint-order:normal]' => '[paint-order:markers] hover:[paint-order:normal]',
    'hover:[paint-order:markers] hover:[paint-order:normal]' => 'hover:[paint-order:normal]',
    'hover:focus:[paint-order:markers] focus:hover:[paint-order:normal]' => 'focus:hover:[paint-order:normal]',
    '[paint-order:markers] [paint-order:normal] [--my-var:2rem] lg:[--my-var:4px]' => '[paint-order:normal] [--my-var:2rem] lg:[--my-var:4px]',
]);

it('handles complex arbitrary property conflicts correctly')->twMerge([
    '[-unknown-prop:::123:::] [-unknown-prop:url(https://hi.com)]' => '[-unknown-prop:url(https://hi.com)]'
]);

it('handles important modifier correctly')->twMerge([
    '![some:prop] [some:other]' => '![some:prop] [some:other]',
    '![some:prop] [some:other] [some:one] ![some:another]' => '[some:one] ![some:another]'
]);
