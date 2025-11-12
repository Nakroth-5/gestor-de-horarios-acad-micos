<?php

namespace App\Providers;

use App\Models\Classroom;
use App\Models\DaySchedule;
use App\Models\Group;
use App\Models\Module;
use App\Models\Role;
use App\Models\Subject;
use App\Models\User;
use App\Observers\ClassroomObserver;
use App\Observers\GroupObserver;
use App\Observers\InfrastructureObserver;
use App\Observers\RoleObserver;
use App\Observers\ScheduleBlockObserver;
use App\Observers\SubjectObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        User::observe(UserObserver::class);
        Role::observe(RoleObserver::class);
        Module::observe(InfrastructureObserver::class);
        Classroom::observe(ClassroomObserver::class);
        Group::observe(GroupObserver::class);
        // DaySchedule::observe(ScheduleBlockObserver::class); // Ahora usa Auditable trait
        Subject::observe(SubjectObserver::class);

    }
}
