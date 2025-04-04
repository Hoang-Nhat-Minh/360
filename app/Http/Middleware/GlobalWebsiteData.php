<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\View;

class GlobalWebsiteData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the first settings record or set default values if null
        $setting = Setting::first();

        View::share([
            'websiteName' => $setting->name ?? 'Default Website Name',
            'websiteDescription' => $setting->description ?? 'Default Description',
            'websiteKeywords' => $setting->keywords ?? 'default,keywords',
            'websiteAuthor' => $setting->author ?? 'Default Author',
            'websiteGoogleSiteVerification' => $setting->google_site_verification ?? '',
            'logoUrl' => $setting && $setting->logo ? asset('storage/' . $setting->logo) : '',
            'logoMainUrl' => $setting && $setting->logoMain ? asset('storage/' . $setting->logoMain) : '',
            'audioUrl' => $setting && $setting->background_music ? asset('storage/' . $setting->background_music) : '',
            'bgStarterUrl' => $setting && $setting->bg_starter ? asset('storage/' . $setting->bg_starter) : '',
            'voice_reader_avatar' => $setting && $setting->voice_reader_avater ? asset('storage/' . $setting->voice_reader_avater) : '',
            'websiteUrl' => substr($request->root(), 7),
            'locationYaw' => $setting->yaw ?? '0',
            'locationPitch' => $setting->pitch ?? '0',
        ]);

        return $next($request);
    }
}
