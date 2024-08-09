<div x-data="{ form: { name: '', phone: '', address: '', payment_method: '', cash_received: ''} }"
    class="hs-overlay hidden size-full fixed top-0 start-0 p-6 mt-4 mr-4 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
    role="dialog" tabindex="-1" aria-labelledby="create-transaction-modal" id="hs-create-transaction-modal">
    <div
        class="m-3 transition-all ease-out opacity-0 md:ml-auto hs-overlay-open:opacity-100 hs-overlay-open:duration-500 md:max-w-2xl md:w-full">
        <div class="flex flex-col p-6 bg-white shadow-lg pointer-events-auto gap-y-4 rounded-xl">
            <div class="flex items-center justify-between px-4">
                <h3 id="hs-medium-modal-label" class="text-xl font-medium text-[#344054]">
                    Data Pembeli
                </h3>
                <button type="button" class="text-red-500" aria-label="Close"
                    data-hs-overlay="#hs-create-transaction-modal">
                    <i class="text-2xl ph ph-x-circle"></i>
                </button>
            </div>
            <div class="border-b border-[#D0D5DD]"></div>
            <form action="{{ route('sale.checkout') }}" method="post">
                @csrf
                <div class="p-4 overflow-y-auto">
                    <div x-data="customerForm()" class="w-full">
                        <div class="w-full mb-4">
                            <label for="customer" class="block text-sm text-[#344054]">Pilih Pembeli Lama</label>
                            <select id="customer" name="customer" x-model="selectedCustomer" @change="fillCustomerData"
                                class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-[#667085]">
                                <option value="" disabled selected>Pilih nama pelanggan</option>
                                @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">
                                    {{ $customer->name }}-{{ $customer->phone }}-{{ $customer->address }}</option>
                                @endforeach
                            </select>
                            <span class="mt-1 text-xs text-red-500">*Kosongkan jika pembeli baru</span>
                        </div>

                        <div class="w-full mb-4">
                            <label for="old_customer_id" class="block text-sm text-[#344054]">ID Pembeli Lama</label>
                            <input type="text" id="old_customer_id" name="old_customer_id" x-model="form.customer_id"
                                class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-[#667085]"
                                placeholder="Masukkan nama pembeli">
                            <span class="mt-1 text-xs text-red-500">*Kosongkan jika pembeli baru</span>
                        </div>
                        <!-- Input Nama Pembeli -->
                        <div class="w-full mb-4">
                            <label for="name" class="block text-sm text-[#344054]">Nama Pembeli</label>
                            <input type="text" id="name" name="name" x-model="form.name"
                                class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-[#667085]"
                                placeholder="Masukkan nama pembeli" required>
                        </div>

                        <!-- Input No Handphone -->
                        <div class="w-full mb-4">
                            <label for="phone" class="block text-sm text-[#344054]">No Handphone</label>
                            <input type="number" id="phone" name="phone" x-model="form.phone"
                                class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-[#667085]"
                                placeholder="Masukkan no handphone" required>
                        </div>

                        <!-- Input Alamat -->
                        <div class="w-full mb-4">
                            <label for="address" class="block text-sm text-[#344054]">Alamat</label>
                            <textarea id="address" name="address" x-model="form.address"
                                class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-[#667085]"
                                placeholder="Masukkan alamat" required cols="30" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="w-full mb-4 rounded-xl bg-gray-50 border border-gray-200 p-4">
                        <label for="name" class="block text-sm text-[#344054]">Pegawai</label>
                        <p class="text-lg text-gray-700 mt-1">{{ Auth::user()->name }}</p>
                    </div>

                    <div x-data="paymentForm()" class="w-full">
                        <!-- Pilihan Metode Pembayaran -->
                        <div class="w-full mb-4">
                            <label for="payment_method" class="block text-sm text-[#344054]">Pembayaran</label>
                            <select id="payment_method" name="payment_method" x-model="form.payment_method"
                                class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-[#667085]"
                                required>
                                <option value="" disabled selected>Pilih Pembayaran</option>
                                <option value="tunai">Tunai</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>

                        <!-- Total Pembayaran -->
                        <div class="w-full mb-4 rounded-xl bg-gray-50 border border-gray-200 p-4">
                            <label for="total" class="block text-sm text-[#344054]">Total Bayar</label>
                            <p class="text-lg text-gray-700 mt-1">Rp. {{ number_format($totalSales, 0, ',', '.') }},00
                            </p>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <!-- Input Uang Masuk (Muncul Saat Pilih "Tunai") -->
                            <template x-if="form.payment_method === 'tunai'">
                                <div class="w-full mb-4">
                                    <label for="cash_received" class="block text-sm text-[#344054]">Uang Masuk</label>
                                    <input type="number" id="cash_received" name="cash_received"
                                        x-model.number="form.cash_received"
                                        class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-[#667085]"
                                        placeholder="Masukkan uang tunai" min="0">
                                </div>
                            </template>

                            <!-- Uang Kembalian -->
                            <template x-if="form.payment_method === 'tunai' && form.cash_received >= totalSales">
                                <div class="w-full mb-4 rounded-xl bg-gray-50 border border-gray-200 p-4">
                                    <label for="change" class="block text-sm text-[#344054]">Uang Kembalian</label>
                                    <p class="text-lg text-gray-700 mt-1" x-text="calculateChange()"></p>
                                </div>
                            </template>
                        </div>
                        <!-- Template untuk metode pembayaran tunai -->
                        <template x-if="form.payment_method === 'tunai'">
                            <div class="flex items-center justify-end px-4 gap-x-2">
                                <button type="submit" :disabled="form.cash_received < totalSales"
                                    class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 rounded-lg bg-[#7F56D9] text-white"
                                    :class="{ 'opacity-50 cursor-not-allowed': form.cash_received < totalSales }">
                                    <span>Simpan</span>
                                    <i class="ph ph-floppy-disk ml-1.5"></i>
                                </button>
                            </div>
                        </template>

                        <!-- Div untuk metode pembayaran selain tunai -->
                        <div x-show="form.payment_method === 'transfer'"
                            class="flex items-center justify-end px-4 gap-x-2">
                            <button type="submit"
                                class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 rounded-lg bg-[#7F56D9] text-white">
                                <span>Simpan</span>
                                <i class="ph ph-floppy-disk ml-1.5"></i>
                            </button>
                        </div>

                        <!-- Div jika belum memilih metode pembayaran -->
                        <div x-show="form.payment_method === ''" 
                        class="flex items-center justify-end px-4 gap-x-2">
                            <button type="button" disabled
                                class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 rounded-lg bg-gray-400 text-white opacity-50 cursor-not-allowed">
                                <span>Pilih Metode Pembayaran</span>
                            </button>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                <input type="hidden" name="total" value="{{ $totalSales }}">
                <input type="hidden" name="date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">

                @foreach ($carts as $cart)
                <input type="hidden" name="cart_items[{{ $cart->id }}][cart_id]" value="{{ $cart->id }}">
                <input type="hidden" name="cart_items[{{ $cart->id }}][tray_id]" value="{{ $cart->tray_id }}">
                <input type="hidden" name="cart_items[{{ $cart->id }}][goods_id]" value="{{ $cart->goods_id }}">
                <input type="hidden" name="cart_items[{{ $cart->id }}][harga_jual]"
                    value="{{ $cart->new_selling_price }}">
                @endforeach

            </form>
        </div>
    </div>
</div>
<script>
    function customerForm() {
        return {
            selectedCustomer: '',
            form: {
                customer_id: '',
                name: '',
                phone: '',
                address: ''
            },
            customers: @json($customers), // Data pelanggan dari server

            fillCustomerData() {
                const customer = this.customers.find(c => c.id == this.selectedCustomer);
                if (customer) {
                    this.form.customer_id = customer.id;
                    this.form.name = customer.name;
                    this.form.phone = customer.phone;
                    this.form.address = customer.address;
                }
            }
        }
    }

</script>

<script>
    function paymentForm() {
        return {
            form: {
                payment_method: '',
                cash_received: 0
            },
            totalSales: {{ $totalSales }},
            calculateChange() {
                // Hitung uang kembalian
                return (this.form.cash_received - this.totalSales).toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                });
            }
        };
    }

</script>
