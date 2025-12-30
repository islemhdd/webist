<?php

namespace App\Models;

use App\Notifications\DesitionMade;
use App\Notifications\ReportArival;
use App\Notifications\ReportPassed;
use App\Notifications\ReportRefused;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use stdClass;
use Symfony\Component\Security\Core\Role\Role;

define("CHEF_DIVISION", "Chef division");
define("CHEF_DE_BATALLAIN", "Chef de batallaint");
define("CHEF_DE_BRIGADE", "Chef de brigade");
define("CHEF_DE_COMPAGNIE", "Chef de compagnie");
define("MEDECIN", "Medecin");
define("DIRECTEUR_GENERAL", "Directeur général");

class Officer extends User
{
    use Authenticatable, Notifiable;
    protected $guarded = ['role'];
    public $incrementing = false;
    public $timestamps = false;

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }
    // TODO public function haleTimes(): HasMany
    // {
    //     return $this->hasMany(HaleTime::class);
    // }
    // TODO public function isHale(): bool
    // {    //if its the officers time
    //     $hals = $this->haletimes->pluck('day');
    //     $day = Carbon::now()->format('l');
    //     return in_array($day, $hals);
    // }
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
    public function companie(): array //return the companies of the officer
    {


        $comp = DB::select("SELECT distinct companie as c From sections where officer_id=$this->id");
        $compArray = [];
        foreach ($comp as $c) {
            $compArray[] = $c->c;
        }
        return $compArray;
    }



    public function officerNotify(Report $report)
    {
        $canNotify = Schema::hasTable('notifications');

        switch ($this->role->name) {
            case MEDECIN:
                if ($report->status === MEDECIN) {
                    // Find a DIV officer from database
                    $div = Officer::whereHas('role', function ($query) {
                        $query->where('name', CHEF_DIVISION);
                    })->first();

                    if ($div) {
                        if ($canNotify) {
                            $div->notify(new ReportArival($report));
                        }
                        $report->status = CHEF_DIVISION;
                        $report->destination = $div->id;
                        if ($canNotify) {
                            $report->owner->notify(new ReportPassed());
                        }
                    }
                }
                break;

            case CHEF_DE_COMPAGNIE:
                // Find Chef de batallain with same battalion
                $cbt = Officer::whereHas('role', function ($query) {
                    $query->where('name', CHEF_DE_BATALLAIN);
                })->where('bat', $this->bat)->first();

                if ($cbt) {
                    if ($canNotify) {
                        $cbt->notify(new ReportArival($report));
                    }
                    $report->status = CHEF_DE_BATALLAIN;
                    $report->destination = $cbt->id;
                    if ($canNotify) {
                        $report->owner->notify(new ReportPassed());
                    }
                }
                break;

            case CHEF_DE_BATALLAIN:
                // Find Chef de brigade from database
                $cbr = Officer::whereHas('role', function ($query) {
                    $query->where('name', CHEF_DE_BRIGADE);
                })->first();

                if ($cbr) {
                    $report->status = CHEF_DE_BRIGADE;
                    if ($canNotify) {
                        $cbr->notify(new ReportArival($report));
                    }
                    $report->destination = $cbr->id;
                    if ($canNotify) {
                        $report->owner->notify(new ReportPassed());
                    }
                }
                break;
            case CHEF_DE_BRIGADE:
                // Find DIV officer from database
                $div = Officer::whereHas('role', function ($query) {
                    $query->where('name', CHEF_DIVISION);
                })->first();

                if ($div) {
                    $report->status = CHEF_DIVISION;
                    if ($canNotify) {
                        $div->notify(new ReportArival($report));
                    }
                    $report->destination = $div->id;
                    if ($canNotify) {
                        $report->owner->notify(new ReportPassed());
                    }
                }
                break;
            case CHEF_DIVISION:

                if ($report->is_medical) {

                    $med = Officer::whereHas('role', function ($query) {
                        $query->where('name', MEDECIN);
                    })->first();

                    if ($med) {
                        if ($canNotify) {
                            $med->notify(new ReportArival($report));
                        }
                        $report->status = MEDECIN;
                        $report->destination = $med->id;
                        if ($canNotify) {
                            $report->owner->notify(new ReportPassed());
                        }
                    }
                } else {
                    $dg = Officer::whereHas('role', function ($query) {
                        $query->where('name', DIRECTEUR_GENERAL);
                    })->first();
                    if ($dg) {
                        if ($canNotify) {
                            $dg->notify(new ReportArival($report));
                        }
                        $report->status = DIRECTEUR_GENERAL;
                        $report->destination = $dg->id;
                        if ($canNotify) {
                            $report->owner->notify(new ReportPassed($report));
                        }
                    }
                }
                break;

            case DIRECTEUR_GENERAL:
                // Inform owner with DesitionMade

                $report->status = "DONE";

                if ($canNotify) {
                    $report->owner->notify(new DesitionMade($report));
                }
                $report->destination = $report->officer_id;
                break;
        }
        $report->save();
    }
    public function refuse(Report $report, $motif)
    {

        // ? $report->status = "REFUSED";
        $report->refused = 1;
        $report->motif = $motif;
        $report->destination = $report->officer_id;

        $report->save();
        if (Schema::hasTable('notifications')) {
            $report->owner->notify(new ReportRefused($report, $motif));
        }
    }

    public function receivesBroadcastNotificationsOn(): string
    {
        return 'App.Models.Officer.' . $this->id;
    }

    public function isHigherThan(Officer $officer): bool
    {

        $ROLES = [
            'Directeur général' => 5,            // Directeur général
            'Chef division' => 4,           // Chef division
            'Chef de brigade' => 3,           // Chef de brigade
            'Chef de batallaint' => 2,           // Chef de batallaint
            'Chef de compagnie' => 1,            // Chef de compagnie
            'Medecin' => 0            // Medecin
        ];
        if ($this->role->name != "Medecin") {
            return $ROLES[$this->role->name] > $ROLES[$officer->role->name];
        } else {
            return false;
        }
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    public function markAllNotificationsAsRead()
    {
        $this->unreadNotifications()->update(['read_at' => now()]);
    }

    // Notification type specific methods
    public function hasUnreadReportNotifications()
    {
        return $this->unreadNotifications()
            ->where('type', 'App\Notifications\ReportArival')
            ->exists();
    }

    public function getLatestReportNotifications($limit = 5)
    {
        return $this->notifications()
            ->where('type', 'App\Notifications\ReportArival')
            ->latest()
            ->take($limit)
            ->get();
    }

    public function officerSanctions()
    {
        $query = Sanction::join('students', 'sanctions.matricule', '=', 'students.matricule')
            ->leftJoin('sections', 'students.section_id', '=', 'sections.id');


        // Filter by officer role

            if ($this->role->name === "Chef de compagnie") {
                $query->where('sections.officer_id', $this->id);
            } elseif ($this->role->name === "Chef de batallaint") {
                $query->where('students.grade', $this->bat);
            }

            // Calculate relevant dates
            $today = Carbon::today();

            $nextFriday = $today->copy()->next(Carbon::FRIDAY);
            $nextSaturday = $today->copy()->next(Carbon::SATURDAY);

            // Filter sanctions based on their type and dates
            $query->where(function ($q) use ($today,  $nextFriday, $nextSaturday) {
                // Active "arret" sanctions
                $q->where(function ($sub) use ($today) {
                    $sub->where('sanctions.type', 'arret')
                        ->where('sanctions.date_fin', '>=', $today);
                })
                    // Active "consigne" sanctions for next weekend
                    ->orWhere(function ($sub) use ($nextFriday) {
                        $sub->where('sanctions.type', 'consigne')
                            ->whereDate('sanctions.date_debut', '=', $nextFriday);
                    })

                    // Include all "avert" and "blame" sanctions
                    ->orWhere(function ($sub) {
                        $sub->whereIn('sanctions.type', ['avert', 'blame']);
                    });
            });

            return $query->selectRaw("sanctions.*,
                                CONCAT(students.nom, ' ', students.prenom) as full_name,
                                students.section_id as section_id,
                                sections.companie as companie,
                                CASE WHEN sanctions.date_fin >= CURRENT_DATE THEN 1 ELSE 0 END as is_active")
                ->orderByDesc('sanctions.created_at');
    }
    }
