<!DOCTYPE html>
<html>
<head></head>
<body>
</body>
<!-- IE8及以下支持JSON -->
<!--[if lt IE 9]>
<script src="https://g.alicdn.com/aliww/ww/json/json.js" charset="utf-8"></script>
<![endif]-->
<!-- 自动适配移动端与pc端 -->
<script src="https://g.alicdn.com/aliww/??h5.imsdk/2.1.5/scripts/yw/wsdk.js,h5.openim.kit/0.4.0/scripts/kit.js"
        charset="utf-8"></script>
<script>
    window.onload = function () {
        WKIT.init(
            {
                uid: "<?= $data['userid'] ?>",
                appkey: 24564518,
                credential: "<?php echo $data['password'] ?>",
                touid: "<?= $touid ?>",
                autoMsg: 'http://osak94fpd.bkt.clouddn.com/123.mp3',
                autoMsgType: 2,
                customData: {
                    item: { // 上传宝贝id
                        id: '宝贝id'
                    }
                },
                themeBgColor: '#2db769', // 必须设置此项，其他自定义颜色才能生效
                themeColor: '#fff',
                msgBgColor: '#2db769',
                msgColor: '#fff'
            }
        );
    }
</script>
</html>