<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateChatAPIRequest;
use App\Http\Requests\API\UpdateChatAPIRequest;
use App\Models\Chat;
use App\Repositories\ChatRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class ChatAPIController
 */
class ChatAPIController extends AppBaseController
{
    private ChatRepository $chatRepository;

    public function __construct(ChatRepository $chatRepo)
    {
        $this->chatRepository = $chatRepo;
    }

    /**
     * @OA\Get(
     *      path="/api/chats",
     *      summary="Get all Chats",
     *      tags={"Chats"},
     *      description="Display a listing of the Chats",
     *      operationId="index",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Chat")
     *          ),
     *      ),
     *      security={
     *          {"bearerAuth": {}}
     *      }
     * )
     * @OA\Schema(
     *      schema="Chat",
     *      title="Chat",
     *      description="Chat message schema",
     *      @OA\Property(
     *          property="id",
     *          type="integer",
     *          format="int64",
     *          description="Unique identifier for the chat message"
     *      ),
     *      @OA\Property(
     *          property="message",
     *          type="string",
     *          description="Content of the chat message"
     *      ),
     *      @OA\Property(
     *          property="sender_id",
     *          type="integer",
     *          format="int64",
     *          description="Identifier of the user who sent the message"
     *      ),
     *      @OA\Property(
     *          property="receiver_id",
     *          type="integer",
     *          format="int64",
     *          description="Identifier of the user or group who received the message"
     *      ),
     *      @OA\Property(
     *          property="timestamp",
     *          type="string",
     *          format="date-time",
     *          description="Timestamp indicating when the message was sent"
     *      ),
     *      @OA\Property(
     *          property="status",
     *          type="string",
     *          description="Status of the message (e.g., sent, delivered, read)"
     *      ),
     *      @OA\Property(
     *          property="type",
     *          type="string",
     *          description="Type of message (e.g., text, image, video)"
     *      ),
     *      @OA\Property(
     *          property="conversation_id",
     *          type="integer",
     *          format="int64",
     *          description="Identifier of the conversation thread or chat session"
     *      ),
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $chats = $this->chatRepository->all(
            ['*'],
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($chats->toArray(), 'Chats retrieved successfully');
    }

    /**
     * @OA\Post(
     *      path="/api/chats",
     *      summary="Create a new Chat",
     *      tags={"Chats"},
     *      description="Store a newly created Chat in storage",
     *      operationId="store",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Chat data",
     *          @OA\JsonContent(ref="#/components/schemas/CreateChatRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/Chat"
     *              )
     *          )
     *      ),
     *      security={
     *          {"bearerAuth": {}}
     *      }
     * )
     *  * @OA\Schema(
     *      schema="CreateChatRequest",
     *      title="Create Chat Request",
     *      description="Request body schema for creating a new Chat",
     *      required={"message", "sender_id", "receiver_id"},
     *      @OA\Property(
     *          property="message",
     *          type="string",
     *          description="Content of the chat message",
     *      ),
     *      @OA\Property(
     *          property="sender_id",
     *          type="integer",
     *          format="int64",
     *          description="Identifier of the user who sent the message",
     *      ),
     *      @OA\Property(
     *          property="receiver_id",
     *          type="integer",
     *          format="int64",
     *          description="Identifier of the user or group who received the message",
     *      ),
     *      @OA\Property(
     *          property="community_id",
     *          type="integer",
     *          format="int64",
     *          description="Identifier of the user or group who received the message",
     *      ),
     *      @OA\Property(
     *          property="attachment",
     *          type="string",
     *          description="Attachment data or reference (optional)",
     *      ),
     *      @OA\Property(
     *          property="type",
     *          type="string",
     *          description="Type of message (e.g., text, image, video) (optional)",
     *      ),
     *       @OA\Property(
     *          property="status",
     *          type="string",
     *          description="Status",
     *      ),
     * )
     */
    public function store(CreateChatAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $chat = $this->chatRepository->create($input);

        return $this->sendResponse($chat->toArray(), 'Chat saved successfully');
    }

    /**
     * Display the specified Chat.
     * GET|HEAD /chats/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var Chat $chat */
        $chat = $this->chatRepository->find($id);

        if (empty($chat)) {
            return $this->sendError('Chat not found');
        }

        return $this->sendResponse($chat->toArray(), 'Chat retrieved successfully');
    }

    /**
     * Update the specified Chat in storage.
     * PUT/PATCH /chats/{id}
     */
    public function update($id, UpdateChatAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Chat $chat */
        $chat = $this->chatRepository->find($id);

        if (empty($chat)) {
            return $this->sendError('Chat not found');
        }

        $chat = $this->chatRepository->update($input, $id);

        return $this->sendResponse($chat->toArray(), 'Chat updated successfully');
    }

    /**
     * Remove the specified Chat from storage.
     *
     * @param int $id Chat ID
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Delete(
     *     path="/api/chats/{id}",
     *     summary="Remove the specified Chat",
     *     tags={"Chats"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the Chat",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Chat deleted successfully"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Chat not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Chat not found"
     *             )
     *         )
     *     )
     * )
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var Chat $chat */
        $chat = $this->chatRepository->find($id);

        if (empty($chat)) {
            return $this->sendError('Chat not found', 404);
        }

        $chat->delete();

        return $this->sendResponse('Chat deleted successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/user/chat",
     *     summary="Get user chats",
     *     description="Retrieve chats for a user with a specific receiver and community.",
     *     operationId="userChat",
     *     tags={"Chats"},
     *     @OA\Parameter(
     *         name="receiver_id",
     *         in="query",
     *         required=true,
     *         description="The ID of the receiver",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="community_id",
     *         in="query",
     *         required=true,
     *         description="The ID of the community",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="User Chats"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="sender_id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="receiver_id",
     *                         type="integer",
     *                         example=2
     *                     ),
     *                     @OA\Property(
     *                         property="community_id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         example="Hello, how are you?"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         format="date-time",
     *                         example="2023-07-21T17:32:28Z"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         format="date-time",
     *                         example="2023-07-21T17:32:28Z"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Invalid parameters"
     *             )
     *         )
     *     )
     * )
     */
    public function userChat()
    {
        $chats = $this->chatRepository->findByField([
            'sender_id' => 1,
            'receiver_id' => request()->receiver_id,
            'community_id' => request()->community_id,
        ]);

        return $this->sendResponse('User Chats', $chats);
    }
}
