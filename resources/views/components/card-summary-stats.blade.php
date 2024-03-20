<div class=" justify-content-center">
<div class="dashboard-stats rounded-sm panel-shadow p-10 p-md-20  d-flex align-items-center">
    <div class="stat-icon2 requests mr-2">
        {{-- <img src="/assets/default/img/icons/{{isset($attributes['icon']) ? $attributes['icon'] : 'request.svg'}}" alt="icon"> --}}
    </div>
    <div class="d-flex flex-column ">
        <span class="font-30 text-secondary">{{$attributes['value']}}</span>
        <span class="font-14 text-gray font-weight-900">{{$attributes['title']}}</span>
    </div>
</div>
</div>
