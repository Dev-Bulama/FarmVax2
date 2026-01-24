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
        $alerts = OutbreakAlert::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total' => OutbreakAlert::count(),
            'active' => OutbreakAlert::where('status', 'active')->count(),
            'resolved' => OutbreakAlert::where('status', 'resolved')->count(),
            'critical' => OutbreakAlert::where('severity', 'critical')->count(),
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
            'title' => 'required|string|max:255',
            'disease_name' => 'required|string|max:255',
            'description' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:active,resolved',
            'location_type' => 'required|in:country,state,lga,radius',
            'country_id' => 'nullable|exists:countries,id',
            'state_id' => 'nullable|exists:states,id',
            'lga_id' => 'nullable|exists:lgas,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'radius_km' => 'nullable|numeric',
            'affected_species' => 'nullable|string',
            'prevention_measures' => 'nullable|string',
            'symptoms' => 'nullable|string',
            'send_notification' => 'nullable|boolean',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['alert_date'] = now();

        DB::beginTransaction();
        try {
            $alert = OutbreakAlert::create($validated);

            // Send notifications if requested
            if ($request->send_notification) {
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
        $alert = OutbreakAlert::with(['creator', 'country', 'state', 'lga'])
            ->findOrFail($id);

        $affectedUsers = $this->getAffectedUsers($alert);

        return view('admin.outbreak-alerts.show', compact('alert', 'affectedUsers'));
    }

    /**
     * Update the specified outbreak alert
     */
    public function update(Request $request, $id)
    {
        $alert = OutbreakAlert::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:active,resolved',
            'severity' => 'required|in:low,medium,high,critical',
        ]);

        $alert->update($validated);

        return back()->with('success', 'Alert updated successfully!');
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
     * Send alert notifications to affected users
     */
    protected function sendAlertNotifications($alert)
    {
        $users = $this->getAffectedUsers($alert);

        foreach ($users as $user) {
            // Create notification record
            DB::table('outbreak_alert_notifications')->insert([
                'outbreak_alert_id' => $alert->id,
                'user_id' => $user->id,
                'sent_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // TODO: Send actual SMS/Email/Push notification
            // This would integrate with your notification service
        }

        return $users->count();
    }

    /**
     * Get users affected by the alert based on location
     */
    protected function getAffectedUsers($alert)
    {
        $query = User::where('role', 'farmer');

        switch ($alert->location_type) {
            case 'country':
                if ($alert->country_id) {
                    $query->where('country_id', $alert->country_id);
                }
                break;

            case 'state':
                if ($alert->state_id) {
                    $query->where('state_id', $alert->state_id);
                }
                break;

            case 'lga':
                if ($alert->lga_id) {
                    $query->where('lga_id', $alert->lga_id);
                }
                break;

            case 'radius':
                if ($alert->latitude && $alert->longitude && $alert->radius_km) {
                    // Geographic radius calculation
                    $query->whereNotNull('latitude')
                          ->whereNotNull('longitude')
                          ->whereRaw(
                              "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) <= ?",
                              [$alert->latitude, $alert->longitude, $alert->latitude, $alert->radius_km]
                          );
                }
                break;
        }

        return $query->get();
    }
}