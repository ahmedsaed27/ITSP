<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div>
        <iframe src="{{  $getState() }}" style="width:100%;"></iframe>

    </div>
</x-dynamic-component>
