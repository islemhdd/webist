@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Liste des Consignes</h1>
            <a href="{{ route('consignes.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Nouvelle Consigne
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white shadow-md rounded my-6">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th
                            class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Matricule</th>
                        <th
                            class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Étudiant</th>
                        <th
                            class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Type</th>
                        <th
                            class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Statut</th>
                        <th
                            class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Date Fin</th>
                        <th
                            class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($consignes as $consigne)
                        <tr>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                {{ $consigne->matricule }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                {{ $consigne->studnet->nom ?? 'N/A' }} {{ $consigne->studnet->prenom ?? '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                {{ $consigne->type }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $consigne->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $consigne->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                {{ $consigne->end_date->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 font-medium">
                                <a href="{{ route('consignes.show', $consigne) }}"
                                    class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                                <a href="{{ route('consignes.edit', $consigne) }}"
                                    class="text-green-600 hover:text-green-900 mr-3">Modifier</a>
                                <form action="{{ route('consignes.destroy', $consigne) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette consigne ?')">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $consignes->links() }}
        </div>
    </div>
@endsection
