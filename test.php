<?php

require_once 'dbconfig.php';
require_once 'component.php';

echo json_encode(getUserGroupBillPayModeData('2024-12-05', '23A001', 'cash'));