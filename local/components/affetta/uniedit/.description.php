<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Контейнер для форм",
	"DESCRIPTION" => "Не имеет входных параметров. Требует uniform из JS-файла",
	"ICON" => "/images/sections_top_count.gif",
	"CACHE_PATH" => "Y",
	"SORT" => 20,
	"PATH" => array(
		"ID" => "iarga",
		"NAME" => "iarga",
		"CHILD" => array(
			"ID" => "iarga_uniedit",
			"NAME" => 'Контейнер для форм',
			"SORT" => 30,			
		),
	),
);

?>