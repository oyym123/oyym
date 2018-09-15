<?php
/**
 * Created by PhpStorm.
 * User: OYYM
 * Date: 2017/7/30
 * Time: 16:52
 */


?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>欧阳裕民博客</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto+Mono:300,500,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/sponsor/pater.css"/>
    <link rel="stylesheet" type="text/css" href="/css/all-user/normalize.css">
    <link rel="stylesheet" type="text/css" href="/css/all-user/demo.css"/>
    <script>document.documentElement.className = 'js';</script>
</head>
<body class="loading">
<main>
    <header class="codrops-header">

        <h1 class="codrops-header__title">Grid Loading Animations</h1>
        <a href="../" class="github-corner"
           aria-label="View source on Github">
            <svg class="github-corner__svg" width="80" height="80" viewBox="0 0 250 250" aria-hidden="true">
                <path d="M0,0 L115,115 L130,115 L142,142 L250,250 L250,0 Z"></path>
                <path d="M128.3,109.0 C113.8,99.7 119.0,89.6 119.0,89.6 C122.0,82.7 120.5,78.6 120.5,78.6 C119.2,72.0 123.4,76.3 123.4,76.3 C127.3,80.9 125.5,87.3 125.5,87.3 C122.9,97.6 130.6,101.9 134.4,103.2"
                      fill="currentColor" style="transform-origin: 130px 106px;" class="octo-arm"></path>
                <path d="M115.0,115.0 C114.9,115.1 118.7,116.5 119.8,115.4 L133.7,101.6 C136.9,99.2 139.9,98.4 142.2,98.6 C133.8,88.0 127.5,74.4 143.8,58.0 C148.5,53.4 154.0,51.2 159.7,51.0 C160.3,49.4 163.2,43.6 171.4,40.1 C171.4,40.1 176.1,42.5 178.8,56.2 C183.1,58.6 187.2,61.8 190.9,65.4 C194.5,69.0 197.7,73.2 200.1,77.6 C213.8,80.2 216.3,84.9 216.3,84.9 C212.7,93.1 206.9,96.0 205.4,96.6 C205.1,102.4 203.0,107.8 198.3,112.5 C181.9,128.9 168.3,122.5 157.7,114.1 C157.9,116.9 156.7,120.9 152.7,124.9 L141.0,136.5 C139.8,137.7 141.6,141.9 141.8,141.8 Z"
                      fill="currentColor" class="octo-body"></path>
            </svg>
        </a>
    </header>
    <div class="content content--side">
        <div class="control control--grids">
            <span class="control__title">switch layout</span>
            <div class="control__item">
                <input class="control__radio" type="radio" name="grid-type" value="grid--type-a" id="control-grid-a"
                       checked>
                <label class="control__label" for="control-grid-a">grid A</label>
            </div>
            <div class="control__item">
                <input class="control__radio" type="radio" name="grid-type" value="grid--type-b" id="control-grid-b">
                <label class="control__label" for="control-grid-b">grid B</label>
            </div>
            <div class="control__item">
                <input class="control__radio" type="radio" name="grid-type" value="grid--type-c" id="control-grid-c">
                <label class="control__label" for="control-grid-c">grid C</label>
            </div>
        </div>
    </div>
    <div class="content content--side content--right">
        <div class="control control--effects">
            <span class="control__title">run effect</span>
            <button class="control__btn" data-fx="Hapi">Hapi</button>
            <button class="control__btn" data-fx="Amun">Amun</button>
            <button class="control__btn" data-fx="Kek">Kek</button>
            <button class="control__btn" data-fx="Isis">Isis</button>
            <button class="control__btn" data-fx="Montu">Montu</button>
            <button class="control__btn" data-fx="Osiris">Osiris</button>
            <button class="control__btn" data-fx="Satet">Satet</button>
            <button class="control__btn" data-fx="Atum">Atum</button>
            <button class="control__btn" data-fx="Ra">Ra</button>
            <button class="control__btn" data-fx="Sobek">Sobek</button>
            <button class="control__btn" data-fx="Ptah">Ptah</button>
            <button class="control__btn" data-fx="Bes">Bes</button>
            <button class="control__btn" data-fx="Seker">Seker</button>
            <button class="control__btn" data-fx="Nut">Nut</button>
            <button class="control__btn" data-fx="Shu">Shu</button>
        </div>
    </div>
    <div class="content content--center">
        <div class="grid grid--type-a">
            <div class="grid__sizer"></div>

            <?php
            foreach ($data as $item) {
                ?>
                <div class="grid__item">
                    <a class="grid__link" href="/chat/send-msg?id=<?= $item['userid'] ?>">
                        <img class="grid__img" src="<?= '/images/all-user/set1/' . $item['userid'] . '.jpg' ?>"
                             alt="Some image"/></a>
                </div>
                <?php
            }
            ?>

        </div>
        <div class="grid grid--type-b">
            <div class="grid__sizer"></div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/11.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/5.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link pater pater--small" href="http://synd.co/2oQTgFH">
                    <div class="pater__img"></div>
                    <div class="pater__content">
                        <h2 class="pater__title" aria-label="fullstory">See Every Click, Swipe, <br> and Scroll</h2>
                        <p class="pater__desc">See how users experience your designs with FullStory.</p>
                        <span class="pater__call">Get it free today!</span>
                    </div>
                </a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/2.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/1.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/4.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/6.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/7.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/8.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/5.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/2.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/3.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/4.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/5.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/6.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/7.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/8.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/1.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/2.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/3.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/4.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/5.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/6.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/7.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/8.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set1/11.jpg"
                                                    alt="Some image"/></a>
            </div>
        </div>
        <div class="grid grid--type-c">
            <div class="grid__sizer"></div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set3/1.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set3/2.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link pater" href="http://synd.co/2oQTgFH">
                    <div class="pater__img"></div>
                    <div class="pater__content">
                        <h2 class="pater__title" aria-label="fullstory">See Every Click, Swipe, <br> and Scroll</h2>
                        <p class="pater__desc">See how users experience your designs with FullStory.</p>
                        <span class="pater__call">Get it free today!</span>
                    </div>
                </a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set3/3.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set3/4.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set3/5.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set3/6.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set3/10.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set3/11.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set3/7.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set3/8.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set3/9.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set3/2.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set3/4.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set3/6.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set3/8.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set3/10.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set3/1.jpg"
                                                    alt="Some image"/></a>
            </div>
            <div class="grid__item">
                <a class="grid__link" href="#"><img class="grid__img" src="/images/all-user/set3/2.jpg"
                                                    alt="Some image"/></a>
            </div>
        </div>
    </div>

</main>
<script src="/js/all-user/imagesloaded.pkgd.min.js"></script>
<script src="/js/all-user/masonry.pkgd.min.js"></script>
<script src="/js/all-user/anime.min.js"></script>
<script src="/js/all-user/main.js"></script>

</body>
</html>

