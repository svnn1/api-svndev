<?php

namespace App\Units\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ForgotPasswordRequest
 *
 * @package App\Units\Auth\Http\Requests
 */
class ForgotPasswordRequest extends FormRequest
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
      'email' => 'required|email|exists:users',
    ];
  }
}
