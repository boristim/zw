<?php

/**
 * @file
 * Provides API functions around hook_entity_property_info(). Also see
 * entity.info.inc, which cares for providing entity property info for all core
 * entity types.
 */

/**
 * Get the entity property info array of an entity type.
 *
 * @param $entity_type
 *   The entity type, e.g. node, for which the info shall be returned, or NULL
 *   to return an array with info about all types.
 *
 * @see hook_entity_property_info()
 * @see hook_entity_property_info_alter()
 */
function entity_get_property_info($entity_type = NULL) {
  // Use the advanced drupal_static() pattern, since this is called very often.
  static $drupal_static_fast;
  if (!isset($drupal_static_fast)) {
    $drupal_static_fast['info'] = &drupal_static(__FUNCTION__);
  }
  $info = &$drupal_static_fast['info'];

  // hook_entity_property_info() includes translated strings, so each language
  // is cached separately.
  $langcode = $GLOBALS['language']->language;

  if (empty($info)) {
    if ($cache = cache_get("entity_property_info:$langcode")) {
      $info = $cache->data;
    }
    else {
      $info = module_invoke_all('entity_property_info');
      // Let other modules alter the entity info.
      drupal_alter('entity_property_info', $info);
      cache_set("entity_property_info:$langcode", $info);
    }
  }
  return empty($entity_type) ? $info : (isset($info[$entity_type]) ? $info[$entity_type] : array());
}

/**
 * Returns the default information for an entity property.
 *
 * @return
 *   An array of optional property information keys mapped to their defaults.
 *
 * @see hook_entity_property_info()
 */
function entity_property_info_defaults() {
  return array(
    'type' => 'text',
    'getter callback' => 'entity_property_verbatim_get',
  );
}

/**
 * Gets an array of info about all properties of a given entity type.
 *
 * In contrast to entity_get_property_info(), this function returns info about
 * all properties the entity might have, thus it adds an all properties assigned
 * to entity bundles.
 *
 * @param $entity_type
 *   (optiona) The entity type to return properties for.
 *
 * @return
 *   An array of info about properties. If the type is omitted, all known
 *   properties are returned.
 */
function entity_get_all_property_info($entity_type = NULL) {
  if (!isset($entity_type)) {
    // Retrieve all known properties.
    $properties = array();
    foreach (entity_get_info() as $entity_type => $info) {
      $properties += entity_get_all_property_info($entity_type);
    }
    return $properties;
  }
  // Else retrieve the properties of the given entity type only.
  $info = entity_get_property_info($entity_type);
  $info += array('properties' => array(), 'bundles' => array());
  // Add all bundle properties.
  foreach ($info['bundles'] as $bundle => $bundle_info) {
    $bundle_info += array('properties' => array());
    $info['properties'] += $bundle_info['properties'];
  }
  return $info['properties'];
}

/**
 * Queries for entities having the given property value.
 *
 * @param $entity_type
 *   The type of the entity.
 * @param $property
 *   The name of the property to query for.
 * @param $value
 *   A single property value or an array of possible values to query for.
 * @param $limit
 *   Limit the numer of results. Defaults to 30.
 *
 * @return
 *   An array of entity ids or NULL if there is no information how to query for
 *   the given property.
 */
function entity_property_query($entity_type, $property, $value, $limit = 30) {
  $properties = entity_get_all_property_info($entity_type);
  $info = $properties[$property] + array('type' => 'text', 'queryable' => !empty($properties[$property]['schema field']));

  // We still support the deprecated query callback, so just add in EFQ-based
  // callbacks in case 'queryable' is set to TRUE and make use of the callback.
  if ($info['queryable'] && empty($info['query callback'])) {
    $info['query callback'] = !empty($info['field']) ? 'entity_metadata_field_query' : 'entity_metadata_table_query';
  }

  $type = $info['type'];
  // Make sure an entity or a list of entities are passed on as identifiers
  // with the help of the wrappers. For that ensure the data type matches the
  // passed on value(s).
  if (is_array($value) && !entity_property_list_extract_type($type)) {
    $type = 'list<' . $type . '>';
  }
  elseif (!is_array($value) && entity_property_list_extract_type($type)) {
    $type = entity_property_list_extract_type($type);
  }

  $wrapper = entity_metadata_wrapper($type, $value);
  $value = $wrapper->value(array('identifier' => TRUE));

  if (!empty($info['query callback'])) {
    return $info['query callback']($entity_type, $property, $value, $limit);
  }
}

/**
 * Resets the cached information of hook_entity_property_info().
 */
function entity_property_info_cache_clear() {
  drupal_static_reset('entity_get_property_info');
  // Clear all languages.
  cache_clear_all('entity_property_info:', 'cache', TRUE);
}

/**
 * Implements hook_hook_info().
 */
function entity_hook_info() {
  $hook_info['entity_property_info'] = array(
    'group' => 'info',
  );
  $hook_info['entity_property_info_alter'] = array(
    'group' => 'info',
  );
  return $hook_info;
}

/**
 * Implements hook_field_info_alter().
 * Defines default property types for core field types.
 */
function entity_field_info_alter(&$field_info) {
  if (module_exists('number')) {
    $field_info['number_integer']['property_type'] = 'integer';
    $field_info['number_decimal']['property_type'] = 'decimal';
    $field_info['number_float']['property_type'] = 'decimal';
  }
  if (module_exists('text')) {
    $field_info['text']['property_type'] = 'text';
    $field_info['text']['property_callbacks'][] = 'entity_metadata_field_text_property_callback';
    $field_info['text_long']['property_type'] = 'text';
    $field_info['text_long']['property_callbacks'][] = 'entity_metadata_field_text_property_callback';
    $field_info['text_with_summary']['property_type'] = 'field_item_textsummary';
    $field_info['text_with_summary']['property_callbacks'][] = 'entity_metadata_field_text_property_callback';
  }
  if (module_exists('list')) {
    $field_info['list_integer']['property_type'] = 'integer';
    $field_info['list_boolean']['property_type'] = 'boolean';
    $field_info['list_float']['property_type'] = 'decimal';
    $field_info['list_text']['property_type'] = 'text';
  }
  if (module_exists('taxonomy')) {
    $field_info['taxonomy_term_reference']['property_type'] = 'taxonomy_term';
    $field_info['taxonomy_term_reference']['property_callbacks'][] = 'entity_metadata_field_term_reference_callback';
  }
  if (module_exists('file')) {
    // The callback specifies a custom data structure matching the file field
    // items. We introduce a custom type name for this data structure.
    $field_info['file']['property_type'] = 'field_item_file';
    $field_info['file']['property_callbacks'][] = 'entity_metadata_field_file_callback';
  }
  if (module_exists('image')) {
    // The callback specifies a custom data structure matching the image field
    // items. We introduce a custom type name for this data structure.
    $field_info['image']['property_type'] = 'field_item_image';
    $field_info['image']['property_callbacks'][] = 'entity_metadata_field_file_callback';
    $field_info['image']['property_callbacks'][] = 'entity_metadata_field_image_callback';
  }
}

/**
 * Implements hook_field_create_instance().
 * Clear the cache when a field instance changed.
 */
function entity_field_create_instance() {
  entity_property_info_cache_clear();
}

/**
 * Implements hook_field_delete_instance().
 * Clear the cache when a field instance changed.
 */
function entity_field_delete_instance() {
  entity_property_info_cache_clear();
}

/**
 * Implements hook_field_update_instance().
 * Clear the cache when a field instance changed.
 */
function entity_field_update_instance() {
  entity_property_info_cache_clear();
}

/**
 * Verifies that the given data can be safely used as the given type regardless
 * of the PHP variable type of $data. Example: the string "15" is a valid
 * integer, but "15nodes" is not.
 *
 * @return
 *   Whether the data is valid for the given type.
 */
function entity_property_verify_data_type($data, $type) {
  // As this may be called very often statically cache the entity info using
  // the fast pattern.
  static $drupal_static_fast;
  if (!isset($drupal_static_fast)) {
    // Make use of the same static as entity info.
    entity_get_info();
    $drupal_static_fast['entity_info'] = &drupal_static('entity_get_info');
  }
  $info = &$drupal_static_fast['entity_info'];

  // First off check for entities, which may be represented by their ids too.
  if (isset($info[$type])) {
    if (is_object($data)) {
      return TRUE;
    }
    elseif (isset($info[$type]['entity keys']['name'])) {
      // Read the data type of the name key from the metadata if available.
      $key = $info[$type]['entity keys']['name'];
      $property_info = entity_get_property_info($type);
      $property_type = isset($property_info['properties'][$key]['type']) ? $property_info['properties'][$key]['type'] : 'token';
      return entity_property_verify_data_type($data, $property_type);
    }
    return entity_property_verify_data_type($data, empty($info[$type]['fieldable']) ? 'text' : 'integer');
  }

  switch ($type) {
    case 'site':
    case 'unknown':
      return TRUE;
    case 'date':
    case 'duration':
    case 'integer':
      return is_numeric($data) && strpos($data, '.') === FALSE;
    case 'decimal':
      return is_numeric($data);
    case 'text':
      return is_scalar($data);
    case 'token':
      return is_scalar($data) && preg_match('!^[a-z][a-z0-9_]*$!', $data);
    case 'boolean':
      return is_scalar($data) && (is_bool($data) || $data == 0 || $data == 1);
    case 'uri':
      return valid_url($data, TRUE);
    case 'list':
      return (is_array($data) && array_values($data) == $data) || (is_object($data) && $data instanceof EntityMetadataArrayObject);
    case 'entity':
      return is_object($data) && $data instanceof EntityDrupalWrapper;
    default:
    case 'struct':
      return is_object($data) || is_array($data);
  }
}

/**
 * Creates the entity object for an array of given property values.
 *
 * @param $entity_type
 *   The entity type to create an entity for.
 * @param $values
 *   An array of values as described by the entity's property info. All entity
 *   properties of the given entity type that are marked as required, must be
 *   present.
 *   If the passed values have no matching property, their value will be
 *   assigned to the entity directly, without the use of the metadata-wrapper
 *   property.
 *
 * @return EntityDrupalWrapper
 *   An EntityDrupalWrapper wrapping the newly created entity or FALSE, if
 *   there were no information how to create the entity.
 */
function entity_property_values_create_entity($entity_type, $values = array()) {
  if (entity_type_supports($entity_type, 'create')) {
    $info = entity_get_info($entity_type);
    // Create the initial entity by passing the values for all 'entity keys'
    // to entity_create().
    $entity_keys = array_filter($info['entity keys']);
    $creation_values = array_intersect_key($values, array_flip($entity_keys));

    // In case the bundle key does not match the property that sets it, ensure
    // the bundle key is initialized somehow, so entity_extract_ids()
    // does not bail out during wrapper creation.
    if (!empty($info['entity keys']['bundle'])) {
      $creation_values += array($info['entity keys']['bundle'] => FALSE);
    }
    $entity = entity_create($entity_type, $creation_values);

    // Now set the remaining values using the wrapper.
    $wrapper = entity_metadata_wrapper($entity_type, $entity);
    foreach ($values as $key => $value) {
      if (!in_array($key, $info['entity keys'])) {
        if (isset($wrapper->$key)) {
          $wrapper->$key->set($value);
        }
        else {
          $entity->$key = $value;
        }
      }
    }
    // @todo: Once we require Drupal 7.7 or later, verify the entity has
    // now a valid bundle and throw the EntityMalformedException if not.
    return $wrapper;
  }
  return FALSE;
}


/**
 * Extr                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     p cšpĐ!
ų$rity_level";i:5;}http://zw.local/member/1127.0.0.1Û§D 	  3   Ā    ĸĀ    php%type: !message in %function (line %line of %file).a:6:{s:5:"%type";s:6:"Notice";s:8:"!message";s:25:"Undefined index: elements";s:9:"%function";s:28:"template_preprocess_entity()";s:5:"%file";s:64:"D:\Www\htdocs\zw\sites\all\modules\entity\theme\entity.theme.inc";s:5:"%line";i:168;s:14:"severity_level";i:5;}http://zw.local/member/1127.0.0.1Û§D 	  3  Č     Á    php%type: !message in %function (line %line of %file).a:6:{s:5:"%type";s:6:"Notice";s:8:"!message";s:25:"Undefined index: elements";s:9:"%function";s:28:"template_preprocess_entity()";s:5:"%file";s:64:"D:\Www\htdocs\zw\sites\all\modules\entity\theme\entity.theme.inc";s:5:"%line";i:169;s:14:"severity_level";i:5;}http://zw.local/member/1127.0.0.1Û§D 	  ã3   Ðb    Â    php%type: !message in %function (line %line of %file).a:6:{s:5:"%type";s:6:"Notice";s:8:"!message";s:28:"Undefined index: entity keys";s:9:"%function";s:20:"entity_extract_ids()";s:5:"%file";s:36:"D:\Www\htdocs\zw\includes\common.inc";s:5:"%line";i:7927;s:14:"severity_level";i:5;}http