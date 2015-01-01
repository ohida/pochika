<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->notName('readme.md')
//  ->exclude('vendor')
    ->in(__DIR__.'/engine')
    ->in(__DIR__.'/plugins')
;

return Symfony\CS\Config\Config::create()
//  ->level(Symfony\CS\FixerInterface::NONE_LEVEL)
    ->level(Symfony\CS\FixerInterface::PSR2_LEVEL)
    ->fixers([
//      '-psr0', '-indentation',
        '-braces', '-function_declaration', '-phpdoc_params',
        '-spaces_cast', '-new_with_braces', '-eof_ending',
        '-trailing_spaces', '-empty_return',
        'ordered_use', 'unused_use',
    ])
    ->finder($finder)
;
