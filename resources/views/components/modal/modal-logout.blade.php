<div id="hs-logout-modal"
    class="hs-overlay hidden fixed top-0 end-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none mr-8"
    role="dialog" tabindex="-1" aria-labelledby="hs-logout-modal-label">
    <div
        class="m-3 mt-0 transition-all ease-out opacity-0 hs-overlay-animation-target hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 sm:mx-auto md:max-w-lg md:w-full">
        <div class="flex flex-col gap-4 p-6 bg-[#FCFCFD] border shadow-sm pointer-events-auto rounded-xl md:max-w-lg md:w-full">
            <div class="flex flex-col items-center justify-center border-[#EAECF0]">
                <svg width="91" height="90" viewBox="0 0 91 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="4.5" y="4" width="82" height="82" rx="41" fill="#9E77ED" />
                    <rect x="4.5" y="4" width="82" height="82" rx="41" stroke="#F9F5FF" stroke-width="8" />
                    <path
                        d="M42.875 59.4375C42.875 59.7856 42.7367 60.1194 42.4906 60.3656C42.2444 60.6117 41.9106 60.75 41.5625 60.75H32.375C31.6788 60.75 31.0111 60.4734 30.5188 59.9812C30.0266 59.4889 29.75 58.8212 29.75 58.125V31.875C29.75 31.1788 30.0266 30.5111 30.5188 30.0188C31.0111 29.5266 31.6788 29.25 32.375 29.25H41.5625C41.9106 29.25 42.2444 29.3883 42.4906 29.6344C42.7367 29.8806 42.875 30.2144 42.875 30.5625C42.875 30.9106 42.7367 31.2444 42.4906 31.4906C42.2444 31.7367 41.9106 31.875 41.5625 31.875H32.375V58.125H41.5625C41.9106 58.125 42.2444 58.2633 42.4906 58.5094C42.7367 58.7556 42.875 59.0894 42.875 59.4375ZM60.8661 44.0714L54.3036 37.5089C54.0573 37.2626 53.7233 37.1243 53.375 37.1243C53.0267 37.1243 52.6927 37.2626 52.4464 37.5089C52.2001 37.7552 52.0618 38.0892 52.0618 38.4375C52.0618 38.7858 52.2001 39.1198 52.4464 39.3661L56.7695 43.6875H41.5625C41.2144 43.6875 40.8806 43.8258 40.6344 44.0719C40.3883 44.3181 40.25 44.6519 40.25 45C40.25 45.3481 40.3883 45.6819 40.6344 45.9281C40.8806 46.1742 41.2144 46.3125 41.5625 46.3125H56.7695L52.4464 50.6339C52.2001 50.8802 52.0618 51.2142 52.0618 51.5625C52.0618 51.9108 52.2001 52.2448 52.4464 52.4911C52.6927 52.7374 53.0267 52.8757 53.375 52.8757C53.7233 52.8757 54.0573 52.7374 54.3036 52.4911L60.8661 45.9286C60.9881 45.8067 61.0849 45.6619 61.151 45.5026C61.217 45.3433 61.251 45.1725 61.251 45C61.251 44.8275 61.217 44.6567 61.151 44.4974C61.0849 44.3381 60.9881 44.1933 60.8661 44.0714Z"
                        fill="white" />
                </svg>

                <h3 id="hs-logout-modal-label" class="text-lg text-[#344054] leading-7 my-4">
                    Yakin mau keluar sekarang?
                </h3>
            </div>
            <div class="flex items-center justify-center gap-4">

                <form method="POST" action="{{ route('logout') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit"
                        class="flex items-center gap-1.5 text-sm px-4 py-3 font-medium rounded-lg bg-[#9E77ED] text-[#F8F8F8]">
                        <span>Keluar <i class="ph ph-check"></i></span>
                    </button>
                </form>
                <button type="button"
                    class="flex items-center px-4 py-3 gap-1.5 text-sm font-medium rounded-lg bg-white text-[#606060] border border-[#D0D5DD]"
                    data-hs-overlay="#hs-logout-modal">
                    <span>Batal</span> <i class="ph ph-x"></i>
                </button>
            </div>
        </div>
    </div>
</div>
