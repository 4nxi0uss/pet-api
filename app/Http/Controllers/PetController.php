<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as PsrRequest;
use GuzzleHttp\Psr7\Utils;
use Exception;
use Illuminate\Http\Request;
use GuzzleHttp\RequestOptions;

class PetController extends Controller
{
    const PET_API_URL = 'https://petstore.swagger.io/v2/pet';
    const STATUSES = ['sold', 'pending', 'available'];

    private function exceptionHandler(Exception $e)
    {
        if ($e->getCode() === 404) {
            return response("Pet not found! get", 404);
        } elseif ($e->getCode() === 400) {
            return response("Invalid input supplied", 400);
        } elseif ($e->getCode() === 405) {
            return response("Invalid input supplied", 405);
        } elseif ($e->getCode() === 500) {
            abort(500);
        } elseif ($e->getCode() === 503) {
            abort(503);
        }
    }

    public function getPetById(Request $request)
    {
        $request->validate(['id' => ['required']]);

        if (!is_numeric($request->id)) {
            return response("Invalid ID supplied", 400);
        }

        $formattedUrl = self::PET_API_URL . '/' . $request->id;

        $client = new Client();
        $request = new  PsrRequest('GET', $formattedUrl);

        try {
            $promise = $client->sendAsync($request)->then(function ($response) {
                $body = $response->getBody()->getContents();

                return $body;
            })->wait();

            $pet = (array) json_decode($promise);

            return view('welcome', ['pet' => $pet]);
        } catch (Exception $e) {
            $this->exceptionHandler($e);
        }
    }

    public function getPetByStatus(Request $request)
    {
        if (!in_array($request->status, self::STATUSES)) {
            return response("Invalid status supplied", 400);
        }

        $formattedUrl = self::PET_API_URL . '/findByStatus?status=' . $request->status;

        $client = new Client();
        $request = new  PsrRequest('GET', $formattedUrl);

        try {
            $promise = $client->sendAsync($request)->then(function ($response) {
                $body = $response->getBody()->getContents();

                return $body;
            })->wait();

            $pets = (array) json_decode($promise);

            return view('welcome', ['pets' => $pets]);
        } catch (Exception $e) {
            $this->exceptionHandler($e);
        }
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function store(Request $request)
    {
        $request->validate([
            'createId' => ['required'],
            'createCategoryId' => ['required'],
            'createCategoryName' => ['required'],
            'createName' => ['required'],
            'createPhotoUrls' => ['required'],
            'createTagId' => ['required'],
            'createTagName' => ['required'],
            'createStatus' => ['required']
        ]);

        if (
            !is_numeric($request->createId)
            || !is_numeric($request->createCategoryId)
            || !is_numeric($request->createTagId)
            || !in_array($request->createStatus, self::STATUSES)
        ) {
            return response("Invalid input supplied", 405);
        }

        $body = json_encode([
            'id' => $request->createId,
            'category' => [
                'id' => $request->createCategoryId,
                'name' => $request->createCategoryName
            ],
            'name' => $request->createName,
            'photoUrls' => [$request->createPhotoUrls],
            'tags' => [
                [
                    'id' => $request->createTagId,
                    'name' => $request->createTagName
                ]
            ],
            'status' => $request->createStatus
        ]);

        $headers = [
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];

        $client = new Client();
        $request = new PsrRequest('POST', self::PET_API_URL, $headers, $body);

        try {
            $client->sendAsync($request)->then(function ($response) {
                $body = $response->getBody()->getContents();

                return $body;
            })->wait();

            return redirect()->route('mainPage')->with('success', 'Pet created');
        } catch (Exception $e) {
            $this->exceptionHandler($e);
        }
    }

    public function edit(Request $request, string $id)
    {
        $name = $request->name ?? '';
        $status = $request->status ?? '';

        if (!is_numeric($request->id)) {
            return response("Invalid input supplied", 400);
        }

        $bodyString = '';

        if ($name && $status) {
            $bodyString = "name=$name&status=$status";
        } elseif ($name && !$status) {
            $bodyString = "name=$name";
        } elseif (!$name && $status) {
            $bodyString = "status=$status";
        }

        $headers = [
            'accept' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $formattedUrl = self::PET_API_URL . '/' . $id;

        $client = new Client();
        $request = new PsrRequest('POST', $formattedUrl, $headers, $bodyString);

        try {
            $client->sendAsync($request)->then(function ($response) {
                $body = $response->getBody()->getContents();

                return $body;
            })->wait();

            return redirect()->route('mainPage')->with('success', 'Pet edited');
        } catch (Exception $e) {
            $this->exceptionHandler($e);
        }
    }

    public function storeImage(Request $request, string $id)
    {
        $request->validate([
            'image' => 'image'
        ]);

        if (!is_numeric($id)) {
            return response("Invalid ID supplied $id", 400);
        }

        $imagePath = '';

        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $request->file('img')->storeAs('images', $imageName, 'public');
        }

        $headers = [
            'accept' => 'application/json',
            'Content-Type' => 'multipart/form-data'
        ];

        $storagePath = "../../../storage/app/public/";

        $body =  [
            RequestOptions::MULTIPART  => [
                [
                    'name' => 'file',
                    'contents' =>  Utils::tryFopen(__DIR__ . '/' . $storagePath . $imagePath ?? '', 'r'),
                    'headers' => $headers
                ],
                [
                    'name' => 'additionalMetadata',
                    'contents' => $request->metaData ?? '',
                ]
            ]
        ];

        $formattedUrl = self::PET_API_URL . '/' . $id . '/uploadImage';

        $client = new Client();
        $request = new PsrRequest('POST', $formattedUrl);

        try {
            $client->sendAsync($request, $body)->then(function ($response) {
                $body = $response->getBody()->getContents();

                return $body;
            })->wait();

            return redirect()->route('mainPage')->with('success', 'img uploaded');
        } catch (Exception $e) {
            $this->exceptionHandler($e);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'updateId' => ['required'],
            'updateCategoryId' => ['required'],
            'updateCategoryName' => ['required'],
            'updateName' => ['required'],
            'updatePhotoUrls' => ['required'],
            'updateTagId' => ['required'],
            'updateTagName' => ['required'],
            'updateStatus' => ['required']
        ]);

        if (
            !is_numeric($request->updateId)
            || !is_numeric($request->updateCategoryId)
            || !is_numeric($request->updateTagId)
            || !in_array($request->updateStatus, self::STATUSES)
        ) {
            return response("Invalid input supplied", 405);
        }

        $body = json_encode([
            'id' => $request->updateId,
            'category' => [
                'id' => $request->updateCategoryId,
                'name' => $request->updateCategoryName
            ],
            'name' => $request->updateName,
            'photoUrls' => [$request->updatePhotoUrls],
            'tags' => [
                [
                    'id' => $request->updateTagId,
                    'name' => $request->updateTagName
                ]
            ],
            'status' => $request->updateStatus
        ]);

        $headers = [
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];

        $client = new Client();
        $request = new PsrRequest('POST', self::PET_API_URL, $headers, $body);

        try {
            $client->sendAsync($request)->then(function ($response) {
                $body = $response->getBody()->getContents();

                return $body;
            })->wait();

            return redirect()->route('mainPage')->with('success', 'Pet updated');
        } catch (Exception $e) {
            $this->exceptionHandler($e);
        }
    }

    public function destroy(Request $request, string $id)
    {
        if (!is_numeric($id)) {
            return abort(400);
        }

        $formattedUrl = self::PET_API_URL . '/' . $id;

        $client = new Client();
        $request = new PsrRequest('DELETE', $formattedUrl);

        try {
            $client->sendAsync($request)->then(function ($response) {
                $body = $response->getBody()->getContents();

                return $body;
            })->wait();

            return redirect()->route('mainPage')->with('success', 'pet deleted');
        } catch (Exception $e) {
            $this->exceptionHandler($e);
        }
    }
}
