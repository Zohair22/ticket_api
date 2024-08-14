<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;

/**
 * Class ApiBaseController - main API base class
 *
 * This class is used as an inherited class to add any functionality we want all
 * sub controller API classes to have.
 *
 * Split the standard HTTP responses and structure into a trait, to allow more flexibility here
 *
 *
 */
class ApiBaseController extends Controller
{
    use ApiResponseTrait;
}
