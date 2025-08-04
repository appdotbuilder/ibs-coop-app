<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Member;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MemberController extends Controller
{
    /**
     * Display a listing of members.
     */
    public function index(Request $request)
    {
        $query = Member::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('member_id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $members = $query->latest()->paginate(15);

        return Inertia::render('members/index', [
            'members' => $members,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * Show the form for creating a new member.
     */
    public function create()
    {
        return Inertia::render('members/create', [
            'nextMemberId' => Member::generateMemberId(),
        ]);
    }

    /**
     * Store a newly created member.
     */
    public function store(StoreMemberRequest $request)
    {
        $data = $request->validated();
        $data['member_id'] = Member::generateMemberId();
        
        $member = Member::create($data);

        return redirect()->route('members.show', $member)
            ->with('success', 'Member created successfully.');
    }

    /**
     * Display the specified member.
     */
    public function show(Member $member)
    {
        $member->load(['transactions' => function ($query) {
            $query->latest()->take(10);
        }, 'installments.payments']);

        return Inertia::render('members/show', [
            'member' => $member,
        ]);
    }

    /**
     * Show the form for editing the specified member.
     */
    public function edit(Member $member)
    {
        return Inertia::render('members/edit', [
            'member' => $member,
        ]);
    }

    /**
     * Update the specified member.
     */
    public function update(UpdateMemberRequest $request, Member $member)
    {
        $member->update($request->validated());

        return redirect()->route('members.show', $member)
            ->with('success', 'Member updated successfully.');
    }

    /**
     * Remove the specified member.
     */
    public function destroy(Member $member)
    {
        // Check if member has active transactions or installments
        if ($member->transactions()->where('status', '!=', 'cancelled')->exists() ||
            $member->installments()->where('status', 'active')->exists()) {
            return back()->with('error', 'Cannot delete member with active transactions or installments.');
        }

        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Member deleted successfully.');
    }
}