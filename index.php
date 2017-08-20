<?php

/*
function autoload_class ( $namespace_class ){

  // Adapt to OS. Windows uses '\' as directory separator, linux uses '/'
  $path_file = str_replace( '\\', DIRECTORY_SEPARATOR, $namespace_class );

  // Get the autoload extentions in an array
  $autoload_extensions = explode( ',', spl_autoload_extensions() );

  // Loop over the extensions and load accordingly
  foreach( $autoload_extensions as $autoload_extension ){
      include_once( $path_file . $autoload_extension );
  }

}

// Setting the path (I use linux) so our includes work.
set_include_path( get_include_path() . PATH_SEPARATOR . './' );

spl_autoload_extensions(".php"); // To make sure spl only includes php-files while autoloading.
spl_autoload_register(autoload_class);

*/

spl_autoload_extensions(".php"); // To make sure spl only includes php-files while autoloading.
spl_autoload_register();

use App\Controllers\UserController;

$user  = new UserController();
echo "<pre>";
print_r($user);
echo "</pre>";