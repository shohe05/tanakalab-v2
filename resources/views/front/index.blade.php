@extends('layouts.layout')
<?php $title = 'Recent Articles'; ?>

@section('content')
  <h3 id="content-title">
    <i class="fa fa-file-text-o"></i>
    Articles
  </h3>

  <div id="article-filter">
    <ul>
      <li id="all" class="active"><a href="#">All <span class="count"></span></a></li>
      <li id="clips"><a href="#"><i class="fa fa-thumb-tack"></i> Clips <span class="count"></span></a></li>
      <li id="you"><a href="#"><i class="fa fa-user"></i> You <span class="count"></span></a></li>
    </ul>
  </div>

  <div id="article-list">
    <ul>
    </ul>
    <p id="more" style="display: none;">もっと見る</p>
  </div>
@stop

@section('additionalCss')
  <style>
    @media screen and ( min-width:480px ) {
      #content {
        width: 800px;
      }
    }
  </style>
@stop

@section('additionalJs')
  <script>
    $(function() {
      var query = decodeURI(getQueryVariable('query'));
      $('#search-text-box').val(query);
      Article.search(location.search, 1).then(function(data) {
        var articles = data.response;
        renderArticles(articles);

        if (data.meta.has_more_pages) {
          $('#more').show();
        }

        $('#more').on('click', function() {
          Article.search(query, parseInt(data.meta.current) + 1).then(function(data2) {
            var articleList = ArticleView.renderList(data2.response);
            articles = articles.concat(data2.response);
            $('#article-filter #all span').text(articles.length);
            $('#article-filter #clips span').text(clippedArticles(articles).length);
            $('#article-filter #you span').text(ownedArticles(articles).length);
            $('#article-list>ul').append(articleList);
            if (!data2.meta.has_more_pages) {
              $('#more').hide();
            }
            data = data2;
          });
        });

        $('#article-filter #all span').text(articles.length);
        $('#article-filter #clips span').text(clippedArticles(articles).length);
        $('#article-filter #you span').text(ownedArticles(articles).length);

        // article-filter all
        $('#article-filter #all').on('click', function() {
          renderArticles(articles);
        });

        // article-filter clips
        $('#article-filter #clips').on('click', function() {
          renderArticles(clippedArticles(articles));
        });

        // article-filter you
        $('#article-filter #you').on('click', function() {
          renderArticles(ownedArticles(articles));
        });
      });

      function renderArticles(articles) {
        $('#article-list>ul').empty();
        var articleList = ArticleView.renderList(articles);
        $('#article-list>ul').append(articleList);
      }

      function clippedArticles(articles) {
        var clippedArticles = [];
        $.each(articles, function () {
          var self = this;
          $.each(self.clips, function() {
            if (this.user_id === loginUser().id) {
              clippedArticles.push(self);
            }
          })
        });
        return clippedArticles;
      }

      function ownedArticles(articles) {
        var ownedArticles = [];
        $.each(articles, function () {
          if (this.user_id === loginUser().id) {
            ownedArticles.push(this);
          }
        });
        return ownedArticles;
      }

    })
  </script>
@stop