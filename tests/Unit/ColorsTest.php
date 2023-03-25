<?php

it('handles color conflicts properly')->twMerge([
    'bg-grey-5 bg-hotpink' => 'bg-hotpink',
    'hover:bg-grey-5 hover:bg-hotpink' => 'hover:bg-hotpink'
]);

