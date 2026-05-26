@if($template->logo_path)
    @if(isset($forPdf) && $forPdf)
        <img src="{{ storage_path('app/public/' . $template->logo_path) }}"
             alt="Logo" style="max-height:70px; max-width:100%; display:block;">
    @else
        <img src="{{ asset('storage/' . $template->logo_path) }}"
             alt="Logo" style="max-height:70px; max-width:100%; display:block;">
    @endif
@else
    <div style="font-size:20px; font-weight:bold; color:{{ $template->primary_color }};">
        {{ $invoice->company_name ?? 'Your Company' }}
    </div>
@endif