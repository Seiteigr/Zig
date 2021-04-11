<?php
namespace System\HtmlComponents\Select;

class Select
{
  public static function option($data, $fieldData, $selected, $name)
  {
    foreach ($data as $content) {
      if ($selected && $content->$fieldData == $selected) {
        echo '<option value="'.$content->$fieldData.'" selected="selected">';
          echo $content->$name;
        echo '</option>';
      } else {
        echo '<option value="'.$content->$fieldData.'">';
          echo $content->$name;
        echo '</option>';
      }
    }
  }
}
