<form action="" method="POST">
  Select images: <input type="file" name="img[]" multiple>
  <input type="submit">
</form>

<?php
    $temp = $_POST['img'];
    echo "<pre>";
    var_dump($temp);
?>