<div class="bg-white">
    <div class="mx-auto" style="max-width: 1600px">
        <section class="bxslider">
            @foreach ($banners as $banner)
            <div>
                <img class="image-back" src="{{  Storage::url($banner->photo) }}" class="img-fluid"
                    alt="{{ $banner->photo }}">
            </div>
            @endforeach
        </section>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function(){
        $('.bxslider').bxSlider({
            auto: true,
            controls: true,
            // mode: 'fade',
            // autoControls: true,
            // stopAutoOnClick: true,
            pager: false,
            // slideWidth: 600
        });
    });

</script>
@endpush