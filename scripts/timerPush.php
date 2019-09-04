<?php

require_once __DIR__ . "/common.php";

$app->getDispatcher()->dispatch(new Yaf_Request_Simple("timerPush", "index", "push", "timerPush"));