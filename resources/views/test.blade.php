<html>
<head></head>
<body>
<h2>ログイン</h2>
<label for="">email</label>
<input type="text" name="email">
<label for="">pass</label>
<input type="password" name="password">
<button id="login">ログイン</button>

<h2>記事投稿</h2>
<label for="">title</label>
<input type="text" name="title">
<label for="">body</label>
<input type="text" name="body">
タグ2つまで
<input type="text" name="tags">
<input type="text" name="tags">
<button id="post">投稿</button>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script>
    $(function() {
        $.ajaxSetup({xhrFields:{withCredentials:true}});
        $('#login').on('click', function () {
            $.ajax({
                'url': '/api/v1/auth/login',
                'headers': {
                    'Cookie': 'hoge'
                },
                'type': 'POST',
                'data': {'email': $('[name=email]').val(), 'password': $('[name=password]').val()}
            }).done(function(data) {
                alert('成功');
                console.log(data);
            }).fail(function(data) {
                alert('失敗');
                console.log(data);
            });
        });

        $('#post').on('click', function () {
            var tags = [];
            $('[name=tags]').each(function() {
                tags.push($(this).val());
            });
            $.ajax({
                'url': '/api/v1/articles/create',
                'type': 'POST',
                'data': {'article': {'title': $('[name=title]').val(), 'body': $('[name=body]').val()}, 'tags': tags}
            }).done(function(data) {
                alert('成功');
                console.log(data);
            }).fail(function(data) {
                alert('失敗');
                console.log(data);
            });
        });


    });
</script>
</body>
</html>