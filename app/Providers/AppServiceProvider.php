<?php
namespace App\Providers;

use App\Interfaces\DivisionRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\InstitutionRepository;
use App\Repositories\RoleRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\PermissionRepository;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\RoleRepositoryInterface;
use App\Interfaces\PermissionRepositoryInterface;
use App\Interfaces\InstitutionRepositoryInterface;
use App\Interfaces\ReportFollowUpRepositoryInterface;
<<<<<<< HEAD
use App\Interfaces\ReportReportJourneyRepositoryInterface;
=======
use App\Interfaces\ReportJourneyRepositoryInterface;
>>>>>>> 3d57bc4bd70e3aac3b06ee5b357fcda2414ab552
use App\Interfaces\ProfileRepositoryInterface;
use App\Interfaces\ReportJourneyRepositoryInterface;
use App\Interfaces\SubDivisionRepositoryInterface;
use App\Repositories\DivisionRepository;
<<<<<<< HEAD
use App\Repositories\JourneyRepository;
=======
use App\Repositories\ReportFollowUpRepository;
use App\Repositories\ReportJourneyRepository;
>>>>>>> 3d57bc4bd70e3aac3b06ee5b357fcda2414ab552
use App\Repositories\ProfileRepository;
use App\Repositories\ReportJourneyRepository;
use App\Repositories\SubDivisionRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(ProfileRepositoryInterface::class,ProfileRepository::class);
        $this->app->bind(InstitutionRepositoryInterface::class, InstitutionRepository::class);
        $this->app->bind(DivisionRepositoryInterface::class, DivisionRepository::class);
        $this->app->bind(SubDivisionRepositoryInterface::class, SubDivisionRepository::class);
        $this->app->bind(ReportJourneyRepositoryInterface::class, ReportJourneyRepository::class);
        $this->app->bind(ReportFollowUpRepositoryInterface::class, ReportFollowUpRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
