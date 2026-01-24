<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\ProfessionalType;
use App\Models\Specialization;
use App\Models\ServiceArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');

        return view('admin.settings.index', compact('settings'));
    }

   public function general()
{
    $settings = Setting::where('group', 'general')->get();
    return view('admin.settings.general', compact('settings'));
}

public function updateGeneral(Request $request)
{
    $validated = $request->validate([
        'site_name' => 'required|string|max:255',
        'site_tagline' => 'nullable|string|max:255',
        'site_description' => 'nullable|string',
        'contact_email' => 'required|email',
        'contact_phone' => 'nullable|string',
        'office_address' => 'nullable|string',
        'site_logo' => 'nullable|image|max:2048',
        'site_favicon' => 'nullable|image|max:1024',
        'primary_color' => 'nullable|string',
        'secondary_color' => 'nullable|string',
        'facebook_url' => 'nullable|url',
        'twitter_url' => 'nullable|url',
        'instagram_url' => 'nullable|url',
        'linkedin_url' => 'nullable|url',
        'maintenance_mode' => 'nullable|boolean',
        'timezone' => 'nullable|string',
    ]);

    foreach ($validated as $key => $value) {
        if ($request->hasFile($key)) {
            $path = $request->file($key)->store('settings', 'public');
            Setting::set($key, '/storage/' . $path, 'string', 'general');
        } elseif ($value !== null) {
            Setting::set($key, $value, 'string', 'general');
        }
    }

    return redirect()->route('admin.settings.general')
        ->with('success', 'General settings updated successfully!');
}

    // public function updateGeneral(Request $request)
    // {
    //     $validated = $request->validate([
    //         'site_name' => 'required|string|max:255',
    //         'site_description' => 'nullable|string',
    //         'contact_email' => 'required|email',
    //         'contact_phone' => 'nullable|string',
    //         'site_logo' => 'nullable|image|max:2048',
    //         'site_favicon' => 'nullable|image|max:1024',
    //     ]);

    //     foreach ($validated as $key => $value) {
    //         if ($request->hasFile($key)) {
    //             $path = $request->file($key)->store('public/settings');
    //             Setting::set($key, Storage::url($path), 'image', 'general');
    //         } elseif ($value !== null) {
    //             Setting::set($key, $value, 'string', 'general');
    //         }
    //     }

    //     return redirect()->route('admin.settings.general')
    //         ->with('success', 'General settings updated successfully');
    // }

  public function email()
{
    $settings = Setting::where('group', 'email')->get();
    return view('admin.settings.email', compact('settings'));
}

public function updateEmail(Request $request)
{
    $validated = $request->validate([
        'email_provider' => 'required|in:smtp,sendgrid,mailgun,ses',
        'smtp_host' => 'nullable|string',
        'smtp_port' => 'nullable|numeric',
        'smtp_username' => 'nullable|string',
        'smtp_password' => 'nullable|string',
        'smtp_encryption' => 'nullable|in:tls,ssl',
        'sendgrid_api_key' => 'nullable|string',
        'mailgun_domain' => 'nullable|string',
        'mailgun_api_key' => 'nullable|string',
        'ses_key' => 'nullable|string',
        'ses_secret' => 'nullable|string',
        'ses_region' => 'nullable|string',
        'from_email' => 'required|email',
        'from_name' => 'required|string',
    ]);

    foreach ($validated as $key => $value) {
        if ($value !== null) {
            Setting::set($key, $value, 'string', 'email');
        }
    }

    return redirect()->route('admin.settings.email')
        ->with('success', 'Email settings updated successfully!');
}

  public function sms()
{
    $settings = Setting::where('group', 'sms')->get();
    return view('admin.settings.sms', compact('settings'));
}

public function updateSms(Request $request)
{
    $validated = $request->validate([
        'sms_provider' => 'required|in:kudi,termii,africastalking,bulksms,twilio',
        // Kudi SMS
        'kudi_username' => 'nullable|string',
        'kudi_password' => 'nullable|string',
        'kudi_sender_id' => 'nullable|string|max:11',
        // Termii
        'termii_api_key' => 'nullable|string',
        'termii_sender_id' => 'nullable|string',
        // Africa's Talking
        'africastalking_username' => 'nullable|string',
        'africastalking_api_key' => 'nullable|string',
        'africastalking_sender_id' => 'nullable|string',
        // BulkSMS
        'bulksms_api_token' => 'nullable|string',
        'bulksms_sender_id' => 'nullable|string',
        // Twilio
        'twilio_account_sid' => 'nullable|string',
        'twilio_auth_token' => 'nullable|string',
        'twilio_from_number' => 'nullable|string',
    ]);

    foreach ($validated as $key => $value) {
        if ($value !== null) {
            Setting::set($key, $value, 'string', 'sms');
        }
    }

    return redirect()->route('admin.settings.sms')
        ->with('success', 'SMS settings updated successfully!');
}

    // public function updateSms(Request $request)
    // {
    //     $validated = $request->validate([
    //         'sms_provider' => 'required|string',
    //         'sms_api_key' => 'required|string',
    //         'sms_api_secret' => 'nullable|string',
    //         'sms_from_number' => 'required|string',
    //     ]);

    //     foreach ($validated as $key => $value) {
    //         Setting::set($key, $value, 'string', 'sms');
    //     }

    //     return redirect()->route('admin.settings.sms')
    //         ->with('success', 'SMS settings updated successfully');
    // }

   public function ai()
{
    $settings = Setting::where('group', 'ai')->get();
    return view('admin.settings.ai', compact('settings'));
}

public function updateAi(Request $request)
{
    $validated = $request->validate([
        'ai_enabled' => 'required|boolean',
        'ai_provider' => 'required|in:openai,anthropic,google',
        'openai_api_key' => 'nullable|string',
        'openai_model' => 'nullable|string',
        'anthropic_api_key' => 'nullable|string',
        'anthropic_model' => 'nullable|string',
        'google_api_key' => 'nullable|string',
        'google_model' => 'nullable|string',
        'ai_temperature' => 'required|numeric|min:0|max:2',
        'ai_max_tokens' => 'required|integer|min:1|max:4000',
        'ai_system_prompt' => 'nullable|string',
    ]);

    foreach ($validated as $key => $value) {
        if ($value !== null) {
            $type = ($key == 'ai_enabled') ? 'boolean' : 'string';
            Setting::set($key, $value, $type, 'ai');
        }
    }

    return redirect()->route('admin.settings.ai')
        ->with('success', 'AI settings updated successfully!');
}

    // public function updateAi(Request $request)
    // {
    //     $validated = $request->validate([
    //         'ai_enabled' => 'required|boolean',
    //         'ai_provider' => 'required|string',
    //         'ai_api_key' => 'required|string',
    //         'ai_model' => 'required|string',
    //         'ai_temperature' => 'required|numeric|min:0|max:2',
    //         'ai_max_tokens' => 'required|integer|min:1|max:4000',
    //     ]);

    //     Setting::set('ai_enabled', $validated['ai_enabled'], 'boolean', 'ai');
    //     Setting::set('ai_provider', $validated['ai_provider'], 'string', 'ai');
    //     Setting::set('ai_api_key', $validated['ai_api_key'], 'string', 'ai');
    //     Setting::set('ai_model', $validated['ai_model'], 'string', 'ai');
    //     Setting::set('ai_temperature', $validated['ai_temperature'], 'string', 'ai');
    //     Setting::set('ai_max_tokens', $validated['ai_max_tokens'], 'string', 'ai');

    //     return redirect()->route('admin.settings.ai')
    //         ->with('success', 'AI settings updated successfully');
    // }

    public function professionalTypes()
{
    $professionalTypes = ProfessionalType::all();
    $specializations = Specialization::all();
    $serviceAreas = ServiceArea::all();

    return view('admin.settings.professional-types', compact('professionalTypes', 'specializations', 'serviceAreas'));
}

    public function storeProfessionalType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        ProfessionalType::create($validated);

        return redirect()->route('admin.settings.professional')
            ->with('success', 'Professional type added successfully');
    }

    public function storeSpecialization(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Specialization::create($validated);

        return redirect()->route('admin.settings.professional')
            ->with('success', 'Specialization added successfully');
    }

    public function storeServiceArea(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        ServiceArea::create($validated);

        return redirect()->route('admin.settings.professional')
            ->with('success', 'Service area added successfully');
    }

    public function deleteProfessionalType($id)
    {
        ProfessionalType::findOrFail($id)->delete();

        return redirect()->route('admin.settings.professional')
            ->with('success', 'Professional type deleted successfully');
    }

    public function deleteSpecialization($id)
    {
        Specialization::findOrFail($id)->delete();

        return redirect()->route('admin.settings.professional')
            ->with('success', 'Specialization deleted successfully');
    }

    public function deleteServiceArea($id)
    {
        ServiceArea::findOrFail($id)->delete();

        return redirect()->route('admin.settings.professional')
            ->with('success', 'Service area deleted successfully');
    }

    private function updateEnvFile(array $data)
    {
        $envFile = base_path('.env');
        $str = file_get_contents($envFile);

        foreach ($data as $key => $value) {
            $str = preg_replace(
                "/^{$key}=.*/m",
                "{$key}=\"{$value}\"",
                $str
            );
        }

        file_put_contents($envFile, $str);
    }
    public function aiTraining()
{
    $trainingData = \App\Models\ChatbotTrainingData::orderBy('created_at', 'desc')->paginate(20);
    return view('admin.settings.ai-training', compact('trainingData'));
}

public function storeAiTraining(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'type' => 'required|in:text,url,document',
        'content' => 'nullable|string',
        'url' => 'nullable|url',
        'document' => 'nullable|file|mimes:pdf,doc,docx,txt|max:5120',
        'category' => 'required|string',
        'is_active' => 'nullable|boolean',
    ]);

    $data = [
        'title' => $validated['title'],
        'type' => $validated['type'],
        'category' => $validated['category'],
        'is_active' => $request->has('is_active'),
    ];

    if ($validated['type'] == 'url' && isset($validated['url'])) {
        $data['source_url'] = $validated['url'];
        // TODO: Fetch content from URL
        $data['content'] = 'Content from: ' . $validated['url'];
    } elseif ($validated['type'] == 'document' && $request->hasFile('document')) {
        $path = $request->file('document')->store('training-docs', 'public');
        $data['source_url'] = $path;
        // TODO: Extract text from document
        $data['content'] = 'Document uploaded: ' . $path;
    } else {
        $data['content'] = $validated['content'];
    }

    \App\Models\ChatbotTrainingData::create($data);

    return redirect()->route('admin.settings.ai-training')
        ->with('success', 'Training data added successfully!');
}

public function toggleAiTraining($id)
{
    $data = \App\Models\ChatbotTrainingData::findOrFail($id);
    $data->is_active = !$data->is_active;
    $data->save();

    return back()->with('success', 'Training data updated!');
}

public function destroyAiTraining($id)
{
    $data = \App\Models\ChatbotTrainingData::findOrFail($id);
    $data->delete();

    return back()->with('success', 'Training data deleted!');
}

/**
 * Test SMS configuration
 */
public function testSms(Request $request)
{
    $validated = $request->validate([
        'test_phone' => 'required|string',
    ]);

    try {
        $smsService = new \App\Services\SmsService();
        $result = $smsService->send(
            $validated['test_phone'],
            'Test message from FarmVax. Your SMS configuration is working correctly!'
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Test SMS sent successfully to ' . $validated['test_phone'],
                'provider' => $result['provider'] ?? 'unknown'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send SMS: ' . ($result['error'] ?? 'Unknown error')
            ], 400);
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Test Email configuration
 */
public function testEmail(Request $request)
{
    $validated = $request->validate([
        'test_email' => 'required|email',
    ]);

    try {
        $emailService = new \App\Services\EmailService();
        $result = $emailService->send(
            $validated['test_email'],
            'Test Email from FarmVax',
            '<h2>Test Email</h2><p>Your email configuration is working correctly!</p><p>This is a test message from FarmVax system.</p>'
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully to ' . $validated['test_email'],
                'provider' => $result['provider'] ?? 'unknown'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . ($result['error'] ?? 'Unknown error')
            ], 400);
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}
}
