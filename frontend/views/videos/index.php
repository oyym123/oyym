<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>视频列表</title>
    <link rel="stylesheet" type="text/css" href="http://www.jq22.com/jquery/bootstrap-3.3.4.css">
    <link rel="stylesheet" href="/css/video_list/baguetteBox.min.css">
    <link rel="stylesheet" href="/css/video_list/thumbnail-gallery.css">
    <link rel="stylesheet" type="text/css" href="/css/search/search-form.css">
    <!--[if IE]>
    <script src="http://libs.baidu.com/html5shiv/3.7/html5shiv.min.js"></script>
    <![endif]-->
    <style>
        .jq22-demo {
            text-align: center;
            margin-top: 30px;
        }

        .jq22-demo a {
            padding-left: 20px;
            padding-right: 20px;
        }
    </style>
</head>
<body>
<div class="htmleaf-container">
    <div class="container gallery-container">

        <h1>视频列表</h1>
        <div class="jq22-demo">
            <span>热门搜索：</span>
            <?php
            foreach ($hotSearchWords as $hotSearchWord) {
                echo '<a href="/videos?key_words=' . $hotSearchWord . '" class="current">' . $hotSearchWord . '</a>';
            }
            ?>
        </div>
        <br/>
        <br/>
        <br/>
        <section style="position: relative;">
            <form onsubmit="submitFn(this, event);">
                <div class="search-wrapper">
                    <div class="input-holder">
                        <input type="text" class="search-input" placeholder="Type to search"/>
                        <button class="search-icon" onclick="searchToggle(this, event);"><span></span></button>
                    </div>
                    <span class="close" onclick="searchToggle(this, event);"></span>
                    <div class="result-container"></div>
                </div>
            </form>
        </section>
        <br/>

        <p class="page-description text-center"><?php
            if (isset($_GET['key_words'])) {
                echo '以下为 <b>' . $_GET['key_words'] . '</b> 的搜索结果,共计<b>100</b>条记录';
            } ?> </p>

        <div class="tz-gallery">
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <a class="lightbox" href="http://osak94fpd.bkt.clouddn.com/1499843007440.mp4">
                            <img src="/images/video_list/park.jpg" alt="Park">
                        </a>
                        <div class="caption">
                            <h3>Thumbnail label</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <a class="lightbox" href="/images/video_list/bridge.jpg">
                            <img src="/images/video_list/bridge.jpg" alt="Bridge">
                        </a>
                        <div class="caption">
                            <h3>Thumbnail label</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <a class="lightbox" href="el.jpg">
                            <img src="/images/video_list/tunnel.jpg" alt="Tunnel">
                        </a>
                        <div class="caption">
                            <h3>Thumbnail label</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <a class="lightbox" href="/images/video_list/coast.jpg">
                            <img src="/images/video_list/coast.jpg" alt="Coast">
                        </a>
                        <div class="caption">
                            <h3>Thumbnail label</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <a class="lightbox" href="/images/video_list/rails.jpg">
                            <img src="/images/video_list/rails.jpg" alt="Rails">
                        </a>
                        <div class="caption">
                            <h3>Thumbnail label</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <a class="lightbox" href="/videos/view">
                            <img src="/images/video_list/traffic.jpg" alt="Traffic">
                        </a>
                        <div class="caption">
                            <h3>Thumbnail label</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>


                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <a class="lightbox" href="/images/video_list/park.jpg">
                            <img src="/images/video_list/park.jpg" alt="Park">
                        </a>
                        <div class="caption">
                            <h3>Thumbnail label</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <a class="lightbox" href="/images/video_list/bridge.jpg">
                            <img src="/images/video_list/bridge.jpg" alt="Bridge">
                        </a>
                        <div class="caption">
                            <h3>Thumbnail label</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <a class="lightbox" href="/images/video_list/tunnel.jpg">
                            <img src="/images/video_list/tunnel.jpg" alt="Tunnel">
                        </a>
                        <div class="caption">
                            <h3>Thumbnail label</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <a class="lightbox" href="/images/video_list/coast.jpg">
                            <img src="/images/video_list/coast.jpg" alt="Coast">
                        </a>
                        <div class="caption">
                            <h3>Thumbnail label</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <a class="lightbox" href="/images/video_list/rails.jpg">
                            <img src="/images/video_list/rails.jpg" alt="Rails">
                        </a>
                        <div class="caption">
                            <h3>Thumbnail label</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <a class="lightbox" href="/videos/view">
                            <img src="/images/video_list/traffic.jpg" alt="Traffic">
                        </a>
                        <div class="caption">
                            <h3>Thumbnail label</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<script type="text/javascript" src="/js/video_list/baguetteBox.min.js"></script>
<script type="text/javascript">
    baguetteBox.run('.tz-gallery');
</script>
<script>window.jQuery || document.write('<script src="/js/search/jquery-1.11.0.min.js"><\/script>')</script>
<script type="text/javascript">
    function searchToggle(obj, evt) {
        var container = $(obj).closest('.search-wrapper');

        if (!container.hasClass('active')) {
            container.addClass('active');
            evt.preventDefault();
        }
        else if (container.hasClass('active') && $(obj).closest('.input-holder').length == 0) {
            container.removeClass('active');
            // clear input
            container.find('.search-input').val('');
            // clear and hide result container when we press close
            container.find('.result-container').fadeOut(100, function () {
                $(this).empty();
            });
        }
    }

    function submitFn(obj, evt) {
        value = $(obj).find('.search-input').val().trim();

        _html = "您的搜索内容: ";

        if (!value.length) {
            _html = "请输入搜索内容";
        }
        else {
            _html += "<b>" + value + "</b>";
            location.href = "?key_words=" + value;
        }

        $(obj).find('.result-container').html('<span>' + _html + '</span>');
        $(obj).find('.result-container').fadeIn(100);

        evt.preventDefault();
    }
</script>


</body>
</html>