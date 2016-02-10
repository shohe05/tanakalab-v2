@extends('layouts.layout')
<?php $title = 'Tags'; ?>

@section('content')
    <h3 id="content-title">
        <i class="fa fa-file-text-o"></i>
        Tags
    </h3>

    <ul id="tags"></ul>
@stop

@section('additionalCss')
@stop

@section('additionalJs')
    <script>
        $(function() {
            Article.tags().then(function(data) {
                $('#loading').hide();
                $('#content').show();
                var tags = data.response;
                $.each(tags, function() {
                    $('#tags').append('<li><a href="/?query=tag:' + this.name + '" style="font-size: 22px;">' + this.name + '</a></li>')
                })
            });
        });
    </script>
@stop
