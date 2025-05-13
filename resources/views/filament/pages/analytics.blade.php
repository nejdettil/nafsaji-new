<x-filament::page>
    {{ $this->form }}

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6" dir="rtl">
        <!-- إحصائيات المستخدمين -->
        <x-filament::section>
            <x-slot name="heading">إحصائيات المستخدمين الجدد</x-slot>
            <div id="users-chart" style="height: 300px;"></div>
            
            <script>
                document.addEventListener('livewire:initialized', () => {
                    const usersChart = new ApexCharts(document.querySelector('#users-chart'), {
                        chart: {
                            type: 'area',
                            height: 300,
                            fontFamily: 'inherit',
                            toolbar: {
                                show: false,
                            },
                        },
                        colors: ['#9333ea'],
                        dataLabels: {
                            enabled: false,
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 3,
                        },
                        series: [{
                            name: 'عدد المستخدمين',
                            data: @json($this->getUsersChartData()['values']),
                        }],
                        xaxis: {
                            categories: @json($this->getUsersChartData()['dates']),
                            labels: {
                                style: {
                                    fontFamily: 'inherit',
                                },
                            },
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    fontFamily: 'inherit',
                                },
                            },
                        },
                        tooltip: {
                            x: {
                                format: 'dd/MM/yy',
                            },
                        },
                        fill: {
                            opacity: 0.1,
                            type: 'solid',
                        },
                    });
                    
                    usersChart.render();
                    
                    // تحديث الرسم البياني عند تحديث البيانات
                    $wire.on('chartDataUpdated', () => {
                        usersChart.updateSeries([{
                            name: 'عدد المستخدمين',
                            data: @json($this->getUsersChartData()['values']),
                        }]);
                        
                        usersChart.updateOptions({
                            xaxis: {
                                categories: @json($this->getUsersChartData()['dates']),
                            }
                        });
                    });
                });
            </script>
        </x-filament::section>
        
        <!-- إحصائيات المختصين -->
        <x-filament::section>
            <x-slot name="heading">إحصائيات المختصين الجدد</x-slot>
            <div id="specialists-chart" style="height: 300px;"></div>
            
            <script>
                document.addEventListener('livewire:initialized', () => {
                    const specialistsChart = new ApexCharts(document.querySelector('#specialists-chart'), {
                        chart: {
                            type: 'area',
                            height: 300,
                            fontFamily: 'inherit',
                            toolbar: {
                                show: false,
                            },
                        },
                        colors: ['#6366f1'],
                        dataLabels: {
                            enabled: false,
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 3,
                        },
                        series: [{
                            name: 'عدد المختصين',
                            data: @json($this->getSpecialistsChartData()['values']),
                        }],
                        xaxis: {
                            categories: @json($this->getSpecialistsChartData()['dates']),
                            labels: {
                                style: {
                                    fontFamily: 'inherit',
                                },
                            },
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    fontFamily: 'inherit',
                                },
                            },
                        },
                        tooltip: {
                            x: {
                                format: 'dd/MM/yy',
                            },
                        },
                        fill: {
                            opacity: 0.1,
                            type: 'solid',
                        },
                    });
                    
                    specialistsChart.render();
                    
                    // تحديث الرسم البياني عند تحديث البيانات
                    $wire.on('chartDataUpdated', () => {
                        specialistsChart.updateSeries([{
                            name: 'عدد المختصين',
                            data: @json($this->getSpecialistsChartData()['values']),
                        }]);
                        
                        specialistsChart.updateOptions({
                            xaxis: {
                                categories: @json($this->getSpecialistsChartData()['dates']),
                            }
                        });
                    });
                });
            </script>
        </x-filament::section>
        
        <!-- إحصائيات الحجوزات -->
        <x-filament::section>
            <x-slot name="heading">إحصائيات الحجوزات</x-slot>
            <div id="bookings-chart" style="height: 300px;"></div>
            
            <script>
                document.addEventListener('livewire:initialized', () => {
                    const bookingsChart = new ApexCharts(document.querySelector('#bookings-chart'), {
                        chart: {
                            type: 'area',
                            height: 300,
                            fontFamily: 'inherit',
                            toolbar: {
                                show: false,
                            },
                        },
                        colors: ['#22c55e'],
                        dataLabels: {
                            enabled: false,
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 3,
                        },
                        series: [{
                            name: 'عدد الحجوزات',
                            data: @json($this->getBookingsChartData()['values']),
                        }],
                        xaxis: {
                            categories: @json($this->getBookingsChartData()['dates']),
                            labels: {
                                style: {
                                    fontFamily: 'inherit',
                                },
                            },
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    fontFamily: 'inherit',
                                },
                            },
                        },
                        tooltip: {
                            x: {
                                format: 'dd/MM/yy',
                            },
                        },
                        fill: {
                            opacity: 0.1,
                            type: 'solid',
                        },
                    });
                    
                    bookingsChart.render();
                    
                    // تحديث الرسم البياني عند تحديث البيانات
                    $wire.on('chartDataUpdated', () => {
                        bookingsChart.updateSeries([{
                            name: 'عدد الحجوزات',
                            data: @json($this->getBookingsChartData()['values']),
                        }]);
                        
                        bookingsChart.updateOptions({
                            xaxis: {
                                categories: @json($this->getBookingsChartData()['dates']),
                            }
                        });
                    });
                });
            </script>
        </x-filament::section>
        
        <!-- إحصائيات الإيرادات -->
        <x-filament::section>
            <x-slot name="heading">إحصائيات الإيرادات</x-slot>
            <div id="revenue-chart" style="height: 300px;"></div>
            
            <script>
                document.addEventListener('livewire:initialized', () => {
                    const revenueChart = new ApexCharts(document.querySelector('#revenue-chart'), {
                        chart: {
                            type: 'area',
                            height: 300,
                            fontFamily: 'inherit',
                            toolbar: {
                                show: false,
                            },
                        },
                        colors: ['#eab308'],
                        dataLabels: {
                            enabled: false,
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 3,
                        },
                        series: [{
                            name: 'الإيرادات (ريال)',
                            data: @json($this->getRevenueChartData()['values']),
                        }],
                        xaxis: {
                            categories: @json($this->getRevenueChartData()['dates']),
                            labels: {
                                style: {
                                    fontFamily: 'inherit',
                                },
                            },
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    fontFamily: 'inherit',
                                },
                            },
                        },
                        tooltip: {
                            x: {
                                format: 'dd/MM/yy',
                            },
                            y: {
                                formatter: function (val) {
                                    return val + " ريال";
                                }
                            }
                        },
                        fill: {
                            opacity: 0.1,
                            type: 'solid',
                        },
                    });
                    
                    revenueChart.render();
                    
                    // تحديث الرسم البياني عند تحديث البيانات
                    $wire.on('chartDataUpdated', () => {
                        revenueChart.updateSeries([{
                            name: 'الإيرادات (ريال)',
                            data: @json($this->getRevenueChartData()['values']),
                        }]);
                        
                        revenueChart.updateOptions({
                            xaxis: {
                                categories: @json($this->getRevenueChartData()['dates']),
                            }
                        });
                    });
                });
            </script>
        </x-filament::section>
        
        <!-- إحصائيات الخدمات الأكثر طلباً -->
        <x-filament::section class="col-span-1 md:col-span-2">
            <x-slot name="heading">الخدمات الأكثر طلباً</x-slot>
            <div id="services-chart" style="height: 400px;"></div>
            
            <script>
                document.addEventListener('livewire:initialized', () => {
                    const servicesChart = new ApexCharts(document.querySelector('#services-chart'), {
                        chart: {
                            type: 'bar',
                            height: 400,
                            fontFamily: 'inherit',
                            toolbar: {
                                show: false,
                            },
                        },
                        colors: ['#9333ea', '#6366f1', '#22c55e', '#eab308', '#ef4444'],
                        plotOptions: {
                            bar: {
                                distributed: true,
                                borderRadius: 5,
                                dataLabels: {
                                    position: 'top',
                                },
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: function (val) {
                                return val;
                            },
                            style: {
                                fontSize: '12px',
                                fontFamily: 'inherit',
                            }
                        },
                        series: [{
                            name: 'عدد الحجوزات',
                            data: @json($this->getServicesChartData()['data']),
                        }],
                        xaxis: {
                            categories: @json($this->getServicesChartData()['labels']),
                            labels: {
                                style: {
                                    fontFamily: 'inherit',
                                },
                            },
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    fontFamily: 'inherit',
                                },
                            },
                        },
                    });
                    
                    servicesChart.render();
                    
                    // تحديث الرسم البياني عند تحديث البيانات
                    $wire.on('chartDataUpdated', () => {
                        servicesChart.updateSeries([{
                            name: 'عدد الحجوزات',
                            data: @json($this->getServicesChartData()['data']),
                        }]);
                        
                        servicesChart.updateOptions({
                            xaxis: {
                                categories: @json($this->getServicesChartData()['labels']),
                            }
                        });
                    });
                });
            </script>
        </x-filament::section>
    </div>
</x-filament::page>
