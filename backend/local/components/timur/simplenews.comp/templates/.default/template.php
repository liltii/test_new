<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}

/** @var array $arResult*/
/** @var array $arParams*/

foreach (array_keys($arResult['YEARS']) as $year) {
    /*
     * p.s.
     * Дубль кода можно потом убрать тернарным выражением
     */
    if ($year == 'all') {
        echo '<a href="/news.php">'.print_r($year ,true).'</a>' . PHP_EOL;
    } else {
        echo '<a href="?year='.$year.'">'.print_r($year ,true).'</a>' . PHP_EOL;
    }
}

foreach ($arResult['ITEMS'] as $item) {
    echo '<pre>'.print_r($item ,true).'</pre>';
}

global $APPLICATION;
$APPLICATION->IncludeComponent(
   "bitrix:main.pagenavigation",
   "",
   array(
      "NAV_OBJECT" => $arResult['NAV'],
      "SEF_MODE" => "N",
   ),
   false
);