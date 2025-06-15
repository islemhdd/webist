<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Student;
use App\Models\ListeRdv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MedicalSpecialtyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Tableau de correspondance des rôles et spécialités
     */
    private function getSpecialtyMapping()
    {
        return [
            'Psychologue' => 'psycho',
            'Dentiste' => 'dentiste',
            'Médecin général' => 'médecin générale',
            'Medecin general' => 'médecin générale', // Alternative spelling
            'Medecin' => 'all', // Médecin chef a accès à tout
            'Medecin chef' => 'all', // Alternative spelling
            'Chef Medecin' => 'all' // Alternative spelling
        ];
    }

    /**
     * Récupérer les motifs et services par spécialité
     */
    private function getMotifsAndServicesBySpecialty($specialty)
    {
        // Motifs identiques à ListeRdvController (consultation, urgences)
        $baseMotifsSimple = [
            'consultation',
            'urgences'
        ];

        // Services similaires au contrôleur original mais spécialisés par médecin
        $data = [
            'psycho' => [
                'motifs' => $baseMotifsSimple,
                'services' => [
                    'psychiatrie',
                    'neurologie',
                    'consultation_psychologique',
                    'therapie_comportementale',
                    'evaluation_psychometrique',
                    'soutien_psychologique'
                ]
            ],
            'dentiste' => [
                'motifs' => $baseMotifsSimple,
                'services' => [
                    'chirurgie_dentaire',
                    'orthodontie',
                    'parodontologie',
                    'endodontie',
                    'radiologie_dentaire',
                    'prevention_buccodentaire'
                ]
            ],
            'médecin générale' => [
                'motifs' => $baseMotifsSimple,
                'services' => [
                    'cardiologie',
                    'pneumologie',
                    'gastroenterologie',
                    'dermatologie',
                    'ophtalmologie',
                    'orl',
                    'medecine_generale'
                ]
            ]
        ];

        // Si c'est le médecin chef (all), retourner tous les services comme dans ListeRdvController
        if ($specialty === 'all') {
            $allServices = [
                'cardiologie',
                'pneumologie',
                'gastroenterologie',
                'chirurgie_generale',
                'orthopedie',
                'neurologie',
                'dermatologie',
                'gynecologie',
                'urologie',
                'ophtalmologie',
                'orl',
                'psychiatrie',
                'radiologie',
                'anesthesie',
                'reanimation'
            ];

            return [
                'motifs' => $baseMotifsSimple, // Même motifs simplifiés
                'services' => $allServices // Tous les services comme le médecin chef original
            ];
        }

        return $data[$specialty] ?? ['motifs' => [], 'services' => []];
    }

    /**
     * Dashboard spécifique par spécialité
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $userRole = $user->role->name ?? '';
        $specialtyMapping = $this->getSpecialtyMapping();
        $allowedSpecialty = $specialtyMapping[$userRole] ?? null;

        if (!$allowedSpecialty) {
            abort(403, 'Accès non autorisé.');
        }

        // Statistiques selon la spécialité
        $stats = $this->getSpecialtyStats($allowedSpecialty);

        return view('medical.dashboard', compact('stats', 'userRole', 'allowedSpecialty') + $this->getSpecialtyDisplayInfo($userRole));
    }

    /**
     * Liste des patients selon la spécialité
     */
    public function patientsList(Request $request)
    {
        $user = Auth::user();
        $userRole = $user->role->name ?? '';
        $specialtyMapping = $this->getSpecialtyMapping();
        $allowedSpecialty = $specialtyMapping[$userRole] ?? null;

        if (!$allowedSpecialty) {
            abort(403, 'Accès non autorisé.');
        }

        $query = Patient::with(['student']);

        // Filtrer par spécialité si ce n'est pas le médecin chef
        if ($allowedSpecialty !== 'all') {
            $query->where('type_medecin', $allowedSpecialty);
        }

        // Filtres additionnels
        if ($request->filled('validation')) {
            switch ($request->validation) {
                case '1':
                    $query->where('valider', 1);
                    break;
                case '0':
                    $query->where('valider', 0);
                    break;
                case '2':
                    $query->where('valider', 2);
                    break;
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('matricule', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('student', function($subQ) use ($search) {
                      $subQ->where('nom', 'LIKE', '%' . $search . '%')
                           ->orWhere('prenom', 'LIKE', '%' . $search . '%');
                  });
            });
        }

        $perPage = $request->get('per_page', 15);
        $perPage = in_array($perPage, [15, 25, 50, 100]) ? $perPage : 15;

        $patients = $query->orderBy('patients.created_at', 'desc')
                         ->paginate($perPage)
                         ->appends(request()->query());        // Get specialty display info
        $specialtyInfo = $this->getSpecialtyDisplayInfo($userRole);

        return view('medical.patients-list', compact(
            'patients',
            'userRole',
            'allowedSpecialty'
        ) + $specialtyInfo);
    }

    /**
     * Valider un patient (avec vérification de spécialité)
     */
    public function validatePatient(Request $request, $id)
    {
        $user = Auth::user();
        $userRole = $user->role->name ?? '';
        $specialtyMapping = $this->getSpecialtyMapping();
        $allowedSpecialty = $specialtyMapping[$userRole] ?? null;

        if (!$allowedSpecialty) {
            abort(403, 'Accès non autorisé.');
        }

        $patient = Patient::findOrFail($id);

        // Vérifier si ce médecin peut valider ce patient
        if ($allowedSpecialty !== 'all' && $patient->type_medecin !== $allowedSpecialty) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez valider que les patients de votre spécialité.'
            ], 403);
        }

        $request->validate([
            'avis_medecin' => 'required|string|max:2000',
        ]);

        try {
            $patient->valider = 1;
            $patient->validated_at = Carbon::now();
            $patient->avis_medecin = $request->avis_medecin;

            // S'assurer que le type médecin correspond à la spécialité du médecin
            if ($allowedSpecialty !== 'all' && !$patient->type_medecin) {
                $patient->type_medecin = $allowedSpecialty;
            }

            $patient->save();

            return response()->json([
                'success' => true,
                'message' => 'Patient validé avec succès.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la validation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les statistiques par spécialité
     */
    private function getSpecialtyStats($allowedSpecialty)
    {
        $today = Carbon::today();

        if ($allowedSpecialty !== 'all') {
            // Pour les médecins spécialisés : uniquement leurs patients d'aujourd'hui
            $query = Patient::whereDate('created_at', $today)
                ->where('type_medecin', $allowedSpecialty);
        } else {
            // Pour le médecin chef : tous les patients d'aujourd'hui
            $query = Patient::whereDate('created_at', $today);
        }

        $total = $query->count();
        $validated = (clone $query)->where('valider', 1)->count();
        $pending = (clone $query)->where('valider', 0)->count();
        $rejected = (clone $query)->where('valider', 2)->count();

        // Statistiques des rendez-vous pour aujourd'hui
        if ($allowedSpecialty !== 'all') {
            $appointmentsToday = \DB::table('liste_rdvs')
                ->whereDate('date', $today)
                ->where('type_medecin', $allowedSpecialty)
                ->count();
        } else {
            $appointmentsToday = \DB::table('liste_rdvs')
                ->whereDate('date', $today)
                ->count();
        }

        return [
            'total' => $total,
            'validated' => $validated,
            'pending' => $pending,
            'rejected' => $rejected,
            'appointments_today' => $appointmentsToday,
            'specialty' => $allowedSpecialty
        ];
    }

    /**
     * Assigner un patient à une spécialité (pour le médecin chef)
     */
    public function assignSpecialty(Request $request, $id)
    {
        $user = Auth::user();
        $userRole = $user->role->name ?? '';

        // Seul le médecin chef peut assigner des spécialités
        if ($userRole !== 'Medecin') {
            abort(403, 'Seul le médecin chef peut assigner des spécialités.');
        }

        $request->validate([
            'type_medecin' => 'required|in:psycho,dentiste,médecin générale',
        ]);

        $patient = Patient::findOrFail($id);
        $patient->type_medecin = $request->type_medecin;
        $patient->save();

        return response()->json([
            'success' => true,
            'message' => 'Spécialité assignée avec succès.'
        ]);
    }

    /**
     * Statistiques filtrées pour les médecins spécialisés
     */
    public function getFilteredStats(Request $request)
    {
        $user = Auth::user();
        $userRole = $user->role->name ?? '';
        $specialtyMapping = $this->getSpecialtyMapping();
        $allowedSpecialty = $specialtyMapping[$userRole] ?? null;

        if (!$allowedSpecialty) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $dateStart = $request->get('date_start', Carbon::today()->format('Y-m-d'));
        $dateEnd = $request->get('date_end', Carbon::today()->format('Y-m-d'));

        // Base query pour les patients
        $baseQuery = Patient::whereBetween('created_at', [$dateStart, $dateEnd]);

        // Assurer que les médecins spécialisés ne voient que leurs données
        if ($allowedSpecialty !== 'all') {
            $baseQuery->where('type_medecin', $allowedSpecialty);
        }

        $total = $baseQuery->count();
        $validated = Patient::whereBetween('created_at', [$dateStart, $dateEnd])
            ->when($allowedSpecialty !== 'all', function($q) use ($allowedSpecialty) {
                return $q->where('type_medecin', $allowedSpecialty);
            })
            ->where('valider', 1)
            ->count();

        $pending = Patient::whereBetween('created_at', [$dateStart, $dateEnd])
            ->when($allowedSpecialty !== 'all', function($q) use ($allowedSpecialty) {
                return $q->where('type_medecin', $allowedSpecialty);
            })
            ->where('valider', 0)
            ->count();

        $rejected = Patient::whereBetween('created_at', [$dateStart, $dateEnd])
            ->when($allowedSpecialty !== 'all', function($q) use ($allowedSpecialty) {
                return $q->where('type_medecin', $allowedSpecialty);
            })
            ->where('valider', 2)
            ->count();

        $stats = [
            'total' => $total,
            'validated' => $validated,
            'pending' => $pending,
            'rejected' => $rejected,
        ];

        return response()->json($stats);
    }

    /**
     * Liste des rendez-vous selon la spécialité
     */
    public function appointmentsList(Request $request)
    {
        $user = Auth::user();
        $userRole = $user->role->name ?? '';
        $specialtyMapping = $this->getSpecialtyMapping();
        $allowedSpecialty = $specialtyMapping[$userRole] ?? null;

        if (!$allowedSpecialty) {
            abort(403, 'Accès non autorisé.');
        }

        $query = \DB::table('liste_rdvs as r')
            ->join('students as s', 'r.matricule', '=', 's.matricule')
            ->select('r.*', 's.nom', 's.prenom', 's.section_id as bat',
                     \DB::raw('NULL as phone'));

        // Filtrer par spécialité si ce n'est pas le médecin chef
        if ($allowedSpecialty !== 'all') {
            $query->where('r.type_medecin', $allowedSpecialty);
        }

        // Filtres additionnels
        if ($request->filled('date')) {
            $query->whereDate('r.date', $request->date);
        }

        if ($request->filled('service')) {
            $query->where('r.service', 'LIKE', '%' . $request->service . '%');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('r.matricule', 'LIKE', '%' . $search . '%')
                  ->orWhere('s.nom', 'LIKE', '%' . $search . '%')
                  ->orWhere('s.prenom', 'LIKE', '%' . $search . '%');
            });
        }

        $perPage = $request->get('per_page', 15);
        $perPage = in_array($perPage, [15, 25, 50, 100]) ? $perPage : 15;

        $appointments = $query->orderBy('r.date', 'desc')
                             ->orderBy('r.created_at', 'desc')
                             ->paginate($perPage)
                             ->appends(request()->query());

        return view('medical.appointments-list', compact('appointments', 'userRole', 'allowedSpecialty'));
    }

    /**
     * Afficher le formulaire de création d'un nouveau rendez-vous
     */
    public function showCreateAppointmentForm()
    {
        $user = Auth::user();
        $userRole = $user->role->name ?? '';
        $specialtyMapping = $this->getSpecialtyMapping();
        $allowedSpecialty = $specialtyMapping[$userRole] ?? null;

        if (!$allowedSpecialty) {
            abort(403, 'Accès non autorisé.');
        }

        // Récupérer les informations d'affichage de la spécialité
        $displayInfo = $this->getSpecialtyDisplayInfo($userRole);
        $specialtyName = $displayInfo['specialtyName'];
        $specialtyIcon = $displayInfo['specialtyIcon'];

        // Récupérer les motifs et services selon la spécialité
        $motifsAndServices = $this->getMotifsAndServicesBySpecialty($allowedSpecialty);
        $motifs = $motifsAndServices['motifs'];
        $services = $motifsAndServices['services'];

        return view('medical.appointments-create', compact('userRole', 'allowedSpecialty', 'specialtyName', 'specialtyIcon', 'motifs', 'services'));
    }

    /**
     * Créer un nouveau rendez-vous
     */
    public function createAppointment(Request $request)
    {
        $user = Auth::user();
        $userRole = $user->role->name ?? '';
        $specialtyMapping = $this->getSpecialtyMapping();
        $allowedSpecialty = $specialtyMapping[$userRole] ?? null;

        if (!$allowedSpecialty) {
            abort(403, 'Accès non autorisé.');
        }

        // Validation identique au ListeRdvController
        $request->validate([
            'matricule' => 'required|digits:7|exists:students,matricule',
            'motif' => 'required|in:consultation,urgences',
            'service' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today'
        ]);

        // Déterminer le type_medecin basé sur le rôle de l'utilisateur
        $typeMedecin = match($userRole) {
            'Psychologue' => 'psycho',
            'Dentiste' => 'dentiste',
            'Médecin général' => 'médecin générale',
            'Medecin' => 'chef_médecin', // Médecin chef
            default => 'médecin générale'
        };

        // Récupérer automatiquement les informations de l'étudiant (comme ListeRdvController)
        $student = \DB::table('students')->where('matricule', $request->matricule)->first();

        if (!$student) {
            return redirect()->back()
                           ->withErrors(['matricule' => 'Étudiant non trouvé avec ce matricule.'])
                           ->withInput();
        }

        try {
            // Créer le rendez-vous en utilisant le modèle (les infos étudiant seront récupérées via la relation)
            ListeRdv::create([
                'type_medecin' => $typeMedecin,
                'matricule' => $request->matricule,
                'motif' => $request->motif,
                'service' => $request->service,
                'date' => $request->date
            ]);

            return redirect()->route('medical.appointments')
                           ->with('success', 'Rendez-vous créé avec succès pour ' . $student->prenom . ' ' . $student->nom . '.');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withErrors(['general' => 'Erreur lors de la création: ' . $e->getMessage()])
                           ->withInput();
        }
    }

    /**
     * Supprimer un rendez-vous
     */
    public function deleteAppointment(Request $request, $matricule, $date)
    {
        $user = Auth::user();
        $userRole = $user->role->name ?? '';
        $specialtyMapping = $this->getSpecialtyMapping();
        $allowedSpecialty = $specialtyMapping[$userRole] ?? null;

        if (!$allowedSpecialty) {
            abort(403, 'Accès non autorisé.');
        }

        try {
            $query = \DB::table('liste_rdvs')
                ->where('matricule', $matricule)
                ->whereDate('date', $date);

            // Vérifier que le médecin peut supprimer ce RDV
            if ($allowedSpecialty !== 'all') {
                $appointment = $query->first();
                if ($appointment && $appointment->type_medecin !== $allowedSpecialty) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Vous ne pouvez supprimer que les rendez-vous de votre spécialité.'
                    ], 403);
                }
            }

            $deleted = $query->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Rendez-vous supprimé avec succès.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Rendez-vous non trouvé.'
                ], 404);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Statistiques des rendez-vous par spécialité
     */
    public function appointmentsStats(Request $request)
    {
        $user = Auth::user();
        $userRole = $user->role->name ?? '';
        $specialtyMapping = $this->getSpecialtyMapping();
        $allowedSpecialty = $specialtyMapping[$userRole] ?? null;

        if (!$allowedSpecialty) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $today = Carbon::today();
        $dateStart = $request->get('date_start', $today->format('Y-m-d'));
        $dateEnd = $request->get('date_end', $today->format('Y-m-d'));

        $query = \DB::table('liste_rdvs')
            ->whereBetween('date', [$dateStart, $dateEnd]);

        if ($allowedSpecialty !== 'all') {
            $query->where('type_medecin', $allowedSpecialty);
        }

        $totalAppointments = $query->count();
        $todayAppointments = \DB::table('liste_rdvs')
            ->whereDate('date', $today)
            ->when($allowedSpecialty !== 'all', function($q) use ($allowedSpecialty) {
                return $q->where('type_medecin', $allowedSpecialty);
            })
            ->count();

        $weekAppointments = \DB::table('liste_rdvs')
            ->whereBetween('date', [$today, $today->copy()->addDays(7)])
            ->when($allowedSpecialty !== 'all', function($q) use ($allowedSpecialty) {
                return $q->where('type_medecin', $allowedSpecialty);
            })
            ->count();

        return response()->json([
            'total' => $totalAppointments,
            'today' => $todayAppointments,
            'week' => $weekAppointments,
            'specialty' => $allowedSpecialty
        ]);
    }

    /**
     * Statistiques par spécialité
     */
    public function statistics(Request $request)
    {
        $user = Auth::user();
        $userRole = $user->role->name ?? '';
        $specialtyMapping = $this->getSpecialtyMapping();
        $allowedSpecialty = $specialtyMapping[$userRole] ?? null;

        if (!$allowedSpecialty) {
            abort(403, 'Accès non autorisé');
        }

        $today = Carbon::today();

        // Pour les médecins spécialisés : UNIQUEMENT les statistiques d'aujourd'hui de leur spécialité
        if ($allowedSpecialty !== 'all') {
            // Statistiques des patients de la spécialité pour aujourd'hui uniquement
            $patientsQuery = Patient::whereDate('created_at', $today)
                ->where('type_medecin', $allowedSpecialty);

            $validPatientsToday = (clone $patientsQuery)->where('valider', 1)->count();
            $invalidPatientsToday = (clone $patientsQuery)->where('valider', 0)->count();

            // Statistiques des rendez-vous de la spécialité pour aujourd'hui uniquement
            $appointmentsQuery = \DB::table('liste_rdvs')
                ->whereDate('date', $today)
                ->where('type_medecin', $allowedSpecialty);

            $consultationRdvToday = (clone $appointmentsQuery)->where('motif', 'consultation')->count();
            $urgenceRdvToday = (clone $appointmentsQuery)->where('motif', 'urgences')->count();

            // Convocations spécialisées par spécialité - NOUVELLE LOGIQUE
            $convocationsWithPsy = 0;
            $convocationsWithoutPsy = 0;

            if ($allowedSpecialty === 'psycho') {
                // Psychologue : validé si champ psy rempli (non vide et non null)
                $convocationsWithPsy = \DB::table('convoncus')
                    ->whereNotNull('psy')
                    ->where('psy', '!=', '')
                    ->count();
                $convocationsWithoutPsy = \DB::table('convoncus')
                    ->where(function($q) {
                        $q->whereNull('psy')->orWhere('psy', '=', '');
                    })
                    ->count();
            } elseif ($allowedSpecialty === 'médecin générale') {
                // Médecin général : validé si champ medGen rempli (non vide et non null)
                $convocationsWithPsy = \DB::table('convoncus')
                    ->whereNotNull('medGen')
                    ->where('medGen', '!=', '')
                    ->count();
                $convocationsWithoutPsy = \DB::table('convoncus')
                    ->where(function($q) {
                        $q->whereNull('medGen')->orWhere('medGen', '=', '');
                    })
                    ->count();
            } elseif ($allowedSpecialty === 'dentiste') {
                // Dentiste : validé si champ chirDent rempli (non vide et non null)
                $convocationsWithPsy = \DB::table('convoncus')
                    ->whereNotNull('chirDent')
                    ->where('chirDent', '!=', '')
                    ->count();
                $convocationsWithoutPsy = \DB::table('convoncus')
                    ->where(function($q) {
                        $q->whereNull('chirDent')->orWhere('chirDent', '=', '');
                    })
                    ->count();
            }

            // PAS d'exemptions pour les médecins spécialisés - collection vide
            $exemptionsToday = collect();

            // Informations d'affichage de la spécialité
            $specialtyInfo = $this->getSpecialtyDisplayInfo($userRole);

            return view('medical.statistics', compact(
                'validPatientsToday',
                'invalidPatientsToday',
                'consultationRdvToday',
                'urgenceRdvToday',
                'convocationsWithPsy',
                'convocationsWithoutPsy',
                'exemptionsToday',
                'userRole',
                'allowedSpecialty'
            ) + $specialtyInfo);
        }

        // Code pour le médecin chef (inchangé) - avec exemptions
        // Ce bloc reste identique pour maintenir la fonctionnalité du médecin chef

        // Pour le MÉDECIN CHEF uniquement : statistiques complètes de toutes les spécialités avec exemptions

        // Statistiques globales de toutes les spécialités pour aujourd'hui
        $validPatientsToday = Patient::whereDate('patients.created_at', $today)->where('valider', 1)->count();
        $invalidPatientsToday = Patient::whereDate('patients.created_at', $today)->where('valider', 0)->count();

        // Rendez-vous globaux pour aujourd'hui
        $consultationRdvToday = \DB::table('liste_rdvs')
            ->whereDate('date', $today)
            ->where('motif', 'consultation')
            ->count();
        $urgenceRdvToday = \DB::table('liste_rdvs')
            ->whereDate('date', $today)
            ->where('motif', 'urgences')
            ->count();

        // Statistiques détaillées par spécialité pour le médecin chef - LOGIQUE MISE À JOUR
        $specialtyStats = [
            'psycho' => [
                'patients_today' => Patient::whereDate('created_at', $today)->where('type_medecin', 'psycho')->count(),
                'patients_valid' => Patient::whereDate('created_at', $today)->where('type_medecin', 'psycho')->where('valider', 1)->count(),
                'appointments_today' => \DB::table('liste_rdvs')->whereDate('date', $today)->where('type_medecin', 'psycho')->count(),
                // Psychologue : validé si champ psy rempli uniquement (non vide et non null)
                'convocations_valid' => \DB::table('convoncus')
                    ->whereNotNull('psy')
                    ->where('psy', '!=', '')
                    ->count(),
                'convocations_invalid' => \DB::table('convoncus')
                    ->where(function($q) {
                        $q->whereNull('psy')->orWhere('psy', '=', '');
                    })
                    ->count()
            ],
            'médecin générale' => [
                'patients_today' => Patient::whereDate('created_at', $today)->where('type_medecin', 'médecin générale')->count(),
                'patients_valid' => Patient::whereDate('created_at', $today)->where('type_medecin', 'médecin générale')->where('valider', 1)->count(),
                'appointments_today' => \DB::table('liste_rdvs')->whereDate('date', $today)->where('type_medecin', 'médecin générale')->count(),
                // Médecin général : validé si champ medGen rempli uniquement (non vide et non null)
                'convocations_valid' => \DB::table('convoncus')
                    ->whereNotNull('medGen')
                    ->where('medGen', '!=', '')
                    ->count(),
                'convocations_invalid' => \DB::table('convoncus')
                    ->where(function($q) {
                        $q->whereNull('medGen')->orWhere('medGen', '=', '');
                    })
                    ->count()
            ],
            'dentiste' => [
                'patients_today' => Patient::whereDate('created_at', $today)->where('type_medecin', 'dentiste')->count(),
                'patients_valid' => Patient::whereDate('created_at', $today)->where('type_medecin', 'dentiste')->where('valider', 1)->count(),
                'appointments_today' => \DB::table('liste_rdvs')->whereDate('date', $today)->where('type_medecin', 'dentiste')->count(),
                // Dentiste : validé si champ chirDent rempli uniquement (non vide et non null)
                'convocations_valid' => \DB::table('convoncus')
                    ->whereNotNull('chirDent')
                    ->where('chirDent', '!=', '')
                    ->count(),
                'convocations_invalid' => \DB::table('convoncus')
                    ->where(function($q) {
                        $q->whereNull('chirDent')->orWhere('chirDent', '=', '');
                    })
                    ->count()
            ]
        ];

        // Convocations globales pour le médecin chef - NOUVELLE LOGIQUE STRICTE
        // Valides : TOUS les champs (psy, medGen, chirDent, avisSpe) remplis simultanément
        $convocationsWithPsy = \DB::table('convoncus')
            ->whereNotNull('psy')->where('psy', '!=', '')
            ->whereNotNull('medGen')->where('medGen', '!=', '')
            ->whereNotNull('chirDent')->where('chirDent', '!=', '')
            ->whereNotNull('avisSpe')->where('avisSpe', '!=', '')
            ->count();

        // Non valides : au moins UN champ vide ou null
        $convocationsWithoutPsy = \DB::table('convoncus')
            ->where(function($q) {
                $q->whereNull('psy')->orWhere('psy', '=', '')
                  ->orWhereNull('medGen')->orWhere('medGen', '=', '')
                  ->orWhereNull('chirDent')->orWhere('chirDent', '=', '')
                  ->orWhereNull('avisSpe')->orWhere('avisSpe', '=', '');
            })->count();

        // Exemptions UNIQUEMENT pour le médecin chef
        $exemptionsToday = \DB::table('exemptions')
            ->whereDate('date_debut', '<=', $today)
            ->whereDate('date_fin', '>=', $today)
            ->selectRaw('motif, COUNT(*) as count')
            ->groupBy('motif')
            ->get();

        // Informations d'affichage
        $specialtyInfo = $this->getSpecialtyDisplayInfo($userRole);

        return view('statistics.index', compact(
            'validPatientsToday',
            'invalidPatientsToday',
            'consultationRdvToday',
            'urgenceRdvToday',
            'convocationsWithPsy',
            'convocationsWithoutPsy',
            'exemptionsToday',
            'specialtyStats',
            'userRole',
            'allowedSpecialty'
        ) + $specialtyInfo);
    }

    /**
     * Filter statistics by grade (for AJAX requests)
     * - Médecins spécialisés : filtrage par grade pour leur spécialité uniquement
     * - Médecin chef : filtrage par grade pour toutes les spécialités
     */
    public function filterStatistics(Request $request)
    {
        try {
            $user = Auth::user();
            $userRole = $user->role->name ?? '';
            $specialtyMapping = $this->getSpecialtyMapping();
            $allowedSpecialty = $specialtyMapping[$userRole] ?? null;

            if (!$allowedSpecialty) {
                return response()->json(['error' => 'Accès non autorisé'], 403);
            }

            $grade = $request->query('grade');
            $today = Carbon::today();

            // Validation du grade
            if (!in_array($grade, ['all', '1', '2', '3'])) {
                return response()->json(['error' => 'Grade invalide'], 400);
            }

            // Pour les médecins spécialisés : filtrage par grade pour leur spécialité uniquement
            if ($allowedSpecialty !== 'all') {
                return $this->getSpecialtyStatsByGrade($today, $grade, $allowedSpecialty);
            }

            // Pour le médecin chef : statistiques complètes avec filtrage par grade
            if ($grade === 'all') {
                return $this->getAllStatisticsForChief($today);
            }

            // Filtrer par grade spécifique (médecin chef uniquement)
            return $this->getStatisticsByGradeForChief($today, $grade);
        } catch (\Exception $e) {
            \Log::error('Erreur lors du filtrage des statistiques médicales: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur'], 500);
        }
    }

    /**
     * Get statistics for a specific specialty filtered by grade
     * Pour les médecins spécialisés seulement
     */
    private function getSpecialtyStatsByGrade($today, $grade, $allowedSpecialty)
    {
        // Si grade "all", retourner les stats de la spécialité sans filtrage par grade
        if ($grade === 'all') {
            // Statistiques des patients de la spécialité pour aujourd'hui
            $validPatientsToday = Patient::whereDate('created_at', $today)
                ->where('type_medecin', $allowedSpecialty)
                ->where('valider', 1)
                ->count();

            $invalidPatientsToday = Patient::whereDate('created_at', $today)
                ->where('type_medecin', $allowedSpecialty)
                ->where('valider', 0)
                ->count();

            // Statistiques des rendez-vous de la spécialité pour aujourd'hui
            $consultationRdvToday = \DB::table('liste_rdvs')
                ->whereDate('date', $today)
                ->where('type_medecin', $allowedSpecialty)
                ->where('motif', 'consultation')
                ->count();

            $urgenceRdvToday = \DB::table('liste_rdvs')
                ->whereDate('date', $today)
                ->where('type_medecin', $allowedSpecialty)
                ->where('motif', 'urgences')
                ->count();

            // Convocations selon la spécialité - NOUVELLE LOGIQUE MISE À JOUR
            $convocationsWithPsy = 0;
            $convocationsWithoutPsy = 0;

            if ($allowedSpecialty === 'psycho') {
                // Convocations valides: psy NON-NULL ET non-vide, les autres champs ignorés
                $convocationsWithPsy = \DB::table('convoncus')
                    ->whereNotNull('psy')
                    ->where('psy', '!=', '')
                    ->count();
                // Convocations invalides: psy NULL OU vide
                $convocationsWithoutPsy = \DB::table('convoncus')
                    ->where(function($q) {
                        $q->whereNull('psy')->orWhere('psy', '=', '');
                    })
                    ->count();
            } elseif ($allowedSpecialty === 'médecin générale') {
                // Convocations valides: medGen NON-NULL ET non-vide, les autres champs ignorés
                $convocationsWithPsy = \DB::table('convoncus')
                    ->whereNotNull('medGen')
                    ->where('medGen', '!=', '')
                    ->count();
                // Convocations invalides: medGen NULL OU vide
                $convocationsWithoutPsy = \DB::table('convoncus')
                    ->where(function($q) {
                        $q->whereNull('medGen')->orWhere('medGen', '=', '');
                    })
                    ->count();
            } elseif ($allowedSpecialty === 'dentiste') {
                // Convocations valides: chirDent NON-NULL ET non-vide, les autres champs ignorés
                $convocationsWithPsy = \DB::table('convoncus')
                    ->whereNotNull('chirDent')
                    ->where('chirDent', '!=', '')
                    ->count();
                // Convocations invalides: chirDent NULL OU vide
                $convocationsWithoutPsy = \DB::table('convoncus')
                    ->where(function($q) {
                        $q->whereNull('chirDent')->orWhere('chirDent', '=', '');
                    })
                    ->count();
            }

            return response()->json([
                'validPatientsToday' => $validPatientsToday,
                'invalidPatientsToday' => $invalidPatientsToday,
                'consultationRdvToday' => $consultationRdvToday,
                'urgenceRdvToday' => $urgenceRdvToday,
                'convocationsWithPsy' => $convocationsWithPsy,
                'convocationsWithoutPsy' => $convocationsWithoutPsy,
                'exemptionsToday' => [] // Pas d'exemptions pour les médecins spécialisés
            ]);
        }

        // Filtrage par grade spécifique pour la spécialité
        $studentMatricules = \DB::table('students')->where('grade', $grade)->pluck('matricule');

        if ($studentMatricules->isEmpty()) {
            return response()->json([
                'validPatientsToday' => 0,
                'invalidPatientsToday' => 0,
                'consultationRdvToday' => 0,
                'urgenceRdvToday' => 0,
                'convocationsWithPsy' => 0,
                'convocationsWithoutPsy' => 0,
                'exemptionsToday' => []
            ]);
        }

        // Patients de la spécialité filtrés par grade pour aujourd'hui
        $validPatientsToday = Patient::whereDate('created_at', $today)
            ->where('type_medecin', $allowedSpecialty)
            ->whereIn('matricule', $studentMatricules)
            ->where('valider', 1)
            ->count();

        $invalidPatientsToday = Patient::whereDate('created_at', $today)
            ->where('type_medecin', $allowedSpecialty)
            ->whereIn('matricule', $studentMatricules)
            ->where('valider', 0)
            ->count();

        // Rendez-vous de la spécialité filtrés par grade pour aujourd'hui
        $consultationRdvToday = \DB::table('liste_rdvs')
            ->whereDate('date', $today)
            ->where('type_medecin', $allowedSpecialty)
            ->whereIn('matricule', $studentMatricules)
            ->where('motif', 'consultation')
            ->count();

        $urgenceRdvToday = \DB::table('liste_rdvs')
            ->whereDate('date', $today)
            ->where('type_medecin', $allowedSpecialty)
            ->whereIn('matricule', $studentMatricules)
            ->where('motif', 'urgences')
            ->count();

        // Convocations de la spécialité filtrées par grade - NOUVELLE LOGIQUE MISE À JOUR
        $convocationsWithPsy = 0;
        $convocationsWithoutPsy = 0;

        if ($allowedSpecialty === 'psycho') {
            // Convocations valides: psy NON-NULL ET non-vide pour ces étudiants
            $convocationsWithPsy = \DB::table('convoncus')
                ->whereIn('matricule', $studentMatricules)
                ->whereNotNull('psy')
                ->where('psy', '!=', '')
                ->count();
            // Convocations invalides: psy NULL OU vide pour ces étudiants
            $convocationsWithoutPsy = \DB::table('convoncus')
                ->whereIn('matricule', $studentMatricules)
                ->where(function($q) {
                    $q->whereNull('psy')->orWhere('psy', '=', '');
                })
                ->count();
        } elseif ($allowedSpecialty === 'médecin générale') {
            // Convocations valides: medGen NON-NULL ET non-vide pour ces étudiants
            $convocationsWithPsy = \DB::table('convoncus')
                ->whereIn('matricule', $studentMatricules)
                ->whereNotNull('medGen')
                ->where('medGen', '!=', '')
                ->count();
            // Convocations invalides: medGen NULL OU vide pour ces étudiants
            $convocationsWithoutPsy = \DB::table('convoncus')
                ->whereIn('matricule', $studentMatricules)
                ->where(function($q) {
                    $q->whereNull('medGen')->orWhere('medGen', '=', '');
                })
                ->count();
        } elseif ($allowedSpecialty === 'dentiste') {
            // Convocations valides: chirDent NON-NULL ET non-vide pour ces étudiants
            $convocationsWithPsy = \DB::table('convoncus')
                ->whereIn('matricule', $studentMatricules)
                ->whereNotNull('chirDent')
                ->where('chirDent', '!=', '')
                ->count();
            // Convocations invalides: chirDent NULL OU vide pour ces étudiants
            $convocationsWithoutPsy = \DB::table('convoncus')
                ->whereIn('matricule', $studentMatricules)
                ->where(function($q) {
                    $q->whereNull('chirDent')->orWhere('chirDent', '=', '');
                })
                ->count();
        }

        return response()->json([
            'validPatientsToday' => $validPatientsToday,
            'invalidPatientsToday' => $invalidPatientsToday,
            'consultationRdvToday' => $consultationRdvToday,
            'urgenceRdvToday' => $urgenceRdvToday,
            'convocationsWithPsy' => $convocationsWithPsy,
            'convocationsWithoutPsy' => $convocationsWithoutPsy,
            'exemptionsToday' => [] // Pas d'exemptions pour les médecins spécialisés
        ]);
    }

    // === MÉTHODES DUPLIQUÉES SUPPRIMÉES ===
    // Les méthodes getAllStatisticsForChief() et getStatisticsByGradeForChief()
    // dupliquées ont été supprimées pour éviter les erreurs de compilation.
    // Les versions correctes sont maintenues plus bas dans le fichier.

    /**
     * Get all statistics for chief without grade filtering
     * Pour le médecin chef uniquement
     */
    private function getAllStatisticsForChief($today)
    {
        // Statistiques globales de toutes les spécialités pour aujourd'hui
        $validPatientsToday = Patient::whereDate('patients.created_at', $today)->where('valider', 1)->count();
        $invalidPatientsToday = Patient::whereDate('patients.created_at', $today)->where('valider', 0)->count();

        // Rendez-vous globaux pour aujourd'hui
        $consultationRdvToday = \DB::table('liste_rdvs')
            ->whereDate('date', $today)
            ->where('motif', 'consultation')
            ->count();
        $urgenceRdvToday = \DB::table('liste_rdvs')
            ->whereDate('date', $today)
            ->where('motif', 'urgences')
            ->count();

        // Convocations globales pour le médecin chef - NOUVELLE LOGIQUE STRICTE
        // Valides : TOUS les champs (psy, medGen, chirDent, avisSpe) remplis simultanément
        $convocationsWithPsy = \DB::table('convoncus')
            ->whereNotNull('psy')->where('psy', '!=', '')
            ->whereNotNull('medGen')->where('medGen', '!=', '')
            ->whereNotNull('chirDent')->where('chirDent', '!=', '')
            ->whereNotNull('avisSpe')->where('avisSpe', '!=', '')
            ->count();

        // Non valides : au moins UN champ vide ou null
        $convocationsWithoutPsy = \DB::table('convoncus')
            ->where(function($q) {
                $q->whereNull('psy')->orWhere('psy', '=', '')
                  ->orWhereNull('medGen')->orWhere('medGen', '=', '')
                  ->orWhereNull('chirDent')->orWhere('chirDent', '=', '')
                  ->orWhereNull('avisSpe')->orWhere('avisSpe', '=', '');
            })->count();

        // Exemptions UNIQUEMENT pour le médecin chef
        $exemptionsToday = \DB::table('exemptions')
            ->whereDate('date_debut', '<=', $today)
            ->whereDate('date_fin', '>=', $today)
            ->selectRaw('motif, COUNT(*) as count')
            ->groupBy('motif')
            ->get();

        // Statistiques détaillées par spécialité pour le médecin chef - NOUVELLE STRUCTURE AVEC VALIDES/INVALIDES
        $specialtyStats = [
            'psycho' => [
                'patients_today' => Patient::whereDate('created_at', $today)->where('type_medecin', 'psycho')->count(),
                'patients_valid' => Patient::whereDate('created_at', $today)->where('type_medecin', 'psycho')->where('valider', 1)->count(),
                'appointments_today' => \DB::table('liste_rdvs')->whereDate('date', $today)->where('type_medecin', 'psycho')->count(),
                'convocations_valid' => \DB::table('convoncus')
                    ->whereNotNull('psy')
                    ->where('psy', '!=', '')
                    ->count(),
                'convocations_invalid' => \DB::table('convoncus')
                    ->where(function($q) {
                        $q->whereNull('psy')->orWhere('psy', '=', '');
                    })
                    ->count()
            ],
            'médecin générale' => [
                'patients_today' => Patient::whereDate('created_at', $today)->where('type_medecin', 'médecin générale')->count(),
                'patients_valid' => Patient::whereDate('created_at', $today)->where('type_medecin', 'médecin générale')->where('valider', 1)->count(),
                'appointments_today' => \DB::table('liste_rdvs')->whereDate('date', $today)->where('type_medecin', 'médecin générale')->count(),
                'convocations_valid' => \DB::table('convoncus')
                    ->whereNotNull('medGen')
                    ->where('medGen', '!=', '')
                    ->count(),
                'convocations_invalid' => \DB::table('convoncus')
                    ->where(function($q) {
                        $q->whereNull('medGen')->orWhere('medGen', '=', '');
                    })
                    ->count()
            ],
            'dentiste' => [
                'patients_today' => Patient::whereDate('created_at', $today)->where('type_medecin', 'dentiste')->count(),
                'patients_valid' => Patient::whereDate('created_at', $today)->where('type_medecin', 'dentiste')->where('valider', 1)->count(),
                'appointments_today' => \DB::table('liste_rdvs')->whereDate('date', $today)->where('type_medecin', 'dentiste')->count(),
                'convocations_valid' => \DB::table('convoncus')
                    ->whereNotNull('chirDent')
                    ->where('chirDent', '!=', '')
                    ->count(),
                'convocations_invalid' => \DB::table('convoncus')
                    ->where(function($q) {
                        $q->whereNull('chirDent')->orWhere('chirDent', '=', '');
                    })
                    ->count()
            ]
        ];

        return response()->json([
            'validPatientsToday' => $validPatientsToday,
            'invalidPatientsToday' => $invalidPatientsToday,
            'consultationRdvToday' => $consultationRdvToday,
            'urgenceRdvToday' => $urgenceRdvToday,
            'convocationsWithPsy' => $convocationsWithPsy,
            'convocationsWithoutPsy' => $convocationsWithoutPsy,
            'exemptionsToday' => $exemptionsToday,
            'specialtyStats' => $specialtyStats
        ]);
    }

    /**
     * Get statistics for chief filtered by specific grade
     * Pour le médecin chef uniquement
     */
    private function getStatisticsByGradeForChief($today, $grade)
    {
        // Récupérer les matricules des étudiants du grade spécifié
        $studentMatricules = \DB::table('students')->where('grade', $grade)->pluck('matricule');

        if ($studentMatricules->isEmpty()) {
            return response()->json([
                'validPatientsToday' => 0,
                'invalidPatientsToday' => 0,
                'consultationRdvToday' => 0,
                'urgenceRdvToday' => 0,
                'convocationsWithPsy' => 0,
                'convocationsWithoutPsy' => 0,
                'exemptionsToday' => [],
                'specialtyStats' => [
                    'psycho' => [
                        'patients_today' => 0,
                        'patients_valid' => 0,
                        'appointments_today' => 0,
                        'convocations_valid' => 0,
                        'convocations_invalid' => 0
                    ],
                    'médecin générale' => [
                        'patients_today' => 0,
                        'patients_valid' => 0,
                        'appointments_today' => 0,
                        'convocations_valid' => 0,
                        'convocations_invalid' => 0
                    ],
                    'dentiste' => [
                        'patients_today' => 0,
                        'patients_valid' => 0,
                        'appointments_today' => 0,
                        'convocations_valid' => 0,
                        'convocations_invalid' => 0
                    ]
                ]
            ]);
        }

        // Patients filtrés par grade
        $validPatientsToday = Patient::whereDate('created_at', $today)
            ->where('valider', 1)
            ->whereIn('matricule', $studentMatricules)
            ->count();

        $invalidPatientsToday = Patient::whereDate('created_at', $today)
            ->where('valider', 0)
            ->whereIn('matricule', $studentMatricules)
            ->count();

        // Rendez-vous filtrés par grade
        $consultationRdvToday = \DB::table('liste_rdvs')
            ->whereDate('date', $today)
            ->where('motif', 'consultation')
            ->whereIn('matricule', $studentMatricules)
            ->count();

        $urgenceRdvToday = \DB::table('liste_rdvs')
            ->whereDate('date', $today)
            ->where('motif', 'urgences')
            ->whereIn('matricule', $studentMatricules)
            ->count();

        // Convocations globales pour le médecin chef filtrées par grade
        $convocationsWithPsy = \DB::table('convoncus')
            ->whereIn('matricule', $studentMatricules)
            ->whereNotNull('psy')->where('psy', '!=', '')
            ->whereNotNull('medGen')->where('medGen', '!=', '')
            ->whereNotNull('chirDent')->where('chirDent', '!=', '')
            ->whereNotNull('avisSpe')->where('avisSpe', '!=', '')
            ->count();

        $convocationsWithoutPsy = \DB::table('convoncus')
            ->whereIn('matricule', $studentMatricules)
            ->where(function($q) {
                $q->whereNull('psy')->orWhere('psy', '=', '')
                  ->orWhereNull('medGen')->orWhere('medGen', '=', '')
                  ->orWhereNull('chirDent')->orWhere('chirDent', '=', '')
                  ->orWhereNull('avisSpe')->orWhere('avisSpe', '=', '');
            })->count();

        // Exemptions filtrées par grade
        $exemptionsToday = \DB::table('exemptions')
            ->whereDate('date_debut', '<=', $today)
            ->whereDate('date_fin', '>=', $today)
            ->whereIn('matricule', $studentMatricules)
            ->selectRaw('motif, COUNT(*) as count')
            ->groupBy('motif')
            ->get();

        // Statistiques détaillées par spécialité filtrées par grade - NOUVELLE STRUCTURE
        $specialtyStats = [
            'psycho' => [
                'patients_today' => Patient::whereDate('created_at', $today)
                    ->where('type_medecin', 'psycho')
                    ->whereIn('matricule', $studentMatricules)
                    ->count(),
                'patients_valid' => Patient::whereDate('created_at', $today)
                    ->where('type_medecin', 'psycho')
                    ->whereIn('matricule', $studentMatricules)
                    ->where('valider', 1)
                    ->count(),
                'appointments_today' => \DB::table('liste_rdvs')
                    ->whereDate('date', $today)
                    ->where('type_medecin', 'psycho')
                    ->whereIn('matricule', $studentMatricules)
                    ->count(),
                'convocations_valid' => \DB::table('convoncus')
                    ->whereIn('matricule', $studentMatricules)
                    ->whereNotNull('psy')
                    ->where('psy', '!=', '')
                    ->count(),
                'convocations_invalid' => \DB::table('convoncus')
                    ->whereIn('matricule', $studentMatricules)
                    ->where(function($q) {
                        $q->whereNull('psy')->orWhere('psy', '=', '');
                    })
                    ->count()
            ],
            'médecin générale' => [
                'patients_today' => Patient::whereDate('created_at', $today)
                    ->where('type_medecin', 'médecin générale')
                    ->whereIn('matricule', $studentMatricules)
                    ->count(),
                'patients_valid' => Patient::whereDate('created_at', $today)
                    ->where('type_medecin', 'médecin générale')
                    ->whereIn('matricule', $studentMatricules)
                    ->where('valider', 1)
                    ->count(),
                'appointments_today' => \DB::table('liste_rdvs')
                    ->whereDate('date', $today)
                    ->where('type_medecin', 'médecin générale')
                    ->whereIn('matricule', $studentMatricules)
                    ->count(),
                'convocations_valid' => \DB::table('convoncus')
                    ->whereIn('matricule', $studentMatricules)
                    ->whereNotNull('medGen')
                    ->where('medGen', '!=', '')
                    ->count(),
                'convocations_invalid' => \DB::table('convoncus')
                    ->whereIn('matricule', $studentMatricules)
                    ->where(function($q) {
                        $q->whereNull('medGen')->orWhere('medGen', '=', '');
                    })
                    ->count()
            ],
            'dentiste' => [
                'patients_today' => Patient::whereDate('created_at', $today)
                    ->where('type_medecin', 'dentiste')
                    ->whereIn('matricule', $studentMatricules)
                    ->count(),
                'patients_valid' => Patient::whereDate('created_at', $today)
                    ->where('type_medecin', 'dentiste')
                    ->whereIn('matricule', $studentMatricules)
                    ->where('valider', 1)
                    ->count(),
                'appointments_today' => \DB::table('liste_rdvs')
                    ->whereDate('date', $today)
                    ->where('type_medecin', 'dentiste')
                    ->whereIn('matricule', $studentMatricules)
                    ->count(),
                'convocations_valid' => \DB::table('convoncus')
                    ->whereIn('matricule', $studentMatricules)
                    ->whereNotNull('chirDent')
                    ->where('chirDent', '!=', '')
                    ->count(),
                'convocations_invalid' => \DB::table('convoncus')
                    ->whereIn('matricule', $studentMatricules)
                    ->where(function($q) {
                        $q->whereNull('chirDent')->orWhere('chirDent', '=', '');
                    })
                    ->count()
            ]
        ];

        return response()->json([
            'validPatientsToday' => $validPatientsToday,
            'invalidPatientsToday' => $invalidPatientsToday,
            'consultationRdvToday' => $consultationRdvToday,
            'urgenceRdvToday' => $urgenceRdvToday,
            'convocationsWithPsy' => $convocationsWithPsy,
            'convocationsWithoutPsy' => $convocationsWithoutPsy,
            'exemptionsToday' => $exemptionsToday,
            'specialtyStats' => $specialtyStats
        ]);
    }

    /**
     * API pour les statistiques du dashboard médical
     * Retourne les données pour les cartes du dashboard du médecin chef
     */
    public function dashboardStats(Request $request)
    {
        $user = Auth::user();
        $userRole = $user->role->name ?? '';
        $specialtyMapping = $this->getSpecialtyMapping();
        $allowedSpecialty = $specialtyMapping[$userRole] ?? null;

        if (!$allowedSpecialty) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $type = $request->query('type', 'daily');
        $today = Carbon::today();

        if ($type === 'daily' && $allowedSpecialty === 'all') {
            // Statistiques quotidiennes pour le médecin chef
            $patientsToday = Patient::whereDate('created_at', $today)->count();
            $validatedToday = Patient::whereDate('created_at', $today)->where('valider', 1)->count();
            $pendingToday = Patient::whereDate('created_at', $today)->where('valider', 0)->count();
            $appointmentsToday = \DB::table('liste_rdvs')->whereDate('date', $today)->count();

            return response()->json([
                'patients' => $patientsToday,
                'validated' => $validatedToday,
                'pending' => $pendingToday,
                'appointments' => $appointmentsToday
            ]);
        }

        if ($type === 'specialty' && $allowedSpecialty === 'all') {
            // Statistiques par spécialité pour le médecin chef avec la nouvelle structure
            $specialties = [
                [
                    'type' => 'psycho',
                    'name' => 'Psychologie',
                    'validated' => Patient::where('type_medecin', 'psycho')->where('valider', 1)->count(),
                    'pending' => Patient::where('type_medecin', 'psycho')->where('valider', 0)->count(),
                    'total' => Patient::where('type_medecin', 'psycho')->count(),
                    'convocations_valid' => \DB::table('convoncus')
                        ->whereNotNull('psy')
                        ->where('psy', '!=', '')
                        ->count(),
                    'convocations_invalid' => \DB::table('convoncus')
                        ->where(function($q) {
                            $q->whereNull('psy')->orWhere('psy', '=', '');
                        })
                        ->count()
                ],
                [
                    'type' => 'médecin générale',
                    'name' => 'Médecine Générale',
                    'validated' => Patient::where('type_medecin', 'médecin générale')->where('valider', 1)->count(),
                    'pending' => Patient::where('type_medecin', 'médecin générale')->where('valider', 0)->count(),
                    'total' => Patient::where('type_medecin', 'médecin générale')->count(),
                    'convocations_valid' => \DB::table('convoncus')
                        ->whereNotNull('medGen')
                        ->where('medGen', '!=', '')
                        ->count(),
                    'convocations_invalid' => \DB::table('convoncus')
                        ->where(function($q) {
                            $q->whereNull('medGen')->orWhere('medGen', '=', '');
                        })
                        ->count()
                ],
                [
                    'type' => 'dentiste',
                    'name' => 'Dentaire',
                    'validated' => Patient::where('type_medecin', 'dentiste')->where('valider', 1)->count(),
                    'pending' => Patient::where('type_medecin', 'dentiste')->where('valider', 0)->count(),
                    'total' => Patient::where('type_medecin', 'dentiste')->count(),
                    'convocations_valid' => \DB::table('convoncus')
                        ->whereNotNull('chirDent')
                        ->where('chirDent', '!=', '')
                        ->count(),
                    'convocations_invalid' => \DB::table('convoncus')
                        ->where(function($q) {
                            $q->whereNull('chirDent')->orWhere('chirDent', '=', '');
                        })
                        ->count()
                ]
            ];

            return response()->json($specialties);
        }

        // Pour les médecins spécialisés, retourner seulement leurs statistiques
        if ($allowedSpecialty !== 'all') {
            $stats = $this->getSpecialtyStats($allowedSpecialty);
            return response()->json($stats);
        }

        return response()->json(['error' => 'Type de statistique non supporté'], 400);
    }

    /**
     * Get specialty display information
     * Retourne les informations d'affichage pour chaque spécialité médicale
     */
    private function getSpecialtyDisplayInfo($userRole)
    {
        $specialtyInfo = [
            'Medecin' => [
                'specialtyName' => 'Médecin Chef',
                'specialtyIcon' => 'fas fa-user-md',
                'specialtyColor' => 'blue'
            ],
            'Psychologue' => [
                'specialtyName' => 'Psychologie',
                'specialtyIcon' => 'fas fa-brain',
                'specialtyColor' => 'purple'
            ],
            'Dentiste' => [
                'specialtyName' => 'Dentaire',
                'specialtyIcon' => 'fas fa-tooth',
                'specialtyColor' => 'green'
            ],
            'Médecin général' => [
                'specialtyName' => 'Médecine Générale',
                'specialtyIcon' => 'fas fa-stethoscope',
                'specialtyColor' => 'indigo'
            ]
        ];

        return $specialtyInfo[$userRole] ?? [
            'specialtyName' => 'Spécialité Médicale',
            'specialtyIcon' => 'fas fa-user-md',
            'specialtyColor' => 'gray'
        ];
    }
}
