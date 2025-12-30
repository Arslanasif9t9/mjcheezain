@props(['balance'])

<div class="bg-white p-4 rounded shadow text-center rounded-[20px] flex justify-center items-center">
    <div>
        <p class="text-2xl font-bold">Current Balance</p>
        <p class="text-3xl font-bold p-4 w-max mx-auto" style="border-bottom: 2px solid black;">
            {{ $balance }} <span class="text-sm">PKR</span>
        </p>
        <button class="mt-4 px-4 py-2 bg-green-500 text-white rounded">
            <a href="/vendor/withdraw">Withdraw Now</a>
        </button>
    </div>
</div>