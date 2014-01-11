<?php

require_once 'bootstrap.php';

$UserManager->doLogout();
header('Location: index.php');