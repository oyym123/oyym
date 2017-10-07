<?php

/* @var $this yii\web\View */

$this->title = '管理后台';
?>
<!DOCTYPE html>

<!--[if IEMobile 7]>
<html class="no-js iem7 oldie"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]>
<html class="no-js ie7 oldie" lang="en"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]>
<html class="no-js ie8 oldie" lang="en"><![endif]-->
<!--[if (IE 9)&!(IEMobile)]>
<html class="no-js ie9" lang="en"><![endif]-->
<!--[if (gt IE 9)|(gt IEMobile 7)]><!-->
<html class="no-js" lang="en"><!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>Developr</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- http://davidbcalhoun.com/2010/viewport-metatag -->
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- For all browsers -->
    <link rel="stylesheet" href="/css/bootstrap/reset.css?v=1">
    <link rel="stylesheet" href="/css/bootstrap/style.css?v=1">
    <link rel="stylesheet" href="/css/bootstrap/colors.css?v=1">
    <link rel="stylesheet" media="print" href="/css/bootstrap/print.css?v=1">
    <!-- For progressively larger displays -->
    <link rel="stylesheet" media="only all and (min-width: 480px)" href="/css/bootstrap/480.css?v=1">
    <link rel="stylesheet" media="only all and (min-width: 768px)" href="/css/bootstrap/768.css?v=1">
    <link rel="stylesheet" media="only all and (min-width: 992px)" href="/css/bootstrap/992.css?v=1">
    <link rel="stylesheet" media="only all and (min-width: 1200px)" href="/css/bootstrap/1200.css?v=1">
    <!-- For Retina displays -->
    <link rel="stylesheet"
          media="only all and (-webkit-min-device-pixel-ratio: 1.5), only screen and (-o-min-device-pixel-ratio: 3/2), only screen and (min-device-pixel-ratio: 1.5)"
          href="/css/bootstrap/2x.css?v=1">

    <!-- Webfonts -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>

    <!-- Additional styles -->
    <link rel="stylesheet" href="/css/bootstrap/styles/agenda.css?v=1">
    <link rel="stylesheet" href="/css/bootstrap/styles/dashboard.css?v=1">
    <link rel="stylesheet" href="/css/bootstrap/styles/form.css?v=1">
    <link rel="stylesheet" href="/css/bootstrap/styles/modal.css?v=1">
    <link rel="stylesheet" href="/css/bootstrap/styles/progress-slider.css?v=1">
    <link rel="stylesheet" href="/css/bootstrap/styles/switches.css?v=1">

    <!-- JavaScript at bottom except for Modernizr -->
    <script src="/js/bootstrap/libs/modernizr.custom.js"></script>

    <!-- For Modern Browsers -->
    <link rel="shortcut icon" href="/images/bootstrap/favicons/favicon.png">
    <!-- For everything else -->
    <link rel="shortcut icon" href="/images/bootstrap/favicons/favicon.ico">
    <!-- For retina screens -->
    <link rel="apple-touch-icon-precomposed" sizes="114x114"
          href="/images/bootstrap/favicons/apple-touch-icon-retina.png">
    <!-- For iPad 1-->
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/images/bootstrap/favicons/apple-touch-icon-ipad.png">
    <!-- For iPhone 3G, iPod Touch and Android -->
    <link rel="apple-touch-icon-precomposed" href="/images/bootstrap/favicons/apple-touch-icon.png">

    <!-- iOS web-app metas -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!-- Startup image for web apps -->
    <link rel="apple-touch-startup-image" href="/images/bootstrap/splash/ipad-landscape.png"
          media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
    <link rel="apple-touch-startup-image" href="/images/bootstrap/splash/ipad-portrait.png"
          media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
    <link rel="apple-touch-startup-image" href="/images/bootstrap/splash/iphone.png"
          media="screen and (max-device-width: 320px)">

    <!-- Microsoft clear type rendering -->
    <meta http-equiv="cleartype" content="on">

    <!-- IE9 Pinned Sites: http://msdn.microsoft.com/en-us/library/gg131029.aspx -->
    <meta name="application-name" content="Developr Admin Skin">
    <meta name="msapplication-tooltip" content="Cross-platform admin template.">
    <meta name="msapplication-starturl" content="http://www.display-inline.fr/demo/developr">
    <!-- These custom tasks are examples, you need to edit them to show actual pages -->
    <meta name="msapplication-task"
          content="name=Agenda;action-uri=http://www.display-inline.fr/demo/developr/agenda.html;icon-uri=http://www.display-inline.fr/demo/developr/img/favicons/favicon.ico">
    <meta name="msapplication-task"
          content="name=My profile;action-uri=http://www.display-inline.fr/demo/developr/profile.html;icon-uri=http://www.display-inline.fr/demo/developr/img/favicons/favicon.ico">
</head>

<body class="clearfix with-menu with-shortcuts">

<!-- Prompt IE 6 users to install Chrome Frame -->
<!--[if lt IE 7]><p class="message red-gradient simpler">Your browser is <em>ancient!</em> <a
        href="http://browsehappy.com/">Upgrade to a different browser</a> or <a
</p><![endif]-->

<!-- Title bar -->
<header role="banner" id="title-bar">
    <h2><a href="http://ouyym.com/" target="_blank" title="oyym博客">欧阳裕民博客</a></h2>
</header>

<!-- Button to open/hide menu -->
<a href="#" id="open-menu"><span>Menu</span></a>

<!-- Button to open/hide shortcuts -->
<a href="#" id="open-shortcuts"><span class="icon-thumbs"></span></a>

<?php echo $content; ?>

<!-- Side tabs shortcuts -->
<ul id="shortcuts" role="complementary" class="children-tooltip tooltip-right">
    <li class="current"><a href="./" class="shortcut-dashboard" title="Dashboard">Dashboard</a></li>
    <li><a href="inbox.html" class="shortcut-messages" title="Messages">Messages</a></li>
    <li><a href="agenda.html" class="shortcut-agenda" title="Agenda">Agenda</a></li>
    <li><a href="tables.html" class="shortcut-contacts" title="Contacts">Contacts</a></li>
    <li><a href="explorer.html" class="shortcut-medias" title="Medias">Medias</a></li>
    <li><a href="sliders.html" class="shortcut-stats" title="Stats">Stats</a></li>
    <li class="at-bottom"><a href="form.html" class="shortcut-settings" title="Settings">Settings</a></li>
    <li><span class="shortcut-notes" title="Notes">Notes</span></li>
</ul>

<!-- Sidebar/drop-down menu -->
<section id="menu" role="complementary">

    <!-- This wrapper is used by several responsive layouts -->
    <div id="menu-content">

        <header>
            Administrator
        </header>

        <div id="profile">
            <img src="/images/bootstrap/user.png" width="64" height="64" alt="User name" class="user-icon">
            Hello
            <span class="name"><?= Yii::$app->user->identity->name ?></span>
        </div>

        <!-- By default, this section is made for 4 icons, see the doc to learn how to change this, in "basic markup explained" -->
        <ul id="access" class="children-tooltip">
            <li><a href="inbox.html" title="Messages"><span class="icon-chat"></span><span class="count">2</span></a>
            </li>
            <li><a href="calendars.html" title="Calendar"><span class="icon-calendar"></span></a></li>
            <li><a href="login.html" title="Profile"><span class="icon-user"></span></a></li>
            <li><a href="login.html"<span class="icon-movie"></span></a></li>
        </ul>

        <section class="navigable">
            <ul class="big-menu">
                <li class="with-right-arrow">
                    <span><span class="list-count">11</span>Main styles</span>
                    <ul class="big-menu">
                        <li><a href="typography.html">Typography</a></li>
                        <li><a href="columns.html">Columns</a></li>
                        <li><a href="tables.html">Tables</a></li>
                        <li><a href="colors.html">Colors &amp; backgrounds</a></li>
                        <li><a href="icons.html">Icons</a></li>
                        <li><a href="files.html">Files &amp; Gallery</a></li>
                        <li class="with-right-arrow">
                            <span><span class="list-count">4</span>Forms &amp; buttons</span>
                            <ul class="big-menu">
                                <li><a href="buttons.html">Buttons</a></li>
                                <li><a href="form.html">Form elements</a></li>
                                <li><a href="textareas.html">Textareas &amp; WYSIWYG</a></li>
                                <li><a href="form-layouts.html">Form layouts</a></li>
                                <li><a href="wizard.html">Wizard</a></li>
                            </ul>
                        </li>
                        <li class="with-right-arrow">
                            <span><span class="list-count">2</span>Agenda &amp; Calendars</span>
                            <ul class="big-menu">
                                <li><a href="agenda.html">Agenda</a></li>
                                <li><a href="calendars.html">Calendars</a></li>
                            </ul>
                        </li>
                        <li><a href="blocks.html">Blocks &amp; infos</a></li>
                    </ul>
                </li>
                <li class="with-right-arrow">
                    <span><span class="list-count">8</span>Main features</span>
                    <ul class="big-menu">
                        <li><a href="auto-setup.html">Automatic setup</a></li>
                        <li><a href="responsive.html">Responsiveness</a></li>
                        <li><a href="tabs.html">Tabs</a></li>
                        <li><a href="sliders.html">Slider &amp; progress</a></li>
                        <li><a href="modals.html">Modal windows</a></li>
                        <li class="with-right-arrow">
                            <span><span class="list-count">3</span>Messages &amp; notifications</span>
                            <ul class="big-menu">
                                <li><a href="messages.html">Messages</a></li>
                                <li><a href="notifications.html">Notifications</a></li>
                                <li><a href="tooltips.html">Tooltips</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="with-right-arrow">
                    <a href="ajax-demo/submenu.html" class="navigable-ajax" title="Menu title">With ajax sub-menu</a>
                </li>
            </ul>
        </section>

        <script language="javascript" type="text/javascript">
            function showLeftTime() {
                var now = new Date();
                var year = now.getFullYear();
                var month = now.getUTCMonth();
                var day = now.getDate();
                var hours = now.getHours();
                var minutes = now.getMinutes();
                var seconds = now.getSeconds();
                document.all.show.innerHTML = "" + year + "年" + (month + 1) + "月" + day + "日 " + hours + ":" + minutes + ":" + seconds + "";
//一秒刷新一次显示时间
                setTimeout(showLeftTime, 1000);
            }
        </script>


        <ul class="unstyled-list">
            <li class="title-menu">Today's event</li>
            <li>
                <ul class="calendar-menu">
                    <li>
                        <a href="#" title="See event">
                            <time datetime="2017-09-26"><b><?= date('d') ?></b> <?= date('D') ?></time>
                            <label class="green">
                                <body onload="showLeftTime()">
                                <label id="show">当前时间</label>
                                </body>
                            </label>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="title-menu">New messages</li>
            <li>
                <ul class="message-menu">
                    <li>
							<span class="message-status">
								<a href="#" class="starred" title="Starred">Starred</a>
								<a href="#" class="new-message" title="Mark as read">New</a>
							</span>
                        <span class="message-info">
								<span class="blue">17:12</span>
								<a href="#" class="attach" title="Download attachment">Attachment</a>
							</span>
                        <a href="#" title="Read message">
                            <strong class="blue">oyym</strong><br>
                            <strong>Mail subject</strong>
                        </a>
                    </li>
                    <li>
                        <a href="#" title="Read message">
								<span class="message-status">
									<span class="unstarred">Not starred</span>
									<span class="new-message">New</span>
								</span>
                            <span class="message-info">
									<span class="blue">15:47</span>
								</span>
                            <strong class="blue">May Starck</strong><br>
                            <strong>Mail subject a bit longer</strong>
                        </a>
                    </li>
                    <li>
							<span class="message-status">
								<span class="unstarred">Not starred</span>
							</span>
                        <span class="message-info">
								<span class="blue">15:12</span>
							</span>
                        <strong class="blue">May Starck</strong><br>
                        Read message
                    </li>
                </ul>
            </li>
        </ul>

    </div>
    <!-- End content wrapper -->

    <!-- This is optional -->
    <footer id="menu-footer">
        <p class="button-height">
            <input type="checkbox" name="auto-refresh" id="auto-refresh" checked="checked" class="switch float-right">
            <label for="auto-refresh">Auto-refresh</label>
        </p>
    </footer>

</section>
<!-- End sidebar/drop-down menu -->

<!-- JavaScript at the bottom for fast page loading -->

<!-- Scripts -->
<script src="/js/bootstrap/libs/jquery-1.8.2.min.js"></script>
<script src="/js/bootstrap/setup.js"></script>

<!-- Template functions -->
<script src="/js/bootstrap/developr.input.js"></script>
<script src="/js/bootstrap/developr.message.js"></script>
<script src="/js/bootstrap/developr.modal.js"></script>
<script src="/js/bootstrap/developr.navigable.js"></script>
<script src="/js/bootstrap/developr.notify.js"></script>
<script src="/js/bootstrap/developr.scroll.js"></script>
<script src="/js/bootstrap/developr.progress-slider.js"></script>
<script src="/js/bootstrap/developr.tooltip.js"></script>
<script src="/js/bootstrap/developr.confirm.js"></script>
<script src="/js/bootstrap/developr.agenda.js"></script>
<script src="/js/bootstrap/developr.tabs.js"></script>        <!-- Must be loaded last -->

<!-- Tinycon -->
<script src="/js/bootstrap/libs/tinycon.min.js"></script>

<script>

    // Call template init (optional, but faster if called manually)
    $.template.init();

    // Favicon count
    Tinycon.setBubble(2);

    // If the browser support the Notification API, ask user for permission (with a little delay)
    if (notify.hasNotificationAPI() && !notify.isNotificationPermissionSet()) {
        setTimeout(function () {
            notify.showNotificationPermission('Your browser supports desktop notification, click here to enable them.', function () {
                // Confirmation message
                if (notify.hasNotificationPermission()) {
                    notify('Notifications API enabled!', 'You can now see notifications even when the application is in background', {
                        icon: '/images/bootstrap/demo/icon.png',
                        system: true
                    });
                }
                else {
                    notify('Notifications API disabled!', 'Desktop notifications will not be used.', {
                        icon: '/images/bootstrap/demo/icon.png'
                    });
                }
            });

        }, 2000);
    }

    /*
     * Handling of 'other actions' menu
     */

    var otherActions = $('#otherActions'),
        current = false;

    // Other actions
    $('.list .button-group a:nth-child(2)').menuTooltip('Loading...', {

        classes: ['with-mid-padding'],
        ajax: 'ajax-demo/tooltip-content.html',

        onShow: function (target) {
            // Remove auto-hide class
            target.parent().removeClass('show-on-parent-hover');
        },

        onRemove: function (target) {
            // Restore auto-hide class
            target.parent().addClass('show-on-parent-hover');
        }
    });

    // Delete button
    $('.list .button-group a:last-child').data('confirm-options', {

        onShow: function () {
            // Remove auto-hide class
            $(this).parent().removeClass('show-on-parent-hover');
        },

        onConfirm: function () {
            // Remove element
            $(this).closest('li').fadeAndRemove();

            // Prevent default link behavior
            return false;
        },

        onRemove: function () {
            // Restore auto-hide class
            $(this).parent().addClass('show-on-parent-hover');
        }

    });

    // Demo modal
    function openModal() {
        $.modal({
            content: '<p>This is an example of modal window. You can open several at the same time (click links below!), move them and resize them.</p>' +
            '<p>The plugin provides several other functions to control them, try below:</p>' +
            '<ul class="simple-list with-icon">' +
            '    <li><a href="javascript:void(0)" onclick="openModal()">Open new blocking modal</a></li>' +
            '    <li><a href="javascript:void(0)" onclick="$.modal.alert(\'This is a non-blocking modal, you can switch between me and the other modal\', { blocker: false })">Open non-blocking modal</a></li>' +
            '    <li><a href="javascript:void(0)" onclick="$(this).getModalWindow().setModalTitle(\'\')">Remove title</a></li>' +
            '    <li><a href="javascript:void(0)" onclick="$(this).getModalWindow().setModalTitle(\'New title\')">Change title</a></li>' +
            '    <li><a href="javascript:void(0)" onclick="$(this).getModalWindow().loadModalContent(\'ajax-demo/auto-setup.html\')">Load Ajax content</a></li>' +
            '</ul>',
            title: 'Example modal window',
            width: 300,
            scrolling: false,
            actions: {
                'Close': {
                    color: 'red',
                    click: function (win) {
                        win.closeModal();
                    }
                },
                'Center': {
                    color: 'green',
                    click: function (win) {
                        win.centerModal(true);
                    }
                },
                'Refresh': {
                    color: 'blue',
                    click: function (win) {
                        win.closeModal();
                    }
                },
                'Abort': {
                    color: 'orange',
                    click: function (win) {
                        win.closeModal();
                    }
                }
            },
            buttons: {
                'Close': {
                    classes: 'huge blue-gradient glossy full-width',
                    click: function (win) {
                        win.closeModal();
                    }
                }
            },
            buttonsLowPadding: true
        });
    }
    ;

    // Demo alert
    function openAlert() {
        $.modal.alert('This is an alert message', {
            buttons: {
                'Thanks, captain obvious': {
                    classes: 'huge blue-gradient glossy full-width',
                    click: function (win) {
                        win.closeModal();
                    }
                }
            }
        });
    }
    ;

    // Demo prompt
    function openPrompt() {
        var cancelled = false;

        $.modal.prompt('Please enter a value between 5 and 10:', function (value) {
            value = parseInt(value);
            if (isNaN(value) || value < 5 || value > 10) {
                $(this).getModalContentBlock().message('Please enter a correct value', {
                    append: false,
                    classes: ['red-gradient']
                });
                return false;
            }

            $.modal.alert('Value: <strong>' + value + '</strong>');

        }, function () {
            if (!cancelled) {
                $.modal.alert('Oh, come on....');
                cancelled = true;
                return false;
            }
        });
    }
    ;

    // Demo confirm
    function openConfirm() {
        $.modal.confirm('?', function () {
            $.modal.alert('Me gusta!');

        }, function () {
            $.modal.alert('Meh.');
        });
    }
    ;

    /*
     * Agenda scrolling
     * This example shows how to remotely control an agenda. most of the time, the built-in controls
     * using headers work just fine
     */


    // Demo loading modal
    function openLoadingModal() {
        var timeout;

        $.modal({
            contentAlign: 'center',
            width: 240,
            title: 'Loading',
            content: '<div style="line-height: 25px; padding: 0 0 10px"><span id="modal-status">正在链接服务器...</span><br><span id="modal-progress">0%</span></div>',
            buttons: {},
            scrolling: false,
            actions: {
                'Cancel': {
                    color: 'red',
                    click: function (win) {
                        win.closeModal();
                    }
                }
            },
            onOpen: function () {
                // Progress bar
                var progress = $('#modal-progress').progress(100, {
                        size: 200,
                        style: 'large',
                        barClasses: ['anthracite-gradient', 'glossy'],
                        stripes: true,
                        darkStripes: false,
                        showValue: false
                    }),

                    // Loading state
                    loaded = 0,

                    // Window
                    win = $(this),

                    // Status text
                    status = $('#modal-status'),

                    // Function to simulate loading
                    simulateLoading = function () {
                        ++loaded;
                        progress.setProgressValue(loaded + '%', true);
                        if (loaded === 100) {
                            progress.hideProgressStripes().changeProgressBarColor('green-gradient');
                            status.text('完成!');
                            /*win.getModalContentBlock().message('Content loaded!', {
                             classes: ['green-gradient', 'align-center'],
                             arrow: 'bottom'
                             });*/
                            setTimeout(function () {
                                win.closeModal();
                            }, 10);
                        }
                        else {
                            if (loaded === 1) {
                                status.text('加载数据中...');
                                progress.changeProgressBarColor('blue-gradient');
                            }
                            else if (loaded === 25) {
                                status.text('加载资源 (1/3)...');
                            }
                            else if (loaded === 45) {
                                status.text('加载资源 (2/3)...');
                            }
                            else if (loaded === 85) {
                                status.text('加载资源 (3/3)...');
                            }
                            else if (loaded === 92) {
                                status.text('正在初始化...');
                            }
                            timeout = setTimeout(simulateLoading, 10);
                        }
                    };

                // Start
                timeout = setTimeout(simulateLoading, 300);
            },
            onClose: function () {
                // Stop simulated loading if needed
                clearTimeout(timeout);
            }
        });
    }
    ;

</script>

<!-- Charts library -->
<!-- Load the AJAX API -->
<script>


</script>
</body>
</html>