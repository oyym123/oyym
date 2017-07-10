<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'defaultYear' => 3, // 2 = 2016
    'downloadIosAppUrl' => '',
    // 自定义的时间格式
    'appDownloadUrl' => 'http://a.app.qq.com/o/simple.jsp?pkgname=com.zgzzzs.www',
    // 最新版本
    'lastVersion' => [
        1 => [// ios
            'version' => '4.6.9',
            'update_detail' => "1、修复了部分bug\n2、调整界面UI，优化用户体验",
        ],
        2 => [// android
            'version' => '4.6.7',
            'update_detail' => "1、修复了部分bug\n2、调整界面UI，优化用户体验",
        ],
    ],
    // 苹果不显示在线视频的版本
    'unDisplayVideosInIos' => [], // 该方式弃用, 根据发版版本与 配置变量lastVersion 版本判断
    'collegeVersion' => 9,
    'captchaValidateCount' => 15, // 注册时有效的验证码验证次数, 如果超过该次数则不允许验证
    'defaultPhoto' => 'http://img.51zzzs.cn/default_user_photo' . rand(7, 10) . '.png',
    'defaultImage' => 'http://img.51zzzs.cn/default_image.png',
    'defaultProductImg' => 'http://img.51zzzs.cn/default_product_img.png',
    'serverTel' => '4009191918',
    'serverQq' => '466813637',
    'goEasy' => '9cc55b21-9fb6-422b-965a-ba9e822633aa',
    'homeIconQq' => 'http://img.51zzzs.cn/home_icon_qq.png',
    'homeIconTel' => 'http://img.51zzzs.cn/home_icon_tel.png',
];
