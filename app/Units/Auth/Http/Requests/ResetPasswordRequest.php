<?php

namespace App\Units\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ResetPasswordRequest
 *
 * @package App\Units\Auth\Http\Requests
 */
class ResetPasswordRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize(): bool
  {
    return TRUE;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules(): array
  {
    return [
      'token'    => 'required|string',
      'email'    => 'required|string|email|exists:users',
      'password' => 'required|string|min:8|max:60|confirmed',
    ];
  }
}
