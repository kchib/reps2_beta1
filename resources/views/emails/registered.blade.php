<html>
<body>
<h3>Вы зарегестрировались на сайте Reps.ru</h3>
<h4>Пожалуста, подтвердите Вашу почту</h4>
<h4>Ссылка для подтверждения почты: <a href="{{ route('email_verified', ['token'=>$token])}}">подтвердить</a></h4>
</body>
</html>