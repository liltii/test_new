<?php

AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "clearSiteCacheByTag");

function clearSiteCacheByTag(&$arFields) {
    if ($arFields['IBLOCK_ID'] == 1 && \Bitrix\Main\Config\Option::get('iblock', 'simple_news_component_cache_a') == 'on') {
        $taggedCache = \Bitrix\Main\Application::getInstance()->getTaggedCache();
        $taggedCache->clearByTag('iblock_id_1');
    }
}