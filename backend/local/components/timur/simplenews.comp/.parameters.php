<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arComponentParameters = array(
    "GROUPS" => array(
        "SETTINGS" => array(
            "NAME" => "Данные для компонента",
            "SORT" => 10,
        ),
    ),
	"PARAMETERS" => array(
        "CACHE_TIME" => array("DEFAULT" => "3600"),
        "IBLOCK_ID" => Array(
            "PARENT" => "SETTINGS",
            "NAME" => "ID инфоблока новостей",
            "TYPE" => "STRING",
        ),
        "PAGE_ELEMENT_COUNT" => Array(
            "PARENT" => "SETTINGS",
            "NAME" => "Число новостей на 1-ой странице",
            "TYPE" => "STRING",
        ),
	),

);
