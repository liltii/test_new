<?php

use Bitrix\Iblock\Elements\ElementNewsTable as News;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\Web\Uri;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}

Loader::includeModule('iblock');

class SimpleNewsComponent extends \CBitrixComponent
{
    public function onPrepareComponentParams($params)
    {
        parent::onPrepareComponentParams($params);

        if (!empty($params['IBLOCK_ID'])) {
            $params['IBLOCK_ID'] = intval($params['IBLOCK_ID']);
        } else {
            ShowError(Loc::getMessage('IBLOCK_ID_FOUND'));
            return;
        }

        $params['CACHE_TIME'] = intval($params['CACHE_TIME']);
        $params['PAGE_ELEMENT_COUNT'] = intval($params['PAGE_ELEMENT_COUNT']);

        if ($params['CACHE_TYPE'] == 'A') {
            \Bitrix\Main\Config\Option::set('iblock', 'simple_news_component_cache_a', 'on');
        } else {
            \Bitrix\Main\Config\Option::set('iblock', 'simple_news_component_cache_a', 'off');
        }

        if ($params['CACHE_TYPE'] == 'N') {
            $params['CACHE_TIME'] = 0;
        }

        $params['PAGE'] = $_REQUEST['page'] ?: 'page-1';

        $params['YEAR'] = $_REQUEST['year'] ?: 'all';

        return $params;
    }

    public function executeComponent(): void
    {
        $cache = \Bitrix\Main\Data\Cache::createInstance();
        $taggedCache = Application::getInstance()->getTaggedCache();

        $this->arResult = [];

        if ($cache->initCache($this->arParams['CACHE_TIME'], "simple_news_component_" . serialize($this->arParams), "/iblock/news")) {
            $this->arResult = $cache->getVars();
        } elseif ($cache->startDataCache()) {

            $taggedCache->startTagCache('news_new');
            $taggedCache->registerTag('iblock_id_1');

            $nav = new \Bitrix\Main\UI\PageNavigation("page");
            $nav->allowAllRecords(true)
                ->setPageSize($this->arParams['PAGE_ELEMENT_COUNT'])
                ->initFromUri();

            $filter = ['ACTIVE' => 'Y'];

            $this->arResult['YEARS'] = $this->getYearsAllNews();

            if ($this->arParams['YEAR'] != 'all') {
                $filterDate = [
                    'ID' => $this->arResult['YEARS'][$this->arParams['YEAR']],
                    [
                        'LOGIC' => 'AND',
                        ['>=ACTIVE_FROM' => $this->getDateByYear($this->arParams['YEAR'], 'start')],
                        ['<=ACTIVE_FROM' => $this->getDateByYear($this->arParams['YEAR'], 'end')],
                    ],
                ];
                $filter = array_merge($filter, $filterDate);
            }

            $rsNews = News::getList([
                'filter' => $filter,
                'order' => ['ACTIVE_FROM' => 'DESC'],
                'select' => ['NAME', 'ACTIVE_FROM', 'PREVIEW_TEXT', 'PREVIEW_PICTURE'], // P.S. PREVIEW_PICTURE через СFile::GetPath вытащить потом можно
                "count_total" => true,
                "offset" => $nav->getOffset(),
                "limit" => $nav->getLimit(),
            ]);

            $this->setTitle($rsNews->getSelectedRowsCount());

            $nav->setRecordCount($rsNews->getCount());

            $this->arResult['NAV'] = $nav;


            while ($news = $rsNews->fetch()) {
                $this->arResult['ITEMS'][] = $news;
            }

            $taggedCache->endTagCache();
            $cache->endDataCache($this->arResult);
        }

        $this->includeComponentTemplate();
    }

    private function setTitle($getCount): void
    {
        global $APPLICATION;

        $APPLICATION->SetTitle("Список новостей (" . $getCount . " шт.)");
    }

    private function getYearsAllNews()
    {
        $arYears = [];

        $rsNews = News::getList([
            'order' => ['ACTIVE_FROM' => 'DESC'],
            'select' => ['ID', 'ACTIVE_FROM'],
            "count_total" => true,
            "cache" => ['ttl' => 86000] // Закэшируем запрос
        ]);
        $arYears['all'] = 'все';
        while ($news = $rsNews->fetch()) {
            $year = ConvertDateTime($news['ACTIVE_FROM'], "YYYY", "ru");
            $arYears[$year][] = $news['ID'];
        }

        return $arYears;
    }

    private function getDateByYear(string $year, string $point)
    {
        if ($point == 'start') {
            return date('d.m.Y 00:00:00', strtotime("01/01/{$year}"));
        } else {
            return date('d.m.Y 23:59:59', strtotime("12/01/{$year}"));
        }
    }
}