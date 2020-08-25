<h3 class="flex items-center font-normal text-white mb-6 text-base no-underline">
    <span class="sidebar-label">
        New Dashboards
    </span>
</h3>

<ul class="list-reset mb-8">
    @foreach($dashboards as $resource)
        <li class="leading-tight mb-4 ml-8 text-sm">
            <router-link
                class="text-white text-justify no-underline dim"
                :to="{
                    name: 'nova-dashboard',
                    params: {
                        dashboardKey: '{!! $resource->resourceUri() !!}'
                    }
                }">
                {{ $resource->resourceLabel() }}
            </router-link>
        </li>
    @endforeach
</ul>
