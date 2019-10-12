<ul>
  <li><span>Name: </span><a href="<?php print $url; ?>"><?php print $name; ?></a></li>
  <li><span>Picture: </span><img src="<?php print $field_picture; ?>" height="100"/></li>
  <li><span>Year of creation: </span><?php print $field_year_of_creation; ?></li>
  <li><span>Style: </span><?php print $field_style; ?></li>
  <li><span>Members: </span>
    <table>
      <tr>
        <td>First name</td>
        <td>Last name</td>
        <td>Date of birth</td>
        <td>Joining date</td>
      </tr>
      <?php foreach ($field_members as $member) : ?>
        <tr>
          <td><a href="<?php print $member['url']; ?>"><?php print $member['firstname']; ?></a></td>
          <td><a href="<?php print $member['url']; ?>"><?php print $member['lastname']; ?></a></td>
          <td><?php print $member['field_date_of_birth']; ?></td>
          <td><?php print $member['field_joining_date']; ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </li>
  </li>
  </li>
  <li><span>Official website: </span><?php print $field_official_website; ?></li>
</ul>