<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pets</title>

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

    @if (\Session::has('success'))
        <span class="success">
            {{ \Session::get('success') }}
        </span>
    @endif

    <h2>Create Pet</h2>
    <form action="{{ route('pet.create') }}" method="POST">
        @csrf
        <label for="createId"> pet id:
            <input required type="number" name="createId" min="0">
        </label>
        @error('createId')
            <p class="text-danger"> {{ $message }} </p>
        @enderror
        <br>
        <label for="createName"> pet name:
            <input required type="text" name="createName">
        </label>
        @error('createName')
            <p class="text-danger"> {{ $message }} </p>
        @enderror
        <br>
        <label for="createCategoryId"> pet category id:
            <input required type="number" name="createCategoryId" min="0">
        </label>
        @error('createCategoryId')
            <p class="text-danger"> {{ $message }} </p>
        @enderror
        <br>
        <label for="createCategoryName"> pet category name:
            <input required type="text" name="createCategoryName">
        </label>
        @error('createCategoryName')
            <p class="text-danger"> {{ $message }} </p>
        @enderror
        <br>
        <label for="createTagId"> pet tag id:
            <input required type="number" name="createTagId" min="0">
        </label>
        @error('createTagId')
            <p class="text-danger"> {{ $message }} </p>
        @enderror
        <br>
        <label for="createTagName"> pet tag name:
            <input required type="text" name="createTagName">
        </label>
        @error('createTagName')
            <p class="text-danger"> {{ $message }} </p>
        @enderror
        <br>
        <label for="createPhotoUrls"> pet photo urls:
            <input required type="text" name="createPhotoUrls">
        </label>
        @error('createPhotoUrls')
            <p class="text-danger"> {{ $message }} </p>
        @enderror
        <br>
        <label for="createStatus"> pet status:
            <select name="createStatus">
                <option value="available">available</option>
                <option value="pending">pending</option>
                <option value="sold">sold</option>
            </select>
        </label>
        @error('createStatus')
            <p class="text-danger"> {{ $message }} </p>
        @enderror
        <br>
        <input type="submit" value="Submit">
    </form>

    <h2>get pet by Id</h2>
    <form action="{{ route('pet.getById') }}" method="GET">
        <label for="id"> pet id:
            <input required type="number" name="id" min="0">
        </label>
        @error('id')
            <p class="text-danger"> {{ $message }} </p>
        @enderror
        <input type="submit" value="Submit">
    </form>

    @isset($pet)
        <div class="row">
            <p>id: {{ $pet['id'] ?? 'Missing id' }} </p>
            <p>name: {{ $pet['name'] ?? 'John Doe' }} </p>
            <p>status: {{ $pet['status'] ?? 'Missing status' }} </p>
            <div class="row">
                <form action="{{ route('pet.destroy', $pet['id']) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="submit" value="Delete pet">
                </form>
                <a href="{{ route('editPage', $pet['id'] ?? 0) }}">Edit</a>
                <a href="{{ route('addImgPage', $pet['id'] ?? 0) }}">Add image</a>
            </div>
        </div>
    @endisset

    <h2>get pet by status</h2>
    <form action="{{ route('pet.getByStatus') }}" method="GET">
        <label for="status"> pet name:
            <select name="status">
                <option value="available">available</option>
                <option value="pending">pending</option>
                <option value="sold">sold</option>
            </select>
        </label>
        @error('status')
            <p class="text-danger"> {{ $message }} </p>
        @enderror
        <input type="submit" value="Submit">
    </form>

    @isset($pets)
        @foreach ($pets as $pet)
            <div class="row">
                <p>name: {{ $pet->name ?? 'John Doe' }} </p>
                <p>id: {{ $pet->id ?? 'missing id' }} </p>
                <p>status: {{ $pet->status ?? 'Missing status' }} </p>

                <div class="row">
                    <form action="{{ route('pet.destroy', $pet->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="Delete pet">
                    </form>
                    <a href="{{ route('editPage', $pet->id ?? 0) }}">Edit</a>
                    <a href="{{ route('addImgPage', $pet->id ?? 0) }}">Add image</a>
                </div>
            </div>
        @endforeach
    @endisset

    <h2>update existing Pet</h2>
    <form action="{{ route('pet.fullUpdate') }}" method="POST">
        @csrf
        @method('PUT')
        <label for="updateId"> pet id:
            <input required type="number" name="updateId" min="0">
        </label>
        @error('updateId')
            <p class="text-danger"> {{ $message }} </p>
        @enderror
        <br>
        <label for="updateName"> pet name:
            <input required type="text" name="updateName">
        </label>
        @error('updateName')
            <p class="text-danger"> {{ $message }} </p>
        @enderror
        <br>
        <label for="updateCategoryId"> pet category id:
            <input required type="number" name="updateCategoryId" min="0">
        </label>
        @error('updateCategoryId')
            <p class="text-danger"> {{ $message }} </p>
        @enderror
        <br>
        <label for="updateCategoryName"> pet category name:
            <input required type="text" name="updateCategoryName">
        </label>
        @error('updateCategoryName')
            <p class="text-danger"> {{ $message }} </p>
        @enderror
        <br>
        <label for="updateTagId"> pet tag id:
            <input required type="number" name="updateTagId" min="0">
        </label>
        @error('updateTagId')
            <p class="text-danger"> {{ $message }} </p>
        @enderror
        <br>
        <label for="updateTagName"> pet tag name:
            <input required type="text" name="updateTagName">
        </label>
        @error('updateTagName')
            <p class="text-danger"> {{ $message }} </p>
        @enderror
        <br>
        <label for="updatePhotoUrls"> pet photo urls:
            <input required type="text" name="updatePhotoUrls">
        </label>
        @error('updatePhotoUrls')
            <p class="text-danger"> {{ $message }} </p>
        @enderror
        <br>
        <label for="updateStatus"> pet status:
            <select name="updateStatus">
                <option value="available">available</option>
                <option value="pending">pending</option>
                <option value="sold">sold</option>
            </select>
        </label>
        @error('updateStatus')
            <p class="text-danger"> {{ $message }} </p>
        @enderror
        <br>
        <input type="submit" value="Submit">
    </form>
</body>

</html>
