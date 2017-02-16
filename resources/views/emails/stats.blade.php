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
<h1>Posting is done, you can see stats in the page linked below.</h1>

<p>
    <a href="{{ route('stats', ['queue' => $queue->id]) }}"></a>
</p>
</body>
</html>