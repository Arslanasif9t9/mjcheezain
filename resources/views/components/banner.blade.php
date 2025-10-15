{{-- resources/views/components/banner.blade.php --}}

<div id="carouselExampleCaptions" class="carousel slide mh-25 pointer-event px-10 md:px-10 lg:px-20 h-[200px] md:h-[300px] lg:h-[350px]" data-ride="carousel">
    <ol class="carousel-indicators h-inherit">
        <li data-target="#carouselExampleCaptions" data-slide-to="0" class=""></li>
        <li data-target="#carouselExampleCaptions" data-slide-to="1" class="active"></li>
        <li data-target="#carouselExampleCaptions" data-slide-to="2" class=""></li>
    </ol>
    <div class="carousel-inner carousel-fade">
        {{-- Slide 1 --}}
        <div class="carousel-item h-[200px] md:h-[300px] lg:h-[350px]">
            <img src="{{ asset('img/banner1.jpeg') }}" class="d-block w-100 h-100" height="450px" alt="Coding Blog Banner">
        </div>
        
        {{-- Slide 2 --}}
        <div class="carousel-item active h-[200px] md:h-[300px] lg:h-[350px]">
            <img src="{{ asset('img/header background blue.jpg') }}" class="d-block w-100 h-100" height="450px" alt="The Best Coding Blog">
            <div class="carousel-caption d-none d-md-block">
                <h2>The Best Coding Blog</h2>
                <p>Technology, News, Development and Trends.</p>
                <button class="btn btn-danger">Technology</button>
                <button class="btn btn-primary">Web Development</button>
                <button class="btn btn-success">Tech Fun</button>
            </div>
        </div>
        
        {{-- Slide 3 --}}
        <div class="carousel-item h-[200px] md:h-[300px] lg:h-[350px]">
            <img src="{{ asset('img/default-banner.jpg') }}" class="d-block w-100 h-100" height="450px" alt="Award Winning Blog">
            <div class="carousel-caption d-none d-md-block">
                <h2>Award Winning Blog</h2>
                <p>Technology, News, Development and Trends</p>
                <button class="btn btn-danger">Technology</button>
                <button class="btn btn-primary">Web Development</button>
                <button class="btn btn-success">Tech Fun</button>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-target="#carouselExampleCaptions" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-target="#carouselExampleCaptions" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </button>
</div>