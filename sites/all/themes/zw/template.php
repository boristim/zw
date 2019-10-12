<?php

function zw_preprocess_entity(&$variables) {

  if ($variables['element'] instanceof EntityMember) {
    $variables['firstname'] = $variables['element']->firstname;
    $variables['lastname'] = $variables['element']->lastname;
    $variables['field_date_of_birth'] = format_date(strtotime($variables['element']->field_date_of_birth[LANGUAGE_NONE][0]['value']));
    $variables['field_joining_date'] = format_date(strtotime($variables['element']->field_joining_date[LANGUAGE_NONE][0]['value']));
    $variables['url'] = url('member/' . $variables['element']->id);
  }

  if ($variables['element'] instanceof EntityBand) {
    $variables['name'] = $variables['element']->name;
    $variables['field_picture'] = file_create_url($variables['element']->field_picture[LANGUAGE_NONE][0]['uri']);
    $variables['field_year_of_creation'] = $variables['element']->field_year_of_creation[LANGUAGE_NONE][0]['value'];
    $variables['field_style'] = $variables['element']->field_style[LANGUAGE_NONE][0]['value'];
    $members = [];
    if (count($variables['element']->field_members[LANGUAGE_NONE])) {
      $members = [];
      foreach ($variables['element']->field_members[LANGUAGE_NONE] as $member) {
        $tmp['element'] = $member['entity'];
        zw_preprocess_entity($tmp);
        $members[] = $tmp;
      }
    }
    $variables['field_members'] = $members;
    $variables['field_official_website'] = $variables['element']->field_official_website[LANGUAGE_NONE][0]['value'];
    $variables['url'] = url('bands/' . $variables['element']->id);
  }
}