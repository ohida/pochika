<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Config;

class SearchRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'q' => 'max:'.Config::get('pochika.search_query_max'),
		];
	}

	/**
	 * Get the sanitized input for the request.
	 *
	 * @return array
	 */
	public function sanitize()
	{
		return $this->all();
	}

	public function response(array $errors)
	{
		return $this->redirector->to(url('search'))
			->withInput($this->except($this->dontFlash))
			->withErrors($errors, $this->errorBag);
	}

}
