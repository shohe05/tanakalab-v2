@extends('layouts.layout')
<?php $title = 'Recent Posts'; ?>

@section('content')
  <h3 id="content-title">
    <i class="fa fa-file-text-o"></i>
    Articles
  </h3>

  <div id="article-filter">
    <ul>
      <li><a href="#">All <span>10</span></a></li>
      <li><a href="#"><i class="fa fa-thumb-tack"></i> Clips <span>5</span></a></li>
      <li><a href="#"><i class="fa fa-user"></i> You <span>3</span></a></li>
    </ul>
  </div>

  <div id="article-list">
    <ul>
    </ul>
  </div>
@stop

@section('additionalJs')
  <script>
    $(function() {
      Article.all().then(function(data) {
        var articles = data.response;
        var articleList = ArticleView.renderList(articles);
        $('#article-list>ul').append(articleList);
      })
    })
  </script>
@stop