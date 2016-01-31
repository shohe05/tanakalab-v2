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
    <input type="text" id="search-text-box" name="search_query" value="" placeholder="Search">
    <!-- </div> -->
</header>

<div id="nav">
    <ul id="nav-ul">
        <li>
            <a href="/article/create">
                <i class="fa fa-pencil fa-2x"></i>
                <p>NEW</p>
            </a>
        </li>
        <li class="current">
            <a href="/">
                <i class="fa fa-home fa-2x"></i>
                <p>HOME</p>
            </a>
        </li>
        <li>
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
</body>
</html>