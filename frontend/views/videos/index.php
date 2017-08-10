<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>幻灯片插件ROYALSLIDER-VIDEO GALLERY</title>
    <meta name="keywords" content="幻灯图,焦点图,左右轮播图,jquery焦点图"/>
    <meta name="description" content="具体效果请看演示，一流资源网推荐下载。" />
    <link href="/css/video-player/royalslider.css" rel="stylesheet">
    <script  src="/js/video-player/jquery-1.8.3.min.js"></script>
    <script src="/js/video-player/jquery.royalslider.min.js"></script>
    <link href="/css/video-player/reset.css" rel="stylesheet">
    <link href="/css/video-player/rs-default.css" rel="stylesheet">

    <style>
        #video-gallery {
            width: 100%;
        }

        .videoGallery .rsTmb {
            padding: 20px;
        }
        .videoGallery .rsThumbs .rsThumb {
            width: 220px;
            height: 80px;
            border-bottom: 1px solid #2E2E2E;
        }
        .videoGallery .rsThumbs {
            width: 220px;
            padding: 0;
        }
        .videoGallery .rsThumb:hover {
            background: #000;
        }
        .videoGallery .rsThumb.rsNavSelected {
            background-color: #02874A;
            border-bottom:-color #02874A;
        }

        .sampleBlock {
            left: 3%;
            top: 1%;
            width: 100%;
            max-width: 400px;
        }


        @media screen and (min-width: 0px) and (max-width: 500px) {
            .videoGallery .rsTmb {
                padding: 6px 8px;
            }
            .videoGallery .rsTmb h5 {
                font-size: 12px;
                line-height: 17px;
            }
            .videoGallery .rsThumbs.rsThumbsVer {
                width: 100px;
                padding: 0;
            }
            .videoGallery .rsThumbs .rsThumb {
                width: 100px;
                height: 47px;
            }
            .videoGallery .rsTmb span {
                display: none;
            }
            .videoGallery .rsOverflow,
            .royalSlider.videoGallery {
                height: 300px !important;
            }
            .sampleBlock {
                font-size: 14px;
            }
        }

    </style>

</head>
<body >
<div  class="page wrapper main-wrapper">
    <div class="row clearfix">
        <div class="col span_6 fwImage">
            <div id="video-gallery" class="royalSlider videoGallery rsDefault">
                <a class="rsImg" data-rsVideo="http://www.youtube.com/watch?v=HFbHRWwyihE" href="/images/video-player/admin-video.png">
                    <div class="rsTmb">
                        <h5>New RoyalSlider</h5>
                        <span>by Dmitry Semenov</span>
                    </div>
                </a><a class="rsImg" data-rsVideo="http://od83l5fvw.bkt.clouddn.com/DXodcDCPWl_RUHRv7KseqBeMwqc=/llS_h-tV72eA-o9iLz8hmVKE43kw" href="/images/video-player/319715493_640.jpg">
                    <div class="rsTmb">
                        <h5>Stanley Piano</h5>
                        <span>by Digital Kitchen</span>
                    </div>
                </a>
                <div class="rsContent">
                    <a class="rsImg" data-rsVideo="http://od83l5fvw.bkt.clouddn.com/DXodcDCPWl_RUHRv7KseqBeMwqc=/llS_h-tV72eA-o9iLz8hmVKE43kw" href=/images/video-player/210393954_640.jpg">
                        <div class="rsTmb">
                            <h5>I Believe I Can Fly</h5>
                            <span>by sebastien montaz-rosset</span>
                        </div>
                    </a>
                    <h3 class="rsABlock sampleBlock">Animated block, to show you that you can put anything you want inside each slide.</h3>
                </div>
                <a class="rsImg" data-rsVideo="http://od83l5fvw.bkt.clouddn.com/DXodcDCPWl_RUHRv7KseqBeMwqc=/llS_h-tV72eA-o9iLz8hmVKE43kw" href="/images/video-player/311891198_640.jpg">
                    <div class="rsTmb">
                        <h5>Dubstep Dispute</h5>
                        <span>by Fluxel Media</span>
                    </div>
                </a><a class="rsImg" data-rsVideo="http://od83l5fvw.bkt.clouddn.com/DXodcDCPWl_RUHRv7KseqBeMwqc=/llS_h-tV72eA-o9iLz8hmVKE43kw" href="/images/video-player/318502540_640.jpg">
                    <div class="rsTmb">
                        <h5>The Epic & The Beasts</h5>
                        <span>by Sebastian Linda</span>
                    </div>
                </a><a class="rsImg" data-rsVideo="http://od83l5fvw.bkt.clouddn.com/DXodcDCPWl_RUHRv7KseqBeMwqc=/llS_h-tV72eA-o9iLz8hmVKE43kw" href="/images/video-player/284709146_640.jpg">
                    <div class="rsTmb">
                        <h5>Barcode Band</h5>
                        <span>by Kang woon Jin</span>
                    </div>
                </a><a class="rsImg" data-rsVideo="http://od83l5fvw.bkt.clouddn.com/DXodcDCPWl_RUHRv7KseqBeMwqc=/llS_h-tV72eA-o9iLz8hmVKE43kw" href="/images/video-player/308375094_640.jpg">
                    <div class="rsTmb">
                        <h5>Samm Hodges Reel</h5>
                        <span>by Animal</span>
                    </div>
                </a><a class="rsImg" data-rsVideo="http://od83l5fvw.bkt.clouddn.com/DXodcDCPWl_RUHRv7KseqBeMwqc=/llS_h-tV72eA-o9iLz8hmVKE43kw" href="images/02.jpg">
                    <div class="rsTmb">
                        <h5>The Foundry Showreel</h5>
                        <span>by The Foundry</span>
                    </div>
                </a>
            </div>
        </div>
    </div>


</div>
<script>
    jQuery(document).ready(function ($) {
        $('#video-gallery').royalSlider({
            arrowsNav: false,
            fadeinLoadedSlide: true,
            controlNavigationSpacing: 0,
            controlNavigation: 'thumbnails',

            thumbs: {
                autoCenter: false,
                fitInViewport: true,
                orientation: 'vertical',
                spacing: 0,
                paddingBottom: 0
            },
            keyboardNavEnabled: true,
            imageScaleMode: 'fill',
            imageAlignCenter: true,
            slidesSpacing: 0,
            loop: false,
            loopRewind: true,
            numImagesToPreload: 3,
            video: {
                autoHideArrows: true,
                autoHideControlNav: false,
                autoHideBlocks: true
            },
            autoScaleSlider: true,
            autoScaleSliderWidth: 960,
            autoScaleSliderHeight: 450
        });
    });

</script>
</body>
</html>
