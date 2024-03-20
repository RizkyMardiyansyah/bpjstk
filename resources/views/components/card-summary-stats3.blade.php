<style>
    .panel-shadow{
        box-shadow: none;
    }
    .panel-shadow:hover{
        box-shadow: none;
    }
</style>
<div class="dashboard-stats rounded-sm panel-shadow p-10 p-md-20 d-flex align-items-center">
    <div class="stat-icon3 requests">
        <img src="/assets/default/img/icons/{{isset($attributes['icon']) ? $attributes['icon'] : 'request.svg'}}" alt="icon">
    </div>
    <div class="d-flex flex-column ml-15">
        <span class="font-30 text-secondary">{{$attributes['value']}}</span>
        <span class="font-14 text-gray font-weight-900">{{$attributes['title']}}</span>
    </div>
</div>
