<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;

class ValidateTokenController extends Controller
{
    public function validateToken(Request $request)
    {
        $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText('aaTz/gikuW4OUlUqulZjxGHzLxOF7HVPCMz76e4yz42eEh7ACrN8mpJeqwcQqAQnO7mZxI9LYx6DxxgyxkNZQA'));
        $token = $config->parser()->parse($request->token);
        assert($token instanceof UnencryptedToken);

        $constraints = $config->validationConstraints();

        if ($config->validator()->validate($token, new SignedWith($config->signer(), $config->verificationKey()))) {
            return "درسته";
        } else {
            return response()->json('اشتباهه', 403);
        }
    }
}
