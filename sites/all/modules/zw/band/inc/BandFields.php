<?php

class BandFields {
  public static function fields() {
    $field_bases['field_members'] = [
      'active' => 1,
      'cardinality' => -1,
      'deleted' => 0,
      'entity_types' => [],
      'field_name' => 'field_members',
      'indexes' => [
        'target_id' => [
          0 => 'target_id',
        ],
      ],
      'locked' => 0,
      'module' => 'entityreference',
      'settings' => [
        'handler' => 'base',
        'handler_settings' => [
          'sort' => [
            'type' => 'none',
          ],
          'target_bundles' => [
            'member' => 'member',
          ],
        ],
        'target_type' => 'member',
      ],
      'translatable' => 0,
      'type' => 'entityreference',
    ];

    $field_bases['field_official_website'] = [
      'active' => 1,
      'cardinality' => 1,
      'deleted' => 0,
      'entity_types' => [],
      'field_name' => 'field_official_website',
      'indexes' => [
        'format' => [
          0 => 'format',
        ],
      ],
      'locked' => 0,
      'module' => 'text',
      'settings' => [
        'max_length' => 255,
      ],
      'translatable' => 0,
      'type' => 'text',
    ];

    $field_bases['field_picture'] = [
      'active' => 1,
      'cardinality' => 1,
      'deleted' => 0,
      'entity_types' => [],
      'field_name' => 'field_picture',
      'indexes' => [
        'fid' => [
          0 => 'fid',
        ],
      ],
      'locked' => 0,
      'module' => 'image',
      'settings' => [
        'default_image' => 0,
        'uri_scheme' => 'public',
      ],
      'translatable' => 0,
      'type' => 'image',
    ];

    $field_bases['field_style'] = [
      'active' => 1,
      'cardinality' => 1,
      'deleted' => 0,
      'entity_types' => [],
      'field_name' => 'field_style',
      'indexes' => [
        'value' => [
          0 => 'value',
        ],
      ],
      'locked' => 0,
      'module' => 'list',
      'settings' => [
        'allowed_values_function' => '',
      ],
      'translatable' => 0,
      'type' => 'list_text',
    ];

    $field_bases['field_year_of_creation'] = [
      'active' => 1,
      'cardinality' => 1,
      'deleted' => 0,
      'entity_types' => [],
      'field_name' => 'field_year_of_creation',
      'indexes' => [],
      'locked' => 0,
      'module' => 'number',
      'settings' => [],
      'translatable' => 0,
      'type' => 'number_integer',
    ];

    return $field_bases;
  }

  public static function instances() {
    $field_instances['field_members'] = [
      'bundle' => 'band',
      'default_value' => NULL,
      'deleted' => 0,
      'description' => '',
      'display' => [
        'default' => [
          'label' => 'above',
          'module' => 'entityreference',
          'settings' => [
            'bypass_access' => FALSE,
            'link' => FALSE,
          ],
          'type' => 'entityreference_label',
          'weight' => 3,
        ],
        'teaser' => [
          'label' => 'above',
          'settings' => [],
          'type' => 'hidden',
          'weight' => 0,
        ],
      ],
      'entity_type' => 'band',
      'field_name' => 'field_members',
      'label' => 'Members',
      'required' => 0,
      'settings' => [
        'user_register_form' => FALSE,
      ],
      'widget' => [
        'active' => 1,
        'module' => 'options',
        'settings' => [],
        'type' => 'options_select',
        'weight' => 4,
      ],
    ];

    $field_instances['field_official_website'] = [
      'bundle' => 'band',
      'default_value' => NULL,
      'deleted' => 0,
      'description' => '',
      'display' => [
        'default' => [
          'label' => 'above',
          'module' => 'text',
          'settings' => [],
          'type' => 'text_default',
          'weight' => 4,
        ],
        'teaser' => [
          'label' => 'above',
          'settings' => [],
          'type' => 'hidden',
          'weight' => 0,
        ],
      ],
      'entity_type' => 'band',
      'field_name' => 'field_official_website',
      'label' => 'Official website',
      'required' => 0,
      'settings' => [
        'text_processing' => 0,
        'user_register_form' => FALSE,
      ],
      'widget' => [
        'active' => 1,
        'module' => 'text',
        'settings' => [
          'size' => 60,
        ],
        'type' => 'text_textfield',
        'weight' => 5,
      ],
    ];

    $field_instances['field_picture'] = [
      'bundle' => 'band',
      'deleted' => 0,
      'description' => '',
      'display' => [
        'default' => [
          'label' => 'above',
          'module' => 'image',
          'settings' => [
            'image_link' => '',
            'image_style' => '',
          ],
          'type' => 'image',
          'weight' => 0,
        ],
        'teaser' => [
          'label' => 'above',
          'settings' => [],
          'type' => 'hidden',
          'weight' => 0,
        ],
      ],
      'entity_type' => 'band',
      'field_name' => 'field_picture',
      'label' => 'Picture',
      'required' => 0,
      'settings' => [
        'alt_field' => 0,
        'default_image' => 0,
        'file_directory' => '',
        'file_extensions' => 'png gif jpg jpeg',
        'max_filesize' => '',
        'max_resolution' => '',
        'min_resolution' => '',
        'title_field' => 0,
        'user_register_form' => FALSE,
      ],
      'widget' => [
        'active' => 1,
        'module' => 'image',
        'settings' => [
          'preview_image_style' => 'thumbnail',
          'progress_indicator' => 'throbber',
        ],
        'type' => 'image_image',
        'weight' => 1,
      ],
    ];

    $field_instances['field_style'] = [
      'bundle' => 'band',
      'default_value' => NULL,
      'deleted' => 0,
      'description' => '',
      'display' => [
        'default' => [
          'label' => 'above',
          'module' => 'list',
          'settings' => [],
          'type' => 'list_default',
          'weight' => 2,
        ],
        'teaser' => [
          'label' => 'above',
          'settings' => [],
          'type' => 'hidden',
          'weight' => 0,
        ],
      ],
      'entity_type' => 'band',
      'field_name' => 'field_style',
      'label' => 'Style',
      'required' => 0,
      'settings' => [
        'user_register_form' => FALSE,
      ],
      'widget' => [
        'active' => 1,
        'module' => 'options',
        'settings' => [],
        'type' => 'options_select',
        'weight' => 3,
      ],
    ];

    $field_instances['field_year_of_creation'] = [
      'bundle' => 'band',
      'default_value' => NULL,
      'deleted' => 0,
      'description' => '',
      'display' => [
        'default' => [
          'label' => 'above',
          'module' => 'number',
          'settings' => [
            'decimal_separator' => '.',
            'prefix_suffix' => TRUE,
            'scale' => 0,
            'thousand_separator' => '',
          ],
          'type' => 'number_integer',
          'weight' => 1,
        ],
        'teaser' => [
          'label' => 'above',
          'settings' => [],
          'type' => 'hidden',
          'weight' => 0,
        ],
      ],
      'entity_type' => 'band',
      'field_name' => 'field_year_of_creation',
      'label' => 'Year of creation',
      'required' => 0,
      'settings' => [
        'max' => 9999,
        'min' => 1000,
        'prefix' => '',
        'suffix' => '',
        'user_register_form' => FALSE,
      ],
      'widget' => [
        'active' => 0,
        'module' => 'number',
        'settings' => [],
        'type' => 'number',
        'weight' => 2,
      ],
    ];

    return $field_instances;
  }

}