<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnimalHealthProfessional;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class ProfessionalApprovalController extends Controller
{
    /**
     * Show pending professionals for approval
     */
    public function index()
    {
        $pendingProfessionals = AnimalHealthProfessional::with(['user.country', 'user.state', 'user.lga'])
            ->where('approval_status', 'pending')
            ->latest()
            ->paginate(20);

        $stats = [
            'pending' => AnimalHealthProfessional::where('approval_status', 'pending')->count(),
            'approved' => AnimalHealthProfessional::where('approval_status', 'approved')->count(),
            'rejected' => AnimalHealthProfessional::where('approval_status', 'rejected')->count(),
            'total' => AnimalHealthProfessional::count(),
        ];

        return view('admin.professionals.approvals', compact('pendingProfessionals', 'stats'));
    }

    /**
     * Approve a professional
     */
    public function approve($id)
    {
        DB::beginTransaction();
        
        try {
            $professional = AnimalHealthProfessional::with('user')->findOrFail($id);
            
            // Check if already approved
            if ($professional->approval_status === 'approved') {
                return back()->with('info', 'This professional is already approved.');
            }
            
            // Update professional record
            $professional->update([
                'approval_status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);
            
            // Update user account status
            $professional->user->update([
                'account_status' => 'active',
                'status' => 'active',
                'is_approved' => true,
            ]);
            
            DB::commit();
            
            // Send approval email
            $this->sendApprovalEmail($professional);
            
            return back()->with('success', 'Professional approved successfully! Notification email sent to ' . $professional->user->email);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Professional approval failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to approve professional: ' . $e->getMessage());
        }
    }

    /**
     * Reject a professional
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);
        
        DB::beginTransaction();
        
        try {
            $professional = AnimalHealthProfessional::with('user')->findOrFail($id);
            
            // Update professional record
            $professional->update([
                'approval_status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);
            
            // Update user account status
            $professional->user->update([
                'account_status' => 'suspended',
                'is_approved' => false,
            ]);
            
            DB::commit();
            
            // Send rejection email
            $this->sendRejectionEmail($professional, $request->rejection_reason);
            
            return back()->with('success', 'Professional application rejected. Notification email sent.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Professional rejection failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to reject professional: ' . $e->getMessage());
        }
    }

    /**
     * Bulk approve multiple professionals
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'professional_ids' => 'required|array',
            'professional_ids.*' => 'exists:animal_health_professionals,id',
        ]);
        
        $successCount = 0;
        $failCount = 0;
        
        foreach ($request->professional_ids as $professionalId) {
            try {
                DB::beginTransaction();
                
                $professional = AnimalHealthProfessional::with('user')->findOrFail($professionalId);
                
                if ($professional->approval_status !== 'approved') {
                    $professional->update([
                        'approval_status' => 'approved',
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                    ]);
                    
                    $professional->user->update([
                        'account_status' => 'active',
                        'status' => 'active',
                        'is_approved' => true,
                    ]);
                    
                    DB::commit();
                    
                    $this->sendApprovalEmail($professional);
                    $successCount++;
                }
                
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Bulk approval failed for professional ' . $professionalId . ': ' . $e->getMessage());
                $failCount++;
            }
        }
        
        $message = "Approved {$successCount} professional(s) successfully.";
        if ($failCount > 0) {
            $message .= " {$failCount} failed.";
        }
        
        return back()->with('success', $message);
    }

    /**
     * Send approval email to professional
     */
    protected function sendApprovalEmail($professional)
    {
        try {
            Mail::send('emails.professional-approved', [
                'professional' => $professional,
            ], function ($message) use ($professional) {
                $message->to($professional->user->email, $professional->user->name)
                    ->subject('🎉 Your FarmVax Professional Account is Approved!');
            });
            
            \Log::info('Approval email sent to: ' . $professional->user->email);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send approval email: ' . $e->getMessage());
            // Don't throw exception - approval should succeed even if email fails
        }
    }

    /**
     * Send rejection email to professional
     */
    protected function sendRejectionEmail($professional, $reason)
    {
        try {
            Mail::send('emails.professional-rejected', [
                'professional' => $professional,
                'reason' => $reason,
            ], function ($message) use ($professional) {
                $message->to($professional->user->email, $professional->user->name)
                    ->subject('FarmVax Professional Application Update');
            });
            
            \Log::info('Rejection email sent to: ' . $professional->user->email);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send rejection email: ' . $e->getMessage());
        }
    }

    /**
     * Show professional details for approval review
     */
    public function show($id)
    {
        $professional = AnimalHealthProfessional::with([
            'user.country',
            'user.state',
            'user.lga',
            'verificationDocuments'
        ])->findOrFail($id);

        return view('admin.professionals.review', compact('professional'));
    }
}