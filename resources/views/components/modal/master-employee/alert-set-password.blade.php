<div class="hs-overlay hidden size-full fixed top-0 left-0 p-6 mt-4 mr-4 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
    role="dialog" tabindex="-1" aria-labelledby="alert-set-password-modal-label"
    id="hs-alert-set-password-modal-{{ $employee->id }}">

    <div
        class="m-3 transition-all ease-out opacity-0 md:ml-auto hs-overlay-open:opacity-100 hs-overlay-open:duration-500 md:max-w-xl md:w-full">
        <div class="flex flex-col p-6 bg-white border border-gray-200 shadow-lg pointer-events-auto gap-y-4 rounded-xl">
            <div class="flex items-center justify-between px-4">
                <h3 id="hs-medium-modal-label" class="text-xl font-medium text-[#344054]">
                    Akun Ini sudah memiliki password
                </h3>
                <button type="button" class="text-red-500" aria-label="Close"
                    data-hs-overlay="#hs-alert-set-password-modal-{{ $employee->id }}">
                    <i class="text-2xl ph ph-x-circle"></i>
                </button>
            </div>
            <div class="border-b border-[#D0D5DD]"></div>
            <div class="px-4 overflow-y-auto">
                <div class="flex items-center justify-center py-4">
                    <svg class="size-40" width="91" height="90" viewBox="0 0 91 90" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect x="4.5" y="4" width="82" height="82" rx="41" fill="#F04438" />
                        <rect x="4.5" y="4" width="82" height="82" rx="41" stroke="#FEF3F2" stroke-width="8" />
                        <path
                            d="M32.375 33.1875V56.8125C32.375 57.1606 32.2367 57.4944 31.9906 57.7406C31.7444 57.9867 31.4106 58.125 31.0625 58.125C30.7144 58.125 30.3806 57.9867 30.1344 57.7406C29.8883 57.4944 29.75 57.1606 29.75 56.8125V33.1875C29.75 32.8394 29.8883 32.5056 30.1344 32.2594C30.3806 32.0133 30.7144 31.875 31.0625 31.875C31.4106 31.875 31.7444 32.0133 31.9906 32.2594C32.2367 32.5056 32.375 32.8394 32.375 33.1875ZM46.1562 42.1289L42.875 43.1953V39.75C42.875 39.4019 42.7367 39.0681 42.4906 38.8219C42.2444 38.5758 41.9106 38.4375 41.5625 38.4375C41.2144 38.4375 40.8806 38.5758 40.6344 38.8219C40.3883 39.0681 40.25 39.4019 40.25 39.75V43.1953L36.9687 42.1289C36.6376 42.0201 36.2768 42.0473 35.9658 42.2046C35.6547 42.3618 35.4189 42.6361 35.3101 42.9673C35.2013 43.2984 35.2285 43.6592 35.3857 43.9702C35.543 44.2813 35.8173 44.5172 36.1484 44.6259L39.4297 45.6907L37.4052 48.4798C37.2982 48.6188 37.2201 48.7778 37.1757 48.9475C37.1312 49.1172 37.1212 49.2941 37.1462 49.4677C37.1713 49.6413 37.2309 49.8082 37.3215 49.9584C37.4121 50.1086 37.532 50.2391 37.6739 50.3422C37.8158 50.4453 37.977 50.5189 38.1478 50.5586C38.3187 50.5984 38.4958 50.6035 38.6686 50.5736C38.8415 50.5438 39.0066 50.4796 39.1542 50.3848C39.3019 50.2901 39.429 50.1667 39.5281 50.022L41.5527 47.2329L43.5772 50.022C43.6763 50.1667 43.8034 50.2901 43.9511 50.3848C44.0987 50.4796 44.2638 50.5438 44.4367 50.5736C44.6095 50.6035 44.7866 50.5984 44.9575 50.5586C45.1283 50.5189 45.2895 50.4453 45.4314 50.3422C45.5734 50.2391 45.6932 50.1086 45.7838 49.9584C45.8744 49.8082 45.934 49.6413 45.9591 49.4677C45.9841 49.2941 45.9741 49.1172 45.9297 48.9475C45.8852 48.7778 45.8071 48.6188 45.7002 48.4798L43.6756 45.6907L46.9569 44.6259C47.2734 44.5082 47.5323 44.273 47.6799 43.9693C47.8274 43.6655 47.8522 43.3166 47.7491 42.995C47.646 42.6735 47.423 42.404 47.1263 42.2427C46.8296 42.0814 46.4822 42.0406 46.1562 42.1289ZM63.5469 42.9722C63.4393 42.6441 63.2068 42.3716 62.8997 42.2137C62.5926 42.0559 62.2357 42.0254 61.9062 42.1289L58.625 43.1953V39.75C58.625 39.4019 58.4867 39.0681 58.2406 38.8219C57.9944 38.5758 57.6606 38.4375 57.3125 38.4375C56.9644 38.4375 56.6306 38.5758 56.3844 38.8219C56.1383 39.0681 56 39.4019 56 39.75V43.1953L52.7187 42.1305C52.5548 42.0772 52.3819 42.0567 52.2101 42.0702C52.0382 42.0837 51.8706 42.1309 51.717 42.2091C51.5633 42.2873 51.4266 42.3951 51.3146 42.5261C51.2026 42.6572 51.1175 42.809 51.0642 42.973C51.0109 43.137 50.9903 43.3098 51.0038 43.4817C51.0173 43.6536 51.0645 43.8211 51.1427 43.9748C51.221 44.1284 51.3287 44.2651 51.4598 44.3772C51.5908 44.4892 51.7427 44.5743 51.9066 44.6276L55.1879 45.6923L53.1634 48.4814C53.0564 48.6204 52.9783 48.7795 52.9339 48.9492C52.8894 49.1188 52.8794 49.2957 52.9044 49.4694C52.9295 49.643 52.9891 49.8098 53.0797 49.96C53.1703 50.1102 53.2902 50.2407 53.4321 50.3438C53.574 50.4469 53.7352 50.5205 53.906 50.5603C54.0769 50.6 54.254 50.6051 54.4268 50.5753C54.5997 50.5454 54.7648 50.4812 54.9124 50.3865C55.0601 50.2917 55.1872 50.1683 55.2863 50.0236L57.3109 47.2345L59.3354 50.0236C59.4345 50.1683 59.5617 50.2917 59.7093 50.3865C59.8569 50.4812 60.022 50.5454 60.1949 50.5753C60.3677 50.6051 60.5448 50.6 60.7157 50.5603C60.8866 50.5205 61.0477 50.4469 61.1896 50.3438C61.3316 50.2407 61.4514 50.1102 61.542 49.96C61.6326 49.8098 61.6922 49.643 61.7173 49.4694C61.7423 49.2957 61.7323 49.1188 61.6879 48.9492C61.6434 48.7795 61.5653 48.6204 61.4584 48.4814L59.4338 45.6923L62.7151 44.6276C63.0445 44.5178 63.317 44.282 63.4729 43.9717C63.6288 43.6614 63.6554 43.302 63.5469 42.9722Z"
                            fill="white" />
                    </svg>
                </div>
                <ul class="mb-4 ml-1 text-gray-700 list-disc">
                    <li>Akun sudah memiliki password, untuk mengubahnya silahkan login melalui akun tersebut.</li>
                    <li>Atau bisa reset menggunakan tombol di bawah.</li>
                </ul>
                <div class="flex items-center justify-center px-4 gap-x-2">
                    <!-- Reset Password Form -->
                    {{-- <form action="{{ route('employees.reset-password', $employee->id) }}" method="post">
                        @csrf
                        @method('PUT') --}}
                        <button type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-set-password-modal-{{ $employee->id }}" data-hs-overlay="#hs-set-password-modal-{{ $employee->id }}"
                            class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 text-white bg-red-500 rounded-lg hover:bg-red-600">
                            <span>Reset Password</span>
                            <i class="ml-1.5 ph ph-keyhole"></i>
                        </button>
                    {{-- </form> --}}
                    <!-- Cancel Button -->
                    <button type="button" aria-label="Close"
                        data-hs-overlay="#hs-alert-set-password-modal-{{ $employee->id }}"
                        class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                        <span>Batal</span>
                        <i class="ph ph-x-circle ml-1.5"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
