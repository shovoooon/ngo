<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Http\Resources\MemberResource;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('member_code', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        }

        $members = $query->latest()->paginate(15)->withQueryString();

        return Inertia::render('Members/Index', [
            'members' => MemberResource::collection($members),
            'filters' => $request->only('search')
        ]);
    }

    public function create()
    {
        return Inertia::render('Members/Create');
    }

    public function store(StoreMemberRequest $request)
    {
        $validated = $request->validated();

        // Generate a simple unique member code (e.g. M-1001)
        $latestMember = Member::latest('id')->first();
        $nextId = $latestMember ? $latestMember->id + 1 : 1;
        $validated['member_code'] = 'M-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        Member::create($validated);

        return redirect()->route('members.index')->with('success', 'Member created successfully.');
    }

    public function show(Member $member)
    {
        $member->load(['loans', 'savingsAccounts']);
        return Inertia::render('Members/Show', [
            'member' => (new MemberResource($member))->resolve()
        ]);
    }

    public function edit(Member $member)
    {
        return Inertia::render('Members/Edit', [
            'member' => (new MemberResource($member))->resolve()
        ]);
    }

    public function update(UpdateMemberRequest $request, Member $member)
    {
        $member->update($request->validated());

        return redirect()->route('members.index')->with('success', 'Member updated successfully.');
    }

    public function destroy(Member $member)
    {
        // Add protection logic if they have active loans or savings
        if ($member->loans()->where('status', 'active')->exists()) {
            return back()->with('error', 'Cannot delete member with active loans.');
        }

        if ($member->savingsAccount && $member->savingsAccount->balance > 0) {
            return back()->with('error', 'Cannot delete member with active savings balance.');
        }

        $member->delete();

        return redirect()->route('members.index')->with('success', 'Member deleted successfully.');
    }
}
