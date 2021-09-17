<?php

define('CHAR_POOL', 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefhijklmnopqrstuvwxyz1234567890');
$text = substr(str_shuffle(CHAR_POOL), 0, 5);

session_start();
if (isset($_SESSION['captchaKey']))
	unset($_SESSION['captchaKey']);

$_SESSION['captchaKey'] = strtoupper($text);

define('WIDTH', 320);
define('HEIGHT', 120);

$image = imagecreate(WIDTH, HEIGHT);

imagecolorallocate($image, 255, 255, 255);

// Captcha Text
for ($i = 1; $i <= 5; $i++) {
	$fontSize = rand(44, 54);

	$startingX = (60 * ($i - 1)) + 30;

	$pos = [rand($startingX - 5, $startingX + 5), rand(65, 85), rand(-30, 30)];

	$RGBcolours = [rand(0, 255), rand(0, 255), rand(0, 255)];
	$fontColour = imagecolorallocate($image, $RGBcolours[0], $RGBcolours[1], $RGBcolours[2]);

	imagettftext($image, $fontSize, $pos[2], $pos[0], $pos[1],  $fontColour, realpath('./fonts/' .  rand(1, 10) . '.ttf'), $text[$i - 1]);
}

// Random Character Background
$charColour = imagecolorallocate($image, 0, 0, 0);
for ($i = 0; $i < 160; $i++)
{
	imagestring($image, rand(1, 2), rand(0, WIDTH), rand(0, HEIGHT), CHAR_POOL[rand(0, strlen(CHAR_POOL) - 1)], $charColour);
}

// Random Lines
for ($i = 1; $i <= 40; $i++)
{
	$pos = [rand(1, WIDTH), rand(1, WIDTH), rand(1, WIDTH), rand(1, WIDTH)];

	$RGBcolours = [rand(0, 255), rand(0, 255), rand(0, 255)];
	$fontColour = imagecolorallocate($image, $RGBcolours[0], $RGBcolours[1], $RGBcolours[2]);

	imageline($image, $pos[0], $pos[1], $pos[2], $pos[3], $fontColour);
}

// Output Completed Image
header('Content-Type: image/png');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

imagepng($image);