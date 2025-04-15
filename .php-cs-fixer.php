<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rules = [
    '@PSR12' => true,
    'phpdoc_indent' => true,
    'binary_operator_spaces' => [
        'operators' => ['=>' => null],
    ],
    'blank_line_after_namespace' => true,
    'blank_line_after_opening_tag' => true,
    'blank_line_before_statement' => [
        'statements' => ['declare'],
    ],
    'single_space_around_construct' => true,
    'control_structure_braces' => true,
    'control_structure_continuation_position' => true,
    'declare_parentheses' => true,
    'no_multiple_statements_per_line' => true,
    'braces_position' => true,
    'statement_indentation' => true,
    'no_extra_blank_lines' => true,
    'cast_spaces' => [
        'space' => 'none',
    ],
    'class_definition' => true,
    'concat_space' => [
        'spacing' => 'one',
    ],
    'declare_equal_normalize' => true,
    'elseif' => true,
    'encoding' => true,
    'full_opening_tag' => true,
    'function_declaration' => true,
    'type_declaration_spaces' => true,
    'single_line_comment_style' => [
        'comment_types' => ['hash'],
    ],
    'heredoc_to_nowdoc' => true,
    'include' => true,
    'indentation_type' => true,
    'linebreak_after_opening_tag' => true,
    'lowercase_cast' => true,
    'lowercase_keywords' => true,
    'lowercase_static_reference' => true, // added from Symfony
    'magic_constant_casing' => true,
    'magic_method_casing' => true, // added from Symfony
    'method_argument_space' => true,
    'class_attributes_separation' => false,
    'visibility_required' => true,
    'native_function_casing' => true,
    'no_alias_functions' => true,
    'no_blank_lines_after_class_opening' => true,
    'no_blank_lines_after_phpdoc' => true,
    'no_closing_tag' => true,
    'no_empty_phpdoc' => true,
    'no_empty_statement' => true,
    'no_extra_blank_lines' => true,
    'no_leading_import_slash' => true,
    'no_leading_namespace_whitespace' => true,
    'no_multiline_whitespace_around_double_arrow' => true,
    'multiline_whitespace_before_semicolons' => true,
    'no_short_bool_cast' => true,
    'no_singleline_whitespace_before_semicolons' => true,
    'no_spaces_after_function_name' => true,
    'no_spaces_around_offset' => true,
    'spaces_inside_parentheses' => false,
    'no_trailing_comma_in_singleline' => true,
    'no_trailing_whitespace' => true,
    'no_trailing_whitespace_in_comment' => true,
    'no_unneeded_control_parentheses' => true,
    'no_unreachable_default_argument_value' => true,
    'no_useless_return' => true,
    'no_whitespace_before_comma_in_array' => true,
    'no_whitespace_in_blank_line' => true,
    'normalize_index_brace' => true,
    'not_operator_with_successor_space' => false,
    'object_operator_without_whitespace' => true,
    'ordered_imports' => ['sort_algorithm' => 'alpha'],
    'phpdoc_no_access' => true,
    'phpdoc_no_package' => true,
    'phpdoc_no_useless_inheritdoc' => true,
    'phpdoc_scalar' => true,
    'phpdoc_single_line_var_spacing' => true,
    'phpdoc_summary' => true,
    'phpdoc_to_comment' => false,
    'phpdoc_trim' => true,
    'phpdoc_types' => true,
    'phpdoc_var_without_name' => false,
    'increment_style' => ['style' => 'post'],
    'no_mixed_echo_print' => true,
    'self_accessor' => true,
    'array_syntax' => ['syntax' => 'short'],
    'simplified_null_return' => false,
    'single_blank_line_at_eof' => true,
    'single_class_element_per_statement' => true,
    'single_import_per_statement' => true,
    'single_line_after_imports' => true,
    'single_quote' => true,
    'space_after_semicolon' => true,
    'standardize_not_equals' => true,
    'switch_case_semicolon_to_colon' => true,
    'switch_case_space' => true,
    'ternary_operator_spaces' => true,
    'trailing_comma_in_multiline' => true,
    'trim_array_spaces' => true,
    'unary_operator_spaces' => true,
    'line_ending' => true,
    'whitespace_after_comma_in_array' => true,
    'fully_qualified_strict_types' => true,

    // customizations
    'no_unused_imports' => true,
    'logical_operators' => true,
    'short_scalar_cast' => true,
    'no_unset_cast' => true,
    'blank_lines_before_namespace' => true,
];

$finder = Finder::create()
    ->notPath('vendor')
    ->in(getcwd())
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new Config())
    ->setFinder($finder)
    ->setRules($rules)
    ->setRiskyAllowed(true)
    ->setUsingCache(true);
