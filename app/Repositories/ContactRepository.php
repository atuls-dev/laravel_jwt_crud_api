<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use App\Helpers\UploadHelper;
use App\Interfaces\CrudInterface;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class ContactRepository implements CrudInterface
{
    /**
     * Authenticated User Instance.
     *
     * @var User
     */
    public User | null $user;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->user = Auth::guard()->user();
    }

    /**
     * Get All Contacts.
     *
     * @return collections Array of Contact Collection
     */
    public function getAll(): Paginator
    {
        return $this->user->contacts()
            ->orderBy('id', 'desc')
            ->with('user')
            ->paginate(10);
    }

    /**
     * Get Paginated Contact Data.
     *
     * @param int $pageNo
     * @return collections Array of Contact Collection
     */
    public function getPaginatedData($perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 12;
        return Contact::orderBy('id', 'desc')
            ->with('user')
            ->paginate($perPage);
    }

    /**
     * Get Searchable Contact Data with Pagination.
     *
     * @param int $pageNo
     * @return collections Array of Contact Collection
     */
    public function searchContact($keyword, $perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 10;

        return Contact::where('name', 'like', '%' . $keyword . '%')
            ->orWhere('email', 'like', '%' . $keyword . '%')
            ->orWhere('phone', 'like', '%' . $keyword . '%')
            ->orderBy('id', 'desc')
            ->with('user')
            ->paginate($perPage);
    }

    /**
     * Create New Contact.
     *
     * @param array $data
     * @return object Contact Object
     */
    public function create(array $data): Contact
    {
        //$titleShort      = Str::slug(substr($data['name'], 0, 20));
         
        $data['user_id'] = $this->user->id;

        if (!empty($data['image'])) {
            $data['image'] = UploadHelper::upload('image', $data['image'], 'images-'.$data['user_id']. '-' . time(), 'uploads/images/contacts');
        }

        return Contact::create($data);
    }

    /**
     * Get Contact Detail By ID.
     *
     * @param int $id
     * @return void
     */
    public function getByID(int $id): Contact|null
    {
        return Contact::with('user')->find($id);
    }

    /**
     * Update Contact By ID.
     *
     * @param int $id
     * @param array $data
     * @return object Updated Contact Object
     */
    public function update(int $id, array $data): Contact|null
    {
        $contact = Contact::find($id);
        if (!empty($data['image'])) {
            $data['image'] = UploadHelper::upload('image', $data['image'], 'images-'.$data['user_id']. '-' . time(), 'uploads/images/contacts');
        } else {
            $data['image'] = $contact->image;
        }

        if (is_null($contact)) {
            return null;
        }

        // If everything is OK, then update.
        $contact->update($data);

        // Finally return the updated Contact.
        return $this->getByID($contact->id);
    }

    /**
     * Delete Contact.
     *
     * @param int $id
     * @return boolean true if deleted otherwise false
     */
    public function delete(int $id): bool
    {
        $contact = Contact::find($id);
        if (empty($contact)) {
            return false;
        }

        //UploadHelper::deleteFile('uploads/images/contacts/' . $contact->image);
        $contact->delete($contact);
        return true;
    }
}
