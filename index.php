<?php

ini_set('max_execution_time',0);
ini_set('memory_limit', '512M');
date_default_timezone_set('UTC');

// Подключаем все самое необходимое
include_once('simple_html_dom.php');
include_once('connect.php');

// Копируем полную версию изображения
function scraping_FULL($url,$file_number) {
    $full_page = file_get_html($url);
    foreach($full_page->find('div.image_frame') as $element)
    $one_big_image = $element->find('img', 0)->src;					// Тут можно оттестить - работает ли вообще

    // Копируем в папку превью картинки
   	$fp = fopen("upload/$file_number-big.jpg", 'w');
    fwrite($fp, file_get_contents($one_big_image)); 
    fclose($fp);
}

// Забираем последние изображения
$html = file_get_html('http://ru.sex.com/all/');

foreach($html->find('a.image_wrapper') as $one) {

    // Путь до картинки с кратким превью
    $one_image = $one->find('img', 0)->src;

    // Проверяем изображение по базе
    foreach($db->query("select * from sex_com where img='$one_image' ORDER by id LIMIT 0, 1") as $row) {
       $img_in_base = $row['img'];
    }

    if ($img_in_base == NULL) {

    	// Бредовая хуйня - когда можно просто посмотреть базу
		$file_number=(int)file_get_contents ('FILES');
		$file_number++;
		$file_number_file = fopen ('FILES', "r+"); // Since 28.11.2013
		flock($file_number_file,2);
		fputs($file_number_file, $file_number);
		fclose($file_number_file);	

        // Копируем в папку превью картинки
   	    $fp = fopen("upload/$file_number-small.jpg", 'w');
        fwrite($fp, file_get_contents($one_image)); 
        fclose($fp);

        // Добавляем в базу новое значение
        $db->exec("INSERT INTO sex_com(img) VALUES('$one_image')");

	    // Воруем полную версию картинки
	    $full_link = "http://ru.sex.com" . $one->href;
	    scraping_FULL($full_link,$file_number);

    }



}












?>