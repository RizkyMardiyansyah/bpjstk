<style>
    .notification{
        border-bottom: 1px solid rgba(0, 0, 0, 0.6);
        margin-bottom: 20px;
        
    }
    .border{
        border: 1px solid rgba(0, 0, 0, 0.4);
        border-radius: 10px;
        padding: 10px;
        margin: 5px;
    }
    .btn-primary {
    font-size: 10px !important;
    width: 80px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.5;
    border-radius: 4px;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
}

.btn{
    height: 30px;
}


.btnn {
    font-size: 10px !important;
    width: 80px;
    color: var(--primary);
    
    background-color: transparent;
    border: 1px solid var(--primary);
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.5;
    border-radius: 4px;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
    
}

.btnn:hover {
    color: #fff;
    background-color:var(--primary);
    border-color:var(--primary);
    .btn-primary{
    color: var(--primary);
    background-color: transparent;}
}

.bttn:focus,
.btnn.focus {
    color: #fff;
    background-color: var(--primary);
    border-color: var(--primary);
}
</style>
<div class="row m-0" style="margin-top: -20px">
    <div class="row m-0">       
        <div class="row m-0 justify-content-between align-items-center">
                <h3 class="text-dark-blue mb-2" >{{__('update.top_organization')}}</h3>
        </div> 
    </div>
    <div class="row m-0 mb-2 ml-auto">
        <button style="border-radius:0; border-top-left-radius: 10px; border-bottom-left-radius: 10px;" class="btn ml-auto teacher btn-primary">{{__("public.colaburator")}}</button>
    <button style="border-radius:0; border-top-right-radius: 10px; border-bottom-right-radius: 10px;" class="btn student btnn">{{__("public.students")}}</button>
    
    </div>
    
</div>


    <div class="bg-white noticeboard rounded-sm panel-shadow py-10 py-md-20 px-15 px-md-30 height">
        
        
       
                
    <div class="mt-4">
        <canvas id="organizationCanvasStatistic"></canvas>
    </div>



@push('scripts_bottom')
<script src="/assets/default/vendors/chartjs/chart.min.js"></script>
<script src="/assets/admin/vendor/owl.carousel/owl.carousel.min.js"></script>

<script>
    var organizationCanvasStatistic = document.getElementById("organizationCanvasStatistic").getContext("2d")
    chart = {};

var backgroundColors = [
        // "rgba(103, 119, 239, 0.7)",
        "rgba(255, 99, 132, 0.7)",
        
        // Tambahkan warna lain sesuai kebutuhan
    ];

function makeStatisticsChart(t, a, e, s, r) {

    for(const [key, value] of Object.entries(chart)){
        chart[key].destroy();
        chart[key] = null;
    }

    chart[t] = new Chart(a, {
        type: "bar", // Mengubah jenis chart menjadi "bar"
        data: {
            labels: s,
            datasets: [{
                label: e,
                data: r,
                borderWidth: 5,
                borderColor: "#6777ef",
                backgroundColor: "rgba(103, 119, 239, 0.7)", // Ganti warna latar belakang sesuai kebutuhan
                hoverBackgroundColor: "#6777ef",
            }]
        },
        options: {
            legend: { display: !1 },
            scales: {
                yAxes: [{
                    gridLines: { display: !1, drawBorder: !1 },
                    ticks: { stepSize: 150 }
                }],
                xAxes: [{
                    gridLines: { color: "#fbfbfb", lineWidth: 2 }
                }]
            }
        }
    });
}

(function () {
    "use strict";
    

@php
        
        $getLearnerData = $topOrganizationChart;
        $getTeacherData = $topOrganizationChartTeacher;
@endphp

makeStatisticsChart('organizationCanvasStatistic', 
organizationCanvasStatistic, 
'Organization', 
@json($getTeacherData['labels']),
@json($getTeacherData['data']));
$("body").on("click", ".teacher", function (t) {
        t.preventDefault();
        $(this).removeClass("btnn");
        $(this).addClass("btn-primary");
        $(".student").removeClass("btn-primary");
        $(".student").addClass("btnn");
        makeStatisticsChart('organizationCanvasStatistic', 
organizationCanvasStatistic, 
'Organization', 
@json($getTeacherData['labels']),
@json($getTeacherData['data']));
       
    });

    $("body").on("click", ".student", function (t) {
        t.preventDefault();
        $(this).removeClass("btnn");
        $(this).addClass("btn-primary");
        $(".teacher").removeClass("btn-primary");
        $(".teacher").addClass("btnn");
        makeStatisticsChart('organizationCanvasStatistic', 
organizationCanvasStatistic, 
'Organization', 
@json($getLearnerData['labels']),
@json($getLearnerData['data']));
       
       
    });

})(jQuery);



</script>


@endpush
</div>






