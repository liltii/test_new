<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arComponentDescription = array(
    "NAME" => Loc::getMessage("TEST_NAME"),
    "PATH" => array(
        "ID" => "simplenews",
        "NAME" => Loc::getMessage("TEST_CHILD"),
    ),
);
