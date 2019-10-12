<?php

class MemberFields {
  public static function fields() {

    $field_bases['field_date_of_birth'] = [
      'active' => 1,
      'cardinality' => 1,
      'deleted' => 0,
      'entity_types' => [],
      'field_name' => 'field_date_of_birth',
      'indexes' => [],
      'locked' => 0,
      'module' => 'date',
      'settings' => [
        'cache_count' => 4,
        'cache_enabled' => 0,
        'granularity' => [
          'day' => 'day',
          'hour' => 0,
          'minute' => 0,
          'month' => 'month',
          'second' => 0,
          'year' => 'year',
        ],
        'timezone_db' => '',
        'todate' => '',
        'tz_handling' => 'none',
      ],
      'translatable' => 0,
      'type' => 'datetime',
    ];

    $field_bases['field_joining_date'] = [
      'active' => 1,
      'cardinality' => 1,
      'deleted' => 0,
      'entity_types' => [],
      'field_name' => 'field_joining_date',
      'indexes' => [],
      'locked' => 0,
      'module' => 'date',
      'settings' => [
        'cache_count' => 4,
        'cache_enabled' => 0,
        'granularity' => [
          'day' => 'day',
          'hour' => 0,
          'minute' => 0,
          'month' => 'month',
          'second' => 0,
          'year' => 'year',
        ],
        'timezone_db' => '',
        'todate' => '',
        'tz_handling' => 'none',
      ],
      'translatable' => 0,
      'type' => 'datetime',
    ];

    return $field_bases;
  }

  public static function instances() {
    $field_instances['field_date_of_birth'] = [
      'bundle' => 'member',
      'deleted' => 0,
      'description' => '',
      'display' => [
        'default' => [
          'label' => 'above',
          'module' => 'date',
          'settings' => [
            'format_type' => 'long',
            'fromto' => 'both',
            'multiple_from' => '',
            'multiple_number' => '',
            'multiple_to' => '',
            'show_remaining_days' => FALSE,
          ],
          'type' => 'date_default',
          'weight' => 0,
        ],
        'teaser' => [
          'label' => 'above',
          'settings' => [],
          'type' => 'hidden',
          'weight' => 0,
        ],
      ],
      'entity_type' => 'member',
      'field_name' => 'field_date_of_birth',
      'label' => 'Date of birth',
      'required' => 0,
      'settings' => [
        'default_value' => 'now',
        'default_value2' => 'same',
        'default_value_code' => '',
        'default_value_code2' => '',
        'user_register_form' => FALSE,
      ],
      'widget' => [
        'active' => 1,
        'module' => 'date',
        'settings' => [
          'increment' => 15,
          'input_format' => 'm/d/Y - H:i:s',
          'input_format_custom' => '',
          'label_position' => 'above',
          'no_fieldset' => 0,
          'text_parts' => [],
          'year_range' => '-3:+3',
        ],
        'type' => 'date_popup',
        'weight' => 10,
      ],
    ];

    $field_instances['field_joining_date'] = [
      'bundle' => 'member',
      'deleted' => 0,
      'description' => '',
      'display' => [
        'default' => [
          'label' => 'above',
          'module' => 'date',
          'settings' => [
            'format_type' => 'long',
            'fromto' => 'both',
            'multiple_from' => '',
            'multiple_number' => '',
            'multiple_to' => '',
            'show_remaining_days' => FALSE,
          ],
          'type' => 'date_default',
          'weight' => 1,
        ],
        'teaser' => [
          'label' => 'above',
          'settings' => [],
          'type' => 'hidden',
          'weight' => 0,
        ],
      ],
      'entity_type' => 'member',
      'field_name' => 'field_joining_date',
      'label' => 'Joining date',
      'required' => 0,
      'settings' => [
        'default_value' => 'now',
        'default_value2' => 'same',
        'default_value_code' => '',
        'default_value_code2' => '',
        'user_register_form' => FALSE,
      ],
      'widget' => [
        'active' => 1,
        'module' => 'date',
        'settings' => [
          'increment' => 15,
          'input_format' => 'm/d/Y - H:i:s',
          'input_format_custom' => '',
          'label_position' => 'above',
          'no_fieldset' => 0,
          'text_parts' => [],
          'year_range' => '-3:+3',
        ],
        'type' => 'date_popup',
        'weight' => 20,
      ],
    ];

    return $field_instances;
  }
}