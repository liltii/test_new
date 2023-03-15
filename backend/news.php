<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>

<?php
global $APPLICATION;

$APPLICATION->IncludeComponent(
	"timur:simplenews.comp", 
	".default", 
	array(
		"IBLOCK_ID" => "1",
		"PAGE_ELEMENT_COUNT" => "2",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "0",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);
?>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
