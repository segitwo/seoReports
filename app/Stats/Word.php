<?php

namespace App\Stats;

class Word extends \ZipArchive {

    // Файлы для включения в архив
    private $files;

    // Путь к шаблону
    public $path;
    
    public $struct = [];

    public function __construct($filename, $template_path = 'word/' ){
        
        // Путь к шаблону
        $this->path = dirname(__FILE__) . "/" . $template_path;
        
        $filename = dirname(__FILE__) . "/" . $filename;
        
        // Если не получилось открыть файл, то жизнь бессмысленна.
        if ($this->open($filename, \ZIPARCHIVE::CREATE) !== TRUE) {
            die("Unable to open <$filename>\n");
        }
        
        //используем рекурсивную функцию для поиска всех файлов каталога
        $this->filePath($this->path);

        // Добавляем каждый файл в цикле
        foreach($this->struct as $f) {
            $this->addFile($this->path . $f , $f);
        }
    }
    
    private function filePath($file_folder) {
        
        $dir = opendir($file_folder);
        
        while(false !== ($file = readdir($dir))) { // перебираем все файлы из нашей папки
            if ($file != "." && $file != "..") { 
                if(is_file($file_folder.$file)) { // проверяем файл ли мы взяли из папки
                    //echo $file_folder.$file."/<br>";
                    $this->struct[] = str_replace($this->path, "", $file_folder).$file;
                } else {
                    //echo $file_folder.$file."/<br>";
                    $this->filePath($file_folder.$file."/");
                }
            }
        }
        closedir($dir); 
    }

    // Упаковываем архив
    public function create(){
        $this->close();
    }
}
