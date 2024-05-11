<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pets | Edit</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <style>
        .text-danger {
            color: red;
        }

        .success {
            color: black;
            background-color: lightgreen;
        }

        .row {
            display: flex;
            flex-direction: row;
            gap: 10px;
            align-items: center;
        }
    </style>
</head>

<body class="antialiased">
    <a href="{{ route('mainPage') }}">
        <h1>Pet api</h1>
    </a>

    <h2>edit Pet {{ $id }} </h2>
    <form action="{{ route('pet.edit', $id) }}" method="POST">
        @csrf
        <label for="name"> pet name:
            <input required type="text" name="name">
        </label>
        @error('name')
            <p class="text-danger"> {{ $message }} </p>
        @enderror
        <br>
        <label for="status"> pet status:
            <select name="status">
                <option value="available">available</option>
                <option value="pending">pending</option>
                <option value="sold">sold</option>
            </select>
        </label>
        @error('status')
            <p class="text-danger"> {{ $message }} </p>
        @enderror
        <br>
        <input type="submit" value="Send">
    </form>
</body>

</html>
