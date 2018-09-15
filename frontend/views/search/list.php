<?php
/**
 * Created by PhpStorm.
 * User: Alienware
 * Date: 2017/9/25
 * Time: 0:28
 */

?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>欧阳裕民博客</title>
    <link href="https://fonts.googleapis.com/css?family=Overpass+Mono:400,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/search/normalize.css"/>
    <link rel="stylesheet" type="text/css" href="/css/search/demo.css">
    <script>document.documentElement.className = 'js';</script>
</head>
<body>
<div class="htmleaf-container">
    <main>
        <div class="message-mobile">This is a hover effect. Best viewed on desktop.</div>
        <section class="content">
            <?php
            $active = ['deneb', 'pollux', 'canopus', 'rigel', 'altair', 'alphard', 'polaris', 'hamal', 'castor', 'vega'];
            $res = array_chunk($searchInfo['channel']['item'], 4);
            foreach ($res as $item) {
                ?>
                <div class="grid grid--effect-<?= $active[rand(1, 9)] ?>">
                    <?php
                    foreach ($item as $t) {
                        $guid = isset($t['guid']) ? $t['guid'] : '';
                        if ($guid) {
                            $url = 'search/url?url=' . str_replace('http://www.btbook.us/wiki/', '', $guid);
                        } else {
                            $url = '#';
                        }
                        ?>
                        <a href="<?= $url ?>"
                           target="_blank" class="grid__item grid__item--c<?= rand(1, 3) ?>">
                            <div class="stack">
                                <div class="stack__deco"></div>
                                <div class="stack__deco"></div>
                                <div class="stack__deco"></div>
                                <div class="stack__deco"></div>
                                <div class="stack__figure">
                                    <img class="stack__img" src="/images/search/<?= rand(1, 3) ?>.png" alt="Image"/>
                                </div>
                            </div>
                            <div class="grid__item-caption">
                                <h3 class="grid__item-title"><?php
                                    if (is_array($t['description']['p'])) {
                                        echo \app\helpers\Helper::breakString($t['description']['p'][0], 13);
                                    } else {
                                        echo \app\helpers\Helper::breakString($t['description']['p'], 8);
                                    }
                                    ?></h3>
                                <div class="column column--left">
                                    <span class="column__text">上传日期</span>

                                </div>
                                <div class="column column--right">
                                    <span class="column__text"><?= $t['pubDate'] ?></span>
                                </div>
                            </div>
                        </a>
                    <?php } ?>
                </div>
                <?php
            } ?>
        </section>
    </main>
</div>
<script src="/js/search/anime.min.js"></script>
<script src="/js/search/main.js"></script>
<script>
    (function () {
        [].slice.call(document.querySelectorAll('.grid--effect-vega > .grid__item')).forEach(function (stackEl) {
            new VegaFx(stackEl);
        });
        [].slice.call(document.querySelectorAll('.grid--effect-castor > .grid__item')).forEach(function (stackEl) {
            new CastorFx(stackEl);
        });
        [].slice.call(document.querySelectorAll('.grid--effect-hamal > .grid__item')).forEach(function (stackEl) {
            new HamalFx(stackEl);
        });
        [].slice.call(document.querySelectorAll('.grid--effect-polaris > .grid__item')).forEach(function (stackEl) {
            new PolarisFx(stackEl);
        });
        [].slice.call(document.querySelectorAll('.grid--effect-alphard > .grid__item')).forEach(function (stackEl) {
            new AlphardFx(stackEl);
        });
        [].slice.call(document.querySelectorAll('.grid--effect-altair > .grid__item')).forEach(function (stackEl) {
            new AltairFx(stackEl);
        });
        [].slice.call(document.querySelectorAll('.grid--effect-rigel > .grid__item')).forEach(function (stackEl) {
            new RigelFx(stackEl);
        });
        [].slice.call(document.querySelectorAll('.grid--effect-canopus > .grid__item')).forEach(function (stackEl) {
            new CanopusFx(stackEl);
        });
        [].slice.call(document.querySelectorAll('.grid--effect-pollux > .grid__item')).forEach(function (stackEl) {
            new PolluxFx(stackEl);
        });
        [].slice.call(document.querySelectorAll('.grid--effect-deneb > .grid__item')).forEach(function (stackEl) {
            new DenebFx(stackEl);
        });
    })();
</script>
</body>
</html>