AddEventHandler("main", "OnEndBufferContent", Array("WebpConvert", "webpHandler"));

class WebpConvert {

    public static function imageCreateFromAny($filepath) {
        $type = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize()
        $allowedTypes = array(
            //1,  // [] gif
            2,  // [] jpg
            3,  // [] png
            6   // [] bmp
        );
        if (!in_array($type, $allowedTypes)) {
            return false;
        }

        switch ($type) {
            case 1 :
                $im = imagecreatefromgif($filepath);
                break;
            case 2 :
                $im = imagecreatefromjpeg($filepath);
                break;
            case 3 :
                $im = imagecreatefrompng($filepath);
                break;
            case 6 :
                $im = imagecreatefromBmp($filepath);
                break;
				
        }
        return $im;
    }

    public function webpHandler(&$content) {
        global $USER;

        //$start = microtime(true);
        if(!$USER->IsAdmin() && isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false) {
            $cache_dir = '/upload/resize_cache/webp';

            if (!file_exists($_SERVER["DOCUMENT_ROOT"] . $cache_dir)) {
                mkdir($_SERVER["DOCUMENT_ROOT"] . $cache_dir, 0777, true);
            }


			
			//$pattern = '/\/(bitrix|upload|images|local).*\.(jpg|jpeg|png)/U';

			$pattern = '/\/(bitrix|upload|images|local).*\.(jpg|jpeg)/U';
				
            preg_match_all($pattern, $content, $output_array);
			
			//echo "<pre>"; var_dump($output_array[0]); echo "</pre>";
            // $images = array_merge($result_first[1], $result_second[1]);
			
			$images = $output_array[0];
			
			$replace_img = array();

            foreach ($images as $image) {			
				
                $info = parse_url($image);
				
				//var_dump($info);
				
				
				
                $path_dist = $_SERVER["DOCUMENT_ROOT"] . $cache_dir . $info['path'] . ".webp";

                if(!file_exists($path_dist)) {
					
                    if (isset($info['path']) && getimagesize($_SERVER["DOCUMENT_ROOT"] . $info['path'])) {
                        $path = $_SERVER["DOCUMENT_ROOT"] . $info['path'];
                        $path_info = pathinfo($info['path']);

                        if (!file_exists($_SERVER["DOCUMENT_ROOT"] . $cache_dir . $path_info['dirname'])) {
                            mkdir($_SERVER["DOCUMENT_ROOT"] . $cache_dir . $path_info['dirname'], 0777, true);
                        }

                       if (class_exists('Imagick') && false) {
                            $im = new Imagick($path);
                            $im->setImageFormat('webp');
                            $im->setImageAlphaChannel(imagick::ALPHACHANNEL_ACTIVATE);
                            $im->setBackgroundColor(new ImagickPixel('transparent'));
                            $im->writeImage($path_dist);
                        } else { 
                            $im = self::imageCreateFromAny($path);
                            imagewebp($im, $path_dist);
                            imagedestroy($im);
							
							$fpr=fopen($path_dist, "a+");
							fwrite($fpr, chr(0x00));
							fclose($fpr);
                        }
                    }
					
                }
				
				if(file_exists($path_dist)) {
					$replace_img[$image] =  $cache_dir . $info['path'] . ".webp";
				}	
					
            }
			
			 if($replace_img) {
				$content = str_replace(array_keys($replace_img), $replace_img , $content);
			 }

            //$end = microtime(true) - $start;
            //$content .= "<!-- webp time {$end} -->";
        }
    }

}
