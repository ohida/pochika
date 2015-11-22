<?php

$fixers = [
    // [psr-0]
    '-psr0',

    // [psr-1]
    // 'encoding',
    // 'short_tag',

    // [psr-2]
    // 'braces',
    // 'elseif',
    // 'eof_ending',
    // 'function_call_space',
    // 'function_declaration',
    // 'indentation',
    // 'line_after_namespace',
    // 'linefeed',
    // 'lowercase_constants',
    // 'lowercase_keywords',
    // 'method_argument_space',
    // 'multiple_use',
    // 'parenthesis',
    // 'php_closing_tag',
    // 'single_line_after_imports',
    // 'trailing_spaces',
    // 'visibility',

    // [symfony]
    'blankline_after_open_tag',
    'double_arrow_multiline_whitespaces',
    'duplicate_semicolon',
    'extra_empty_lines',
    'include',
    'join_function',
    'list_commas',
    'multiline_array_trailing_comma',
    'namespace_no_leading_whitespace',
    // 'new_with_braces',
    'no_blank_lines_after_class_opening',
    'no_empty_lines_after_phpdocs',
    'object_operator',
    'operators_spaces',
    // 'phpdoc_params',
    'phpdoc_indent',
    'phpdoc_no_access',
    // 'phpdoc_no_empty_return',
    'phpdoc_scalar',
    // 'phpdoc_separation',
    // 'phpdoc_short_description',
    // 'phpdoc_to_comment',
    'phpdoc_trim',
    'phpdoc_type_to_var',
    'phpdoc_var_without_name',
    'remove_leading_slash_use',
    'remove_lines_between_uses',
    // 'return',
    'self_accessor',
    'single_array_no_trailing_comma',
    'single_blank_line_before_namespace',
    'single_quote',
    'spaces_before_semicolon',
    // 'spaces_cast',
    'standardize_not_equal',
    'ternary_spaces',
    'trim_array_spaces',
    // 'unalign_double_arrow',
    // 'unalign_equals',
    'unused_use',
    'whitespacy_lines',

    // [contrib]
    // 'align_double_arrow',
    'multiline_spaces_before_semicolon',
    'ordered_use',
    'phpdoc_order',
    'short_array_syntax',
    // 'strict',
    // 'strict_param',
];

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->exclude('bootstrap')
    ->exclude('database')
    ->exclude('vendor')
    ->exclude('public')
    ->exclude('resources')
    // ->exclude('tests')
    ->in(__DIR__)
    // ->in(__DIR__.'/app/Http/Controllers')
    ;

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::PSR2_LEVEL)
    ->fixers($fixers)
    ->finder($finder)
    ->setUsingCache(true)
    ;
