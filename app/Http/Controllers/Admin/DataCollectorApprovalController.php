<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataCollectorProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DataCollectorApprovalController extends Controller
{
    /**
     * Show pending Data Collector applications
     *
     * @return \Illuminate\View\View
     */
    public function pendingApplications()
    {
        $pending_applications = DataCollectorProfile::pending()
            ->with(['user', 'verificationDocuments'])
            ->orderBy('submitted_at', 'desc')
            ->paginate(10);

        return view('admin.data-collectors.pending', compact('pending_applications'));
    }

    /**
     * Show approved Data Collectors
     *
     * @return \Illuminate\View\View
     */
    public function approvedCollectors()
    {
        $approved_collectors = DataCollectorProfile::approved()
            ->with('user')
            ->orderBy('reviewed_at', 'desc')
            ->paginate(15);

        return view('admin.data-collectors.approved', compact('approved_collectors'));
    }

    /**
     * Review specific Data Collector application
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function reviewApplication($id)
    {
        $profile = DataCollectorProfile::with(['user', 'verificationDocuments'])
            ->findOrFail($id);

        // Check if already reviewed
        if ($profile->approval_status !== 'pending') {
            return redirect()->route('admin.data-collectors.pending')
                ->with('info', 'This application has already been ' . $profile->approval_status);
        }

        return view('admin.data-collectors.review', compact('profile'));
    }

    /**
     * Approve Data Collector application
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $profile = DataCollectorProfile::findOrFail($id);

            // Check if already approved
            if ($profile->approval_status === 'approved') {
                return redirect()->route('admin.data-collectors.pending')
                    ->with('info', 'This application is already approved.');
            }

            // Approve the profile
            $profile->approve(auth()->id());

            // Verify all documents
            foreach ($profile->verificationDocuments as $document) {
                $document->verify(auth()->id(), 'Approved by admin');
            }

            DB::commit();

            // TODO: Send notification email/SMS to the data collector

            return redirect()->route('admin.data-collectors.pending')
                ->with('success', $profile->user->name . ' has been approved as a Data Collector!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Approval failed: ' . $e->getMessage());
        }
    }

    /**
     * Reject Data Collector application
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        try {
            DB::beginTransaction();

            $profile = DataCollectorProfile::findOrFail($id);

            // Reject the profile
            $profile->reject(auth()->id(), $request->rejection_reason);

            // Reject all documents
            foreach ($profile->verificationDocuments as $document) {
                $document->reject(auth()->id(), 'Application rejected');
            }

            DB::commit();

            // TODO: Send rejection notification email/SMS to the data collector

            return redirect()->route('admin.data-collectors.pending')
                ->with('success', 'Application rejected. The user has been notified.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Rejection failed: ' . $e->getMessage());
        }
    }
}