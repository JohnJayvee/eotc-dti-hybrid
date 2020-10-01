<nav class="sticky top-0 z-10">
    <div class="nav px-5 md:px-20 py-3 justify-center items-center border-b-4 border-blue-700 shadow bg-white">
        <div class="flex justify-between">
            <div class="flex flex-row justify-center items-center">
                {{-- <img
                    src="{{ asset('assets/images/dbpay.png') }}"
                    alt="logo"
                    class="w-10 h-8">
                <h1 class="text-blue font-bold text-2xl ml-1">DB<span class="text-red">PAY</span></h1> --}}
              
            </div>
            <div class="flex flex-row font-bold text-blue-700 text-xs md:text-base">
                <div class="flex flex-col md:flex-row md:justify-center md:items-center text-right md:text-left">
                    <p class="pl-1">{{ Carbon::now()->format('l, F d, Y') }}</p>
                    <span class="hidden md:flex mx-1">|</span>
                    <p class="pl-1"> {{ Carbon::now()->format('h:i:s A') }}</p>
                </div>
            </div>
        </div>
    </div>
</nav>
