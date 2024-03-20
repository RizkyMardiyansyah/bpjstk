<style>
    .notification {
        border-bottom: 1px solid rgba(0, 0, 0, 0.6);
        margin-bottom: 20px;
    }

    .border {
        border: 1px solid rgba(0, 0, 0, 0.4);
        border-radius: 10px;
        padding: 10px;
        margin: 5px;
    }
    .box-container {
    display: flex;
    align-items: center;
}

.box {
    display: inline-block;
    width: 10px;
    height: 10px;
    margin-right: 5px;
    border-radius: 2px;
}
.box-wrapper {
    padding-top: 10px;
    padding-bottom: 10px;
    display: flex;
    align-items: center;
}

.box {
    width: 10px;
    height: 10px;
    margin-right: 10px;
    border-radius: 2px;
}

.skills span {
    font-size: 8px;
    color: "#fbfbfb";
    display: inline-block;
}

.bgchart {
    padding: 20px;
    display: block;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    position: relative;
        width: 100%; /* Lebar 100% untuk tampilan ponsel */
    }
.web{
    display: none !important;
}
.mobile{
    display: block !important;
}

@media (min-width: 1200px) {
    .skills span {
    font-size: 12px;
    color: "#fbfbfb";
    display: inline-block;
}
    .bgchart {
    height: 320px;
}
.mobile{
    display: none !important;  
}
.web{
    display: block !important;
}
}


</style>

<div class="row m-0">
    <h3 class="text-dark-blue mb-2">{{ trans('diary.statistic') }}</h3>
    
</div>

<div style="display:flex" class="skills bg-white noticeboard rounded-sm panel-shadow py-10 py-md-20 px-15 px-md-30 bgchart">
    <div class="web" style="flex: 1">

        <div class="box-wrapper">
            <div class="box" style="background-color: #ff9ab0"></div>
            <span>Analytical Problem Solving & Planing</span>
        </div>
        <div class="box-wrapper">
            <div class="box" style="background-color: #fcdf9b;"></div>
            <span>Agile Leadership</span>
        </div>
        <div class="box-wrapper">
            <div class="box" style="background-color: #b28bfe;"></div>
            <span>Driving Digital Innovation</span>
        </div>
        <div class="box-wrapper">
            <div class="box" style="background-color: #f69ef5;"></div>
            <span>Developing Capabilities</span>
        </div>
        <div class="box-wrapper">
            <div class="box" style="background-color: #7c65fe;"></div>
            <span>Results Orientation & Execution</span>
        </div>
        <div class="box-wrapper">
            <div class="box" style="background-color: #9bc1ef;"></div>
            <span>Stakeholder Orientation</span>
        </div>
        <div class="box-wrapper">
            <div class="box" style="background-color: #6adadb;"></div>
            <span>Strategy & Business Acumen</span>
        </div>
        <div class="box-wrapper">
            <div class="box" style="background-color: #a6fea7;"></div>
            <span>Synergistic Collaboration</span>
        </div>
    </div>



    <div >
    <!-- Tambahkan elemen canvas di sini -->
    <canvas class="mobile" style="width: 100%;" id="mobilechart"></canvas>
    <canvas class="web" style="height: 100%; width:100%" id="webchart"></canvas>


    @push('scripts_bottom')
    <script src="/assets/default/vendors/chartjs/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>




    <script>
        // Deklarasikan variabel chart di luar fungsi agar dapat diakses secara global
        var chart = null;
    
        function makeStatisticsChart() {
            // Ambil elemen canvas
            var mobilechart = document.getElementById("mobilechart").getContext("2d");
    
            // Hapus chart yang sudah ada jika ada
            if (chart) {
                chart.destroy();
                chart = null;
            }           
    
            // Data skills
            var originalLabels = [
                "Agile Leadership",
                "Analytical Problem Solving & Planing",
                "Developing Capabilities",
                "Driving Digital Innovation",
                "Results Orientation & Execution",
                "Stakeholder Orientation",
                "Strategy & Business Acumen",
                "Synergistic Collaboration"
            ];
    
            // var originalData = [10, 15, 5, 20, 12, 10, 2, 22];
            var originalData = [{{ $skills1Count }}, {{ $skills2Count }}, {{ $skills3Count }}, {{ $skills4Count }}, {{ $skills5Count }}, {{ $skills6Count }}, {{ $skills7Count }}, {{ $skills8Count }}];
    
            // Warna-warna yang berbeda untuk setiap bar (sesuaikan dengan kebutuhan Anda)
            var barColors = ["#fcdf9b", "#ff9ab0", "#f69ef5", "#b28bfe", "#7c65fe", "#9bc1ef", "#6adadb", "#a6fea7"];
    
            // Simpan hubungan antara label dan data sebelum diurutkan
            var labelDataPairs = originalLabels.map(function(label, index) {
                return {
                    label: label,
                    data: originalData[index],
                    backgroundColor: barColors[index] // Atur warna bar sesuai indeks
                };
            });
    
            // Urutkan data dari yang terkecil
            var sortedPairs = labelDataPairs.slice().sort((a, b) => a.data - b.data);
    
            // Pisahkan labels dan data yang sudah diurutkan
            var sortedLabels = sortedPairs.map(function(pair) {
                return pair.label;
            });
    
            var sortedData = sortedPairs.map(function(pair) {
                return pair.data;
            });
    
            var sortedBarColors = sortedPairs.map(function(pair) {
                return pair.backgroundColor;
            });
    
            // Buat chart baru
            chart = new Chart(mobilechart, {
                type: "horizontalBar",
                data: {
                    labels: sortedLabels,
                    datasets: [{
                        label: "Organization",
                        data: sortedData,
                        borderWidth: 5,
                        backgroundColor: sortedBarColors, // Gunakan warna bar yang telah diurutkan
                        borderColor: sortedBarColors, // Gunakan warna border yang telah diurutkan
                        hoverBackgroundColor: sortedBarColors // Gunakan warna latar belakang saat mouse hover yang telah diurutkan
                    }]
                },
                options: {
                    legend: { display: false },
                    plugins: {
                        datalabels: {
                            display: function(context) {
                                return context.dataset.data[context.dataIndex] > 0; // Hanya tampilkan label saat nilainya lebih besar dari 0
                            },
                            anchor: 'end',
                            align: 'end',
                            color: 'black',
                            font: {
                                size: 8 // Ukuran label 8px
                            },
                            formatter: function(value, context) {
                                return context.chart.data.labels[context.dataIndex];
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                beginAtZero: true, // Mulai dari nilai 0
                                stepSize: 10 // Sesuaikan dengan kebutuhan Anda
                            },
                            display: true, // Menampilkan sumbu X
                            gridLines: {
                                display: true, // Menampilkan garis grid
                                color: "#fbfbfb",
                                lineWidth: 2
                            }
                        }],
                        yAxes: [{
                            display: true // Menyembunyikan sumbu Y karena ini adalah orientasi horizontal
                        }]
                    }
                }
            });
        }
    
        makeStatisticsChart();
    </script>
    












<script>
    // Deklarasikan variabel chart di luar fungsi agar dapat diakses secara global
    var chart = null;

    function makeStatisticsChart() {
        // Ambil elemen canvas
        var webchart = document.getElementById("webchart").getContext("2d");

        // Hapus chart yang sudah ada jika ada
        if (chart) {
            chart.destroy();
            chart = null;
        }           

        // Data skills
        var originalLabels = [
            "Agile Leadership",
            "Analytical Problem Solving & Planing",
            "Developing Capabilities",
            "Driving Digital Innovation",
            "Results Orientation & Execution",
            "Stakeholder Orientation",
            "Strategy & Business Acumen",
            "Synergistic Collaboration"
        ];

        // var originalData = [10, 15, 5, 20, 12, 10, 2, 22];
        var originalData = [{{ $skills1Count }}, {{ $skills2Count }}, {{ $skills3Count }}, {{ $skills4Count }}, {{ $skills5Count }}, {{ $skills6Count }}, {{ $skills7Count }}, {{ $skills8Count }}];

        // Warna-warna yang berbeda untuk setiap bar (sesuaikan dengan kebutuhan Anda)
        var barColors = ["#fcdf9b", "#ff9ab0", "#f69ef5", "#b28bfe", "#7c65fe", "#9bc1ef", "#6adadb", "#a6fea7"];

        // Simpan hubungan antara label dan data sebelum diurutkan
        var labelDataPairs = originalLabels.map(function(label, index) {
            return {
                label: label,
                data: originalData[index],
                backgroundColor: barColors[index] // Atur warna bar sesuai indeks
            };
        });

        // Urutkan data dari yang terkecil
        var sortedPairs = labelDataPairs.slice().sort((a, b) => a.data - b.data);

        // Pisahkan labels dan data yang sudah diurutkan
        var sortedLabels = sortedPairs.map(function(pair) {
            return pair.label;
        });

        var sortedData = sortedPairs.map(function(pair) {
            return pair.data;
        });

        var sortedBarColors = sortedPairs.map(function(pair) {
            return pair.backgroundColor;
        });

        // Buat chart baru
        chart = new Chart(webchart, {
            type: "bar",
            data: {
                labels: sortedLabels,
                datasets: [{
                    label: "Organization",
                    data: sortedData,
                    borderWidth: 5,
                    backgroundColor: sortedBarColors, // Gunakan warna bar yang telah diurutkan
                    borderColor: sortedBarColors, // Gunakan warna border yang telah diurutkan
                    hoverBackgroundColor: sortedBarColors // Gunakan warna latar belakang saat mouse hover yang telah diurutkan
                }]
            },
            options: {
                legend: { display: false },
                plugins: {
                    datalabels: {
                        display: function(context) {
                            return context.dataset.data[context.dataIndex] > 0; // Hanya tampilkan label saat nilainya lebih besar dari 0
                        },
                        anchor: 'end',
                        align: 'end',
                        color: 'black',
                        formatter: function(value, context) {
                            return context.chart.data.labels[context.dataIndex];
                        }
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true, // Mulai dari nilai 0
                            stepSize: 10 // Sesuaikan dengan kebutuhan Anda
                        }
                    }],
                    xAxes: [{
                        display: false,
                        gridLines: {
                            display: true, // Menampilkan garis grid
                            color: "#fbfbfb",
                            lineWidth: 2
                        }
                    }]
                }
            }
        });
    }

    makeStatisticsChart();
</script>
  
    
    @endpush
    </div>
</div>

