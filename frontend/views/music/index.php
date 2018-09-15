<!DOCTYPE html>

<html xmlns="/www.w3.org/1999/xhtml">
<head id="Head1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>欧阳裕民博客</title>
    <meta name="Keywords" content="想念云上的日子"/>
    <meta name="Description" content="FM音乐电台"/>
    <meta name="Copyright" content="ouyym.com"/>
    <link href="/css/music/web.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/css/music/style.css">
    <script src="/js/music/jquery-1.7.2.min.js"></script>
    <script type="text/javascript">

        function hehe() {

            var aa = "<div style=' position:relative; top:-120px; width:500px; height:200px; margin-left:32%;border-radius:15px; overflow:hidden;  border: 1px solid #CECFD4;-webkit-box-shadow:#666 0px 0px 10px;'><iframe name='iframe_canvas' src='http:\\\\douban.fm/partner/baidu/doubanradio' scrolling='no' frameborder='0' width='500' height='200'  ></iframe></div>";


            document.getElementById("douban").re

            document.getElementById("douban").innerHTML = aa;


        }

    </script>
</head>

<body>
<form method="post" action="" id="form1">
    <div class="aspNetHidden">
        <input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE"
               value="/wEPDwUKLTc1NTI0MDUxNmRkhXbaTpIxMPDoysndg51aitx85REO2oAB9WjTm+jKHs4="/>
    </div>
    <div class="aspNetHidden">
        <input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="CA0B0334"/>
    </div>
    <div id="body">
        <div id="jp_container" role="application" aria-label="media player">
            <div class="jp-type-playlist">
                <div id="jplayer" class="jp-jplayer"></div>
                <div class="jp-time"><span class="jp-current-time" role="timer" aria-label="time"></span><span
                            class="jp-duration" role="timer" aria-label="duration"></span></div>
            </div>
        </div>
    </div>
    <div id="douban" style="margin:auto">
        <div class="grid-music-container f-usn">
            <div class="m-music-play-wrap">
                <div class="u-cover"></div>
                <div class="m-now-info">
                    <h1 class="u-music-title"><strong>标题</strong>
                        <small>歌手</small>
                    </h1>
                    <div class="m-now-controls">
                        <div class="u-control u-process"><span class="buffer-process"></span> <span
                                    class="current-process"></span></div>
                        <div class="u-control u-time">00:00/00:00</div>
                        <div class="u-control u-volume">
                            <div class="volume-process" data-volume="0.50"><span class="volume-current"></span> <span
                                        class="volume-bar"></span> <span class="volume-event"></span></div>
                            <a class="volume-control"></a></div>
                    </div>
                    <div class="m-play-controls"><a class="u-play-btn prev" title="上一曲"></a> <a
                                class="u-play-btn ctrl-play play" title="暂停"></a> <a class="u-play-btn next"
                                                                                     title="下一曲"></a> <a
                                class="u-play-btn mode mode-list current" title="列表循环"></a> <a
                                class="u-play-btn mode mode-random" title="随机播放"></a> <a
                                class="u-play-btn mode mode-single" title="单曲循环"></a></div>
                </div>
            </div>
            <div class="f-cb">&nbsp;</div>
            <div class="m-music-list-wrap"></div>
        </div>
        <script src="/js/music/smusic.min.js"></script>
        <script>


            var musicList = [

                {
                    title: 'Beautiful Now',
                    singer: 'Zedd',
                    cover: 'http://p1.music.126.net/XhCBGqLJhgpNGQwqtNdvWg==/2913705813975955.jpg',
                    src: 'http://m2.music.126.net/Y7JynegNxtQfFHGYYyaXYg==/2892815094065710.mp3'
                },
                {
                    title: 'All I Need',
                    singer: 'Aurosonic',
                    cover: 'http://p1.music.126.net/qXABwIVQkYiwWSI-44FsRw==/5993437883436278.jpg',
                    src: 'http://m2.music.126.net/H0c_CAdMI8JDHKpKXhJ6FQ==/5921969627575441.mp3'
                },
                {
                    title: 'I Can t',
                    singer: 'Wisp X',
                    cover: 'http://p1.music.126.net/KwAPW3_vjs-qDCEW3FSjcA==/7899991046067628.jpg',
                    src: 'http://m2.music.126.net/gVcpmqBXLk9ONYfVgPEqnA==/7790039883267785.mp3'
                },
                {
                    title: 'Lullaby Lonely',
                    singer: 'Denis Kenzo',
                    cover: 'http://p1.music.126.net/wanw_Kcj-tQe64ZgHi5Pqw==/2404631930003676.jpg',
                    src: 'http://m2.music.126.net/kf6FQZwholG_B20RZuGbOg==/3198479325254031.mp3'
                },
                {
                    title: 'A Spring...',
                    singer: 'KoZoRo',
                    cover: 'http://p1.music.126.net/EvWz-TdM6MUjEDS-6pmMbA==/7700979442816625.jpg',
                    src: 'http://m2.music.126.net/8Htby1eZejnEaA-rQxLi8g==/7964862232017711.mp3'
                },
                {
                    title: 'Reception',
                    singer: ' Soty',
                    cover: 'http://p1.music.126.net/ol5I7So8kHncjiFWLLYb8Q==/2262794929978909.jpg',
                    src: 'http://m2.music.126.net/cGsGLvLDeVVAA-Ty7Nxdew==/2053887720697629.mp3'
                },
                {
                    title: 'Dreaming',
                    singer: 'KoZoRo',
                    cover: 'http://p1.music.126.net/R-5Tp3szxlwKd4dSxlNHhw==/7843915952927088.jpg',
                    src: 'http://m2.music.126.net/WigWFuErwgAwJ2sX1uLQeQ==/6634453163541582.mp3'
                },
                {
                    title: 'Thank You',
                    singer: 'KoZoRo',
                    cover: 'http://p1.music.126.net/3LcgUL2_WQ-ILwwzqIyw2w==/7740561859919094.jpg',
                    src: 'http://m2.music.126.net/Z1wT8h1gZjEJFULcLvpdpg==/7711974557600267.mp3'
                },
                {
                    title: 'Natural ',
                    singer: 'Axero',
                    cover: 'http://p1.music.126.net/JN4erIflUNMw3f7U4pE_LQ==/2881819979090097.jpg',
                    src: 'http://m2.music.126.net/x3Zwx231hhf-Vp2LvQfOrA==/7908787139237624.mp3'
                },
                {
                    title: 'GlimpseOf...',
                    singer: 'Approaching Nirvana',
                    cover: 'http://p1.music.126.net/4d8hnmrMxDrKnn_0i1JqyA==/1728432278871211.jpg',
                    src: 'http://m2.music.126.net/rdSd54TlDTkoSH8cKsqTBg==/2107763790463968.mp3'
                },
                {
                    title: 'Sparks',
                    singer: 'Steerner',
                    cover: 'http://p1.music.126.net/YUIOO6IqkiC9j2jBp5mC0g==/7702078952964637.jpg',
                    src: 'http://m2.music.126.net/h6-JqJBpXIffFr8jT4mcAA==/7853811557405877.mp3'
                },
                {
                    title: 'Daydreamer',
                    singer: 'Ahxello',
                    cover: 'http://p1.music.126.net/BH3zJl9vJjH2M42NbRDGlQ==/7915384208397375.jpg',
                    src: 'http://m2.music.126.net/iXrYZegbKVOvXYvOHADr0g==/7796636952597235.mp3'
                },
                {
                    title: 'Wake Up',
                    singer: 'Lenno',
                    cover: 'http://p1.music.126.net/c4V1HCQvQf4anXNAmhYBdA==/6638851208513393.jpg',
                    src: 'http://m2.music.126.net/3LfsW2DGIxgxvKYl4zfVAA==/6633353650375092.mp3'
                },
                {
                    title: 'One Minute',
                    singer: 'Capital Cities',
                    cover: 'http://p1.music.126.net/KHHHpjl0KZpQQRODtQse8Q==/2941193604468807.jpg',
                    src: 'http://m2.music.126.net/RT_9ceO1NlSXuMb3J8E8rQ==/2926899953372532.mp3'
                },
                {
                    title: 'Wake',
                    singer: 'Hillsong Young',
                    cover: 'http://p1.music.126.net/AzS4n1yDi_90Yohr7kq3Zw==/2137450604418153.jpg',
                    src: 'http://m2.music.126.net/MO5FwXUJyMLJ4ykrMQt5kw==/5753744348256608.mp3'
                },
                {
                    title: 'Alone',
                    singer: 'Kristina Antuna',
                    cover: 'http://p1.music.126.net/3MapEQUFo61qY_QUMWTPDg==/5852700394913406.jpg',
                    src: 'http://m2.music.126.net/zTQwNQaLsAsNu2EOQV6uqQ==/5776834092558843.mp3'
                },
                {
                    title: 'Memories',
                    singer: 'Approaching Nirvana',
                    cover: 'http://p1.music.126.net/-Rt_0o6k71V_-OZUjpi_6Q==/6641050232203243.jpg',
                    src: 'http://m2.music.126.net/4uhG3v3vvpRTwJ-HTd5P6w==/6637751697320553.mp3'
                },
                {
                    title: 'Don t Look',
                    singer: 'Usher',
                    cover: 'http://p1.music.126.net/ldpRUbUReUBh45wJIqfHng==/7748258441443132.jpg',
                    src: 'http://m2.music.126.net/EZKPJjipelOCPx2Dp_-bug==/7793338418179960.mp3'
                },
                {
                    title: 'For Us',
                    singer: 'Kozoro',
                    cover: 'http://p1.music.126.net/3fipbbBNProjPmgeVW623Q==/7885697394815923.jpg',
                    src: 'http://m2.music.126.net/z5t-DBv6ugHWN8BCGynjSw==/7715273092477776.mp3'
                },

                {
                    title: 'What We Saw',
                    singer: 'MitiS',
                    cover: 'http://p1.music.126.net/ftqibDeu4kTlz2Tvgnl1qg==/1818592232342162.jpg',
                    src: 'http://m2.music.126.net/Uop1mQNZHudJxwyXgf4oOg==/2015404813729714.mp3'
                },
                {
                    title: 'Oasis ',
                    singer: 'MitiS/Crywolf',
                    cover: 'http://p1.music.126.net/ordkjDymmOFsOrN6hUrtYw==/6657542907059991.jpg',
                    src: 'http://m2.music.126.net/ZuthTwQwFyILWBw1bBmf4Q==/6664139976796473.mp3'
                },
                {
                    title: 'The Opening',
                    singer: 'MitiS',
                    cover: 'http://p1.music.126.net/LqBd9erGz3ThiAYbdhgPyw==/4442026976224269.jpg',
                    src: 'http://m2.music.126.net/4ejTJqRqhjJD8fYlrm5LrQ==/5718559976072369.mp3'
                },

                {
                    title: 'Open Window',
                    singer: 'MitiS',
                    cover: 'http://p1.music.126.net/LqBd9erGz3ThiAYbdhgPyw==/4442026976224269.jpg',
                    src: 'http://m2.music.126.net/UROh089DyN_SS-skPh3gFg==/5718559976072373.mp3'
                },

                {
                    title: 'Don t Look ',
                    singer: 'Usher',
                    cover: 'http://p1.music.126.net/ldpRUbUReUBh45wJIqfHng==/7748258441443132.jpg',
                    src: 'http://m2.music.126.net/EZKPJjipelOCPx2Dp_-bug==/7793338418179960.mp3'
                },

                {
                    title: 'Fade',
                    singer: 'Alan Walker',
                    cover: 'http://p1.music.126.net/YGkMrDa6gmK8NHwxF5ILPw==/2540971374738594.jpg',
                    src: 'http://m2.music.126.net/P1A33vT0LDqbHHBxu2VAMA==/6622358535602245.mp3'
                },

                {
                    title: 'Infinity',
                    singer: 'Ahexllo',
                    cover: 'http://p1.music.126.net/dqLUjO-_IbcgTEKzjbZ0kg==/7863707162105162.jpg',
                    src: 'http://m2.music.126.net/Jaifw7NpHSD3HEO50zK19g==/7755955022598178.mp3'
                },

                {
                    title: 'Hope',
                    singer: 'Tobu',
                    cover: 'http://p1.music.126.net/DXBFpiI4x83ndQAZNt1pkw==/8904944673441329.jpg',
                    src: 'http://m2.music.126.net/F4_AOi5zrsJeJNbsqD2juA==/6671836557397174.mp3'
                },

                {
                    title: 'Wake Me Up',
                    singer: 'Avicii',
                    cover: 'http://p1.music.126.net/vKdjFbqJTHofBq7uEYLKRw==/2535473814614183.jpg',
                    src: 'http://m2.music.126.net/nhml09ojU9KbuBO5ZucycA==/1262239348728853.mp3'
                },

                {
                    title: 'Sky',
                    singer: 'Martelli',
                    cover: 'http://p1.music.126.net/wbGjTXlUXfVqEZPcSciDxg==/6639950721110295.jpg',
                    src: 'http://m2.music.126.net/iE0gUz6XGF15Igne-zlq7w==/2535473814883178.mp3'
                },

                {
                    title: 'Alive',
                    singer: 'Zedd',
                    cover: 'http://p1.music.126.net/vO1BwGdH68KPf5leH9TJ0g==/1274333976699542.jpg',
                    src: 'http://m2.music.126.net/-Wr2SyEkGuZ9vw8kMjlwRA==/5775734580748469.mp3'
                },

                {
                    title: 'Original Fire',
                    singer: '鏈堜唬褰�',
                    cover: 'http://p1.music.126.net/Nko1sPaW2-E5ggxtp-lfMg==/825733232459999.jpg',
                    src: 'http://m2.music.126.net/HpxU5Mi7o4eIY2b1GqU87A==/1901055604487262.mp3'
                },

                {

                    title: 'Without You',

                    singer: 'Dillon Francis',

                    cover: 'http://p1.music.126.net/aOGmHYbRz12NuTbsMHJI7w==/2529976256404504.jpg',

                    src: 'http://m2.music.126.net/E-dfjVTBkVlnSOqEGefwyw==/5913173534253612.mp3'

                },

                {

                    title: 'Virus Origi...',

                    singer: 'Martin Garrix',

                    cover: 'http://p1.music.126.net/EovszKq8rVsWwb1yPRl3gA==/6669637534971197.jpg',

                    src: 'http://m2.music.126.net/LbMLZkrwsLjdJTma_5t07g==/6664139976831960.mp3'

                },


                {

                    title: 'Wasting Moo...',

                    singer: 'Sick Individuals',

                    cover: 'http://p1.music.126.net/OAaeeIE9wtMxDH4ZcAJFlw==/6005532511620779.jpg',

                    src: 'http://m2.music.126.net/iloUK5nb5wkuZGoZO06Kvw==/6002233976484623.mp3'

                },


                {

                    title: 'Bright Lights',

                    singer: 'Syn Cole',

                    cover: 'http://p1.music.126.net/8gPH7B_TeXbzJrqtEPMa_g==/5877989162346518.jpg',

                    src: 'http://m2.music.126.net/pghiNnSFdOboXGZNwVTMhQ==/6064906139382188.mp3'

                },


                {

                    title: 'Where Them...',

                    singer: 'Flo Rida',

                    cover: 'http://p1.music.126.net/rpjeK9mapOfJAVIzdu40eQ==/6625657069198669.jpg',

                    src: 'http://m2.music.126.net/jFxN0b8VoBxVUFoDXnbjGQ==/2083574534647492.mp3'

                },

                {

                    title: 'IGNITE',

                    singer: 'Michael Mind Project',

                    cover: 'http://p1.music.126.net/71rYccwEjIKAHrHMhxFGfQ==/5774635069308312.jpg',

                    src: 'http://m2.music.126.net/ERokqQoFYNJi3C1qjKoaUw==/6069304186009827.mp3'

                },


                {

                    title: 'Keep Our Lov...',

                    singer: 'Afrojack',

                    cover: 'http://p1.music.126.net/kiI_d6f2YPmSqmNwu_jhEA==/5960452534408399.jpg',

                    src: 'http://m2.music.126.net/RErp9NjCtgNWSeGDvMnVbw==/6052811511291328.mp3'

                },


                {

                    title: 'Give Me Every...',

                    singer: 'Pitbull',

                    cover: 'http://p1.music.126.net/Ts32J6sJYTsYA3TmoUXY4A==/2542070883804465.jpg',

                    src: 'http://m2.music.126.net/c20PhzGfxxbFWt1F8OgMMA==/1273234464965188.mp3'

                },


                {

                    title: 'Stay the Ni...',

                    singer: 'Zedd',

                    cover: 'http://p1.music.126.net/WvfXm9xCcSbfhYavGnBW9w==/5926367673766403.jpg',

                    src: 'http://m2.music.126.net/-ERHV5DNXxoIkQ1VXv8u6w==/1311717371984265.mp3'

                },


                {

                    title: 'I Want You...',

                    singer: 'Zedd',

                    cover: 'http://p1.music.126.net/jIDlEZSojmk1cIgiMmxRmQ==/2536573327934541.jpg',

                    src: 'http://m2.music.126.net/8fUMjyt8wf97Ox87-ZZa0g==/7826323766786355.mp3'

                },


                {

                    title: 'Eyes Half...',

                    singer: 'Crywolf',

                    cover: 'http://p1.music.126.net/sHq8On0tlMDA56ZLL8gbrw==/5998935441671741.jpg',

                    src: 'http://m2.music.126.net/VDHttTvO_kBiYEzl6HG39A==/5981343255627912.mp3'

                },


                {

                    title: 'My Sunset ',

                    singer: 'Feint',

                    cover: 'http://p1.music.126.net/UxMUeh1sLst3Vkma2oFsWQ==/614626999935437.jpg',

                    src: 'http://m2.music.126.net/7SSa5fN5FkbZHxC560N6Hw==/1295224697536354.mp3'

                },


                {

                    title: 'Ringtone',

                    singer: 'MetroGnome',

                    cover: 'http://p1.music.126.net/rRKTWZtkuFdm5pzij_1NfQ==/5947258394929291.jpg',

                    src: 'http://m2.music.126.net/1YWQu62sfwS_oVg4TiICUw==/5861496487920476.mp3'

                },

                {

                    title: 'Blu',

                    singer: 'Mitis',

                    cover: 'http://p1.music.126.net/0QhW9YGAreMO16iJ6l5F-w==/2246302255592303.jpg',

                    src: 'http://m2.music.126.net/aJqijgPEIwRvhdCeR9aQew==/3119314488012516.mp3'

                },

                {

                    title: 'Innocent Dis...',

                    singer: 'Mitis',

                    cover: 'http://p1.music.126.net/0QhW9YGAreMO16iJ6l5F-w==/2246302255592303.jpg',

                    src: 'http://m2.music.126.net/s8uyM6-NZZCMtvXo0Ugg8A==/2799356604344870.mp3'

                },


                {

                    title: 'Endeavors',

                    singer: 'Mitis',

                    cover: 'http://p1.music.126.net/0QhW9YGAreMO16iJ6l5F-w==/2246302255592303.jpg',

                    src: 'http://m2.music.126.net/MCAbtTCQPPivZWFkVu7-4g==/3083030604295585.mp3'

                },


                {

                    title: 'The Distance',

                    singer: '鏈堜唬褰�',

                    cover: 'http://p1.music.126.net/QjCLGJ7jTmNhxzyTCVkP3A==/5736152162226594.jpg',

                    src: 'http://m2.music.126.net/em-9QLmiSaCDpNzZg_FsTQ==/5750445813388288.mp3'

                },


                {

                    title: 'Scream',

                    singer: 'Usher',

                    cover: 'http://p1.music.126.net/3DEn3apyx3dDd6TGSh506w==/2540971372812653.jpg',

                    src: 'http://m2.music.126.net/DYAl8n3lLXoMDy_xcQJsKg==/1090715534766649.mp3'

                },


                {

                    title: 'Ten Feet Tall',

                    singer: 'Afrojack',

                    cover: 'http://p1.music.126.net/n_pbWtVO3WLUUsCju1jtqA==/2539871860553850.jpg',

                    src: 'http://m2.music.126.net/ucwxV9e1dz17kcsGLa_MHw==/5989039836531478.mp3'

                },


                {

                    title: 'Illuminate',

                    singer: 'Afrojack',

                    cover: 'http://p1.music.126.net/kiI_d6f2YPmSqmNwu_jhEA==/5960452534408399.jpg',

                    src: 'http://m2.music.126.net/Ph6TZVGaHkOaDtHOucx6Sg==/6018726650832640.mp3'

                },

                {

                    title: 'Firework',

                    singer: 'Katy Perry',

                    cover: 'http://p1.music.126.net/cq8PZj_GC6Vi9LP8pGrUVw==/1692148395152781.jpg',

                    src: 'http://m2.music.126.net/3pB-tRZoga0l19Gc9LU8Ig==/2029698464885829.mp3'

                },


                {

                    title: 'Clarity',

                    singer: 'Zedd',

                    cover: 'http://p1.music.126.net/OlBzjDbNwho_qiEZnodWUg==/2539871861609412.jpg',

                    src: 'http://m2.music.126.net/MyWaykZ-XILx4zAk7rTEaw==/1907652674191597.mp3'

                },


                {

                    title: 'GALAXY',

                    singer: 'Ken Arai',

                    cover: 'http://p1.music.126.net/SVJNKYtlB_n9I7p0017Ndw==/6635552674995921.jpg',

                    src: 'http://m2.music.126.net/bf1BeqBvMtvdQc8plIn7Tw==/3235862723062694.mp3'

                },


                {

                    title: 'Sunburst ',

                    singer: 'MaKo',

                    cover: 'http://p1.music.126.net/FGA_RM2Sr14Uhy0z4wUC4w==/3225967116767698.jpg',

                    src: 'http://m2.music.126.net/hx1lV5_DMGAcsXfNuDx6aA==/2529976257286415.mp3'

                },


                {

                    title: 'Leaving You',

                    singer: 'Audien / Michael',

                    cover: 'http://p1.music.126.net/JgcNLPPUvF1hHxPoSN6Ifg==/2533274790580833.jpg',

                    src: 'http://m2.music.126.net/E4oQ14HgWdfDuBr407TL0Q==/5797724813344402.mp3'

                },


                {

                    title: 'Friends ',

                    singer: 'ThimLife',

                    cover: 'http://p1.music.126.net/I1Wu3PY_SQ-E0A-6YkD0nQ==/3225967119013435.jpg',

                    src: 'http://m2.music.126.net/5i6Ko9PWkKYJGrbRhIXJDg==/6624557558863533.mp3'

                },


                {

                    title: 'A Town Ca...',

                    singer: 'Zac Barnett',

                    cover: 'http://p1.music.126.net/Q0k-V6tFmnolDzPsb7sEwg==/5866994046057442.jpg',

                    src: 'http://m2.music.126.net/qQCuHRFd-IDye9iJD5VJWA==/5993437883324489.mp3'

                },


                {

                    title: 'Brighter',

                    singer: 'The Two Friends',

                    cover: 'http://p1.music.126.net/IpIHhMrNZwWha_hiaNyY9A==/2537672839164775.jpg',

                    src: 'http://m2.music.126.net/n4VCr_aTLpB7SI3dwW9UzA==/3233663700423023.mp3'

                },


                {

                    title: 'River Flo...',

                    singer: 'Mark Pride',

                    cover: 'http://p1.music.126.net/VEVDZhxhSzJyLwZ-R0lvFQ==/1761417627708961.jpg',

                    src: 'http://m2.music.126.net/mmI0IqladI65DqTdKT24ag==/2128654511387653.mp3'

                },


                {

                    title: 'Verge',

                    singer: 'Owl City',

                    cover: 'http://p1.music.126.net/91VrqTL8imc03lUrSyeyJQ==/7703178465823309.jpg',

                    src: 'http://m2.music.126.net/2SvPeTljWmqhhKGFZmPl9A==/2891715582491780.mp3'

                },


                {

                    title: 'Beautiful Times ',

                    singer: 'Owl City',

                    cover: 'http://p1.music.126.net/fx4v-Rop1maKjnXFI_3uFA==/6044015418094279.jpg',

                    src: 'http://m2.music.126.net/6hU0ZqMbimfPBAtbTxA4sw==/5964850580820718.mp3'

                },


                {

                    title: 'Galaxies',

                    singer: 'Owl City',

                    cover: 'http://p1.music.126.net/RkMONSGJ6taQngTKSOsGMw==/5974746185770393.jpg',

                    src: 'http://m2.music.126.net/JQh4rsr0oYMKSAeVyXOZXQ==/5963751069502365.mp3'

                },

                {

                    title: 'Umbrella Beach',

                    singer: 'Owl City',

                    cover: 'http://p1.music.126.net/ULHpWQwg4KH7ICulgowPFA==/2534374303375900.jpg',

                    src: 'http://m2.music.126.net/Ma397661CMiI-hHT8jVdiw==/1265537883572542.mp3'

                },


                {

                    title: 'Circle Track ',

                    singer: 'Arston,Jake Reese',

                    cover: 'http://p1.music.126.net/6K4OOdp8_yylWre-mFO2pg==/6646547790045177.jpg',

                    src: 'http://m2.music.126.net/hQ4IYOSm8_npKsYS03k7lg==/6626756580806029.mp3'

                },

                {

                    title: 'Every Chance...',

                    singer: 'David Guetta',

                    cover: 'http://p1.music.126.net/qFDEUg09Vj7tp7hTEF4ifg==/681697209229626.jpg',

                    src: 'http://tsmusic24.tc.qq.com/5207456.mp3'

                },

                {

                    title: 'Alone',

                    singer: 'Armin van Buuren',

                    cover: 'http://p1.music.126.net/3sDtSglgsHmPDhtMARUUyg==/2536573325940856.jpg',

                    src: 'http://m2.music.126.net/X59oxYXdyPH4gkgtzRjFLw==/5992338371418224.mp3'

                },

                {

                    title: 'Youtopia',

                    singer: 'Armin van Buuren',

                    cover: 'http://p1.music.126.net/lkM-hECLezPlwIojyvzEaw==/6642149743977194.jpg',

                    src: 'http://tsmusic24.tc.qq.com/737866.mp3'

                }

                ,

                {

                    title: 'A Sky Full Of...',

                    singer: 'Coldplay',

                    cover: 'http://p1.music.126.net/JuxMhS9Uy-d_tNRUvZhmgg==/5929666208821819.jpg',

                    src: 'http://tsmusic24.tc.qq.com/7067925.mp3'

                }

                ,

                {

                    title: 'Born (Original Mix) ',

                    singer: 'Mitis',

                    cover: 'http://p1.music.126.net/0QhW9YGAreMO16iJ6l5F-w==/2246302255592303.jpg',

                    src: 'http://m2.music.126.net/vL4E8nTFHYcJJYnDXK-36Q==/3178688115913230.mp3'

                }

                ,

                {

                    title: '瀵勭敓鍏� Remix',

                    singer: 'Dubstep',

                    cover: 'http://p1.music.126.net/mGyMOMEDh2CueZCyqQwCOw==/3244658815977801.jpg',

                    src: 'http://m2.music.126.net/W3QE-8-7cypgblLmfVME_w==/3223768095131431.mp3'

                }

                ,

                {

                    title: 'Life',

                    singer: '7obu',

                    cover: 'http://p1.music.126.net/qTzLUIvrGh88V7Weycn1yA==/6027522744179563.jpg',

                    src: 'http://m2.music.126.net/hwwAJKEl8RkZMPhAuRzdvg==/5942860348544233.mp3'

                }

                ,

                {

                    title: 'Imperfection',

                    singer: 'Approaching Nirvana',

                    cover: 'http://p1.music.126.net/pHVyZy81UdLTtX0cSkwApQ==/2532175278948459.jpg',

                    src: 'http://m2.music.126.net/7gPDR0D6x9QyFgDMkykOsg==/5718559976120783.mp3'

                }

                ,

                {

                    title: 'Titanium',

                    singer: 'David Guetta,Sia',

                    cover: 'http://p1.music.126.net/JXBip73Gnu5Wfx42OAbWKg==/1700944488175683.jpg',

                    src: 'http://m2.music.126.net/KnatP3kp_-qNnYRuRVE-aQ==/1985717999775330.mp3'

                }

            ];


            new SMusic({

                musicList: musicList

            });

        </script>
        <link rel="stylesheet" href="/css/music/smusic.css"/>
    </div>
    <div style=" position:relative; top:-180px; margin:auto;width:500px; height:40px"><a style="text-align:center"
                                                                                         href="#"
                                                                                         class="btn hint  hint-top"
                                                                                         data-hint="歌曲不合口味？点击更换豆瓣电台。"><span>_</span>
            豆瓣电台</a> &nbsp; &nbsp; &nbsp; <a style="text-align:center" href="#" class="btn hint  hint-top"
                                             data-hint="首页更精彩"><span>_</span>返回首页</a>
        <link rel="stylesheet" href="/css/music/style2.css" media="screen" type="text/css"/>
    </div>
    <!--    <div style="color:gray; font-family:微软雅黑; font-size:14px; position:relative; top:130px"> &copy; 2017 ouyym.com 云上的日子-->
    <!--        QQ：844591473 邮箱：kuankuank@vip.qq.com <a style="color:gray; font-family:微软雅黑; font-size:14px;a:hover:black"-->
    <!--                                                target="_blank" href="http://xiaokuan123.qzone.qq.com/" title="留言给我">给我留言</a>-->
    <!--        &nbsp;&nbsp;&nbsp;<a style="color:gray; font-family:微软雅黑; font-size:14px;a:hover:black" target="_blank"-->
    <!--                             href="http://weibo.com/xiaokuan123" title="访问微博">作者微博 &nbsp;&nbsp;</a>-->
    <!--    </div>-->
</form>
</body>
</html>
<script type="text/javascript" src="/js/music/jquery.js"></script>
<script type="text/javascript" src="/js/music/ThreeWebGL.js"></script>
<script type="text/javascript" src="/js/music/ThreeExtras.js"></script>
<script type="text/javascript" src="/js/music/Detector.js"></script>
<script type="text/javascript" src="/js/music/RequestAnimationFrame.js"></script>
<script id="vs" type="x-shader/x-vertex">

			varying vec2 vUv;

			void main() {

				vUv = uv;

				gl_Position = projectionMatrix * modelViewMatrix * vec4( position, 1.0 );

			}





</script>
<script id="fs" type="x-shader/x-fragment">

			uniform sampler2D map;

			uniform vec3 fogColor;

			uniform float fogNear;

			uniform float fogFar;

			varying vec2 vUv;

			void main() {

				float depth = gl_FragCoord.z / gl_FragCoord.w;

				float fogFactor = smoothstep( fogNear, fogFar, depth );

				gl_FragColor = texture2D( map, vUv );

				gl_FragColor.w *= pow( gl_FragCoord.z, 20.0 );

				gl_FragColor = mix( gl_FragColor, vec4( fogColor, gl_FragColor.w ), fogFactor );

			}





</script>
<script type="text/javascript" src="/js/music/cloud.js"></script>

