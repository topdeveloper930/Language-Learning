<?php


namespace App\Http\Controllers\API;



use App\Http\Controllers\APIController;

/**
 * @apiDefine NoUserError Unauthorized
 * @apiError (401) {json} Unauthorized The <code>Bearer Token</code> provided not valid.
 * @apiErrorExample {json} Error-Response:
 * HTTP/1.1 401 Unauthorized
 * Content-Type: application/json
 * {
 *     "error": "Unauthenticated."
 * }
 */

/**
 * @apiDefine NoUserPermit Forbidden
 * @apiError (403) {json} Forbidden The User with <code>Bearer Token</code> provided is not allowed for requested action.
 * @apiErrorExample {json} Error-Response:
 * HTTP/1.1 403 Forbidden
 * Content-Type: application/json
 * {
 *     "error": "Not authorized"
 * }
 */

/**
 * @apiDefine NotFound
 * @apiError (404) {json} NotFound Resource not found.
 * @apiErrorExample {json}  Error-Response:
 * HTTP/1.1 404 Not Found
 * Content-Type: application/json
 * {
 *     "error": ["Resource not found"]
 * }
 */

/**
 * @apiDefine ValidationError Unprocessable
 * @apiError (422) {json} UnprocessableEntity Data validation error(s).
 * @apiErrorExample {json} Error-Response:
 * HTTP/1.1 422 Unprocessable Entity
 * Content-Type: application/json
 * {
 *    "title": ["The title may not be greater than 10 characters."],
 *    "email": [
 *	    "The email must be a valid email address.",
 *	    "The email may not be greater than 150 characters."
 *	  ]
 * }
 */

/**
 * @apiDefine ServerError ServiceUnavailable
 * @apiError (503) {String} ServiceUnavailable Server side error occurred. It may also happen if the student has been already removed.
 * @apiErrorExample {html} Error-Response:
 * HTTP/1.1 503 Service Unavailable
 * Content-Type: text/html; charset=UTF-8
 *
 * Cannot process your request. Please, try later.
 */

/**
 * Dummy class for apidoc sake only.
 *
 * Class AuthenticationController
 * @package App\Http\Controllers\API
 */
class AuthenticationController extends APIController {

	/**
	 * @api {all methods} /api/
	 * @apiName Auth
	 * @apiGroup Authentication
	 * @apiDescription The API uses Bearer Token authentication.<br>
	 * The client must send this token in the Authorization header when making requests to our API.<br>
	 * You will get 401 Unauthorized response code in case of invalid or missing token.<br>
	 * There is also a system of permits. The API user allowed to access only own or permitted data. Otherwise 403 Forbidden response code with "Not authorized" error in the response body returned.<br>
	 * Please, contact the site administrator to obtain valid token.
	 *
	 * @apiParamExample {json} Request-Example:
	 * GET {{host}}/api/teacher/
	 * Accept: application/json
	 * Content-Type: application/json
	 * Authorization: Bearer SOME_TOKEN_STRING_HERE
	 *
	 * @apiUse NoUserError
	 * @apiUse NoUserPermit
	 */
	public function authentication()
	{
		// Fake method for apidoc only.
		return $this->notImplemented();
	}
}