<?php

// header("HTTP/1.0 404 Not Found");
// exit;

echo '
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Docker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link href="//fonts.googleapis.com/css?family=Oxygen:400,800" rel="stylesheet" type="text/css">
    <style type="text/css">
    html,body {
        margin: 0;
        padding: 0
    }
    body {
        overflow: hidden;
        text-align: center;
        background: #1488c6;
    }
    *{
        color: #eee;
        font-weight: 400;
        font-family: Tahoma,Arial,Verdana,serif;
    }
    h1, h4 {
        font-family: Oxygen,Tahoma,Verdana,Arial,serif;
        width: 100%;
        padding: 0;
        margin: 0;
        text-align: center;
    }
    h1 {
        font-size: 220px;
        font-weight: 800;
        text-shadow: 0 0 1px rgb(0, 0, 0);
    }
    .center{
        width: 800px;
        margin: 0 auto;
    }
    ul {
        text-align: left;
        margin-left: 40px;
        list-style-type: square;
    }
    a {
        color: #fff;
    }
    </style>
</head>
<body>
    <div class="center">
        <h1>Docker</h1>
        <ul>
            <li><a href="http://bgsgroup">Site</a></li>
            <li><a href="/phpinfo.php">phpinfo</a></li>
            <li><a href="http://' . $_SERVER['HTTP_HOST'] . ':8081">phpmyadmin</a></li>
        </ul>
    </div>
</body>
</html>
';