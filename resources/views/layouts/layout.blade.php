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

    <!-- </div> -->
</header>
<?php \Log::debug(Request::getPathInfo()); ?>
<div id="nav">
    <ul id="nav-ul">
        <li class="{{ Request::getPathInfo() == '/article/create' ? 'current' : '' }}">
            <a href="/article/create">
                <i class="fa fa-pencil fa-2x"></i>
                <p>NEW</p>
            </a>
        </li>
        <li class="{{ Request::getPathInfo() == '/' || preg_match('/\/article\/\d$/', Request::getPathInfo()) ? 'current' : '' }}">
            <a href="/">
                <i class="fa fa-file-text fa-2x"></i>
                <p>Articles</p>
            </a>
        </li>
        <li class="{{ Request::getPathInfo() == '/tags' ? 'current' : '' }}">
            <a href="#">
                <i class="fa fa-tag fa-2x"></i>
                <p>TAGS</p>
            </a>
        </li>
    </ul>
</div>

<div id="content">
    @yield('content')
</div>

@yield('additionalJs')
<script>
    $('#search-text-box').on('keydown', function(e) {
        if (e.keyCode !== 13) {
            return null;
        }
        var query = $('#search-text-box').val();
        location.href = ARTICLE_INDEX_URL + '?query=' + query
    })
</script>
</body>
</html>