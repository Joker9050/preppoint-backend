<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::getAllGrouped();
        return view('admin.settings.index', compact('settings'));
    }

    public function edit($group)
    {
        $settings = Setting::where('group', $group)->get()->keyBy('key');
        return view('admin.settings.edit', compact('group', 'settings'));
    }

    public function update(Request $request, $group)
    {
        $rules = $this->getValidationRules($group);
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $this->saveSettings($request, $group);

        return redirect()->route('admin.settings.index')
            ->with('success', ucfirst($group) . ' settings updated successfully!');
    }

    private function getValidationRules($group)
    {
        $rules = [];

        switch ($group) {
            case 'general':
                $rules = [
                    'site_title' => 'required|string|max:255',
                    'site_description' => 'nullable|string|max:500',
                    'contact_email' => 'required|email',
                    'contact_phone' => 'nullable|string|max:20',
                    'contact_address' => 'nullable|string|max:500',
                    'maintenance_mode' => 'nullable|boolean',
                ];
                break;

            case 'seo':
                $rules = [
                    'default_seo_title' => 'nullable|string|max:255',
                    'default_seo_description' => 'nullable|string|max:500',
                    'default_keywords' => 'nullable|string|max:255',
                    'og_title' => 'nullable|string|max:255',
                    'og_description' => 'nullable|string|max:500',
                    'og_type' => 'nullable|in:website,article,product',
                    'twitter_title' => 'nullable|string|max:255',
                    'twitter_description' => 'nullable|string|max:500',
                ];
                break;

            case 'social':
                $rules = [
                    'facebook_url' => 'nullable|url',
                    'twitter_url' => 'nullable|url',
                    'instagram_url' => 'nullable|url',
                    'linkedin_url' => 'nullable|url',
                    'youtube_url' => 'nullable|url',
                    'whatsapp_number' => 'nullable|string|max:20',
                    'telegram_channel' => 'nullable|url',
                ];
                break;

            case 'email':
                $rules = [
                    'mail_driver' => 'required|in:smtp,mailgun,sendgrid',
                    'mail_host' => 'nullable|string|max:255',
                    'mail_port' => 'nullable|integer|min:1|max:65535',
                    'mail_username' => 'nullable|string|max:255',
                    'mail_password' => 'nullable|string|max:255',
                    'mail_encryption' => 'nullable|in:tls,ssl,none',
                    'mail_from_address' => 'required|email',
                    'mail_from_name' => 'required|string|max:255',
                    'mailgun_domain' => 'nullable|string|max:255',
                    'mailgun_secret' => 'nullable|string|max:255',
                    'sendgrid_api_key' => 'nullable|string|max:255',
                ];
                break;

            case 'api':
                $rules = [
                    'google_maps_api_key' => 'nullable|string|max:255',
                    'recaptcha_site_key' => 'nullable|string|max:255',
                    'recaptcha_secret_key' => 'nullable|string|max:255',
                    'facebook_app_id' => 'nullable|string|max:255',
                    'facebook_app_secret' => 'nullable|string|max:255',
                    'twitter_api_key' => 'nullable|string|max:255',
                    'twitter_api_secret' => 'nullable|string|max:255',
                ];
                break;

            case 'analytics':
                $rules = [
                    'google_analytics_tracking_id' => 'nullable|string|max:255',
                    'ga4_measurement_id' => 'nullable|string|max:255',
                    'facebook_pixel_id' => 'nullable|string|max:255',
                ];
                break;
        }

        return $rules;
    }

    private function saveSettings(Request $request, $group)
    {
        $encryptedFields = [
            'mail_password',
            'mailgun_secret',
            'sendgrid_api_key',
            'google_maps_api_key',
            'recaptcha_secret_key',
            'facebook_app_secret',
            'twitter_api_secret',
        ];

        foreach ($request->all() as $key => $value) {
            if ($key === '_token') continue;

            $isEncrypted = in_array($key, $encryptedFields);
            $description = $this->getFieldDescription($key);

            Setting::set($key, $value, 'string', $group, $isEncrypted, $description);
        }
    }

    private function getFieldDescription($key)
    {
        $descriptions = [
            'site_title' => 'Main title of the website',
            'site_description' => 'Brief description of the website',
            'contact_email' => 'Primary contact email address',
            'contact_phone' => 'Contact phone number',
            'contact_address' => 'Physical address for contact',
            'maintenance_mode' => 'Enable/disable maintenance mode',
            'default_seo_title' => 'Default SEO title for pages',
            'default_seo_description' => 'Default SEO description',
            'default_keywords' => 'Default SEO keywords',
            'og_title' => 'Open Graph title for social sharing',
            'og_description' => 'Open Graph description',
            'og_type' => 'Open Graph content type',
            'twitter_title' => 'Twitter card title',
            'twitter_description' => 'Twitter card description',
            'facebook_url' => 'Facebook page URL',
            'twitter_url' => 'Twitter profile URL',
            'instagram_url' => 'Instagram profile URL',
            'linkedin_url' => 'LinkedIn profile URL',
            'youtube_url' => 'YouTube channel URL',
            'whatsapp_number' => 'WhatsApp contact number',
            'telegram_channel' => 'Telegram channel URL',
            'mail_driver' => 'Email service provider',
            'mail_host' => 'SMTP host',
            'mail_port' => 'SMTP port',
            'mail_username' => 'SMTP username',
            'mail_password' => 'SMTP password (encrypted)',
            'mail_encryption' => 'SMTP encryption type',
            'mail_from_address' => 'From email address',
            'mail_from_name' => 'From name',
            'mailgun_domain' => 'Mailgun domain',
            'mailgun_secret' => 'Mailgun API key (encrypted)',
            'sendgrid_api_key' => 'SendGrid API key (encrypted)',
            'google_maps_api_key' => 'Google Maps API key (encrypted)',
            'recaptcha_site_key' => 'reCAPTCHA site key',
            'recaptcha_secret_key' => 'reCAPTCHA secret key (encrypted)',
            'facebook_app_id' => 'Facebook App ID',
            'facebook_app_secret' => 'Facebook App Secret (encrypted)',
            'twitter_api_key' => 'Twitter API key',
            'twitter_api_secret' => 'Twitter API secret (encrypted)',
            'google_analytics_tracking_id' => 'Google Analytics tracking ID',
            'ga4_measurement_id' => 'GA4 measurement ID',
            'facebook_pixel_id' => 'Facebook Pixel ID',
        ];

        return $descriptions[$key] ?? '';
    }
}
