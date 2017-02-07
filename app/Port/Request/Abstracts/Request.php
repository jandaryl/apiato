<?php

namespace App\Port\Request\Abstracts;

use App\Port\Exception\Exceptions\IncorrectIdException;
use App\Port\Exception\Exceptions\ValidationFailedException;
use App\Port\HashId\Traits\HashIdTrait;
use App\Port\Request\Traits\RequestTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as LaravelFrameworkRequest;
use Illuminate\Support\Facades\Config;

/**
 * Class Request
 *
 * A.K.A (app/Http/Requests/Request.php)
 *
 * @author  Mahmoud Zalt  <mahmoud@zalt.me>
 */
abstract class Request extends LaravelFrameworkRequest
{

    use HashIdTrait;
    use RequestTrait;

    /**
     * Overriding this function to throw a custom
     * exception instead of the default Laravel exception.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     *
     * @return mixed|void
     */
    public function failedValidation(Validator $validator)
    {
        throw new ValidationFailedException($validator->getMessageBag());
    }

    /**
     * Overriding this function to modify the any user input before
     * applying the validation rules.
     *
     * @return  array
     */
    public function all()
    {
        $requestData = parent::all();

        // the hash ID feature must be enabled to use this decoder feature.
        if (isset($this->decode) && !empty($this->decode) && Config::get('hello.hash-id')) {
            $requestData = $this->decode($requestData);
        }

        return $requestData;
    }

    /**
     * @param array $requestData
     *
     * @return  array
     */
    private function decode(Array $requestData)
    {
        // without decoding the encoded ID's you won't be able to use
        // validation features like `exists:table,id`
        foreach ($this->decode as $id) {

            if (isset($requestData[$id])) {
                // validate the user is not trying to pass real ID
                if (is_int($requestData[$id])) {
                    throw new IncorrectIdException('Only Hashed ID\'s allowed to be passed.');
                }

                // perform the decoding
                $requestData[$id] = $this->decodeThisId($requestData[$id]);
            } // do nothing if the input is incorrect, because what if it's not required!
        }

        return $requestData;
    }

}
