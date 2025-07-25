<?php
session_start();
session_regenerate_id(true); // Optional, but good security
session_destroy();
header("Location: /TSP-System/ticketing-system/");
exit;
