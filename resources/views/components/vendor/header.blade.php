@props(['profilePicture'])

<div class="header flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">Overview</h1>
    <div class="flex items-center w-[70%]">
        <i class='fa-solid fa-magnifying-glass relative left-8 z-10'></i>
        <input type="text" placeholder="Search product, order or customer..."
            class="px-4 py-2 pl-12 border rounded border-0 outline-0 w-full rounded-full" />
    </div>
    <div class="text-xl">
        <a href="./profile.php">
            <button class="bg-white w-10 h-10 rounded-full">
                <i class="fa-solid fa-user"></i>
            </button>
        </a>
    </div>
</div>