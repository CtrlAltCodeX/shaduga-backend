<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUserAPIRequest;
use App\Http\Requests\API\UpdateUserAPIRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Mail\OtpMail;
use App\Repositories\MemberRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

/**
 * Class UserAPIController
 */
class UserAPIController extends AppBaseController
{
    private UserRepository $userRepository;

    public function __construct(
        UserRepository $userRepo,
        public MemberRepository $memberRepository
    ) {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the Users.
     * GET|HEAD /users
     */
    public function index(Request $request): JsonResponse
    {
        $users = $this->userRepository->all(
            ['*'],
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($users->toArray(), 'Users retrieved successfully');
    }

    /**
     * Store a newly created User in storage.
     * POST /users
     */
    public function store(CreateUserAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $user = $this->userRepository->create($input);

        return $this->sendResponse($user->toArray(), 'User saved successfully');
    }

    /**
     * Display the specified User.
     * GET|HEAD /users/{id}
     * 
     * @OA\Get(
     *      path="/users/{id}",
     *      operationId="getUserById",
     *      tags={"Users"},
     *      summary="Get a specific user",
     *      description="Returns the specified user",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the user",
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found"
     *      )
     * )
     * 
     *  * @OA\Schema(
     *      schema="User",
     *      title="User",
     *      description="User model",
     *      @OA\Property(
     *          property="id",
     *          type="integer",
     *          format="int64",
     *          description="ID"
     *      ),
     *      @OA\Property(
     *          property="name",
     *          type="string",
     *          description="Name of the user"
     *      ),
     * )
     */
    public function show($id): JsonResponse
    {
        /** @var User $user */
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        return $this->sendResponse($user->toArray(), 'User retrieved successfully');
    }

    /**
     * Update the specified User in storage.
     * PUT/PATCH /api/users/{id}
     * 
     * @OA\Put(
     *      path="/api/users/{id}",
     *      operationId="updateUser",
     *      tags={"Users"},
     *      summary="Update a specific user",
     *      description="Updates the specified user",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the user",
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="User data to update",
     *          @OA\JsonContent(ref="#/components/schemas/UserRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User updated successfully",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found"
     *      )
     * )
     * 
     * * @OA\Schema(
     *      schema="UserRequest",
     *      title="User Request",
     *      description="User request data",
     *      @OA\Property(
     *          property="name",
     *          type="string",
     *          description="Name of the user"
     *      ),
     *      @OA\Property(
     *          property="email",
     *          type="string",
     *          format="email",
     *          description="Email of the user"
     *      ),
     * )
     */
    public function update($id, UpdateUserAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var User $user */
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        $user = $this->userRepository->update($input, $id);

        return $this->sendResponse($user->toArray(), 'User updated successfully');
    }

    /**
     * Remove the specified User from storage.
     * DELETE /users/{id}
     *
     * @OA\Delete(
     *      path="/users/{id}",
     *      operationId="deleteUser",
     *      tags={"Users"},
     *      summary="Delete a specific user",
     *      description="Deletes the specified user",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the user",
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User deleted successfully"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found"
     *      )
     * )
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var User $user */
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        $user->delete();

        return $this->sendResponse('User deleted successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(
     *                 property="community_id",
     *                 type="array",
     *                 @OA\Items(type="integer"),
     *                 example={1, 2, 3}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="user@example.com"),
     *                     @OA\Property(
     *                          property="community_id",
     *                          type="array",
     *                          @OA\Items(type="integer"),
     *                          example={1, 2, 3}
     *                      )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="User registered successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid input data")
     *         )
     *     )
     * )
     */
    public function register()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'community_id' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $data = [
            'name'       => request()->name,
            'email'      => request()->email,
            'password'   => Hash::make(request()->password),
        ];

        $lastId = $this->userRepository->create($data);

        foreach (request()->community_id as $communities) {
            $this->memberRepository->create([
                "community_id" => $communities,
                "user_id" => $lastId->id,
                "join_date" => now(),
                "status" => 1,
                "role" => 0,
                "last_active" => now()
            ]);
        }

        $user['user'] = $data;
        $user['user']['community_id'] = request()->community_id;

        return $this->sendResponse('Register successfully', $user);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="User login",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="token", type="string", example="JWT token"),
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="user@example.com")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="User login successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid email or password")
     *         )
     *     )
     * )
     * @OA\Info(
     *     title="API Documentation",
     *     version="1.0.0",
     *     description="API documentation for the login endpoint",
     *     @OA\Contact(
     *         email="contact@example.com"
     *     )
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $userIsActive = User::where('status', 1)
            ->where('email', request()->email)
            ->first();

        if (!$userIsActive) return $this->sendError('User is not active');

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return $this->sendError('Unauthorized');
        }

        $user = Auth::user();

        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['user'] = $user;

        return $this->sendResponse('User Login Successfully', $success);
    }

    /**
     * Generate Random Digits
     *
     * @return int
     */
    public function generateOTP($n)
    {
        $generator = "1357902468";
        $result = "";

        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, rand() % strlen($generator), 1);
        }

        // Returning the result
        return $result;
    }

    /**
     * @OA\Post(
     *     path="/api/send/otp",
     *     summary="Send OTP",
     *     description="Send OTP to the user's email address.",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", example="user@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="OTP sent successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal server error message")
     *         )
     *     )
     * )
     */
    public function sendOTP()
    {
        try {
            request()->validate([
                'email' => 'required'
            ]);

            $otp = $this->generateOTP(4);

            // $user = User::where('email', request()->email)
            //     ->first();

            // if (!$user) return $this->sendError('Email Id Invalid');

            Mail::to(request()->email)->send(new OtpMail($otp));

            User::where('email', request()->email)->update([
                'otp' => $otp
            ]);

            return $this->sendResponse('OTP send successfully');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/verify/otp",
     *     summary="Verify OTP",
     *     description="Verify the OTP provided by the user.",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"otp"},
     *             @OA\Property(property="otp", type="string", example="123456"),
     *             @OA\Property(property="email", type="string", example="admin@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP successfully verified",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="OTP successfully matched")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid OTP",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid OTP")
     *         )
     *     )
     * )
     */
    public function verifyOTP()
    {
        request()->validate([
            'otp' => 'required',
            'email' => 'required',
        ]);

        // $user = User::where('email', request()->email)
        //     ->first();

        // if (!$user) return $this->sendError('Email Id Invalid');

        $userWithOTP = User::where('email', request()->email)
            ->where('otp', request()->otp)
            ->first();

        if (!$userWithOTP) return $this->sendError('Invalid OTP');

        User::where('email', request()->email)
            ->where('otp', request()->otp)
            ->update(['status' => 1]);

        return $this->sendResponse('OTP successfully matched');
    }

    /**
     * @OA\Get(
     *     path="/api/current/user",
     *     summary="Get Current User",
     *     description="Returns the current authenticated user",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Current User",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="user", type="object",
     *                      @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=1
     *                      ),
     *                      @OA\Property(
     *                          property="name",
     *                          type="string",
     *                          example="John Doe"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string",
     *                          example="johndoe@example.com"
     *                      )
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Current User"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Please Login!!",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Please Login!!"
     *             )
     *         )
     *     )
     * )
     */
    public function currentUser()
    {
        if (!auth()->check()) return $this->sendError('Please Login!!');

        return $this->sendResponse('Current User', auth()->user());
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout the authenticated user",
     *     operationId="logoutUser",
     *     tags={"Authentication"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully Logged out",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Successfully Logged out")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function logout()
    {
        if (auth()->check()) {
            auth()->user()->tokens()->delete();
        }

        return $this->sendResponse('Successfully Logged out');
    }
}
