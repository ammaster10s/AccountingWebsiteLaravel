<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <?php
        $test = "noboi";
        ?>

    <h1> "Sussy baka" </h1>

    <h1>{{$test}}</h1>

    @if($test == "Pawaris")
        <h1>Correcto</h1>
    @else
        <h1>sussy</h1>
    @endif

    <a href="/Admin" >Admin</a>
    <a href="/Member" >Member</a>
    <a href="/About" >About</a>
</body>
</html>
