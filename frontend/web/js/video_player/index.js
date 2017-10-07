/**
 * Created by Administrator on 2017/4/28.
 */


window.onload = function () {

    //初始化
    var video = $('#video1').videoCt({
        title: '蜡笔小新外传 带家之狼(2017)屁屁剑法',              //标题
        volume: 0.5,                //音量
        barrage: true,              //弹幕开关
        comment: true,              //弹幕
        reversal: true,             //镜像翻转
        playSpeed: true,            //播放速度
        update: true,               //下载
        autoplay: true,            //自动播放
        clarity: {
            type: ['360P', '480P', '720p'],            //清晰度
            src: ['http://oex0i784m.bkt.clouddn.com/%E7%AC%AC01%E8%AF%9D%20%E5%B1%81%E5%B1%81%E5%89%91%E6%B3%95_%E6%A0%87%E6%B8%85.mp4',
                'http://oex0i784m.bkt.clouddn.com/%E7%AC%AC01%E8%AF%9D%20%E5%B1%81%E5%B1%81%E5%89%91%E6%B3%95_%E9%AB%98%E6%B8%85.mp4',
                'http://oex0i784m.bkt.clouddn.com/%E7%AC%AC01%E8%AF%9D%20%E5%B1%81%E5%B1%81%E5%89%91%E6%B3%95_%E8%B6%85%E6%B8%85.mp4']      //链接地址
        },
        commentFile: 'comment.json'           //导入弹幕json数据
    });

    //扩展
    video.title;                    //标题
    video.status;                   //状态
    video.currentTime;              //当前时长
    video.duration;                 //总时长
    video.volume;                   //音量
    video.clarityType;              //清晰度
    video.claritySrc;               //链接地址
    video.fullScreen;               //全屏
    video.reversal;                 //镜像翻转
    video.playSpeed;                //播放速度
    video.cutover;                  //切换下个视频是否自动播放
    video.commentTitle;             //弹幕标题
    video.commentId;                //弹幕id
    video.commentClass;             //弹幕类型
    video.commentSwitch;            //弹幕是否打开
    $('#video1').bind('ended', function () {
        console.log('弹幕json数据：\n' + video.comment());              //获取弹幕json数据
    });
}