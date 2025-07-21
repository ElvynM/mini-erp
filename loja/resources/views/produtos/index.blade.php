<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    teste

    <h1>Produtos</h1>
    <ul>
        @foreach ($produtos as $produto)
            <li>{{ $produto['nome'] }} - {{ $produto['preco'] }}</li>
        @endforeach
</body>
</html>
