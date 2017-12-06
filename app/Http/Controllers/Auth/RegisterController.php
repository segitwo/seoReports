<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\RegistrationToken;
use Illuminate\Http\Request;
use Kitano\Aktiv8me\ActivatesUsers;
use App\Http\Controllers\Controller;
use App\Notifications\Aktiv8me\TokenRenewed;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class RegisterController extends Controller
{
    use ActivatesUsers, RegistersUsers, ThrottlesLogins;

    /** @var \Illuminate\Http\Request */
    protected $request;

    /** @var \App\User */
    protected $user;

    /** @var string */
    protected $redirectTo = '/login';

    /**
     * Will carry flashed messages/json responses
     *
     * @var array
     */
    protected $status = [];

    /**
     * Create a new controller instance.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->middleware(['guest']);
    }

    /**
     * Show resend token form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getResend()
    {
        return view('auth.resend');
    }

    /**
     * Resend token by user request
     *
     * We'll try our best to avoid disclosing any information
     * about users. This feature could be used to check if
     * a given email address is registered or not.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postResend()
    {
        $this->emailValidator($this->request->input())->validate();

        // We take advantage of Laravel's ThrottlesLogins trait,
        // but a recaptcha on the Form should be implemented.
        if ($this->hasTooManyLoginAttempts($this->request)) {
            $this->fireLockoutEvent($this->request);

            return $this->sendLockoutResponse($this->request);
        }

        $this->incrementLoginAttempts($this->request);

        $this->user = User::findByEmail($this->request->input('email'));

        // No user, no go!
        if (is_null($this->user) || ! $this->user->count()) {
            // just apologise and throw some generic message
            $this->status = $this->setStatus(
                trans('aktiv8me.status.account_confirmation'),
                trans('aktiv8me.status.no_can_do'),
                422
            );

            return $this->sendResendResponse();
        }

        if ($this->user->verified) {
            // If a user is already active, we will send him
            // an email with that information, rather than
            // popping up any info alert on the screen.
            $this->setStatus($this->sendUserIsActiveEmail($this->user));

            return $this->sendResendResponse();
        }

        if (! $this->canSendToken($this->user->codes->count())) {
            $this->status = $this->setStatus(
                trans('aktiv8me.status.account_confirmation'),
                trans('aktiv8me.status.max_tokens'),
                403
            );

            return $this->sendResendResponse();
        }

        // good to go! generate new token and mail it
        $this->status = $this->setStatus(
            $this->sendActivationEmail(
                $this->user,
                RegistrationToken::makeToken($this->user->email),
                $this->user->codes->count() + 1
            )
        );

        $this->clearLoginAttempts($this->request);

        return $this->sendResendResponse();
    }

    /**
     * Register a user
     *
     * Overrides method in \Illuminate\Foundation\Auth\RegistersUsers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register()
    {
        $this->registerValidator($this->request->input())->validate();

        $this->storeUser();

        return $this->sendRegisterResponse();
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Confirm/Activate a user
     *
     * This method only supports HTTP requests.
     * Tweaks are necessary, if front-end is
     * a JavaScript App.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify($token)
    {
        /** @var \App\RegistrationToken $valid_token */
        $valid_token = RegistrationToken::findToken($token);

        if (! $valid_token) {
            $this->status = $this->setStatus(
                trans('aktiv8me.status.account_confirmation'),
                trans('aktiv8me.status.invalid_token'),
                403
            );

            return redirect('/')->with('status', $this->status);
        }

        $this->user = $valid_token->user;

        if ($this->tokenIsExpired($valid_token)) {
            $this->renewToken();

            return redirect('/')->with('status', $this->status);
        }

        $this->sendWelcomeEmail($this->user)
             ->destroyToken($valid_token->user_id);

        if ($this->autoLoginEnabled()) {
            $this->guard()->login($this->user);

            $this->status = $this->setStatus(
                trans('aktiv8me.status.account_confirmation'),
                trans('aktiv8me.status.account_confirmed_and_in', ['username' => $this->user->name]),
                false
            );

            return redirect('/')->with('status', $this->status);
        }

        $this->status = $this->setStatus(
            trans('aktiv8me.status.account_confirmation'),
            trans('aktiv8me.status.account_confirmed'),
            false
        );

        return redirect('/login')->with('status', $this->status);
    }

    /**
     * Destroy used tokens
     *
     * @param $user
     *
     * @return $this
     */
    protected function destroyToken($user)
    {
        RegistrationToken::deleteCode($user);

        return $this;
    }

    /**
     * The user has been registered.
     *
     * @return void
     */
    protected function registered()
    {
        if ($this->aktiv8enabled()) {
            $this->status = $this->setStatus(
                $this->sendActivationEmail($this->user, RegistrationToken::makeToken($this->user->email))
            );
        }

        if ($this->canAutoLogin()) {
            $this->guard()->login($this->user);

            $this->status = $this->setStatus(
                trans('aktiv8me.status.login'),
                trans('aktiv8me.status.first_login', ['username' => $this->user->name]),
                false
            );
        }
    }

    /**
     * Update an expired token
     *
     * @return $this
     */
    protected function renewToken()
    {
        if (! $this->canAutoResendToken()) {
            $this->status = $this->setStatus(
                trans('aktiv8me.status.account_confirmation'),
                trans('aktiv8me.status.token_expired').
                $this->canSendToken($this->user->codes->count()) ? trans('aktiv8me.status.can_resend') : '',
                422
            );

            return $this;
        }

        $this->status = $this->setStatus(
            $this->sendTokenUpdatedEmail($this->user, RegistrationToken::updateFor($this->user))
        );

        return $this;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResendResponse()
    {
        if ($this->request->expectsJson()) {
            return response()->json($this->status, $this->status['http_code']);
        }

        return redirect($this->redirectPath())
            ->with('status', $this->status);
    }

    /**
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function sendRegisterResponse()
    {
        if ($this->request->expectsJson()) {
            return response()->json($this->status, $this->status['http_code']);
        }

        return redirect($this->redirectPath())
            ->with('status', $this->status);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return static|\App\User
     */
    protected function storeUser()
    {
        $data = $this->request->input();

        $this->user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $this->registered();
    }
}
