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
use stdClass;
use Symfony\Component\Security\Core\Role\Role;

define("DIV", "Chef division");
define("CBt", "Chef de batallaint");
define("CBr", "Chef de brigade");
define("CC", "Chef de compagnie");
define("MED", "Medecin");
define("DG", "Directeur général");

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
        switch ($this->role->name) {
            case MED:
                if ($report->status === MED) {
                    // Find a DIV officer from database
                    $div = Officer::whereHas('role', function ($query) {
                        $query->where('name', DIV);
                    })->first();

                    if ($div) {
                        $div->notify(new ReportArival($report));
                        $report->status = DIV;
                        $report->owner->notify(new ReportPassed($report));
                    }
                }
                break;

            case CC:

                $cbt = Officer::whereHas('role', function ($query) {
                    $query->where('name', CBt);
                })->where('bat', $this->bat)->first();

                if ($cbt) {
                    $cbt->notify(new ReportArival($report));
                    $report->status = CBt;
                    $report->destination = $cbt->id;
                    $report->owner->notify(new ReportPassed($report));
                }
                break;

            case CBt:
                // Find CBr from database
                $cbr = Officer::whereHas('role', function ($query) {
                    $query->where('name', CBr);
                })->first();

                if ($cbr) {
                    $report->status = CBr;
                    $cbr->notify(new ReportArival($report));
                    $report->destination = $cbr->id;
                    $report->owner->notify(new ReportPassed($report));
                } else {
                    throw new \Exception("No Chef de brigade found");
                }
                break;
            case CBr:
                // Find DIV officer from database
                $div = Officer::whereHas('role', function ($query) {
                    $query->where('name', DIV);
                })->first();

                if ($div) {
                    $report->status = DIV;
                    $div->notify(new ReportArival($report));
                    $report->destination = $div->id;
                    $report->owner->notify(new ReportPassed($report));
                } else {
                    throw new \Exception("No DIV found");
                }
                break;
            case DIV:



                if ($report->is_medical) {

                    $med = Officer::whereHas('role', function ($query) {
                        $query->where('name', MED);
                    })->first();

                    if ($med) {
                        $med->notify(new ReportArival($report));
                        $report->status = MED;
                        $report->destination = $med->id;
                        $report->owner->notify(new ReportPassed($report));
                    }
                } else {
                    $dg = Officer::whereHas('role', function ($query) {
                        $query->where('name', DG);
                    })->first();
                    if ($dg) {
                        $dg->notify(new ReportArival($report));

                        $report->status = DG;
                        $report->destination = $dg->id;
                        $report->owner->notify(new ReportPassed($report));
                    }
                }
                break;




            case DG:
                // Inform owner with DesitionMade

                $report->status = "DONE";
                $report->owner->notify(new DesitionMade($report));
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
        $report->owner->notify(new ReportRefused($report, $motif));
    }

    public function receivesBroadcastNotificationsOn(): string
    {
        return 'App.Models.Officer.' . $this->id;
    }

    public function isHigherThan(Officer $officer): bool
    {

        $ROLES = [
            'DG' => 5,
            'DIV' => 4,
            'CBr' => 3,
            'Chef de batallaint' => 2,
            'Chef de compagnie' => 1,
            'MED' => 0
        ];

        if ($this->role->name != "MED")

            return $ROLES[$this->role->name] > $ROLES[$officer->role->name];
        else
            return false;
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

    public function patients(): Builder
    {
        $patients = Patient::join('students', 'patients.matricule', '=', 'students.matricule')
            ->join('sections', 'students.section_id', '=', 'sections.id')
            ->where('sections.officer_id', $this->id)
            ->select('patients.*');

        return $patients;
    }
    /**
     * filter it next or use ->get() on it
     *
     * @return Builder
     */
    public function officerSanctions(): Builder
    {
        $query = Sanction::join('students', 'sanctions.matricule', '=', 'students.matricule');

        // Filter by officer role
        if ($this->role->name === "CC") {
            $query->join('sections', 'students.section_id', '=', 'sections.id')
                ->where('sections.officer_id', $this->id);
        } elseif ($this->role->name === "CBt") {
            $query->where('students.grade', $this->bat);
        }

        // Calculate relevant dates
        $today = Carbon::today();
        $nextThursday = $today->copy()->next(Carbon::THURSDAY);
        $nextFriday = $today->copy()->next(Carbon::FRIDAY);
        $nextSaturday = $today->copy()->next(Carbon::SATURDAY);

        // Filter sanctions based on their type and dates
        $query->where(function ($q) use ($today, $nextThursday, $nextFriday, $nextSaturday) {
            // Active "arret" sanctions
            $q->where(function ($sub) use ($today) {
                $sub->where('sanctions.type', 'arret')
                    ->where('sanctions.date_fin', '>=', $today);
            })
                // Active "consigne" sanctions for next weekend
                ->orWhere(function ($sub) use ($nextThursday, $nextFriday, $nextSaturday) {
                    $sub->where('sanctions.type', 'consigne')
                        ->where(function ($dates) use ($nextThursday, $nextFriday, $nextSaturday) {
                            $dates->whereDate('sanctions.date_debut', '<=', $nextSaturday)
                                ->whereDate('sanctions.date_fin', '>=', $nextThursday);
                        });
                })
                // Include all "avert" and "blame" sanctions
                ->orWhere(function ($sub) {
                    $sub->whereIn('sanctions.type', ['avert', 'blame']);
                });
        });

        return $query->selectRaw("sanctions.*,
                                CONCAT(students.nom, ' ', students.prenom) as full_name,
                                CASE WHEN sanctions.date_fin >= CURRENT_DATE THEN 1 ELSE 0 END as is_active")
            ->orderByDesc('sanctions.created_at');
    }
}
