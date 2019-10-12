<?php

class BandUIController extends EntityDefaultUIController {

}

function band_form($band, $form_state) {

  $form['name'] = [
    '#type' => 'textfield',
    '#title' => t('Band name'),
    '#required' => TRUE,
    '#weight' => 0
  ];

  $form['field_picture'] = [
    '#type' => 'managed_file',
    '#title' => t('Picture'),
    '#required' => FALSE,
    '#weight' => 1,
    '#upload_validators' => array('file_validate_extensions' => array('jpeg jpg png gif')),
    '#progress_indicator' => 'throbber',
    '#upload_location' => 'public://bands'
  ];

  $form['field_year_of_creation'] = [
    '#type' => 'textfield',
    '#attributes' => array(
      ' type' => 'number',
      ' min' => 1900,
      ' max' => date('Y'),
    ),
    '#maxlength' => 4,
    '#default_value' => date('Y'),
    '#title' => t('Year of creation'),
    '#required' => TRUE,
    '#weight' => 2
  ];

  $form['field_style'] = [
    '#type' => 'select',
    '#title' => t('Style'),
    // Maybe load options from taxononmy
    '#options' => _band_styles(),
    '#required' => FALSE,
    '#weight' => 3
  ];

  $form['field_members'] = [
    '#type' => 'checkboxes',
    '#description' => l(t('Create more members'), 'admin/content/members/add'),
    '#title' => t('Members'),
    '#options' => _band_members_list(),
    '#weight' => 4,
  ];

  $form['field_official_website'] = [
    '#type' => 'textfield',
    '#title' => t('Official website'),
    '#required' => FALSE,
    '#weight' => 5
  ];

  $form['actions'] = ['#type' => 'actions'];
  $form['actions']['submit'] = [
    '#type' => 'submit',
    '#value' => t('Save band'),
    '#weight' => 6,
  ];

  if ($form_state['op'] == 'edit') {
    $band = $form_state['band'];
    $form['name']['#default_value'] = $band->name;
    $form['field_picture']['#default_value'] = $band->field_picture[LANGUAGE_NONE][0]['fid'];
    $form['field_year_of_creation']['#default_value'] = $band->field_year_of_creation[LANGUAGE_NONE][0]['value'];
    $form['field_style']['#default_value'] = $band->field_style[LANGUAGE_NONE][0]['value'];
    if (count($band->field_members[LANGUAGE_NONE])) {
      $default_members = [];
      foreach ($band->field_members[LANGUAGE_NONE] as $npp => $val) {
        $default_members[$val['target_id']] = $val['target_id'];
      }
      $form['field_members']['#default_value'] = $default_members;
    }
    $form['field_official_website']['#default_value'] = $band->field_official_website[LANGUAGE_NONE][0]['value'];
  }

  return $form;
}

function band_form_submit($form, &$form_state) {

  $band = entity_ui_form_submit_build_entity($form, $form_state);

  if ($band instanceof EntityBand) {
    if (!empty($form_state['values']['field_picture'])) {
      $picture = file_load($form_state['values']['field_picture']);
      $picture->status = FILE_STATUS_PERMANENT;
      file_save($picture);
      file_usage_add($picture, 'user', 'user', $GLOBALS['user']->uid);
      $band->field_picture[LANGUAGE_NONE][0] = (array)$picture;
    }
    else {
      $band->field_picture = [];
    }
    $band->field_members = [];
    if (count($form_state['values']['field_members'])) {
      foreach ($form_state['values']['field_members'] as $id => $val) {
        if ($id == $val) {
          $band->field_members[LANGUAGE_NONE][]['target_id'] = $id;
        }
      }
    }
    $band->field_year_of_creation = [LANGUAGE_NONE => [0 => ['value' => (int)$form_state['values']['field_year_of_creation']]]];
    $band->field_style = [LANGUAGE_NONE => [0 => ['value' => $form_state['values']['field_style']]]];
    $band->field_official_website = [LANGUAGE_NONE => [0 => ['value' => $form_state['values']['field_official_website']]]];
    $band->save();
    $form_state['redirect'] = 'admin/content/bands';
  }
}