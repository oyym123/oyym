<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <title>欧阳裕民博客</title>
    <link rel="stylesheet" type="text/css" href="/css/search/search-form.css">

    <!--[if IE]>
    <script src="http://libs.baidu.com/html5shiv/3.7/html5shiv.min.js"></script>
    <![endif]-->
</head>
<body>
<section class="container">
    <form onsubmit="submitFn(this, event);">
        <div class="search-wrapper">
            <div class="input-holder">
                <input type="text" class="search-input" placeholder="Type to search"/>
                <button class="search-icon" onclick="searchToggle(this, event);"><span></span></button>
            </div>
            <span class="close" onclick="searchToggle(this, event);"></span>
            <div class="result-container">

            </div>
        </div>
    </form>
</section>

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
            location.href = "search?key_words=" + value;
        }

        $(obj).find('.result-container').html('<span>' + _html + '</span>');
        $(obj).find('.result-container').fadeIn(100);

        evt.preventDefault();
    }
</script>
</body>
</html>