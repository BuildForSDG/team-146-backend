<?php
namespace App\Http\Controllers\API;


Use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $user = $this->create($request->all());
        $this->guard()->login($user);
        return response()->json([
            'user' => $user,
            'token' => $user->createToken('myChamaApp')->plainTextToken,
            'message' => 'registration successful'
        ], 200);
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            //'password' => ['required', 'string', 'min:4', 'confirmed'],
            // NO PASSWORD CONFIRMATION
            'password' => ['required', 'string', 'min:4'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * Create a token after a valid/successful login .
     *
     * @param  array  $request
     * @return json Response
     */
    public function login(Request $request)
    {
        $credentials = Validator::make($request->only('email','password'), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($credentials->fails()) {
           return response()->json($credentials->errors(), 422);
        }
        if ($credentials->passes()) {

            $credentialsDetails = array(
                'email' => $request->email,
                'password' => $request->password
            );
            if (Auth::attempt($credentialsDetails)) {
                // Authentication passed...
                $authuser = auth()->user();
                return response()->json(
                    [
                        'status' => 200,
                        'message' => 'Login successful',
                        'data' => [
                            'user_id' => $authuser->id,
                            'user_email' => $authuser->email,
                            'token' => $authuser->createToken('myChamaApp')->plainTextToken,
                        ]
                    ],
                    200);
            } else {
                return response()->json(['message' => 'Invalid email or password'], 401);
            }
        }

    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Logged Out'], 200);
    }
}
