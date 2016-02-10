<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ isset($title) ? $title : '' }} | Tanakalab</title>
    <link rel="stylesheet" href="{{ elixir("css/app.css") }}">
    <link rel="stylesheet" href="{{ elixir("css/vendor.css") }}">
    @yield('additionalCss')
    <script src="{{ elixir("js/vendor.js") }}"></script>
    <script src="{{ elixir("js/app.js") }}"></script>
    <script>
        redirectIfNotLogin();
    </script>
</head>
<body>

<header id="header">
    <!-- <div id="search"> -->
    <i class="fa fa-search"></i>
    <input type="text" id="search-text-box" name="query" value="" placeholder="Search">

    <ul id="right">
        <li class="login-user"><img src="" alt="" width="45" height="45"></li>
        {{--<ul id="head-dropdown"><li id="logout-link">Logout</li></ul>--}}
        <ul id="head-dropdown" style="display: none;"><li id="logout-link">Logout</li></ul>
    </ul>
    <!-- </div> -->
</header>
<div id="nav">
    <ul id="nav-ul">
        <li class="{{ Request::getPathInfo() == '/article/create' ? 'current' : '' }}">
            <a href="/article/create">
                <i class="fa fa-pencil fa-2x"></i>
                <p>NEW</p>
            </a>
        </li>
        <li class="{{ Request::getPathInfo() == '/' || preg_match('/\/article\/\d+$/', Request::getPathInfo()) ? 'current' : '' }}">
            <a href="/">
                <i class="fa fa-file-text fa-2x"></i>
                <p>Articles</p>
            </a>
        </li>
        <li class="{{ Request::getPathInfo() == '/tags' ? 'current' : '' }}">
            <a href="/tags">
                <i class="fa fa-tag fa-2x"></i>
                <p>TAGS</p>
            </a>
        </li>
        <li id="right" class="login-user"><img src="" alt="" width="45" height="45"></li>
    </ul>
</div>

<div id="loading"><img src="/img/loading.gif"></div>
<div id="content" style="display:none;">
    @yield('content')
</div>

@yield('additionalJs')
<script>

    $(function() {

        var user = null;
        function getLoginUser() {
            user = loginUser();
            check();
        }

        function check() {
            if (user !== null) {
                clearInterval(interval);
                $('.login-user img').attr('src', user.image_path);

                $('.login-user').on('click', function() {
                    $("#head-dropdown").toggle();
                });

                $('#logout-link').on('click', function() {
                    logout();
                });
            }
            return true;
        }
        var interval = setInterval(getLoginUser, 1);

        $('#search-text-box').on('keydown', function(e) {
            if (e.keyCode !== 13) {
                return null;
            }
            var query = $('#search-text-box').val();

            location.href = ARTICLE_INDEX_URL + '?query=' + encodeURI(query);
        });
    });
</script>
</body>
</html>
