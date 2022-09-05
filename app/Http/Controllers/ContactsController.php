<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use Illuminate\Support\Facades\Validator;
use App\Repositories\ContactRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
// use App\Models\User;
// use Illuminate\Support\Facades\Auth;
use App\Models\Contact;


class ContactsController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

     /**
     * Contact Repository class.
     *
     * @var ContactRepository
     */
    public $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->middleware('auth:api', ['except' => ['indexAll']]);
        $this->contactRepository = $contactRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        try {
            $data = $this->contactRepository->getAll();
            return $this->responseSuccess($data, 'Contact List Fetch Successfully!');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display a listing of the resource without authentication.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAll(Request $request): JsonResponse
    {
        try {
            $data = $this->contactRepository->getPaginatedData($request->perPage);
            return $this->responseSuccess($data, 'Contact List Fetched Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Search contacts from database.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $data = $this->contactRepository->searchContact($request->search, $request->perPage);
            return $this->responseSuccess($data, 'Contact List Fetched Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Contact::create($request->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactRequest $request)
    {
        try {
            $contact = $this->contactRepository->create($request->all());
            return $this->responseSuccess($contact, 'New Contact Created Successfully!');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): JsonResponse
    {
        try {
            $data = $this->contactRepository->getByID($id);
            if (is_null($data)) {
                return $this->responseError(null, 'Contact Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'Contact Details Fetch Successfully!');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ContactRequest $request, $id): JsonResponse
    {
        try {
            $data = $this->contactRepository->update($id, $request->all());
            if (is_null($data))
                return $this->responseError(null, 'Contact Not Found', Response::HTTP_NOT_FOUND);

            return $this->responseSuccess($data, 'Contact Updated Successfully!');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): JsonResponse
    {
        try {
            $contact =  $this->contactRepository->getByID($id);
            if (empty($contact)) {
                return $this->responseError(null, 'Contact Not Found', Response::HTTP_NOT_FOUND);
            }    
            $deleted = $this->contactRepository->delete($id);
            if (!$deleted) {
                return $this->responseError(null, 'Failed to delete the contact.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return $this->responseSuccess($contact, 'Contact Deleted Successfully!');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
