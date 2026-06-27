<?php

require_once __DIR__ . '/../controllers/auth.controller.php';

logout();

header("Location: /projeto_fullstack/pages/login/login.php");
exit;