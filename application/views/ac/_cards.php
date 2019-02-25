<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<html>
   <head>
      <meta charset="utf-8"/>
      <link rel="stylesheet" href="/css/tables.css" />
      <script src="https://code.jquery.com/jquery-1.12.4.min.js" crossorigin=anonymous integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="></script>
      <script src="/js/ac/cards.js"></script>
   </head>
   <body>
      <a href="/auth/logout"><img id="exit_button" src="/img/exit_button.jpg"></a>
      <?php echo $table; ?>
   </body>
</html>
