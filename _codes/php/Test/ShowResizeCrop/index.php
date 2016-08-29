<?php


// resize mantenendo le proporzioni
// in teoria posso anche salvare in un file...
// mettendola in una classe, potrei usare tre opzioni: 
// 
// - display (come immagine/pagina)
// - stream , per averla come stream immagine
// - save, per salvarla
// 
// con le giuste impostazioni puo' divenire una classe piuttosto ficalach
function resize($newWidth, $originalFile, $targetFile) {

    $info = getimagesize($originalFile);
    $mime = $info['mime'];

    switch ($mime) {
            case 'image/jpeg':
                    $image_create_func = 'imagecreatefromjpeg';
                    $image_save_func = 'imagejpeg';
                    $new_image_ext = 'jpg';
                    break;

            case 'image/png':
                    $image_create_func = 'imagecreatefrompng';
                    $image_save_func = 'imagepng';
                    $new_image_ext = 'png';
                    break;

            case 'image/gif':
                    $image_create_func = 'imagecreatefromgif';
                    $image_save_func = 'imagegif';
                    $new_image_ext = 'gif';
                    break;

            default: 
                    throw new Exception('Unknown image type.');
    }

    $img = $image_create_func($originalFile);
    list($width, $height) = getimagesize($originalFile);

    $newHeight = ($height / $width) * $newWidth;
    $tmp = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    imagedestroy($img);

    // se voglio salvare
    // if (file_exists($targetFile)) {
    //         unlink($targetFile);
    // }
    // $image_save_func($tmp, "$targetFile.$new_image_ext");
    
    // se volgio mostrare e basta
    ob_end_clean();  // clean the output buffer ... if turned on.
    header('Content-Type: ' . $mime);  
    $image_save_func($tmp); //you does not want to save.. just display
    imagedestroy($tmp); //but not needed, cause the script exit in next line and free the used memory
    exit;
}

$img = 'rana.jpg';

resize(300, $img);

// $imgData = getimagesize($img);
// $widthR = $imgData['width'] ;
// $heightR = $imgData['height'];
// $mimeR = $imgData['mime'];

// header('Content-Type: ' . $mimeR);  
// echo file_get_contents($img);