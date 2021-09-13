<?php
require __DIR__ .'\database\validation.php';
?>
<!DOCTYPE html>
<html lang='en' dir='ltr'>
  <head>
      <meta name = 'viewport' content = 'width = device-width, initial-scale = 1.0'>
    <meta charset='utf-8'>
    <title>XKCD</title>   <!-- Title of the page-->
    <link rel='stylesheet' href='style.css'>
  </head>
  <body>
    <div class='center'>
      <h1>XKCD COMICS</h1>    <!-- Heading of the page -->
      <form action='index.php' method='post'>   <!-- Form -->
        <div class='txt_field'>
          <input type='email' name = 'email' value='<?php echo $email; ?>' required>   <!-- Email field -->
          <label>Email</label>    <!-- Label -->
        </div>
        <div class='error'>
            <?php echo $error; ?>
        </div>      <!-- Displays error, if any -->
        <input type='submit' name = 'submit' value='Subscribe'>     <!-- Subscribe button -->
      </form>   <!-- End of form -->
    </div>

  </body> <!-- End of body -->
</html>
