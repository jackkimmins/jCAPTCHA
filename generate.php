<?php

//Shuffles the character set a take the first 5 chars as the CAPTCHA text.
define('CHAR_POOL', 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefhijklmnopqrstuvwxyz1234567890');
$text = substr(str_shuffle(CHAR_POOL), 0, 5);

//Saves the CAPTCHA text in the user's session to enable validation later.
session_start();
if (isset($_SESSION['captchaKey']))
	unset($_SESSION['captchaKey']);
$_SESSION['captchaKey'] = strtoupper($text);

//Creates the image with the specified width and height and sets the background colour to white.
define('WIDTH', 320);
define('HEIGHT', 120);
$image = imagecreate(WIDTH, HEIGHT);
imagecolorallocate($image, 255, 255, 255);



//Picks a random font, font size and text orientation for each char in the CAPTCHA text.
for ($i = 1; $i <= 5; $i++) {
	$fontSize = rand(44, 54);

	$startingX = (60 * ($i - 1)) + 30;

	$pos = [rand($startingX - 5, $startingX + 5), rand(65, 85), rand(-30, 30)];

	$RGBcolours = [rand(0, 255), rand(0, 255), rand(0, 255)];
	$fontColour = imagecolorallocate($image, $RGBcolours[0], $RGBcolours[1], $RGBcolours[2]);

	imagettftext($image, $fontSize, $pos[2], $pos[0], $pos[1],  $fontColour, realpath('./fonts/' .  rand(1, 10) . '.ttf'), $text[$i - 1]);
}



//Creates 160 random characters and positions them randomly on the CAPTCHA image.
$charColour = imagecolorallocate($image, 0, 0, 0);
for ($i = 0; $i < 160; $i++)
{
	imagestring($image, rand(1, 2), rand(0, WIDTH), rand(0, HEIGHT), CHAR_POOL[rand(0, strlen(CHAR_POOL) - 1)], $charColour);
}



//Creates 40 random lines with random colours on the CAPTCHA image.
for ($i = 1; $i <= 40; $i++)
{
	$pos = [rand(1, WIDTH), rand(1, WIDTH), rand(1, WIDTH), rand(1, WIDTH)];

	$RGBcolours = [rand(0, 255), rand(0, 255), rand(0, 255)];
	$fontColour = imagecolorallocate($image, $RGBcolours[0], $RGBcolours[1], $RGBcolours[2]);

	imageline($image, $pos[0], $pos[1], $pos[2], $pos[3], $fontColour);
}



//Change the content type to PNG
header('Content-Type: image/png');

//Prevents the image from being cached by the user's web browser.
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//Displays the completed image
imagepng($image);