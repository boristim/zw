<?php

class MemberUIController extends EntityDefaultUIController {

}

function member_form($member, $form_state) {
  $form['firstname'] = [
    '#type' => 'textfield',
    '#title' => t('First name'),
    '#required' => TRUE,
    '#weight' => 10
  ];
  $form['lastname'] = [
    '#type' => 'textfield',
    '#title' => t('Last name'),
    '#required' => TRUE,
    '#weight' => 20
  ];
  //$form += field_default_form('member', $form_state['member'], MemberFields::fields()['field_date_of_birth'], MemberFields::instances()['field_date_of_birth'], LANGUAGE_NONE, NULL, $form, $form_state);
  //$form += field_default_form('member', $form_state['member'], MemberFields::fields()['field_joining_date'], MemberFields::instances()['field_joining_date'], LANGUAGE_NONE, NULL, $form, $form_state);
  $form['field_date_of_birth'] = [
    '#type' => 'date',
    '#title' => t('Date of birth'),
    '#required' => FALSE,
    '#weight' => 30
  ];
  $form['field_joining_date'] = [
    '#type' => 'date',
    '#title' => t('Joining date'),
    '#required' => FALSE,
    '#weight' => 40
  ];

  $form['actions'] = ['#type' => 'actions'];
  $form['actions']['submit'] = [
    '#type' => 'submit',
    '#value' => t('Save member'),
    '#weight' => 50,
  ];
  if ($form_state['op'] == 'edit') {
    $form['firstname']['#default_value'] = $form_state['member']->firstname;
    $form['lastname']['#default_value'] = $form_state['member']->lastname;
    list($y, $m, $d) = sscanf($form_state['member']->field_date_of_birth[LANGUAGE_NONE][0]['value'], '%4d-%2d-%2d');
    $form['field_date_of_birth']['#default_value'] = ['year' => $y, 'month' => $m, 'day' => $d];
    list($y, $m, $d) = sscanf($form_state['member']->field_joining_date[LANGUAGE_NONE][0]['value'], '%4d-%2d-%2d');
    $form['field_joining_date']['#default_value'] = ['year' => $y, 'month' => $m, 'day' => $d];
  }

  return $form;
}

function member_form_submit(&$form, &$form_state) {
  $member = entity_ui_form_submit_build_entity($form, $form_state);
  if ($member instanceof EntityMember) {
    $dt = $form_state['values']['field_date_of_birth'];
    $member->field_date_of_birth = [LANGUAGE_NONE => [0 => ['value' => $dt['year'] . '-' . $dt['month'] . '-' . $dt['day']]]];
    $dt = $form_state['values']['field_joining_date'];
    $member->field_joining_date = [LANGUAGE_NONE => [0 => ['value' => $dt['year'] . '-' . $dt['month'] . '-' . $dt['day']]]];
    $member->save();
    $form_state['redirect'] = 'admin/content/members';
  }
}

