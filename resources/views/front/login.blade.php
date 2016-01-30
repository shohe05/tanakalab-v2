<html>
<head></head>
<body>
<div>
    <input type='text' id='email'>
    <input type='password' id='password'>
    <input type='submit' id='submit' value='ログイン'>
</div>

<script src="{{ elixir("js/vendor.js") }}"></script>
<script src="{{ elixir("js/app.js") }}"></script>
<script>
    $(function() {
        $('#submit').on('click', function() {
            var email = $('#email').val();
            var password = $('#password').val();

            _callApi('POST', LOGIN_API_URL, {'email': email, 'password': password}, function(data){
                var token = data.response.token;
                localStorage.setItem('token', token);
                location.href = ARTICLE_INDEX_URL;
            })
        })
    });
</script>
</body>
</html>
