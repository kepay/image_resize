<?php
// 13.05.2022 m
if(!function_exists("image_resize"))
{
	function image_resize($image, $config) 
    {
        /* 
		// header('Content-Type: image/jpeg');
    	// $img = image_resize('C:/Users/kepay/Desktop/test.jpg', array(array(null, 200, 200, 80, true, true)));
		//
		// return:
		// false (bool) // произошла ошибка загрузки, причины: слишком большой размер, файл не подходящего размера...
		// [
		//	[object image, color #, link],
		//	false // при ошибке 
		// ] (array) // все картинки созданы успешно
		//
		// внимание! миниатюры сохраняются в jpg
		//
		// аргумент 1 (string):
		// путь до исходника 'image/img.jpg'
		//
		// аргумент 2 (array):
		// array(
		// 	// первый требуемый размер
		// 	array(
		// 	 'link' (string) // полный путь до место сохранения 'image/img.jpg'
		// 	 width (int)
		// 	 height (int)
		// 	 quality (int) // качество 0-100
		// 	 scissors (bool) // true: не выходить из рамки / false: обрезать и вписать четко в рамки
		// 	 increase (bool) // false: если исходник меньше, то исходник растянется до требуемого размера / true: если исходник меньше, то исходник не растянется до требуемого размера	
		// 	)
		// );
		//
        */
        $status = false;
	
		if (is_string($image)) {
            $image_info = @getimagesize($image);
            list($width_original, $height_original, $type, $attr) = $image_info;
           
			if ($image_info !== false) 
            {
                switch ($image_info['mime']) {
                    case 'image/jpeg':	$status = true; $src = @imagecreatefromjpeg($image); break;
                    case 'image/png':	$status = true; $src = @imagecreatefrompng($image); break;
                    case 'image/gif':	$status = true; $src = @imagecreatefromgif($image); break;
                }
                if ($width_original > 8192) 	$status = false; // 8K UHD | 8192×4320
			    if ($height_original > 8192) 	$status = false;
                if ($src === false)             $status = false;
            }			
		}
        
		if ($status === true)
		{
			$img = @imagecreatetruecolor(1, 1); 
			$status = @imagecopyresampled($img, $src, 0, 0, 0, 0, 1, 1, $width_original, $height_original);
			if ($status !== false)
			{
				$status = true;
				$color = '#' . dechex(imagecolorat($img, 0, 0));
			}		
		}

        if ($status === true)
        {			
			$image_array = [];
            foreach ($config as $item)
            {
                list($link, $width, $height, $quality, $scissors, $increase) = $item;

				// защита
                $width = $width <= 1 ? 1 : $width;
                $height = $height <= 1 ? 1 : $height;
                $quality = $quality <= 10 ? 10 : $quality;
                $quality = $quality >= 100 ? 100 : $quality;
                $scissors = $scissors === true ? true : false;
                $increase = $increase === true ? true : false;

                if ($width == 0 && $height == 0)
                    { $width = $width_original; $height = $height_original; }
                else if ($width != 0 && $height == 0)
                    { $height = ceil($height_original / ($width_original / $width)); }
                else if ($width == 0 && $height != 0)
                    { $width = ceil($width_original / ($height_original / $height)); }

                if ($increase) // true: если исходник меньше, то исходник не растянется до требуемого размера / false: если исходник меньше, то исходник растянется до требуемого размера
                {
                    $width = $width_original > $width ? $width : $width_original;
                    $height = $height_original > $height ? $height : $height_original;                
                }                

                if ($scissors) // true: не выходить из рамки / false: обрезать и вписать четко в рамки
                {              
                    $tmp = ceil($height_original / ($width_original / $width));
                    if ($tmp > $height) 	
                            $width = ceil($width_original / ($height_original / $height));
                    else    $height = $tmp;
                    $img = imagecreatetruecolor($width, $height);
                } else 
                {
                    $img = imagecreatetruecolor($width, $height);
                    $tw = $width;
                    $th = $height;
                    $k1 = $width / $width_original;
                    $k2 = $height / $height_original;
                    $k = $k1 < $k2 ? $k2 : $k1;          
                    $width = ceil($width_original * $k);
                    $height = ceil($height_original * $k);
					// центровка
                    $x = -($width - $tw) / 2;
					$y = -($height - $th) / 2;
                }
                
				// покраска фона в белый
                $status = @imagefill($img, 0, 0, imagecolorallocate($img, 255, 255, 255));

                if ($status !== false) $status = @imagecopyresampled($img, $src, $x, $y, 0, 0, $width, $height, $width_original, $height_original);
                if ($status !== false) $status = @imagejpeg($img, $link, $quality);
              	$image_array[] = $status !== false ? [$img, $color, $link, $quality, (isset($tw) ? $tw : $width), (isset($th) ? $th : $height)] : false;
            }
        }
        if (isset($image_array))    return $image_array;
        else                        return $status;	
	}
}
