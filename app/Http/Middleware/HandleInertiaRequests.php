<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => function () use ($request) {
                return [
                    'user' => $request->user() ? [
                        'id' => $request->user()->id,
                        'first_name' => $request->user()->first_name,
                        'last_name' => $request->user()->last_name,
                        'middle_name' => $request->user()->middle_name,
                        'email' => $request->user()->email,
                        'role' => $request->user()->role,
                    ] : null,
                ];
            },
            'pending_petitions_count' => function () use ($request) {
                if ($request->user() && $request->user()->role === 'director') {
                    return \App\Models\Petition::where('status', 'pending_review')->count();
                }
                return 0;
            },
            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                    'error' => $request->session()->get('error'),
                ];
            },
            'translations' => function () {
                return collect(scandir(base_path('lang/'.app()->getLocale())))
                    ->filter(fn ($file) => pathinfo($file, PATHINFO_EXTENSION) === 'php')
                    ->mapWithKeys(fn ($file) => [basename($file, '.php') => require base_path('lang/'.app()->getLocale().'/'.$file)])
                    ->all();
            },
        ]);
    }
}
