<?php

if (isset($_POST['txtEmail']) && isset($_POST['txtMsg']) && isset($_POST['txtCaptcha']))
{
    session_start();

    if ($_SESSION['captchaKey'] == strtoupper($_POST['txtCaptcha']))
    {
        echo "true";
    }
    else
    {
        echo $_SESSION['captchaKey'];
    }
}
else
    echo "Missing values!";