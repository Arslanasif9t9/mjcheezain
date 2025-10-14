@props(['user', 'profile', 'dashboardPage', 'imgPath'])

<div class="text-right relative self-end my-1">
    <a href="{{ $dashboardPage }}">
        <img class="w-[50px] h-[50px] rounded-full" src="{{ asset($imgPath) }}" alt="Profile">
    </a>
    <span class="absolute bottom-[-5px] right-[0px] bg-black px-1 rounded-full text-white">
        <i class="fas fa-bars"></i>
    </span>
</div>