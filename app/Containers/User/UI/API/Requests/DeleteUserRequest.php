<?php

namespace App\Containers\User\UI\API\Requests;

use App\Port\Request\Abstracts\Request;

/**
 * Class DeleteUserRequest.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class DeleteUserRequest extends Request
{

    /**
     * The required Permissions to proceed with this request.
     *
     * @var  array
     */
    protected $access = [
        'permission' => 'delete-users'
    ];

    /**
     * Id's that needs decoding before applying the validation rules.
     *
     * @var  array
     */
    protected $decode = [

    ];

    /**
     * @return  array
     */
    public function rules()
    {
        return [

        ];
    }

    /**
     * @return  bool
     */
    public function authorize()
    {
        return $this->validatePermission();
    }
}
