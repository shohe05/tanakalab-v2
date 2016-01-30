<html>
  <head>
    <script src="{{ elixir("js/vendor.js") }}"></script>
    <script src="{{ elixir("js/app.js") }}"></script>
    <script>
      redirectIfNotLogin();
    </script>
  </head>
<body>

<a href="/article/create">記事投稿</a>

  <h1>Articles index</h1>

  <div id="articles">
    <ul></ul>
  </div>

  <script>
    $(function() {
      Article.all().then(function(data) {
        var articles = data.response;
        var articleList = ArticleView.renderList(articles);
        $('#articles').append(articleList);
      })
    })
  </script>
</body>
</html>