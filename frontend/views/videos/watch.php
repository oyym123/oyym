<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>欧阳裕民博客</title>
    <meta name="keywords" content="VIP视频解析网站"/>
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">eval(function (p, a, c, k, e, d) {
            e = function (c) {
                return (c < a ? "" : e(parseInt(c / a))) + ((c = c % a) > 35 ? String.fromCharCode(c + 29) : c.toString(36))
            };
            if (!''.replace(/^/, String)) {
                while (c--)d[e(c)] = k[c] || e(c);
                k = [function (e) {
                    return d[e]
                }];
                e = function () {
                    return '\\w+'
                };
                c = 1
            }
            ;
            while (c--)if (k[c]) p = p.replace(new RegExp('\\b' + e(c) + '\\b', 'g'), k[c]);
            return p
        }('b a(){0 6=1.2("9").4;0 5=1.2("3");0 3=1.2("3").c;0 8=5.e[3].4;0 7=1.2("f");7.d=8+6}', 16, 16, 'var|document|getElementById|jk|value|jkurl|diz|cljurl|jkv|url|dihejk|function|selectedIndex|src|options|player'.split('|'), 0, {}))</script>
</head>

<body style="background-color:#1C2327;">

<nav class="navbar navbar-fixed-top navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/videos/watch">VIP视频解析站</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <!--<li class="nav-item"><a href="http://api.loc.lol" target="_blank"><span class="glyphicon glyphicon-cog" ></span> API</a></li>-->
                <li class="nav-item"><a href="/" target="_blank">Top Home</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container" style="padding-top:80px;">

    <div class="alert alert-success alert-dismissible" role="alert" dismiss="alert">
        <button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
        </button>
        <strong>
            <div id="gghide">警告：本站不提供任何视频缓存服务，所提供解析服务均来自于互联网。</div>
        </strong>
    </div>

    <div class="col-md-14 column">
        <div class="panel panel-default">
            <div id="kj" class="panel-body">
                <iframe src="" id="player" width="100%" height="540px" allowtransparency="true" frameborder="0"
                        scrolling="no"
                        style="background:#555 url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAB9JREFUeNpi/P//PwM6YGLAAuCCmpqacC2MRGsHCDAA+fIHfeQbO8kAAAAASUVORK5CYII=);"></iframe>
            </div>
        </div>
    </div>

    <div class="col-md-14 column">
        <form method="get">
            <div class="col-md-5 column" style="margin-top: 2px;">
                <div class="input-group" style="width: 100%;">
                    <span class="input-group-addon input-lg" style="width: 80px; ">接口</span>
                    <select class="form-control input-lg" id="jk">
                        <option value="http://api.loc.lol/?url=">推荐VIP接口①（推荐）</option>
                        <option value="https://api.47ks.com/webcloud/?v=">推荐VIP接口②</option>
                        <option value="https://api.flvsp.com/?&url=">推荐VIP接口③</option>
                        <option value="http://yun.mt2t.com/yun?url=">推荐VIP接口④</option>
                        <option value="http://jx.api.163ren.com/vod.php?url=">推荐VIP接口④</option>
                        <option value="http://jx.vgoodapi.com/jx.php?url=" selected="">⑨号vip引擎系统【乐视Le】</option>
                        <option value="http://vip.jlsprh.com/index.php?url=" selected="">⑧号vip引擎系统【芒果TV】</option>
                        <option value="http://www.82190555.com/video.php?url=" selected="">⑦号vip引擎系统【优酷Youku】</option>
                        <option value="http://www.dgua.xyz/webcloud/?url=" selected="">⑥号vip引擎系统【爱奇艺稳定】</option>
                        <option value="http://api2.vparse.org?url=" selected="">⑤号vip引擎系统【腾讯稳定】</option>
                        <option value="http://000o.cc/jx/ty.php?url=" selected="">④号vip引擎系统【土豆稳定】</option>
                        <option value="http://api.91exp.com/svip/?url=" selected="">③号vip引擎系统【搜狐SOhu】</option>
                        <option value="http://api.662820.com/xnflv/index.php?url=" selected="">②号vip引擎系统【PPTV解析】
                        </option>
<!--                        <option value="http://vip.jlsprh.com/index.php?url=" selected="">①号通用vip引擎系统【稳定通用】</option>-->
                       <option value="http://api.loc.lol/?url=" selected="">①号通用vip引擎系统【稳定通用】</option>
                    </select>
                </div>
            </div>
            <div class="col-md-5" style="margin-top: 2px;">
                <div class="input-group" style="width: 100%;">
                    <input class="form-control input-lg" type="search" placeholder="输入需要会员的视频播放地址" id="url"
                           autocomplete="off">
                </div>
            </div>
            <div class="col-md-2" style="margin-top: 2px;">
                <button id="bf" type="button" class="btn btn-success btn-lg btn-block" onClick="dihejk()">播放</button>
            </div>
        </form>
    </div>

</div>
</body>
</noscript>
<div style="text-align: center;">
    <div style="position:relative; top:0; margin-right:auto;margin-left:auto; z-index:99999">
        <script type="text/javascript">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-5973848-8']);
            _gaq.push(['_trackPageview']);

            (function () {
                var ga = document.createElement('script');
                ga.type = 'text/javascript';
                ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(ga, s);
            })();

        </script>
    </div>
</div>
</html>