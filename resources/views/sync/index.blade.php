<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Synchronization tools') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl px-6 lg:px-8 overflow-x-auto">
            <div class="min-w-full inline-block py-2 align-middle md:px-6 lg:px-8">
                @can('create', \App\Models\Classroom::class)
                <div class="flex justify-end mb-4">
                    <a href="{{ route('semester.classroom.create', $semester) }}">
                        <x-button>{{ __('Add') }}</x-button>
                    </a>
                </div>
                @endcan

                <div class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col" class="px-12 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    {{ __('#') }}
                                </th>
                                <th scope="col" class="px-12 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    {{ __('Synchronization') }}
                                </th>
                                <th scope="col" class="px-12 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    {{ __('Last Updated') }}
                                </th>
                                <th scope="col" class="px-12 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    {{ __('Options') }}
                                </th>
                            </tr>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900 text-gray-700 dark:text-gray-200">
                            @foreach ($synchronizations as $sync)
                            <tr x-data="{
                                @if ($api = $sync->authorized && $sync->api)
                                loops: {{ json_encode($sync->loops) }},
                                loop: 0,
                                width: '0%',
                                @endif
                                start: false,
                                startSync() {
                                    this.start = true;
                                    this.fetch{{ $sync->sync }}()
                                },
                                sync: '{{ $sync->last }}',
                                fetch{{ $sync->sync }}() {
                                    axios.post('{{ route('synchronizations.store') }}', {
                                        sync: '{{ $sync->sync }}',
                                        @if ($api)
                                        id: this.loops[this.loop],
                                        @endif
                                    }).then(response => {
                                        @if ($api)
                                        this.loop++
                                        this.width = `${((this.loop) / this.loops.length)*100}%`

                                        if (this.loop < this.loops.length) {
                                            this.fetch{{ $sync->sync }}()
                                        } else {
                                            this.start = false;
                                            this.loop = 0;

                                            iziToast.success({
                                                message: response.data.message,
                                                position: 'topRight'
                                            })
                                        }
                                        @else
                                        this.start = false;
                                        iziToast.success({
                                            message: response.data.message,
                                            position: 'topRight'
                                        })
                                        @endif

                                        this.sync = response.data.sync

                                    }).catch(response => {
                                        
                                    })
                                },
                                success(response) {
                                    iziToast.success({
                                        message: response.data.message,
                                        position: 'topRight'
                                    })
                                }
                            }">
                                <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                    {{ $sync->name }}
                                </td>
                                <td class="px-12 py-4 text-sm font-medium whitespace-nowrap" x-text="sync"></td>
                                <td class="px-12 py-4 text-sm font-medium whitespace-nowrap gap-4 grid">
                                    @if ($sync->authorized)
                                    <x-button @click="startSync()" ::disabled="start">Sinkronisasi</x-button>
                                    @endif

                                    @if ($api)
                                    <div class="bg-slate-500 mt-3 transition-all rounded-lg text-center" :class="{
                                        'hidden': ! start,
                                    }">
                                        <div class="p-1 bg-blue-500 text-white text-sm rounded-lg" :style="{width: width}" x-text="width"></div>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>