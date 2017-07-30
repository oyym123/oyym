<?php

/* @var $this yii\web\View */
use frontend\assets\AppAsset;

AppAsset::addScript($this, '@web/js/jquery.immersive-slider.js');
AppAsset::addCss($this, '@web/css/immersive-slider.css');

$this->title = '欧阳裕民个人博客';
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="keywords" content="欧阳裕民个人博客"/>
    <meta name="description" content="欧阳裕民个人博客"/>
    <meta name="title" content="欧阳裕民个人博客"/>
    <meta name="description" content="欧阳裕民个人博客"/>
    <link rel="image_src" href="images/immersive_slider_image.png"/>
    <link rel="shortcut icon" id="favicon" href="favicon.png">
    <meta name="author" content="OYYM">
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script type="text/javascript" src=""></script>
    <link href='' rel='stylesheet' type='text/css'>
    <style>
        html {
            height: 100%;
        }

        body {
            background: #f3f3f3;
            padding: 0;
            text-align: center;
            font-family: 'open sans';
            position: relative;
            margin: 0;
            height: 100%;
        }

        .wrapper {
            height: auto !important;
            height: 100%;
            margin: 0 auto;
            overflow: hidden;
        }

        a {
            text-decoration: none;
        }

        h1, h2 {
            width: 100%;
            float: left;
        }

        h1 {
            margin-top: 25px;
            color: #000;
            margin-bottom: 5px;
            font-size: 50px;
            letter-spacing: -4px;
        }

        h2 {
            color: #444;
            font-weight: 100;
            margin-top: 0;
            margin-bottom: 10px;
        }

        .pointer {
            color: #9b59b6;
            font-family: 'Pacifico', cursive;
            font-size: 30px;
            margin-top: 15px;
        }

        pre {
            margin: 80px auto;
        }

        pre code {
            padding: 35px;
            border-radius: 5px;
            font-size: 15px;
            background: rgba(0, 0, 0, 0.1);
            border: rgba(0, 0, 0, 0.05) 5px solid;
            max-width: 500px;
        }

        .main {
            float: left;
            width: 100%;
            margin: 0 auto;
            background: #161923;
        }

        .main h1 {
            padding: 20px 50px;
            float: left;
            width: 100%;
            font-size: 90px;
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            font-weight: 100;
            color: black;
            margin: 0;
            margin-top: 70px;
            font-family: 'Playfair Display';
            letter-spacing: -1px;
        }

        .main h1.demo1 {
            background: #1ABC9C;
        }

        .reload.bell {
            font-size: 12px;
            padding: 20px;
            width: 45px;
            text-align: center;
            height: 47px;
            border-radius: 50px;
            -webkit-border-radius: 50px;
            -moz-border-radius: 50px;
        }

        .reload.bell #notification {
            font-size: 25px;
            line-height: 140%;
        }

        .reload, .btn {
            display: inline-block;
            border: 4px solid #A2261E;
            border-radius: 5px;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            background: #CC3126;
            display: inline-block;
            line-height: 100%;
            padding: 0.7em;
            text-decoration: none;
            color: #fff;
            width: 100px;
            line-height: 140%;
            font-size: 17px;
            font-family: open sans;
            font-weight: bold;
        }

        .reload:hover {
            background: #A2261E;
        }

        .btn {
            width: 200px;
            color: #fff;
            border: none;
            margin-left: 10px;
            background: black;
        }

        .clear {
            width: auto;
        }

        .btn:hover, .btn:hover {
            background: rgba(0, 0, 0, 0.8);
        }

        .btns {
            width: 410px;
            margin: 50px auto;
        }

        .credit {
            text-align: center;
            color: #444;
            padding: 10px;
            margin: 0 0 0 0;
            background: #161923;
            color: #FFF;
            float: left;
            width: 100%;
        }

        .credit a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }

        .back {
            position: absolute;
            top: 0;
            left: 0;
            text-align: center;
            display: block;
            padding: 7px;
            width: 100%;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            background: rgba(255, 255, 255, 0.25);
            font-weight: bold;
            font-size: 13px;
            color: #000;
            -webkit-transition: all 200ms ease-out;
            -moz-transition: all 200ms ease-out;
            -o-transition: all 200ms ease-out;
            transition: all 200ms ease-out;
        }

        .back:hover {
            color: black;
            background: rgba(255, 255, 255, 0.5);
        }

        .page_container {
            max-width: 960px;
            margin: 50px auto;
        }

        .header {
            background: white;
            padding-top: 10px;
            margin-bottom: 0;
        }

        .header h1 {
            margin-bottom: 0;
            font-size: 45px;
        }

        .header .menu {
            padding-bottom: 10px;
        }

        .benefits {
            color: black;
            height: 100px;
            background: #FFF;
            position: relative;
            width: 100%;
            padding: 25px;
            font-size: 40px;
            font-weight: 100;
            float: left;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
        }

        .benefits .page_container {
            margin-bottom: 50px;
            margin-top: 0;
        }

        .immersive_slider .is-slide .content h2 {
            line-height: 140%;
            font-weight: 100;
            color: white;
            font-weight: 100;
        }

        .immersive_slider .is-slide .content a {
            color: white;
        }

        .immersive_slider .is-slide .content p {
            float: left;
            font-weight: 100;
            width: 100%;
            font-size: 17px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <header>
        <h1>Welcome to my blog !</h1>
        <!--        <h2>Create an immersive slider experience with jQuery Immersive Slider</h2>-->
        <h2>　</h2>
        <p class="credit">Created by <a href="http://www.thepetedesign.com">oyym</a></p>

    </header>
    <div class="main">
        <div class="page_container">
            <div id="immersive_slider">
                <div class="slide" data-blurred="/images/home/slide1_blurred.jpg">
                    <div class="content">
                        <h2><a href="http://www.bucketlistly.com" target="_blank">BucketListly</a></h2>
                        <p>It’s never been easier to watch YouTube on the big screen
                            Send your favorite YouTube videos from your Android phone or tablet to TV with the touch of
                            a button. It’s easy. No wires, no setup, no nothing. Find out more here.</p>
                    </div>
                    <div class="image">
                        <a href="http://www.bucketlistly.com" target="_blank">
                            <img src="/images/home/slide1.jpg" alt="Slider 1">
                        </a>
                    </div>
                </div>
                <div class="slide" data-blurred="/images/home/slide2_blurred.jpg">
                    <div class="content">
                        <h2>BucketListly Apps</h2>
                        <p>It’s never been easier to watch YouTube on the big screen
                            Send your favorite YouTube videos from your Android phone or tablet to TV with the touch of
                            a button. It’s easy. No wires, no setup, no nothing. Find out more here.</p>
                    </div>
                    <div class="image">
                        <a href="http://www.bucketlistly.com/apps" target="_blank"> <img src="/images/home/slide2.jpg"
                                                                                         alt="Slider 1"></a>
                    </div>
                </div>
                <div class="slide" data-blurred="/images/home/slide3_blurred.jpg">
                    <div class="content">
                        <h2><a href="http://www.thepetedesign.com" target="_blank">The Pete Design</a></h2>
                        <p>It’s never been easier to watch YouTube on the big screen
                            Send your favorite YouTube videos from your Android phone or tablet to TV with the touch of
                            a button. It’s easy. No wires, no setup, no nothing. Find out more here.</p>
                    </div>
                    <div class="image">
                        <a href="http://www.thepetedesign.com" target="_blank"><img src="/images/home/slide3.jpg"
                                                                                    alt="Slider 1"></a>
                    </div>
                </div>

                <a href="#" class="is-prev">&laquo;</a>
                <a href="#" class="is-next">&raquo;</a>
            </div>
        </div>
    </div>
    <div class="benefits">
        <div class="page_container">

        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#immersive_slider").immersive_slider({
                container: ".main"
            });
        });

    </script>
</div>


</body>
</html>