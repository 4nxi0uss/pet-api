<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pets | add img</title>

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

    <h2>Add Pet img {{ $id }} </h2>
    <form enctype="multipart/form-data" action="{{ route('pet.uploadImage', $id) }}"  method="POST">
        @csrf
        <label for="metaData">additional Metadata:
            <input required type="text" name="metaData">
        </label>
        @error('metaData')
            <p class="text-danger"> {{ $message }} </p>
        @enderror
        <br>
        <label for="img">
            image:
            <input type="file" name="img">
        </label>
        @error('img')
            <p class="text-danger"> {{ $message }} </p>
        @enderror
        <br>
        <input type="submit" value="Send">
    </form>
</body>

</html>
