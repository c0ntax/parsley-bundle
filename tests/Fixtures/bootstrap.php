<?php

require_once __DIR__.'/../../vendor/autoload.php';

// We have to register a load of Doctrine annotations as that doesn't happen automatically

$annotationsDirs = [
    __DIR__.'/../../vendor/symfony/validator/Constraints',
];

foreach ($annotationsDirs as $annotationsDir) {
    $dh = opendir($annotationsDir);
    while ($file = readdir($dh)) {
        if (!in_array($file, ['.', '..'], true)) {
            $fullPath = $annotationsDir.'/'.$file;
            if (is_file($fullPath)) {
                \Doctrine\Common\Annotations\AnnotationRegistry::registerFile($fullPath);
            }
        }
    }
}
