<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OutbreakAlert;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OutbreakAlertController extends Controller
{
    /**
     * Display a listing of outbreak alerts
     */
    public function index()
    {
        $alerts = OutbreakAlert::with('reporter')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total' => OutbreakAlert::count(),
            'active' => OutbreakAlert::where('is_active', 1)->count(),
            'inactive' => OutbreakAlert::where('is_active', 0)->count(),
            'critical' => OutbreakAlert::where('severity', 'critical')->count(),
            'high' => OutbreakAlert::where('severity', 'high')->count(),
        ];

        return view('admin.outbreak-alerts.index', compact('alerts', 'stats'));
    }

    /**
     * Show the form for creating a new outbreak alert
     */
    public function create()
    {
        return view('admin.outbreak-alerts.create');
    }

    /**
     * Store a newly created outbreak alert
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'disease_name' => 'required|string|max:255',
            'description' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
            'country_id' => 'nullable|exists:countries,id',
            'state_id' => 'nullable|exists:states,id',
            'lga_id' => 'nullable|exists:lgas,id',
            'location' => 'nullable|string',
            'affected_species' => 'nullable|string',
            'preventive_measures' => 'nullable|string',
            'symptoms' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'send_notifications' => 'nullable|boolean',
        ]);

        $validated['reported_by'] = auth()->id();
        $validated['reported_at'] = now();
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        DB::beginTransaction();
        try {
            $alert = OutbreakAlert::create($validated);

            // Send notifications if requested
            if ($request->send_notifications) {
                $this->sendAlertNotifications($alert);
            }

            DB::commit();
            return redirect()->route('admin.outbreak-alerts.index')
                ->with('success', 'Outbreak alert created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error creating alert: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified outbreak alert
     */
    public function show($id)
    {
        $alert = OutbreakAlert::with('reporter')->findOrFail($id);
        
        return view('admin.outbreak-alerts.show', compact('alert'));
    }

    /**
     * Show the form for editing the specified outbreak alert
     */
    public function edit($id)
    {
        $alert = OutbreakAlert::findOrFail($id);
        return view('admin.outbreak-alerts.edit', compact('alert'));
    }

    /**
     * Update the specified outbreak alert
     */
    public function update(Request $request, $id)
    {
        $alert = OutbreakAlert::findOrFail($id);

        $validated = $request->validate([
            'disease_name' => 'required|string|max:255',
            'description' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
            'country_id' => 'nullable|exists:countries,id',
            'state_id' => 'nullable|exists:states,id',
            'lga_id' => 'nullable|exists:lgas,id',
            'location' => 'nullable|string',
            'affected_species' => 'nullable|string',
            'preventive_measures' => 'nullable|string',
            'symptoms' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $alert->update($validated);

        return redirect()->route('admin.outbreak-alerts.index')
            ->with('success', 'Outbreak alert updated successfully!');
    }

    /**
     * Remove the specified outbreak alert
     */
    public function destroy($id)
    {
        $alert = OutbreakAlert::findOrFail($id);
        $alert->delete();

        return redirect()->route('admin.outbreak-alerts.index')
            ->with('success', 'Outbreak alert deleted successfully!');
    }

    /**
     * Toggle alert active status
     */
    public function toggleStatus($id)
    {
        $alert = OutbreakAlert::findOrFail($id);
        $alert->is_active = !$alert->is_active;
        $alert->save();

        $status = $alert->is_active ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "Outbreak alert {$status} successfully!");
    }

    /**
     * Send notifications to re-notify users
     */
    public function resendNotifications($id)
    {
        $alert = OutbreakAlert::findOrFail($id);
        
        $count = $this->sendAlertNotifications($alert);

        return redirect()->back()
            ->with('success', "Notifications sent to {$count} affected users!");
    }

    /**
     * Send alert notifications to affected users
     */
    protected function sendAlertNotifications($alert)
    {
        $affectedUsers = $this->getAffectedUsers($alert);
        $count = 0;

        foreach ($affectedUsers as $user) {
            // TODO: Send actual SMS/Email notification
            // For now, just count affected users
            $count++;
        }

        return $count;
    }

    /**
     * Get users affected by the alert based on location
     */
    protected function getAffectedUsers($alert)
    {
        $query = User::where('role', 'farmer')
                     ->where('account_status', 'active');

        // Filter by country if specified
        if ($alert->country_id) {
            $query->where('country_id', $alert->country_id);
        }

        // Filter by state if specified
        if ($alert->state_id) {
            $query->where('state_id', $alert->state_id);
        }

        // Filter by LGA if specified
        if ($alert->lga_id) {
            $query->where('lga_id', $alert->lga_id);
        }

        return $query->get();
    }
}