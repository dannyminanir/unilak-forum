<x-layouts.app>
    <x-slot name="title">User Management — Admin</x-slot>

    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
        <p class="text-sm text-gray-500 mt-1">Manage student roles, view activity, and enforce community bans</p>
    </x-slot>

    {{-- Filter bar --}}
    <x-card class="mb-6 !p-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row items-center gap-3">
            <input type="text" name="q" value="{{ $search }}" placeholder="Search by name, email, student ID, department..."
                   class="w-full sm:flex-1 text-xs rounded-lg border-gray-300 p-2 shadow-sm focus:border-primary-800 focus:ring-primary-800" />
            
            <select name="role" class="text-xs rounded-lg border-gray-300 p-2 text-gray-700 shadow-sm">
                <option value="">All Roles</option>
                <option value="student" {{ $role === 'student' ? 'selected' : '' }}>Student</option>
                <option value="moderator" {{ $role === 'moderator' ? 'selected' : '' }}>Moderator</option>
                <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>

            <select name="status" class="text-xs rounded-lg border-gray-300 p-2 text-gray-700 shadow-sm">
                <option value="">All Statuses</option>
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                <option value="banned" {{ $status === 'banned' ? 'selected' : '' }}>Suspended / Banned</option>
            </select>

            <x-button type="submit" variant="primary" class="!py-2 !px-4 !text-xs whitespace-nowrap">Filter</x-button>
        </form>
    </x-card>

    {{-- Users Table --}}
    <x-card class="overflow-x-auto !p-0">
        <table class="w-full text-left text-xs text-gray-600">
            <thead class="bg-gray-50 border-b border-gray-200 uppercase font-semibold text-gray-500">
                <tr>
                    <th class="py-3 px-4">Student / User</th>
                    <th class="py-3 px-4">Department & ID</th>
                    <th class="py-3 px-4">Role</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $u)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary-800 text-white flex items-center justify-center font-bold text-xs uppercase flex-shrink-0">
                                    {{ substr($u->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $u->name }}</p>
                                    <p class="text-[11px] text-gray-400">{{ $u->email }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="py-3.5 px-4">
                            <p class="font-medium text-gray-800">{{ $u->department ?? '—' }}</p>
                            <p class="text-[11px] text-gray-400 font-mono">{{ $u->student_id ?? 'No Reg ID' }}</p>
                        </td>

                        <td class="py-3.5 px-4">
                            <form method="POST" action="{{ route('admin.users.role', $u) }}" class="inline-block">
                                @csrf
                                <select name="role" onchange="this.form.submit()"
                                        class="text-xs rounded border-gray-300 p-1 font-semibold {{ $u->role === 'admin' ? 'text-red-700 bg-red-50' : 'text-gray-700' }}">
                                    <option value="student" {{ $u->role === 'student' ? 'selected' : '' }}>Student</option>
                                    <option value="moderator" {{ $u->role === 'moderator' ? 'selected' : '' }}>Moderator</option>
                                    <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </form>
                        </td>

                        <td class="py-3.5 px-4">
                            @if($u->is_banned)
                                <span class="inline-flex items-center gap-1 font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded text-[11px]">
                                    ⛔ Banned
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 font-bold text-green-700 bg-green-50 px-2 py-0.5 rounded text-[11px]">
                                    ✓ Active
                                </span>
                            @endif
                        </td>

                        <td class="py-3.5 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                {{-- Ban / Unban --}}
                                @if($u->id !== auth()->user()->id)
                                    <form method="POST" action="{{ route('admin.users.ban', $u) }}">
                                        @csrf
                                        <button type="submit" class="font-semibold text-xs {{ $u->is_banned ? 'text-green-600 hover:underline' : 'text-amber-600 hover:underline' }}">
                                            {{ $u->is_banned ? 'Unban' : 'Ban' }}
                                        </button>
                                    </form>

                                    {{-- Delete --}}
                                    <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Delete this user permanently?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-semibold text-xs text-red-600 hover:underline ml-2">
                                            Delete
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 italic text-[11px]">You</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-card>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</x-layouts.app>

