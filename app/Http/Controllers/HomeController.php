<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Afficher la page d'accueil
     */
    public function index()
    {
        return response()->json([
            'title' => 'Accueil - ENPEI Système de Gestion Scolaire',
            'description' => 'Découvrez le système de gestion scolaire complet d\'ENPEI couvrant l\'infirmerie, la brigade élève, la scolarité et le suivi pédagogique.'
        ]);
        

    }

    /**
     * Afficher la page À propos
     */
    public function about()
    {
        $statistics = [
            'students' => 2500,
            'teachers' => 150,
            'years' => 25,
            'success_rate' => 95
        ];

        return view('home.about', [
            'title' => 'À Propos - ENPEI',
            'description' => 'Découvrez la mission, les valeurs et l\'histoire de l\'École Nationale Préparatoire aux Études d\'Ingénieur.',
            'statistics' => $statistics
        ]);
    }

    /**
     * Afficher la page Services
     */
    public function services()
    {
        $services = [
            'infirmerie' => [
                'title' => 'Service Infirmerie',
                'description' => 'Gestion complète de la santé scolaire',
                'features' => [
                    'Prise de rendez-vous médicaux',
                    'Convocations automatisées',
                    'Évaluations de santé',
                    'Gestion des exemptions',
                    'Tableau de bord médical'
                ],
                'icon' => 'fas fa-user-md',
                'color' => 'danger'
            ],
            'brigade' => [
                'title' => 'Brigade Élève',
                'description' => 'Surveillance et encadrement disciplinaire',
                'features' => [
                    'Gestion des rendez-vous',
                    'Système de convocations',
                    'Rapports disciplinaires',
                    'Gestion des sorties',
                    'Suivi comportemental'
                ],
                'icon' => 'fas fa-shield-alt',
                'color' => 'warning'
            ],
            'scolarite' => [
                'title' => 'Département Scolarité',
                'description' => 'Administration académique et absences',
                'features' => [
                    'Justification des absences',
                    'Gestion des listes d\'élèves',
                    'Suivi des présences',
                    'Rapports statistiques',
                    'Communication parents'
                ],
                'icon' => 'fas fa-graduation-cap',
                'color' => 'info'
            ],
            'suivi' => [
                'title' => 'Suivi Pédagogique',
                'description' => 'Accompagnement et supervision éducative',
                'features' => [
                    'Signalement de problèmes',
                    'Gestion des retards',
                    'Absences des enseignants',
                    'Suivi des performances',
                    'Interventions ciblées'
                ],
                'icon' => 'fas fa-chart-line',
                'color' => 'success'
            ]
        ];

        return view('home.services', [
            'title' => 'Nos Services - ENPEI',
            'description' => 'Découvrez les quatre modules principaux de notre système de gestion scolaire.',
            'services' => $services
        ]);
    }

    /**
     * Afficher la page Contact
     */
    public function contact()
    {
        $departments = [
            [
                'name' => 'Service Infirmerie',
                'email' => 'infirmerie@enpei.edu.dz',
                'phone' => '+213 21 XX XX XX',
                'icon' => 'fas fa-user-md',
                'color' => 'danger'
            ],
            [
                'name' => 'Brigade Élève',
                'email' => 'brigade@enpei.edu.dz',
                'phone' => '+213 21 XX XX XX',
                'icon' => 'fas fa-shield-alt',
                'color' => 'warning'
            ],
            [
                'name' => 'Département Scolarité',
                'email' => 'scolarite@enpei.edu.dz',
                'phone' => '+213 21 XX XX XX',
                'icon' => 'fas fa-graduation-cap',
                'color' => 'info'
            ],
            [
                'name' => 'Suivi Pédagogique',
                'email' => 'suivi@enpei.edu.dz',
                'phone' => '+213 21 XX XX XX',
                'icon' => 'fas fa-chart-line',
                'color' => 'success'
            ]
        ];

        return view('home.contact', [
            'title' => 'Contact - ENPEI',
            'description' => 'Contactez-nous pour toute information concernant nos services de gestion scolaire.',
            'departments' => $departments
        ]);
    }

    /**
     * Traiter l'envoi du formulaire de contact
     */
    public function sendContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'department' => 'required|string',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10'
        ], [
            'name.required' => 'Le nom est requis.',
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'department.required' => 'Veuillez sélectionner un département.',
            'subject.required' => 'Le sujet est requis.',
            'message.required' => 'Le message est requis.',
            'message.min' => 'Le message doit contenir au moins 10 caractères.'
        ]);

        try {
            // Ici vous pouvez ajouter la logique d'envoi d'email
            // Mail::to('contact@enpei.edu.dz')->send(new ContactMessage($request->all()));

            return response()->json([
                'success' => true,
                'message' => 'Votre message a été envoyé avec succès ! Nous vous contacterons bientôt.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer.'
            ], 500);
        }
    }

    /**
     * API endpoint pour obtenir les statistiques
     */
    public function getStatistics()
    {
        $statistics = [
            'students_count' => 2500,
            'teachers_count' => 150,
            'years_experience' => 25,
            'success_rate' => 95,
            'medical_visits' => 850,
            'disciplinary_cases' => 120,
            'absence_reports' => 450,
            'pedagogical_interventions' => 230
        ];

        return response()->json($statistics);
    }
}
