<?php
    // Разделитель директорий
    define('DS', DIRECTORY_SEPARATOR);

    // Раз уж этот файл призван защитить нас от несанкицонированного листинга,
    // то выводим такую инфу для "любознательных":
    $index_html_contents = "<html><body bgcolor='#FFFFFF'>
        Любопытной варваре на базаре нос оторвали!!! ;)</body></html>";

    // Функция, которая облазит все вложенные директории
    function clones_attack($path, $clone_content) {
        // Фича для независимости от разделителя директорий на конце
        $path = rtrim($path, DS) . DS;

        // Пробуем открыть директорию
        if(($directory_handler = opendir($path)) !== false) {
            // Если клона здесь нет - создаем его
            if(!file_exists($path . 'index.html')) {
                if(($file_handle = fopen($path . 'index.html', 'w')) !== false) {
                    fwrite($file_handle, $clone_content);
                    fclose($file_handle);
                }
            }

            // Продолжаем поиск директорий-жертв клонирования плацебо ;)
            while(($file_name = readdir($directory_handler)) !== false) {
                if($file_name != '.' && $file_name != '..' && $file_name != '.git') {
                    // Если наткнулись на поддиректорию - реКурсируем
                    if(is_dir($path . $file_name)) {
                        if(!clones_attack($path . $file_name, $clone_content))
                            return false;
                    }
                }
            }
            closedir($directory_handler);
            return true;
        } else {
            echo "Failed on " . $path;
            return false;
        }
    }

    // Открываем директорию, которую указали в аргументе
    // и натравилваем нашу функцию
    if(clones_attack($argv[1], $index_html_contents))
        echo "Clones reproduction was successful!";
    else
        echo "Error!";
?>