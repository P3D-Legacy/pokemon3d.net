<div>
    <div class="overflow-hidden rounded-lg shadow-lg">
        <div class="px-5 py-3 text-xl font-semibold text-center bg-gray-50 dark:bg-black dark:text-white">User Registrations</div>
        <canvas class="p-10" id="chartLine"></canvas>
    </div>
      
    <!-- Required chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Chart line -->
    <script>
        const data = {
            labels: @json($labels),
            datasets: [
                {
                    label: "User Registrations",
                    backgroundColor: "hsl(252, 82.9%, 67.8%)",
                    borderColor: "hsl(252, 82.9%, 67.8%)",
                    data: @json($values),
                },
            ],
        };
        
        const configLineChart = {
            type: "line",
            data,
            options: {},
        };
        
        var chartLine = new Chart(
            document.getElementById("chartLine"),
            configLineChart
        );
    </script>
</div>
