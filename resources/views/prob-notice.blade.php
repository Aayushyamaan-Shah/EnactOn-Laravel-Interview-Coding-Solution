
{{-- TODO: add Message logic here --}}
@php
    use App\Models\Prize;
    $current_probability = floatval(Prize::sum('probability'));
    $remaining_probability = 100.0 - $current_probability;
    $msg = "The sum of all prizes must be 100%. Currently its " . $current_probability . "%. You have to add ". $remaining_probability ."% to the prize.";
@endphp
@if( $current_probability < 100)
    <div class="alert alert-danger">
        {{$msg}}
    </div>
@endif
