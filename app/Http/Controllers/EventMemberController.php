<?php

namespace App\Http\Controllers;

use App\Enums\EventRole;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EventMemberController extends Controller
{
    public function index(Event $event): Collection
    {
        $this->authorizeEventAccess($event, 'members');

        return $event->members()->orderBy('name')->get();
    }

    public function store(Request $request, Event $event): JsonResponse
    {
        $this->authorizeEventAccess($event, 'members');

        $data = $request->validate([
            'user_id' => ['required', 'uuid', 'exists:users,id'],
            'role' => ['required', Rule::enum(EventRole::class)],
        ]);

        $user = User::query()->findOrFail($data['user_id']);

        abort_if($event->isOwnedBy($user), 422, 'Pemilik event tidak perlu menjadi anggota.');
        abort_if(
            $event->members()->whereKey($user->id)->exists(),
            422,
            'Pengguna sudah menjadi anggota event.',
        );

        $event->members()->attach($user->id, ['role' => $data['role']]);

        return response()->json([
            'message' => 'Anggota event ditambahkan',
            'data' => $event->members()->whereKey($user->id)->firstOrFail(),
        ], 201);
    }

    public function destroy(Event $event, string $user): JsonResponse
    {
        $this->authorizeEventAccess($event, 'members');

        $member = User::query()->findOrFail($user);

        abort_if($event->isOwnedBy($member), 422, 'Pemilik event tidak dapat dihapus dari anggota.');
        abort_unless($event->members()->whereKey($member->id)->exists(), 404);

        $event->members()->detach($member->id);

        return response()->json([
            'message' => 'Anggota event dihapus',
        ]);
    }
}
