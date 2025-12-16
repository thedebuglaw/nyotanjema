<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
{
    View::composer('*', function ($view) {

        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();

        $permissions = [

            // Patients
            'register_patient' => in_array($user->user_type, ['admin', 'doctor', 'receptionist']),
            'search_patient'   => in_array($user->user_type, ['admin', 'doctor', 'receptionist']),

            // Reports
            'view_reports'     => in_array($user->user_type, ['admin', 'doctor']),
            'clinic_report'    => in_array($user->user_type, ['admin', 'doctor', 'receptionist']),
            'view_statistics'  => in_array($user->user_type, ['admin', 'doctor']),

            // Medicine
            'issue_medicine'   => in_array($user->user_type, ['admin', 'pharmacist']),

            // Attendance
            'reset_user'       => $user->user_type === 'admin',

            // Users
            'register_user'    => $user->user_type === 'admin',

            // Notices
            'manage_notices'   => $user->user_type === 'admin',
        ];

        $view->with('permissions', $permissions);
    });
}

}
