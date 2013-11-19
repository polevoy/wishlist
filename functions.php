<?php
  $server = 'localhost';
  $login = 'root';
  $password = 'root';
  $db = 'Wishlist';

  function get_mysqli() {
  	$mysqli = new mysqli("localhost", "root", "root", "Wishlist");
	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	return $mysqli;
}
?>