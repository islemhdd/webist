@php
    use Illuminate\Support\Carbon;
@endphp





<tr @class([
    'crossed' => $student->consigned == 1,
    'student',
    'studentssortie',
]) class="hover:bg-gray-100 dark:hover:bg-gray-700 transition">
    <td class="px-4 py-2">{{ $student->matricule }}</td>
    <td class="px-4 py-2">{{ $student->nom }}</td>
    <td class="px-4 py-2">prenom</td>
    <td class="px-4 py-2">{{ $student->section->code() }}</td>
    <td class="px-4 py-2 text-center">
        <input name="{{ $student->matricule }}" @checked($student->choix === 'sam') @disabled($student->consigned == 1 || $lock == 1) type="radio"
            onclick="toggleRadio(this)"
            wire:click="setOrUpdateSortie('sam','{{ Carbon::now('Africa/Algiers')->next(Carbon::SATURDAY)->setTime(8, 0, 0)->toDateTimeString() }}','{{ Carbon::now('Africa/Algiers')->next(Carbon::SATURDAY)->setTime(22, 0, 0)->toDateTimeString() }}')"
            class="form-radio h-5 w-5 text-pink-500 focus:ring-pink-400 transition">
    </td>
    <td class="px-4 py-2 text-center">
        <input name="{{ $student->matricule }}" @checked($student->choix === 'ven') @disabled($student->consigned == 1 || $lock == 1) type="radio"
            onclick="toggleRadio(this)"
            wire:click="setOrUpdateSortie('ven','{{ Carbon::now('Africa/Algiers')->next(Carbon::FRIDAY)->setTime(8, 0, 0)->toDateTimeString() }}','{{ Carbon::now('Africa/Algiers')->next(Carbon::FRIDAY)->setTime(22, 0, 0)->toDateTimeString() }}')"
            class="form-radio h-5 w-5 text-purple-500 focus:ring-purple-400 transition">
    </td>
    <td class="px-4 py-2 text-center">
        <input name="{{ $student->matricule }}" @checked($student->choix === '48h') @disabled($student->consigned == 1 || $lock == 1) type="radio"
            onclick="toggleRadio(this)"
            wire:click="setOrUpdateSortie('48h','{{ Carbon::now('Africa/Algiers')->next(Carbon::THURSDAY)->setTime(8, 0, 0)->toDateTimeString() }}','{{ Carbon::now('Africa/Algiers')->next(Carbon::SATURDAY)->setTime(22, 0, 0)->toDateTimeString() }}')"
            class="form-radio h-5 w-5 text-blue-500 focus:ring-blue-400 transition">
    </td>
    <td></td>
</tr>
