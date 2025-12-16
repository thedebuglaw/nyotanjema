<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * DASHBOARD ENTRY POINT
     */
    public function index()
    {
        $user = Auth::user();

        // Notices (all users)
        $notices = DB::table('noticeboards')
            ->join('users', 'noticeboards.user_id', '=', 'users.id')
            ->select(
                'noticeboards.subject',
                'noticeboards.description',
                'noticeboards.time',
                'users.user_type',
                'users.name'
            )
            ->orderByDesc('time')
            ->get();

        // Counts (ADMIN ONLY)
        $doctorcnt = $user->user_type === 'admin'
            ? DB::table('users')->where('user_type', 'doctor')->count()
            : 0;

        $laboratoriancnt = $user->user_type === 'admin'
            ? DB::table('users')->where('user_type', 'laboratorian')->count()
            : 0;

        $pharmacistcnt = $user->user_type === 'admin'
            ? DB::table('users')->where('user_type', 'pharmacist')->count()
            : 0;

        $receptionistcnt = $user->user_type === 'admin'
            ? DB::table('users')->where('user_type', 'receptionist')->count()
            : 0;

        /**
         * PERMISSIONS MATRIX
         */
        $permissions = [
            'view_reports'        => in_array($user->user_type, ['admin', 'doctor']),
            'view_statistics'     => in_array($user->user_type, ['admin', 'doctor']),
            'clinic_report'       => $user->user_type !== 'pharmacist',
            'register_patient'    => $user->user_type !== 'pharmacist',
            'issue_medicine'      => in_array($user->user_type, ['admin', 'pharmacist']),
            'register_user'       => $user->user_type === 'admin',
            'fingerprint'         => $user->user_type === 'admin',
            'reset_user'          => $user->user_type === 'admin',
            'manage_notices'      => $user->user_type === 'admin',
        ];

        return view('dash', [
            'title' => 'Dashboard',
            'notices' => $notices,
            'doctorcnt'=> $doctorcnt,
            'laboratoriancnt'=> $laboratoriancnt,
            'pharmacistcnt'=> $pharmacistcnt,
            'receptionistcnt'=> $receptionistcnt,
            'permissions' => $permissions
        ]);
    }

    /**
     * USER PROFILE
     */
    public function profile()
    {
        $user = Auth::user();

        $log = DB::table('activity_log')
            ->select(
                'description',
                'subject_id',
                'subject_type',
                'causer_type',
                'properties',
                'created_at',
                'updated_at'
            )
            ->where('causer_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('profile', [
            'title' => $user->name,
            'activity' => $log,
            'currentno' => $user->contactnumber,
            'crntmail' => $user->email,
            'education' => $user->education,
            'location' => $user->location,
            'skills' => $user->skills,
            'notes' => $user->notes
        ]);
    }

    /**
     * LANGUAGE SWITCH
     */
    public function setLocale($lan)
    {
        session()->put('locale', $lan);
        return redirect()->back();
    }
}
