<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AnimalHealthProfessional;
use App\Models\Volunteer;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Show the farmer registration form.
     */
    public function showFarmerForm()
    {
        $countries = Country::orderBy('name')->get();
        return view('auth.register-farmer', compact('countries'));
    }

    /**
     * Show the professional registration form.
     */
    public function showProfessionalForm()
    {
        $countries = Country::orderBy('name')->get();
        return view('auth.register-professional', compact('countries'));
    }

    /**
     * Show the volunteer registration form.
     */
    public function showVolunteerForm()
    {
        $countries = Country::orderBy('name')->get();
        return view('auth.register-volunteer', compact('countries'));
    }

    /**
     * Register a farmer.
     */
    public function registerFarmer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Personal Information
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)],
            
            // Location Information
            'country_id' => ['required', 'exists:countries,id'],
            'state_id' => ['required', 'exists:states,id'],
            'lga_id' => ['required', 'exists:lgas,id'],
            'address' => ['nullable', 'string', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            
            // Farm Information
            'farm_name' => ['nullable', 'string', 'max:255'],
            'farm_size' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            
            // Terms
            'terms' => ['required', 'accepted'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'farmer',
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'lga_id' => $request->lga_id,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'farm_name' => $request->farm_name,
            'farm_size' => $request->farm_size,
            'is_active' => true,
            'status' => 'active',
            'account_status' => 'active',
        ]);

        // Auto-login
        Auth::login($user);

        return redirect()->route('individual.dashboard')
            ->with('success', 'Registration successful! Welcome to FarmVax.');
    }

    /**
     * Register a professional.
     */
    public function registerProfessional(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Personal Information
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)],
            
            // Location Information
            'country_id' => ['required', 'exists:countries,id'],
            'state_id' => ['required', 'exists:states,id'],
            'lga_id' => ['required', 'exists:lgas,id'],
            'address' => ['nullable', 'string', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            
            // Professional specific fields
            'professional_type' => ['required', 'in:veterinarian,paraveterinarian,community_animal_health_worker,others'],
            'license_number' => ['nullable', 'string', 'max:100'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:50'],
            'organization' => ['nullable', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:255'],
            
            // Document uploads
            'certificate' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:1024'],
            'id_card' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'license' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            
            // Terms
            'terms' => ['required', 'accepted'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'animal_health_professional',
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'lga_id' => $request->lga_id,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_active' => true,
            'status' => 'active',
            'account_status' => 'active',
        ]);

        // Handle file uploads
        $documents = [];

        if ($request->hasFile('certificate')) {
            $certificatePath = $request->file('certificate')->store('documents/certificates', 'public');
            $documents[] = [
                'type' => 'Professional Certificate',
                'file_name' => $request->file('certificate')->getClientOriginalName(),
                'file_path' => $certificatePath,
                'uploaded_at' => now()->toDateTimeString(),
            ];
        }

        if ($request->hasFile('id_card')) {
            $idCardPath = $request->file('id_card')->store('documents/id-cards', 'public');
            $documents[] = [
                'type' => 'ID Card',
                'file_name' => $request->file('id_card')->getClientOriginalName(),
                'file_path' => $idCardPath,
                'uploaded_at' => now()->toDateTimeString(),
            ];
        }

        if ($request->hasFile('license')) {
            $licensePath = $request->file('license')->store('documents/licenses', 'public');
            $documents[] = [
                'type' => 'Practice License',
                'file_name' => $request->file('license')->getClientOriginalName(),
                'file_path' => $licensePath,
                'uploaded_at' => now()->toDateTimeString(),
            ];
        }

        // Create professional profile (pending approval)
        AnimalHealthProfessional::create([
            'user_id' => $user->id,
            'professional_type' => $request->professional_type ?? 'others',
            'license_number' => $request->license_number,
            'experience_years' => $request->experience_years ?? 0,
            'organization' => $request->organization,
            'specialization' => $request->specialization,
            'verification_documents' => !empty($documents) ? json_encode($documents) : null,
            'approval_status' => 'pending',
            'submitted_at' => now(),
        ]);

        // Auto-login
        Auth::login($user);

        return redirect()->route('professional.pending-approval')
            ->with('success', 'Registration successful! Your application is pending approval.');
    }

    /**
     * Register a volunteer.
     */
    public function registerVolunteer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Personal Information
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)],
            
            // Location Information
            'country_id' => ['required', 'exists:countries,id'],
            'state_id' => ['required', 'exists:states,id'],
            'lga_id' => ['required', 'exists:lgas,id'],
            'address' => ['nullable', 'string', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            
            // Volunteer specific fields
            'organization' => ['nullable', 'string', 'max:255'],
            'motivation' => ['nullable', 'string', 'max:500'],
            
            // Terms
            'terms' => ['required', 'accepted'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'volunteer',
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'lga_id' => $request->lga_id,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_active' => true,
            'status' => 'active',
            'account_status' => 'active',
        ]);

        // Create volunteer profile (auto-approved)
        Volunteer::create([
            'user_id' => $user->id,
            'organization' => $request->organization,
            'motivation' => $request->motivation,
            'approval_status' => 'approved',
            'is_active' => true,
            'submitted_at' => now(),
        ]);

        // Auto-login
        Auth::login($user);

        return redirect()->route('volunteer.dashboard')
            ->with('success', 'Registration successful! Welcome to FarmVax Volunteer Program.');
    }

    /**
     * General registration handler (for generic register route)
     */
    public function register(Request $request)
    {
        // Determine which type of registration based on role
        $role = $request->input('role', 'farmer');

        switch ($role) {
            case 'farmer':
                return $this->registerFarmer($request);
            
            case 'animal_health_professional':
                return $this->registerProfessional($request);
            
            case 'volunteer':
                return $this->registerVolunteer($request);
            
            default:
                return redirect()->back()
                    ->with('error', 'Invalid registration type selected.');
        }
    }
}