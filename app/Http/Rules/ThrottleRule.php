<?php

namespace App\Http\Rules;

use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class ThrottleRule implements Rule
{
    use ThrottlesLogins;
    /**
     * The throttle key.
     *
     * @var string
     */
    protected $key = 'validation';

    /**
     * The maximum number of attempts a user can perform.
     *
     * @var int
     */
    protected $maxAttempts = 5;

    /**
     * The amount of minutes to restrict the requests by.
     *
     * @var int
     */
    protected $decayInMinutes = 10;

    /**
     * Create a new rule instance.
     *
     * @param string $key
     * @param int    $maxAttempts
     * @param int    $decayInMinutes
     *
     * @return void
     */
    public function __construct($key = 'validation', $maxAttempts = 5, $decayInMinutes = 10)
    {
        $this->key = $key;
        $this->maxAttempts = $maxAttempts;
        $this->decayInMinutes = $decayInMinutes;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->hasTooManyLoginAttempts(request())) {
            return false;
        }

        $this->incrementLoginAttempts(request());

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Too many attempts. Please try again later.');
    }

    protected function throttleKey(Request $request)
    {
        return $request->input($request->ip());
    }

}