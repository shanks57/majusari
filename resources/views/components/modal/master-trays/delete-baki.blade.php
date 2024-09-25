<div
    class="hs-overlay hidden size-full fixed top-0 start-0 p-6 mt-4 mr-4 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
    role="dialog" tabindex="-1" aria-labelledby="form-modal-label" id="hs-delete-baki">

    <div
        class="m-3 transition-all ease-out opacity-0 md:ml-auto hs-overlay-open:opacity-100 hs-overlay-open:duration-500 md:max-w-md md:w-full">
        <div class="flex flex-col p-6 bg-white shadow-lg pointer-events-auto gap-y-4 rounded-xl">
            <div class="flex flex-col items-center justify-center border-[#EAECF0]">
                <svg width="90" height="90" viewBox="0 0 90 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="4" y="4" width="82" height="82" rx="41" fill="#F04438"/>
                    <rect x="4" y="4" width="82" height="82" rx="41" stroke="#FEE4E2" stroke-width="8"/>
                    <path d="M46.9688 53.5312C46.9688 53.9206 46.8533 54.3013 46.637 54.625C46.4206 54.9488 46.1132 55.2011 45.7534 55.3501C45.3937 55.4991 44.9978 55.5381 44.6159 55.4622C44.234 55.3862 43.8832 55.1987 43.6079 54.9234C43.3326 54.648 43.1451 54.2972 43.0691 53.9153C42.9931 53.5334 43.0321 53.1376 43.1811 52.7778C43.3301 52.4181 43.5825 52.1106 43.9062 51.8943C44.23 51.678 44.6106 51.5625 45 51.5625C45.5222 51.5625 46.0229 51.7699 46.3921 52.1391C46.7613 52.5083 46.9688 53.0091 46.9688 53.5312ZM45 35.8125C41.3808 35.8125 38.4375 38.4621 38.4375 41.7188V42.375C38.4375 42.7231 38.5758 43.0569 38.8219 43.3031C39.0681 43.5492 39.4019 43.6875 39.75 43.6875C40.0981 43.6875 40.4319 43.5492 40.6781 43.3031C40.9242 43.0569 41.0625 42.7231 41.0625 42.375V41.7188C41.0625 39.9141 42.8295 38.4375 45 38.4375C47.1706 38.4375 48.9375 39.9141 48.9375 41.7188C48.9375 43.5234 47.1706 45 45 45C44.6519 45 44.3181 45.1383 44.0719 45.3844C43.8258 45.6306 43.6875 45.9644 43.6875 46.3125V47.625C43.6875 47.9731 43.8258 48.3069 44.0719 48.5531C44.3181 48.7992 44.6519 48.9375 45 48.9375C45.3481 48.9375 45.6819 48.7992 45.9281 48.5531C46.1742 48.3069 46.3125 47.9731 46.3125 47.625V47.5069C49.305 46.9573 51.5625 44.5702 51.5625 41.7188C51.5625 38.4621 48.6192 35.8125 45 35.8125ZM62.0625 45C62.0625 48.3746 61.0618 51.6735 59.187 54.4794C57.3121 57.2853 54.6473 59.4723 51.5295 60.7637C48.4118 62.0551 44.9811 62.393 41.6713 61.7346C38.3615 61.0763 35.3212 59.4512 32.935 57.065C30.5488 54.6788 28.9237 51.6385 28.2654 48.3287C27.607 45.0189 27.9449 41.5882 29.2363 38.4705C30.5277 35.3527 32.7147 32.6879 35.5206 30.813C38.3265 28.9382 41.6254 27.9375 45 27.9375C49.5238 27.9423 53.8609 29.7415 57.0597 32.9403C60.2585 36.1391 62.0577 40.4762 62.0625 45ZM59.4375 45C59.4375 42.1445 58.5908 39.3532 57.0044 36.979C55.4179 34.6047 53.1631 32.7542 50.525 31.6615C47.8869 30.5687 44.984 30.2828 42.1834 30.8399C39.3828 31.397 36.8103 32.772 34.7912 34.7911C32.772 36.8103 31.397 39.3828 30.8399 42.1834C30.2828 44.984 30.5688 47.8869 31.6615 50.525C32.7542 53.1631 34.6047 55.4179 36.979 57.0043C39.3532 58.5908 42.1445 59.4375 45 59.4375C48.8277 59.4332 52.4974 57.9107 55.2041 55.2041C57.9107 52.4974 59.4332 48.8277 59.4375 45Z" fill="white"/>
                </svg>

                <h3 id="hs-delete-baki" class="text-lg font-medium text-[#344054] leading-7 mt-4">
                    Yakin mau hapus baki ini?
                </h3>
            </div>
            <div class="flex items-center justify-center gap-4">
                <form action="{{ route('traysgoods.baki.delete', ['id' => $tray->id]) }}" method="POST"> 
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center gap-1.5 text-sm px-4 py-3 font-medium rounded-lg bg-[#F04438] text-[#F8F8F8]">
                        <span>Hapus <i class="ph ph-trash"></i></span>
                    </button>
                </form>

                <button type="button"
                    class="flex items-center px-4 py-3 gap-1.5 text-sm font-medium rounded-lg bg-white text-[#606060] border border-[#D0D5DD]"
                    data-hs-overlay="#hs-delete-baki">
                    <span>Batal</span> <i class="ph ph-x"></i>
                </button>
            </div>
        </div>
    </div>
</div>

