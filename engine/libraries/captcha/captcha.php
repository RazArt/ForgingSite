<?php
	session_start();
	
	$captcha_width = 200;                           //Ширина изображения
	
	$captcha_height = 50;                           //Высота изображения
	
	$captcha_font_size = 20;                        //Размер шрифта
	
	$captcha_fonts = ['./fonts/trebuc.ttf',         //Массив с используемыми шрифтами
					  './fonts/cour.ttf',
					  './fonts/frizqt.ttf'];
	
   $captcha_key = strtolower($_GET['key']);
   
	if (isset($_SESSION['user_captcha'][$captcha_key]))
	{
		$captcha_code = $_SESSION['user_captcha'][$captcha_key]['code'];
		
		$background = imagecreatetruecolor($captcha_width, $captcha_height);
		
		$color[0] = imagecolorallocate($background, 255, 255, 255);
		
		imagefill($background, 0, 0, $color[0]);
		
		for ($i = 0; $i < mb_strlen($captcha_code); $i++)
		{
		   $letter_color = imagecolorallocate($background, rand(0, 200), rand(0, 200), rand(0, 200));
		   
		   $letter_x = ($i + 1) * ($captcha_font_size + 5) + rand(1, 5);
		   
		   $letter_y = (($captcha_height * 2)/3) + rand(0, 5);
		   
		   $letter_size = rand($captcha_font_size - 2, $captcha_font_size + 2);
		   
		   $letter_font = $captcha_fonts[array_rand($captcha_fonts)];
		  
		   imagettftext($background, $letter_size, rand(-45, 45), $letter_x, $letter_y, $letter_color, $letter_font, $captcha_code[$i]);
		}

		header("Content-type: image/gif");
		
		imagepng($background);
		
		imagedestroy($background);
	}
 