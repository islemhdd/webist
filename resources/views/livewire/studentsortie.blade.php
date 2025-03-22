@php
    use Illuminate\Support\Carbon;

@endphp





<tr @class(['crossed' => $student->consigned == 1]) style="text-indent:3%">


    <td>{{$student->id}}</td>
    <td>{{$student->name}}</td>
    <td>prenom</td>
    <td>{{$student->section->code()}} </td>
    <td><input @disabled($student->consigned==1) type="radio" wire:click="setOrUpdateSortie('sam','{{Carbon::now('Africa/Algiers')
      ->next(Carbon::SATURDAY)
      ->setTime(8, 0, 0)
      ->toDateTimeString()}}','{{
      Carbon::now('Africa/Algiers')
      ->next(Carbon::SATURDAY)
      ->setTime(22, 0, 0)
      ->toDateTimeString()}}')" onclick="toggleRadio(this)">


    <td><input @disabled($student->consigned==1) type="radio"  name="{{$student->id}}" wire:click="setOrUpdateSortie('ven','{{Carbon::now('Africa/Algiers')
       ->next(Carbon::FRIDAY)
      ->setTime(8, 0, 0)
      ->toDateTimeString()}}','{{Carbon::now('Africa/Algiers')
       ->next(Carbon::FRIDAY)
      ->setTime(22, 0, 0)
      ->toDateTimeString()}}')" name="{{$student->id}}" onclick="toggleRadio(this)" value=ven></td>
    <td><input @disabled($student->consigned==1) type="radio" onclick="toggleRadio(this)" value='48h'  name="{{$student->id}}" wire:click="setOrUpdateSortie('48h','{{Carbon::now('Africa/Algiers')
       ->next(Carbon::THURSDAY)
      ->setTime(8, 0, 0)
      ->toDateTimeString()}}',{{Carbon::now('Africa/Algiers')
      ->next(Carbon::SATURDAY)
      ->setTime(8, 0, 0)
      ->toDateTimeString()}}','{{
      Carbon::now('Africa/Algiers')
      ->next(Carbon::SATURDAY)
      ->setTime(22, 0, 0)
      ->toDateTimeString()}})"  ></td>
    <td>
      {{--TODO: added for a drop list to specefie if the sortie if special like  36h and  permissions --}}
    </td>

</tr>
