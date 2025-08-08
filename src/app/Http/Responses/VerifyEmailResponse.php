<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\VerifyEmailResponse as VerifyEmailResponseContract;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;

class VerifyEmailResponse implements VerifyEmailResponseContract
{
  public function toResponse($request)
  {
    return app(LoginController::class)->loginFromEmailVerify($request);
  }
}
