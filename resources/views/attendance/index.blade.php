<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Attendance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl px-6 lg:px-8 overflow-x-auto">
            <div class="min-w-full inline-block py-2 align-middle md:px-6 lg:px-8">

                <div class="grid grid-cols-1 md:grid-cols-5 gap-6" x-data="{
                    scanned: '',
                    options: false,
                    cam: '',
                    status: null,
                    total: {{ json_encode($total) }},
                    student: {
                        nim: '-',
                        name: '-',
                        classroom: '-',
                        timeLeft: '-',
                        site: '-',
                    },
                    submit() {
                        if (this.processed.includes(this.scanned)) {
                            this.status = '{{ __('Already scanned') }}'
                            this.scanned = ''
                            return;
                        }

                        if (this.scanned.length != 12) {
                            this.status = '{{ __('QR invalid') }}'
                            this.scanned = ''
                            return;
                        }

                        this.processed.push(this.scanned)
                            
                        axios.post('{{ route('attendance.store') }}', {
                            qr: this.scanned,
                        }).then(response => {
                            this.scanned = ''

                            this.total = response.data.total
                            this.student = response.data.student
                            this.status = response.data.status
                        }).catch(response => {
                            this.scanned = ''
                            this.status = '{{ __('Not Found') }}'
                        })
                    },
                    camLists() {
                        QrScanner.listCameras(true).then(cameras => {
                            cameras.forEach(camera => {
                                $refs.listCameras.add(new Option(camera.label, camera.id))
                            })
                        })
                    }
                }" x-init="
                qrScanner = new QrScanner($refs.scanner, result => {
                    if (scanned != result.data) {
                        scanned = result.data;

                        let audio = new Audio('{{ asset('beep.mp3')}}')
                        audio.loop = false
                        audio.play()

                        submit()

                        setTimeout(() => {
                            scanned = ''
                        }, 500)
                    }

                }, {
                    highlightScanRegion: true,
                    highlightCodeOutline: true,
                })

                qrScanner.start().then(() => {
                    camLists()
                })
                ">
                    <div>
                        <video src="" x-ref="scanner"></video>
                        
                        <div class="my-4">
                            <x-button class="w-full" @click="options = !options">
                                <i class="mdi mdi-cog"></i>
                                {{ __('Custom options') }}
                            </x-button>
                        </div>
                        
                        <div x-show="options">
                            
                            <div class="my-4 flex flex-col gap-4">
                                <x-button @click="qrScanner.start().then(() => camLists())">
                                    <i class="mdi mdi-camera"></i>
                                    {{ __('Start') }}
                                </x-button>
                                <x-button @click="qrScanner.stop()">
                                    <i class="mdi mdi-camera-off"></i>
                                    {{ __('Stop') }}
                                </x-button>
                            </div>

                            <x-label>
                                <i class="mdi mdi-camera"></i>
                                {{ __('Use Camera:') }}
                            </x-label>
                            <x-select class="w-full" x-model="cam" x-ref="listCameras" @change="qrScanner.setCamera(cam)" />
                        </div>
                    </div>

                    <div class="col-span-3">
                        <div class="grid grid-cols-4 gap-6">
                            <x-input type="text" class="col-span-3" x-model="scanned" placeholder="{{ __('qr') }}" wire:keydown.enter="submit()" />

                            <x-button type="button" @click="submit()">
                                <i class="mdi mdi-check"></i>
                                {{ __('Submit') }}
                            </x-button>
                        </div>

                        <div class="text-white p-3 my-4 rounded-lg" :class="{
                            'bg-green-500': status == true,
                            'bg-red-500': status != true,
                        }" x-show="status">
                            <template x-if="status == true">
                                <h1>
                                    <i class="mdi mdi-check"></i> {{ __('Attendance successful') }}
                                </h1>
                            </template>

                            <template x-if="status == 'not found'">
                                <div>
                                    <h1>
                                        <i class="mdi mdi-close"></i> {{ __('Attendance failed') }}
                                    </h1>
                                    <h2>{{ __('Please check the participant again') }}</h2>
                                </div>
                            </template>
                            <template x-if="status != true && status != 'not found'">
                                <div>
                                    <h1>
                                        <i class="mdi mdi-close"></i> {{ __('Attendance failed') }}
                                    </h1>
                                    
                                    <h2>
                                        {{ __('Participant has already attended on') }} <span x-text="status"></span>
                                    </h2>
                                </div>
                            </template>
                        </div>

                        <div class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-lg mt-4 shadow-lg">
                            <div class="grid grid-cols-2 p-6">
                                <div class="mb-4">
                                    <h4>{{ __('Student ID') }}</h4>
                                    <h5 class="font-semibold" x-text="student.nim"></h5>
                                </div>

                                <div class="mb-4">
                                    <h4>{{ __('Name') }}</h4>
                                    <h5 class="font-semibold" x-text="student.name"></h5>
                                </div>
                                
                                <div>
                                    <h4>{{ __('Classroom') }}</h4>
                                    <h5 class="font-semibold text-xl" x-text="student.classroom"></h5>
                                </div>
                                
                                <div>
                                    <h4>{{ __('Site') }}</h4>
                                    <h5 class="font-semibold text-xl" x-text="student.site"></h5>
                                </div>
                            </div>

                            <div class="my-4 bg-blue-400 text-white rounded-tr-xl p-6 w-max">
                                <h4>{{ __('Attendance time') }}</h4>
                                <h5 class="font-semibold text-xl" x-text="student.timeLeft"></h5>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="mb-4 font-bold text-xl text-gray-900 dark:text-gray-100">{{ __('Attendance Summary') }}</h4>

                        <div class="grid grid-cols-2 divide-x">
                            <div>
                                <h5 class="text-gray-700 dark:text-gray-200">{{ __('Present') }}</h5>
                                <h6 class="font-semibold text-gray-800 dark:text-gray-100" x-text="total.attend"></h6>
                            </div>
                            <div class="pl-5">
                                <h5 class="text-gray-700 dark:text-gray-200">{{ __('Absent') }}</h5>
                                <h6 class="font-semibold text-gray-800 dark:text-gray-100" x-text="total.absent"></h6>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>